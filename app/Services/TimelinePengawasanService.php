<?php

namespace App\Services;

use App\Models\PelakuUsaha;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Menghitung tahapan (stepper) dan riwayat kejadian (timeline) pengawasan
 * untuk satu Pelaku Usaha berdasarkan data yang sudah ada:
 * Jadwal -> Telaah Dokumen (Pengawasan Tidak Langsung) -> Pengawasan Lapangan (BA)
 * -> Surat Peringatan -> Selesai.
 */
class TimelinePengawasanService
{
    public const STAGES = [
        'jadwal'   => ['label' => 'Jadwal',              'icon' => 'fa-calendar-check'],
        'telaah'   => ['label' => 'Telaah Dokumen',      'icon' => 'fa-file-magnifying-glass'],
        'lapangan' => ['label' => 'Pengawasan Lapangan', 'icon' => 'fa-person-hiking'],
        'sp'       => ['label' => 'Surat Peringatan',    'icon' => 'fa-envelope-open-text'],
        'selesai'  => ['label' => 'Selesai',             'icon' => 'fa-flag-checkered'],
    ];

    /** relasi BA => [label, route show, warna] */
    public const BA_TYPES = [
        'baWasPrls'     => ['label' => 'BA WAS PRL',    'route' => 'ba-was-prl.show',    'color' => 'primary'],
        'baWasAlses'    => ['label' => 'BA WAS ALSE',   'route' => 'ba-was-alse.show',   'color' => 'info'],
        'baReklamasis'  => ['label' => 'BA Reklamasi',  'route' => 'ba-reklamasi.show',  'color' => 'teal'],
        'baPpks'        => ['label' => 'BA PPK',        'route' => 'ba-ppk.show',        'color' => 'indigo'],
        'baPencemarans' => ['label' => 'BA Pencemaran', 'route' => 'ba-pencemaran.show', 'color' => 'orange'],
    ];

    public const STATUS_LABELS = [
        'draft' => 'Draft',
        'proses' => 'Proses',
        'selesai' => 'Selesai',
        'tindak_lanjut' => 'Tindak Lanjut',
        'belum_dilaksanakan' => 'Belum Dilaksanakan',
        'sedang_berjalan' => 'Sedang Berjalan',
        'dibatalkan' => 'Dibatalkan',
    ];

    /**
     * Relasi yang perlu di-eager load agar perhitungan tidak N+1.
     */
    public static function eagerLoads(bool $full = false): array
    {
        $baCols = 'id,pelaku_usaha_id,nomor_ba,tanggal_pengawasan,status,created_at';

        $loads = [
            'jadwalPengawasans',
            'pengawasanTidakLangsungs',
            'suratPeringatans',
        ];

        foreach (array_keys(self::BA_TYPES) as $rel) {
            $loads[$rel] = $full
                ? fn ($q) => $q->withCount('arsipDokumen')
                : fn ($q) => $q->selectRaw($baCols);
        }

        return $loads;
    }

    /**
     * Hasil lengkap: stages, current stage, progress, events.
     */
    public function build(PelakuUsaha $pu, bool $withEvents = true): array
    {
        $bas = $this->collectBa($pu);
        $ptls = $pu->pengawasanTidakLangsungs ?? collect();
        $sps = $pu->suratPeringatans ?? collect();
        $jadwals = ($pu->jadwalPengawasans ?? collect())->where('status', '!=', 'dibatalkan');

        $stages = $this->computeStages($bas, $ptls, $sps, $jadwals);

        // Tahap aktif = tahap pertama yang belum selesai/dilewati
        $current = 'selesai';
        foreach ($stages as $key => $s) {
            if (!in_array($s['state'], ['done', 'skipped'])) {
                $current = $key;
                break;
            }
        }
        $hasActivity = $bas->isNotEmpty() || $ptls->isNotEmpty() || $sps->isNotEmpty() || $jadwals->isNotEmpty();
        if (!$hasActivity) {
            $current = 'belum';
        }

        $doneCount = collect($stages)->whereIn('state', ['done', 'skipped'])->count();

        return [
            'stages' => $stages,
            'current' => $current,
            'current_label' => $current === 'belum' ? 'Belum Ada Pengawasan' : self::STAGES[$current]['label'],
            'progress' => (int) round($doneCount / count(self::STAGES) * 100),
            'perlu_tindakan' => collect($stages)->contains(fn ($s) => $s['state'] === 'alert'),
            'last_activity' => $this->lastActivity($bas, $ptls, $sps, $jadwals),
            'total_ba' => $bas->count(),
            'events' => $withEvents ? $this->buildEvents($bas, $ptls, $sps, $jadwals) : collect(),
        ];
    }

    /**
     * Gabungkan semua BA dari 5 jenis ke dalam satu koleksi berlabel.
     */
    protected function collectBa(PelakuUsaha $pu): Collection
    {
        $all = collect();
        foreach (self::BA_TYPES as $rel => $meta) {
            foreach ($pu->{$rel} ?? [] as $ba) {
                $all->push((object) [
                    'model' => $ba,
                    'label' => $meta['label'],
                    'color' => $meta['color'],
                    'url' => route($meta['route'], $ba->id),
                    'status' => $ba->status,
                    'tanggal' => $ba->tanggal_pengawasan ?? $ba->created_at,
                ]);
            }
        }
        return $all;
    }

    protected function computeStages(Collection $bas, Collection $ptls, Collection $sps, Collection $jadwals): array
    {
        $s = [];
        foreach (self::STAGES as $key => $meta) {
            $s[$key] = $meta + ['state' => 'pending', 'note' => null, 'date' => null];
        }

        $baStatuses = $bas->pluck('status');
        $ptlStatuses = $ptls->pluck('status');
        $spIssued = $sps->filter(fn ($sp) => $this->spLevel($sp) !== null);

        // ---------- 1. JADWAL ----------
        $laterExists = $ptls->isNotEmpty() || $bas->isNotEmpty() || $sps->isNotEmpty();
        if ($jadwals->where('status', 'selesai')->isNotEmpty()) {
            $s['jadwal']['state'] = 'done';
            $s['jadwal']['date'] = $jadwals->where('status', 'selesai')->max('tanggal_rencana');
            $s['jadwal']['note'] = $jadwals->count() . ' jadwal';
        } elseif ($jadwals->isNotEmpty() && !$laterExists) {
            $next = $jadwals->sortBy('tanggal_rencana')->first();
            $s['jadwal']['state'] = 'active';
            $s['jadwal']['date'] = $next->tanggal_rencana;
            $s['jadwal']['note'] = self::STATUS_LABELS[$next->status] ?? $next->status;
        } elseif ($laterExists) {
            $s['jadwal']['state'] = 'done';
            $s['jadwal']['note'] = $jadwals->isNotEmpty() ? $jadwals->count() . ' jadwal' : 'Otomatis';
            $s['jadwal']['date'] = $jadwals->max('tanggal_rencana');
        }

        // ---------- 2. TELAAH DOKUMEN ----------
        if ($ptls->isNotEmpty()) {
            if ($ptlStatuses->contains('proses')) {
                $s['telaah']['state'] = 'active';
                $s['telaah']['note'] = 'Sedang ditelaah';
            } else {
                $s['telaah']['state'] = 'done';
                $s['telaah']['note'] = $ptls->count() . ' telaah';
            }
            $s['telaah']['date'] = $ptls->max(fn ($p) => $p->tanggal_telaah ?? $p->tanggal_laporan);
        } elseif ($bas->isNotEmpty() || $sps->isNotEmpty()) {
            $s['telaah']['state'] = 'skipped';
            $s['telaah']['note'] = 'Tidak tercatat';
        }

        // ---------- 3. PENGAWASAN LAPANGAN ----------
        $allPtlSelesai = $ptls->isNotEmpty() && $ptlStatuses->every(fn ($v) => $v === 'selesai');
        if ($bas->isNotEmpty()) {
            $berjalan = $baStatuses->filter(fn ($v) => in_array($v, ['draft', 'proses']))->count();
            if ($berjalan > 0) {
                $s['lapangan']['state'] = 'active';
                $s['lapangan']['note'] = $berjalan . ' BA berjalan';
            } else {
                $s['lapangan']['state'] = 'done';
                $s['lapangan']['note'] = $bas->count() . ' BA';
            }
            $s['lapangan']['date'] = $bas->max('tanggal');
        } elseif ($spIssued->isNotEmpty() || $allPtlSelesai) {
            $s['lapangan']['state'] = 'skipped';
            $s['lapangan']['note'] = $allPtlSelesai ? 'Cukup telaah dokumen' : 'Tidak tercatat';
        }

        // ---------- 4. SURAT PERINGATAN ----------
        $butuhTindakLanjut = $baStatuses->contains('tindak_lanjut') || $ptlStatuses->contains('tindak_lanjut');
        $laporanBermasalah = $sps->contains(function ($sp) {
            for ($i = 1; $i <= 5; $i++) {
                if (($sp->{"laporan_{$i}_status"} ?? null) === 'Tidak Menyampaikan Laporan') {
                    return true;
                }
            }
            return false;
        });

        if ($spIssued->isNotEmpty()) {
            $levels = $spIssued->map(fn ($sp) => $this->spLevel($sp));
            $s['sp']['state'] = 'done';
            $s['sp']['note'] = $levels->contains('PENCABUTAN') ? 'Pencabutan' : $levels->sort()->last() . ' terbit';
            $s['sp']['date'] = $spIssued->max('updated_at');
        } elseif ($butuhTindakLanjut || $laporanBermasalah) {
            $s['sp']['state'] = 'alert';
            $s['sp']['note'] = $laporanBermasalah ? 'Laporan tidak disampaikan' : 'Perlu tindak lanjut';
        } elseif (in_array($s['lapangan']['state'], ['done', 'skipped'])) {
            $s['sp']['state'] = 'skipped';
            $s['sp']['note'] = 'Tidak diperlukan';
        }

        // ---------- 5. SELESAI ----------
        $hasMain = $bas->isNotEmpty() || $ptls->isNotEmpty();
        $baSelesai = $baStatuses->every(fn ($v) => $v === 'selesai');
        $ptlSelesai = $ptlStatuses->every(fn ($v) => $v === 'selesai');
        if ($hasMain && $baSelesai && $ptlSelesai && $s['sp']['state'] !== 'alert') {
            $s['selesai']['state'] = 'done';
            $s['selesai']['note'] = 'Pengawasan tuntas';
            $s['selesai']['date'] = collect([$bas->max('tanggal'), $s['telaah']['date']])->filter()->max();
        }

        // Tahap pertama yang masih "pending" setelah tahap selesai = sedang menunggu
        foreach ($s as $key => $stage) {
            if ($stage['state'] === 'pending') {
                $s[$key]['state'] = 'next';
                break;
            }
            if (in_array($stage['state'], ['active', 'alert'])) {
                break;
            }
        }

        return $s;
    }

    /**
     * Level SP tertinggi yang sudah terbit pada record surat peringatan.
     */
    protected function spLevel($sp): ?string
    {
        if (!empty($sp->sanksi)) {
            return strtoupper($sp->sanksi);
        }
        foreach (['sp3' => 'SP3', 'sp2' => 'SP2', 'sp1' => 'SP1'] as $f => $label) {
            if (!empty($sp->{"{$f}_nomor_surat_tgl"})) {
                return $label;
            }
        }
        return null;
    }

    protected function lastActivity(Collection $bas, Collection $ptls, Collection $sps, Collection $jadwals): ?Carbon
    {
        $dates = collect()
            ->merge($bas->pluck('tanggal'))
            ->merge($ptls->map(fn ($p) => $p->tanggal_telaah ?? $p->tanggal_laporan ?? $p->created_at))
            ->merge($sps->pluck('updated_at'))
            ->merge($jadwals->pluck('tanggal_rencana'))
            ->filter();

        return $dates->isEmpty() ? null : Carbon::parse($dates->max());
    }

    /**
     * Daftar kejadian kronologis (terbaru di atas).
     */
    protected function buildEvents(Collection $bas, Collection $ptls, Collection $sps, Collection $jadwals): Collection
    {
        $events = collect();

        foreach ($jadwals as $j) {
            $events->push([
                'date' => $j->tanggal_rencana,
                'stage' => 'jadwal',
                'icon' => 'fa-calendar-check',
                'color' => 'primary',
                'title' => 'Jadwal Pengawasan' . ($j->jenis_pengawasan ? ': ' . $j->jenis_pengawasan : ''),
                'desc' => $j->tim_pengawas ? 'Tim: ' . $j->tim_pengawas : $j->catatan,
                'badge' => self::STATUS_LABELS[$j->status] ?? $j->status,
                'badge_status' => $j->status,
                'url' => null,
            ]);
        }

        foreach ($ptls as $p) {
            if ($p->tanggal_laporan) {
                $events->push([
                    'date' => $p->tanggal_laporan,
                    'stage' => 'telaah',
                    'icon' => 'fa-inbox',
                    'color' => 'secondary',
                    'title' => 'Laporan Masuk' . ($p->nomor ? ' – ' . $p->nomor : ''),
                    'desc' => $p->pelapor_sumber ? 'Sumber: ' . $p->pelapor_sumber : null,
                    'badge' => null,
                    'badge_status' => null,
                    'url' => route('pengawasan-tidak-langsung.show', $p->id),
                ]);
            }
            $events->push([
                'date' => $p->tanggal_telaah ?? $p->tanggal_laporan ?? $p->created_at,
                'stage' => 'telaah',
                'icon' => 'fa-file-magnifying-glass',
                'color' => 'purple',
                'title' => 'Telaah Dokumen (Pengawasan Tidak Langsung)' . ($p->nomor ? ' – ' . $p->nomor : ''),
                'desc' => $p->rekomendasi ? \Illuminate\Support\Str::limit(strip_tags($p->rekomendasi), 140) : null,
                'badge' => self::STATUS_LABELS[$p->status] ?? $p->status,
                'badge_status' => $p->status,
                'url' => route('pengawasan-tidak-langsung.show', $p->id),
            ]);
        }

        foreach ($bas as $ba) {
            $arsip = $ba->model->arsip_dokumen_count ?? 0;
            $events->push([
                'date' => $ba->tanggal,
                'stage' => 'lapangan',
                'icon' => 'fa-clipboard-check',
                'color' => $ba->color,
                'title' => $ba->label . ' – ' . ($ba->model->nomor_ba ?: 'Tanpa nomor'),
                'desc' => $arsip ? $arsip . ' arsip dokumen terlampir' : null,
                'badge' => self::STATUS_LABELS[$ba->status] ?? $ba->status,
                'badge_status' => $ba->status,
                'url' => $ba->url,
            ]);
        }

        foreach ($sps as $sp) {
            $url = route('surat-peringatan.show', $sp->id);

            if ($sp->tanggal_penerbitan) {
                $events->push([
                    'date' => $sp->tanggal_penerbitan,
                    'stage' => 'sp',
                    'icon' => 'fa-stamp',
                    'color' => 'secondary',
                    'title' => 'Penerbitan KKPRL' . ($sp->nomor_kkprl ? ' – ' . $sp->nomor_kkprl : ''),
                    'desc' => $sp->detil_kegiatan ? \Illuminate\Support\Str::limit($sp->detil_kegiatan, 120) : null,
                    'badge' => null,
                    'badge_status' => null,
                    'url' => $url,
                ]);
            }

            for ($i = 1; $i <= 5; $i++) {
                $tgl = $sp->{"laporan_{$i}_tgl"};
                $st = $sp->{"laporan_{$i}_status"};
                if (!$tgl && !$st) {
                    continue;
                }
                $events->push([
                    'date' => $tgl ?? $sp->updated_at,
                    'stage' => 'sp',
                    'icon' => 'fa-file-signature',
                    'color' => match ($st) {
                        'Tepat Waktu' => 'success',
                        'Terlambat' => 'warning',
                        'Tidak Menyampaikan Laporan' => 'danger',
                        default => 'secondary',
                    },
                    'title' => "Laporan Pelaksanaan KKPRL ke-{$i}",
                    'desc' => null,
                    'badge' => $st,
                    'badge_status' => $st,
                    'url' => $url,
                ]);
            }

            foreach (['sp1' => 'SP 1', 'sp2' => 'SP 2', 'sp3' => 'SP 3'] as $f => $label) {
                $nomor = $sp->{"{$f}_nomor_surat_tgl"};
                if (!$nomor && !$sp->{"{$f}_keterangan"}) {
                    continue;
                }
                $parsed = $this->parseDateFromText($nomor);
                $events->push([
                    'date' => $parsed ?? $sp->updated_at,
                    'date_estimated' => $parsed === null,
                    'stage' => 'sp',
                    'icon' => 'fa-triangle-exclamation',
                    'color' => 'danger',
                    'title' => "Surat Peringatan {$label} diterbitkan",
                    'desc' => trim(($nomor ? 'No: ' . $nomor : '') . ($sp->{"{$f}_status_terkirim"} ? ' · Terkirim: ' . $sp->{"{$f}_status_terkirim"} : '')) ?: $sp->{"{$f}_keterangan"},
                    'badge' => $label,
                    'badge_status' => 'sp',
                    'url' => $url,
                ]);
            }

            if (strtoupper((string) $sp->sanksi) === 'PENCABUTAN') {
                $events->push([
                    'date' => $sp->updated_at,
                    'date_estimated' => true,
                    'stage' => 'sp',
                    'icon' => 'fa-ban',
                    'color' => 'danger',
                    'title' => 'Sanksi Pencabutan',
                    'desc' => null,
                    'badge' => 'Pencabutan',
                    'badge_status' => 'sp',
                    'url' => $url,
                ]);
            }
        }

        return $events
            ->filter(fn ($e) => !empty($e['date']))
            ->map(function ($e) {
                $e['date'] = Carbon::parse($e['date']);
                $e['date_estimated'] = $e['date_estimated'] ?? false;
                return $e;
            })
            ->sortByDesc(fn ($e) => $e['date']->timestamp)
            ->values();
    }

    /**
     * Ambil tanggal dari teks bebas seperti "123/SP/2026, 12/03/2026" atau "2026-03-12".
     */
    protected function parseDateFromText(?string $text): ?Carbon
    {
        if (!$text) {
            return null;
        }
        try {
            if (preg_match('/(\d{4})-(\d{1,2})-(\d{1,2})/', $text, $m)) {
                return Carbon::create((int) $m[1], (int) $m[2], (int) $m[3]);
            }
            if (preg_match('/\b(\d{1,2})[\/\-.](\d{1,2})[\/\-.](\d{4})\b/', $text, $m)) {
                return Carbon::create((int) $m[3], (int) $m[2], (int) $m[1]);
            }
            $bulan = ['januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4, 'mei' => 5, 'juni' => 6,
                'juli' => 7, 'agustus' => 8, 'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12];
            if (preg_match('/(\d{1,2})\s+(' . implode('|', array_keys($bulan)) . ')\s+(\d{4})/i', $text, $m)) {
                return Carbon::create((int) $m[3], $bulan[strtolower($m[2])], (int) $m[1]);
            }
        } catch (\Throwable $e) {
            return null;
        }
        return null;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\BaPencemaran;
use App\Models\BaPpk;
use App\Models\BaReklamasi;
use App\Models\BaWasAlse;
use App\Models\BaWasPrl;
use App\Models\PelakuUsaha;
use App\Models\TimelineTahapan;
use Illuminate\Console\Command;

class BackfillTimelineTahapan extends Command
{
    protected $signature = 'timeline:backfill {--dry-run : Tampilkan tanpa menyimpan}';
    protected $description = 'Backfill TimelineTahapan dari semua Berita Acara (BA) yang sudah ada, agar Tindak Lanjut tersinkronisasi.';

    /**
     * Mapping kelas BA → label yang digunakan di catatan timeline.
     */
    private const BA_MODELS = [
        BaWasPrl::class     => 'BA WAS PRL',
        BaWasAlse::class    => 'BA WAS ALSE',
        BaReklamasi::class  => 'BA Reklamasi',
        BaPpk::class        => 'BA PPK',
        BaPencemaran::class => 'BA Pencemaran',
    ];

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach (self::BA_MODELS as $modelClass => $label) {
            $tableName = (new $modelClass)->getTable();
            $this->info("→ Memproses {$label} ({$tableName})...");

            $modelClass::with('pelakuUsaha')
                ->whereNotNull('pelaku_usaha_id')
                ->chunkById(100, function ($records) use ($label, $dryRun, &$created, &$updated, &$skipped) {
                    foreach ($records as $ba) {
                        if (!$ba->pelakuUsaha) {
                            $skipped++;
                            continue;
                        }

                        $nomorBa = $ba->nomor_ba ?: ('BA-' . $ba->id);
                        $tanggal = $ba->tanggal_pengawasan ?? $ba->created_at;

                        if ($dryRun) {
                            $this->line("  [DRY] Pelaku Usaha #{$ba->pelaku_usaha_id} ({$ba->pelakuUsaha->nama_perusahaan}) ← {$label} {$nomorBa}");
                            $created++;
                            continue;
                        }

                        // 1. Tahap Pemberitahuan → selesai
                        $pemberitahuan = TimelineTahapan::firstOrCreate(
                            [
                                'pelaku_usaha_id' => $ba->pelaku_usaha_id,
                                'tahap'           => 'pemberitahuan',
                            ],
                            [
                                'status'  => 'selesai',
                                'tanggal' => $tanggal,
                                'catatan' => "Selesai otomatis (backfill) melalui penerbitan {$label} ({$nomorBa})",
                            ]
                        );
                        if ($pemberitahuan->wasRecentlyCreated) {
                            $created++;
                        } elseif ($pemberitahuan->status === 'belum') {
                            $pemberitahuan->update([
                                'status'  => 'selesai',
                                'tanggal' => $tanggal,
                                'catatan' => "Selesai otomatis (backfill) melalui penerbitan {$label} ({$nomorBa})",
                            ]);
                            $updated++;
                        } else {
                            $skipped++;
                        }

                        // 2. Tahap Pengawasan → proses
                        $pengawasan = TimelineTahapan::firstOrCreate(
                            [
                                'pelaku_usaha_id' => $ba->pelaku_usaha_id,
                                'tahap'           => 'pengawasan',
                            ],
                            [
                                'status'  => 'proses',
                                'tanggal' => $tanggal,
                                'catatan' => "Telah terbit {$label}: {$nomorBa} (backfill)",
                            ]
                        );
                        if ($pengawasan->wasRecentlyCreated) {
                            $created++;
                        } elseif ($pengawasan->status === 'belum') {
                            $pengawasan->update([
                                'status'  => 'proses',
                                'tanggal' => $tanggal,
                                'catatan' => "Telah terbit {$label}: {$nomorBa} (backfill)",
                            ]);
                            $updated++;
                        } else {
                            $skipped++;
                        }
                    }
                });
        }

        $this->newLine();
        $this->info("═══ Hasil Backfill ═══");
        $this->info("  Dibuat baru : {$created}");
        $this->info("  Diperbarui  : {$updated}");
        $this->info("  Dilewati    : {$skipped}");

        if ($dryRun) {
            $this->warn("  Mode DRY-RUN — tidak ada data yang disimpan.");
        }

        return Command::SUCCESS;
    }
}

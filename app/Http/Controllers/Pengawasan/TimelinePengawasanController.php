<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\PelakuUsaha;
use App\Services\TimelinePengawasanService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TimelinePengawasanController extends Controller
{
    public function __construct(protected TimelinePengawasanService $service)
    {
    }

    public function index(Request $request)
    {
        $pelakuUsahas = PelakuUsaha::query()
            ->with(array_merge(['kabupaten:id,nama', 'jenisUsaha:id,nama'], TimelinePengawasanService::eagerLoads()))
            // Default: hanya pelaku usaha yang tercatat di menu Pengawasan (punya BA)
            // atau yang tahapan timeline-nya sudah dimulai. ?semua=1 untuk menampilkan semua.
            ->when(!$request->boolean('semua'), fn ($q) => $q->where(function ($q2) {
                foreach (array_keys(TimelinePengawasanService::BA_TYPES) as $rel) {
                    $q2->orWhereHas($rel);
                }
                $q2->orWhereHas('timelineTahapans');
            }))
            ->when($request->q, fn ($q, $v) => $q->where(function ($q2) use ($v) {
                $q2->where('nama_perusahaan', 'like', "%{$v}%")->orWhere('nomor_pkkprl', 'like', "%{$v}%");
            }))
            // Filter berdasarkan jenis pengawasan (BA type)
            ->when($request->pengawasan && $request->pengawasan !== 'semua', function ($q) use ($request) {
                $relMap = [
                    'prl'        => 'baWasPrls',
                    'alse'       => 'baWasAlses',
                    'reklamasi'  => 'baReklamasis',
                    'ppk'        => 'baPpks',
                    'pencemaran' => 'baPencemarans',
                ];
                $rel = $relMap[$request->pengawasan] ?? null;
                if ($rel) {
                    $q->whereHas($rel);
                }
            })
            ->orderBy('nama_perusahaan')
            ->get();

        $rows = $pelakuUsahas->map(function ($pu) {
            $pu->timeline = $this->service->build($pu, false);
            return $pu;
        });

        // Ringkasan per tahap (sebelum filter tahap)
        $summary = [
            'belum' => 0,
            'selesai' => 0,
            'perlu_tindakan' => 0,
        ];
        foreach (array_keys(TimelinePengawasanService::STAGES) as $k) {
            $summary[$k] = 0;
        }
        foreach ($rows as $r) {
            $curr = $r->timeline['current'] ?? 'belum';
            $summary[$curr] = ($summary[$curr] ?? 0) + 1;
            if (!empty($r->timeline['perlu_tindakan'])) {
                $summary['perlu_tindakan']++;
            }
        }

        if ($tahap = $request->tahap) {
            $rows = $tahap === 'perlu_tindakan'
                ? $rows->filter(fn ($r) => $r->timeline['perlu_tindakan'])
                : $rows->filter(fn ($r) => $r->timeline['current'] === $tahap);
        }

        $rows = match ($request->urut) {
            'progress' => $rows->sortByDesc(fn ($r) => $r->timeline['progress']),
            'aktivitas' => $rows->sortByDesc(fn ($r) => optional($r->timeline['last_activity'])->timestamp ?? 0),
            default => $rows,
        };

        $perPage = 15;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $paginated = new LengthAwarePaginator(
            $rows->values()->forPage($page, $perPage),
            $rows->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('timeline-pengawasan.index', [
            'items' => $paginated,
            'summary' => $summary,
            'total' => $pelakuUsahas->count(),
            'stages' => TimelinePengawasanService::STAGES,
        ]);
    }

    public function show(PelakuUsaha $pelakuUsaha)
    {
        $pelakuUsaha->load(array_merge(
            ['jenisUsaha', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'dokumenPelakuUsahas', 'dokumens', 'timelineTahapans.files'],
            TimelinePengawasanService::eagerLoads(true)
        ));

        $company = \App\Models\Company::where('nama_perusahaan', $pelakuUsaha->nama_perusahaan)->first();

        $timeline = $this->service->build($pelakuUsaha);

        return view('timeline-pengawasan.show', compact('pelakuUsaha', 'timeline', 'company'));
    }
}

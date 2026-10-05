<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
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
            ->when($request->q, fn ($q, $v) => $q->where(function ($q2) use ($v) {
                $q2->where('nama_perusahaan', 'like', "%{$v}%")->orWhere('nomor_pkkprl', 'like', "%{$v}%");
            }))
            ->when($request->kabupaten_id, fn ($q, $v) => $q->where('kabupaten_id', $v))
            ->orderBy('nama_perusahaan')
            ->get();

        $rows = $pelakuUsahas->map(function ($pu) {
            $pu->timeline = $this->service->build($pu, false);
            return $pu;
        });

        // Ringkasan per tahap (sebelum filter tahap)
        $summary = ['belum' => 0, 'perlu_tindakan' => 0];
        foreach (array_keys(TimelinePengawasanService::STAGES) as $k) {
            $summary[$k] = 0;
        }
        foreach ($rows as $r) {
            $summary[$r->timeline['current']]++;
            if ($r->timeline['perlu_tindakan']) {
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

        $kabupatens = Kabupaten::whereIn('id', PelakuUsaha::whereNotNull('kabupaten_id')->distinct()->pluck('kabupaten_id'))
            ->orderBy('nama')->get(['id', 'nama']);

        return view('timeline-pengawasan.index', [
            'items' => $paginated,
            'summary' => $summary,
            'total' => $pelakuUsahas->count(),
            'kabupatens' => $kabupatens,
            'stages' => TimelinePengawasanService::STAGES,
        ]);
    }

    public function show(PelakuUsaha $pelakuUsaha)
    {
        $pelakuUsaha->load(array_merge(
            ['jenisUsaha', 'provinsi', 'kabupaten'],
            TimelinePengawasanService::eagerLoads(true)
        ));

        $timeline = $this->service->build($pelakuUsaha);

        return view('timeline-pengawasan.show', compact('pelakuUsaha', 'timeline'));
    }
}

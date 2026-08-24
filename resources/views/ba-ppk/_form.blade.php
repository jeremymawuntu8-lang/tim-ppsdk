@csrf

@php
    $pengawasData = old('pengawas', isset($baPpk) ? $baPpk->pengawas->toArray() : []);
    $ba = $baPpk ?? null;
    
    $jenisUsahaOptions = [
        'Konservasi', 'Diklat', 'Litbang', 'Budidaya', 'Pertanian', 'Peternakan',
        'Perkebunan', 'Pergudangan', 'Pariwisata', 'Industri', 'Agroforestry',
        'Pertambangan Tanah Jarang', 'Energi baru dan terbarukan', 'Usaha minyak dan gas bumi',
        'Kepelabuhan/perhubungan', 'Pemukiman', 'Usaha perikanan/kelautan', 'Pertambangan Minerba',
        'Fasum/fasos', 'Adat istiadat/upacara', 'Hankam', 'KSN yang ditetapkan Presiden'
    ];
    $rekomendasiOptions = [
        'Pelaku usaha dinyatakan taat',
        'Pelaku usaha mengurus perizinan',
        'Pelaku usaha memperbaiki kerusakan/pencemaran',
        'Dilakukan pemeriksaan lanjutan',
        'Penerapan sanksi'
    ];
@endphp

<style>
    .form-section-title { font-weight: 700; font-size: .95rem; color: #444; margin-bottom: .75rem; padding-bottom: .35rem; border-bottom: 2px solid #e9ecef; }
    .nav-tabs .nav-link { font-weight: 600; color: #6c757d; }
    .nav-tabs .nav-link.active { color: var(--bs-primary, #0d6efd); }
    .repeater-row { background: #f8f9fa; border-radius: 8px; padding: 12px; }
    .accordion-button { font-weight: 600; }
</style>

<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-utama" type="button"><i class="fas fa-info-circle me-1"></i> 1. Informasi Utama</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pengawas" type="button"><i class="fas fa-users me-1"></i> 2. Tim Pengawas</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-profil" type="button"><i class="fas fa-building me-1"></i> 3. Profil Usaha</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-izin" type="button"><i class="fas fa-file-contract me-1"></i> 4. Perizinan</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pemenuhan" type="button"><i class="fas fa-check-square me-1"></i> 5. Pemenuhan & Kesimpulan</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pengesahan" type="button"><i class="fas fa-signature me-1"></i> 6. Pengesahan & File</button>
    </li>
</ul>

<div class="tab-content">
    
    {{-- TAB 1: Informasi Utama --}}
    <div class="tab-pane fade show active" id="tab-utama">
        <div class="row g-3">
            <div class="col-md-6 col-12">
                <label class="form-label">Nomor BA <span class="text-danger">*</span></label>
                <input type="text" name="nomor_ba" class="form-control" value="{{ old('nomor_ba', $ba->nomor_ba ?? '') }}" required>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Unit Kerja <small class="text-muted">(Nama UPT/Satwas/Wilker)</small></label>
                <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja', $ba->unit_kerja ?? '') }}">
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label">Tanggal Pengawasan <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_pengawasan" class="form-control" value="{{ old('tanggal_pengawasan', $ba?->tanggal_pengawasan?->format('Y-m-d') ?? date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label">Jam (WITA)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                    <input type="time" name="jam_wita" class="form-control" value="{{ old('jam_wita', $ba?->jam_wita ? substr($ba->jam_wita, 0, 5) : '') }}">
                </div>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label">Lokasi Pengawasan</label>
                <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $ba->lokasi ?? '') }}">
            </div>
        </div>
    </div>

    {{-- TAB 2: Tim Pengawas --}}
    <div class="tab-pane fade" id="tab-pengawas">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label fw-bold mb-0">Tim Pengawas</label>
            <button type="button" id="btnTambahPengawas" class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="fas fa-plus"></i> Tambah Anggota</button>
        </div>

        <div id="pengawasWrapper">
            @forelse($pengawasData as $i => $pg)
                <div class="row g-2 mb-3 pengawas-row repeater-row" data-pengawas-id="{{ $i }}">
                    <div class="col-md-4 col-6"><input type="text" name="pengawas[{{ $i }}][nama]" class="form-control form-control-sm pengawas-nama-input" placeholder="Nama Anggota" value="{{ $pg['nama'] ?? '' }}"></div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[{{ $i }}][nip]" class="form-control form-control-sm" placeholder="NIP / No. KTA" value="{{ $pg['nip'] ?? '' }}"></div>
                    <div class="col-md-4 col-10"><input type="text" name="pengawas[{{ $i }}][jabatan]" class="form-control form-control-sm" placeholder="Jabatan" value="{{ $pg['jabatan'] ?? '' }}"></div>
                    <div class="col-md-1 col-2"><button type="button" class="btn btn-outline-danger btn-sm w-100 btn-hapus-pengawas" title="Hapus"><i class="fas fa-trash"></i></button></div>
                </div>
            @empty
                <div class="row g-2 mb-3 pengawas-row repeater-row" data-pengawas-id="0">
                    <div class="col-md-4 col-6"><input type="text" name="pengawas[0][nama]" class="form-control form-control-sm pengawas-nama-input" placeholder="Nama Anggota"></div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[0][nip]" class="form-control form-control-sm" placeholder="NIP / No. KTA"></div>
                    <div class="col-md-4 col-10"><input type="text" name="pengawas[0][jabatan]" class="form-control form-control-sm" placeholder="Jabatan"></div>
                    <div class="col-md-1 col-2"><button type="button" class="btn btn-outline-danger btn-sm w-100 btn-hapus-pengawas" title="Hapus"><i class="fas fa-trash"></i></button></div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- TAB 3: Profil Usaha --}}
    <div class="tab-pane fade" id="tab-profil">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Pelaku Usaha Utama (Opsional)</label>
                <select name="pelaku_usaha_id" id="pelakuUsahaSelect" class="form-select" style="width: 100%;">
                    <option value="">-- Pilih atau Ketik Nama Baru --</option>
                    @foreach($pelakuUsahas ?? [] as $pu)
                        <option value="{{ $pu->id }}" @selected(old('pelaku_usaha_id', $ba->pelaku_usaha_id ?? null) == $pu->id)>{{ $pu->nama_perusahaan }}</option>
                    @endforeach
                </select>
                <div class="form-text">Pilih agar terkoneksi dengan database, atau ketik langsung.</div>
            </div>
            
            <div class="col-md-6 col-12">
                <label class="form-label">Nama Penanggung Jawab</label>
                <input type="text" name="nama_pj" class="form-control pj-nama-input" value="{{ old('nama_pj', $ba->nama_pj ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">No Identitas (NIK/Passport)</label>
                <input type="text" name="nik_pj" class="form-control" value="{{ old('nik_pj', $ba->nik_pj ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat</label>
                <textarea name="alamat_pj" class="form-control" rows="2">{{ old('alamat_pj', $ba->alamat_pj ?? '') }}</textarea>
            </div>
            
            <div class="col-md-6 col-12">
                <label class="form-label">Status Penanaman Modal</label>
                <select name="status_modal" class="form-select">
                    <option value="">-- Pilih --</option>
                    <option value="asing" @selected(old('status_modal', $ba->status_modal ?? '') == 'asing')>Modal Asing</option>
                    <option value="dalam_negeri" @selected(old('status_modal', $ba->status_modal ?? '') == 'dalam_negeri')>Modal Dalam Negeri</option>
                </select>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Kepemilikan Saham</label>
                <select name="kepemilikan_saham" class="form-select">
                    <option value="">-- Pilih --</option>
                    <option value="swasta" @selected(old('kepemilikan_saham', $ba->kepemilikan_saham ?? '') == 'swasta')>Swasta</option>
                    <option value="pemerintah" @selected(old('kepemilikan_saham', $ba->kepemilikan_saham ?? '') == 'pemerintah')>Pemerintah</option>
                </select>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Nama Pemilik Saham 1</label>
                <input type="text" name="nama_saham_1" class="form-control" value="{{ old('nama_saham_1', $ba->nama_saham_1 ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Nama Pemilik Saham 2</label>
                <input type="text" name="nama_saham_2" class="form-control" value="{{ old('nama_saham_2', $ba->nama_saham_2 ?? '') }}">
            </div>

            <div class="col-md-6 col-12">
                <label class="form-label">Nama Pulau</label>
                <input type="text" name="nama_pulau" class="form-control" value="{{ old('nama_pulau', $ba->nama_pulau ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Kategori Lokasi</label>
                <select name="kategori_lokasi" class="form-select">
                    <option value="">-- Pilih --</option>
                    <option value="ppk" @selected(old('kategori_lokasi', $ba->kategori_lokasi ?? '') == 'ppk')>PPK (Pulau-Pulau Kecil)</option>
                    <option value="ppkt" @selected(old('kategori_lokasi', $ba->kategori_lokasi ?? '') == 'ppkt')>PPKT (Pulau-Pulau Kecil Terluar)</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <label class="form-label">Jenis Usaha / Kegiatan</label>
            @php $savedJenis = old('jenis_usaha', $ba->jenis_usaha ?? []); if(!is_array($savedJenis)) $savedJenis = []; @endphp
            <div class="row g-2">
                @foreach($jenisUsahaOptions as $jenis)
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="jenis_usaha[]" value="{{ $jenis }}" id="ju_{{ \Str::slug($jenis) }}" @checked(in_array($jenis, $savedJenis))>
                            <label class="form-check-label" for="ju_{{ \Str::slug($jenis) }}">{{ $jenis }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- TAB 4: Perizinan --}}
    <div class="tab-pane fade" id="tab-izin">
        <div class="form-section-title">Syarat Wajib Memiliki Rekomendasi PPK</div>
        <div class="mb-4">
            <div class="form-check form-switch mb-1">
                <input class="form-check-input" type="checkbox" name="syarat_rdtr_belum" value="1" @checked(old('syarat_rdtr_belum', $ba->syarat_rdtr_belum ?? false))>
                <label class="form-check-label">Belum tersedia RDTR</label>
            </div>
            <div class="form-check form-switch mb-1">
                <input class="form-check-input" type="checkbox" name="syarat_rdtr_non_oss" value="1" @checked(old('syarat_rdtr_non_oss', $ba->syarat_rdtr_non_oss ?? false))>
                <label class="form-check-label">Telah tersedia RDTR namun belum terintegrasi OSS</label>
            </div>
            <div class="form-check form-switch mb-1">
                <input class="form-check-input" type="checkbox" name="syarat_rtr_zonasi" value="1" @checked(old('syarat_rtr_zonasi', $ba->syarat_rtr_zonasi ?? false))>
                <label class="form-check-label">RTR belum memuat zonasi pemanfaatan PPK < 100 km2</label>
            </div>
            <div class="form-check form-switch mb-1">
                <input class="form-check-input" type="checkbox" name="syarat_pengecualian_pkkpr" value="1" @checked(old('syarat_pengecualian_pkkpr', $ba->syarat_pengecualian_pkkpr ?? false))>
                <label class="form-check-label">Tidak termasuk kondisi tertentu yang dikecualikan dalam penerbitan PKKPR</label>
            </div>
        </div>

        <div class="accordion" id="accordionIzin">
            <!-- Rekomendasi PPK -->
            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#colRekPpk">1) REKOMENDASI PPK</button></h2>
                <div id="colRekPpk" class="accordion-collapse collapse show" data-bs-parent="#accordionIzin">
                    <div class="accordion-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="rek_ppk_ada" value="1" @checked(old('rek_ppk_ada', $ba->rek_ppk_ada ?? false))>
                            <label class="form-check-label fw-bold">Dokumen Ada?</label>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-9 col-12"><label class="form-label text-muted mb-1">Jenis Rekomendasi</label>
                                <select name="rek_ppk_jenis" class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    <option value="Rekomendasi PPK < 100 km2" @selected(old('rek_ppk_jenis', $ba->rek_ppk_jenis ?? '') == 'Rekomendasi PPK < 100 km2')>Rekomendasi PPK < 100 km2</option>
                                    <option value="Rekomendasi PPK oleh PMA" @selected(old('rek_ppk_jenis', $ba->rek_ppk_jenis ?? '') == 'Rekomendasi PPK oleh PMA')>Rekomendasi PPK oleh PMA</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-12"><label class="form-label text-muted mb-1">Status S/TS</label>
                                <select name="rek_ppk_jenis_sts" class="form-select form-select-sm">
                                    <option value="">-</option><option value="S" @selected(old('rek_ppk_jenis_sts', $ba->rek_ppk_jenis_sts ?? '') == 'S')>S</option><option value="TS" @selected(old('rek_ppk_jenis_sts', $ba->rek_ppk_jenis_sts ?? '') == 'TS')>TS</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5"><label class="form-label text-muted mb-1">Nomor</label><input type="text" name="rek_ppk_nomor" class="form-control form-control-sm" value="{{ old('rek_ppk_nomor', $ba->rek_ppk_nomor ?? '') }}"></div>
                            <div class="col-md-2"><label class="form-label text-muted mb-1">S/TS</label><select name="rek_ppk_nomor_sts" class="form-select form-select-sm"><option value="">-</option><option value="S" @selected(old('rek_ppk_nomor_sts', $ba->rek_ppk_nomor_sts ?? '') == 'S')>S</option><option value="TS" @selected(old('rek_ppk_nomor_sts', $ba->rek_ppk_nomor_sts ?? '') == 'TS')>TS</option></select></div>
                            <div class="col-md-3"><label class="form-label text-muted mb-1">Tgl Terbit</label><input type="date" name="rek_ppk_tgl" class="form-control form-control-sm" value="{{ old('rek_ppk_tgl', $ba?->rek_ppk_tgl?->format('Y-m-d') ?? '') }}"></div>
                            <div class="col-md-2"><label class="form-label text-muted mb-1">S/TS</label><select name="rek_ppk_tgl_sts" class="form-select form-select-sm"><option value="">-</option><option value="S" @selected(old('rek_ppk_tgl_sts', $ba->rek_ppk_tgl_sts ?? '') == 'S')>S</option><option value="TS" @selected(old('rek_ppk_tgl_sts', $ba->rek_ppk_tgl_sts ?? '') == 'TS')>TS</option></select></div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5"><label class="form-label text-muted mb-1">Penerbit</label><input type="text" name="rek_ppk_penerbit" class="form-control form-control-sm" value="{{ old('rek_ppk_penerbit', $ba->rek_ppk_penerbit ?? '') }}"></div>
                            <div class="col-md-2"><label class="form-label text-muted mb-1">S/TS</label><select name="rek_ppk_penerbit_sts" class="form-select form-select-sm"><option value="">-</option><option value="S" @selected(old('rek_ppk_penerbit_sts', $ba->rek_ppk_penerbit_sts ?? '') == 'S')>S</option><option value="TS" @selected(old('rek_ppk_penerbit_sts', $ba->rek_ppk_penerbit_sts ?? '') == 'TS')>TS</option></select></div>
                            <div class="col-md-3"><label class="form-label text-muted mb-1">Masa Berlaku</label><input type="text" name="rek_ppk_masa_berlaku" class="form-control form-control-sm" value="{{ old('rek_ppk_masa_berlaku', $ba->rek_ppk_masa_berlaku ?? '') }}"></div>
                            <div class="col-md-2"><label class="form-label text-muted mb-1">S/TS</label><select name="rek_ppk_masa_berlaku_sts" class="form-select form-select-sm"><option value="">-</option><option value="S" @selected(old('rek_ppk_masa_berlaku_sts', $ba->rek_ppk_masa_berlaku_sts ?? '') == 'S')>S</option><option value="TS" @selected(old('rek_ppk_masa_berlaku_sts', $ba->rek_ppk_masa_berlaku_sts ?? '') == 'TS')>TS</option></select></div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5"><label class="form-label text-muted mb-1">Jenis Kegiatan</label><input type="text" name="rek_ppk_jenis_kegiatan" class="form-control form-control-sm" value="{{ old('rek_ppk_jenis_kegiatan', $ba->rek_ppk_jenis_kegiatan ?? '') }}"></div>
                            <div class="col-md-2"><label class="form-label text-muted mb-1">S/TS</label><select name="rek_ppk_jenis_kegiatan_sts" class="form-select form-select-sm"><option value="">-</option><option value="S" @selected(old('rek_ppk_jenis_kegiatan_sts', $ba->rek_ppk_jenis_kegiatan_sts ?? '') == 'S')>S</option><option value="TS" @selected(old('rek_ppk_jenis_kegiatan_sts', $ba->rek_ppk_jenis_kegiatan_sts ?? '') == 'TS')>TS</option></select></div>
                            <div class="col-md-3"><label class="form-label text-muted mb-1">Luas Izin (Ha)</label><input type="text" name="rek_ppk_luas_izin" class="form-control form-control-sm" value="{{ old('rek_ppk_luas_izin', $ba->rek_ppk_luas_izin ?? '') }}"></div>
                            <div class="col-md-2"><label class="form-label text-muted mb-1">S/TS</label><select name="rek_ppk_luas_izin_sts" class="form-select form-select-sm"><option value="">-</option><option value="S" @selected(old('rek_ppk_luas_izin_sts', $ba->rek_ppk_luas_izin_sts ?? '') == 'S')>S</option><option value="TS" @selected(old('rek_ppk_luas_izin_sts', $ba->rek_ppk_luas_izin_sts ?? '') == 'TS')>TS</option></select></div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-12"><label class="form-label text-muted mb-1">Luas Pemanfaatan (Ha)</label><input type="text" name="rek_ppk_luas_pemanfaatan" class="form-control form-control-sm" value="{{ old('rek_ppk_luas_pemanfaatan', $ba->rek_ppk_luas_pemanfaatan ?? '') }}"></div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5"><label class="form-label text-muted mb-1">Koordinat Izin</label><textarea name="rek_ppk_koordinat_izin" class="form-control form-control-sm" rows="2">{{ old('rek_ppk_koordinat_izin', $ba->rek_ppk_koordinat_izin ?? '') }}</textarea></div>
                            <div class="col-md-2"><label class="form-label text-muted mb-1">S/TS</label><select name="rek_ppk_koordinat_izin_sts" class="form-select form-select-sm"><option value="">-</option><option value="S" @selected(old('rek_ppk_koordinat_izin_sts', $ba->rek_ppk_koordinat_izin_sts ?? '') == 'S')>S</option><option value="TS" @selected(old('rek_ppk_koordinat_izin_sts', $ba->rek_ppk_koordinat_izin_sts ?? '') == 'TS')>TS</option></select></div>
                            <div class="col-md-5"><label class="form-label text-muted mb-1">Koor Eksisting</label><textarea name="rek_ppk_koordinat_eksisting" class="form-control form-control-sm" rows="2">{{ old('rek_ppk_koordinat_eksisting', $ba->rek_ppk_koordinat_eksisting ?? '') }}</textarea></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Other Docs -->
            @php 
            $docBlocks = [
                ['id' => 'Pkkpr', 'title' => '2) PKKPR', 'prefix' => 'pkkpr', 'fields' => ['nomor'=>'Nomor','tgl'=>'Tanggal Terbit (Date)','penerbit'=>'Penerbit','luas'=>'Luas (Ha)','koordinat'=>'Koordinat (Area)']],
                ['id' => 'Lingkungan', 'title' => '3) PERSETUJUAN LINGKUNGAN', 'prefix' => 'lingkungan', 'fields' => ['nomor'=>'Nomor','tgl'=>'Tanggal Terbit (Date)','penerbit'=>'Penerbit']],
                ['id' => 'Nib', 'title' => '4) NIB', 'prefix' => 'nib', 'fields' => ['nomor'=>'Nomor','tgl'=>'Tanggal Terbit (Date)','kbli'=>'Kode KBLI']],
                ['id' => 'IzinUsaha', 'title' => '5) PERIZINAN BERUSAHA', 'prefix' => 'izin_usaha', 'fields' => ['nomor'=>'Nomor','tgl'=>'Tanggal Terbit (Date)','penerbit'=>'Penerbit','masa'=>'Masa Berlaku','jenis'=>'Jenis Kegiatan Usaha','luas'=>'Luas (Ha)','lokasi'=>'Lokasi','koordinat'=>'Koordinat (Area)']],
                ['id' => 'DokLain', 'title' => '6) DOKUMEN LAINNYA', 'prefix' => 'dok_lain', 'fields' => ['jenis'=>'Jenis Dokumen','nomor'=>'Nomor','tgl'=>'Tanggal Terbit (Date)','penerbit'=>'Penerbit','lokasi'=>'Lokasi']],
            ];
            @endphp
            @foreach($docBlocks as $blk)
            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#col{{$blk['id']}}">{{$blk['title']}}</button></h2>
                <div id="col{{$blk['id']}}" class="accordion-collapse collapse" data-bs-parent="#accordionIzin">
                    <div class="accordion-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="{{$blk['prefix']}}_ada" value="1" @checked(old($blk['prefix'].'_ada', $ba->{$blk['prefix'].'_ada'} ?? false))>
                            <label class="form-check-label fw-bold">Dokumen Ada?</label>
                        </div>
                        <div class="row g-2">
                            @foreach($blk['fields'] as $fkey => $flabel)
                                @php $fname = $blk['prefix'].'_'.$fkey; $val = $ba?->$fname; @endphp
                                @if(str_contains($flabel, '(Date)'))
                                    <div class="col-md-6 mb-2"><label class="form-label text-muted mb-1">{{ str_replace(' (Date)','',$flabel) }}</label><input type="date" name="{{$fname}}" class="form-control form-control-sm" value="{{ old($fname, ($val instanceof \Carbon\Carbon ? $val->format('Y-m-d') : $val)) }}"></div>
                                @elseif(str_contains($flabel, '(Area)'))
                                    <div class="col-12 mb-2"><label class="form-label text-muted mb-1">{{ str_replace(' (Area)','',$flabel) }}</label><textarea name="{{$fname}}" class="form-control form-control-sm" rows="2">{{ old($fname, $val) }}</textarea></div>
                                @else
                                    <div class="col-md-6 mb-2"><label class="form-label text-muted mb-1">{{$flabel}}</label><input type="text" name="{{$fname}}" class="form-control form-control-sm" value="{{ old($fname, $val) }}"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- TAB 5: Pemenuhan & Kesimpulan --}}
    <div class="tab-pane fade" id="tab-pemenuhan">
        <div class="form-section-title">Pemeriksaan Pemenuhan Ketentuan Pemanfaatan PPK</div>
        <div class="row g-3 mb-4">
            <div class="col-md-9 col-8">30% luasan lahan yang dikelola untuk ruang terbuka hijau</div>
            <div class="col-md-3 col-4"><select name="pemenuhan_rth" class="form-select form-select-sm"><option value="">-</option><option value="S" @selected(old('pemenuhan_rth', $ba->pemenuhan_rth ?? '') == 'S')>S</option><option value="TS" @selected(old('pemenuhan_rth', $ba->pemenuhan_rth ?? '') == 'TS')>TS</option></select></div>
            
            <div class="col-md-9 col-8">Kegiatan pemanfaatan PPK sesuai dengan RTR</div>
            <div class="col-md-3 col-4"><select name="pemenuhan_rtr" class="form-select form-select-sm"><option value="">-</option><option value="S" @selected(old('pemenuhan_rtr', $ba->pemenuhan_rtr ?? '') == 'S')>S</option><option value="TS" @selected(old('pemenuhan_rtr', $ba->pemenuhan_rtr ?? '') == 'TS')>TS</option></select></div>
            
            <div class="col-md-9 col-8">Pemberian akses publik</div>
            <div class="col-md-3 col-4"><select name="pemenuhan_akses" class="form-select form-select-sm"><option value="">-</option><option value="S" @selected(old('pemenuhan_akses', $ba->pemenuhan_akses ?? '') == 'S')>S</option><option value="TS" @selected(old('pemenuhan_akses', $ba->pemenuhan_akses ?? '') == 'TS')>TS</option></select></div>
            
            <div class="col-md-9 col-8">Jenis kegiatan sesuai dengan luas, topografi dan tipologi pulau</div>
            <div class="col-md-3 col-4"><select name="pemenuhan_jenis" class="form-select form-select-sm"><option value="">-</option><option value="S" @selected(old('pemenuhan_jenis', $ba->pemenuhan_jenis ?? '') == 'S')>S</option><option value="TS" @selected(old('pemenuhan_jenis', $ba->pemenuhan_jenis ?? '') == 'TS')>TS</option></select></div>
        </div>

        <div class="form-section-title mt-4">Dugaan & Kesimpulan</div>
        <div class="mb-3 p-3 border rounded">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="dugaan_pelanggaran_ada" value="1" @checked(old('dugaan_pelanggaran_ada', $ba->dugaan_pelanggaran_ada ?? false))>
                <label class="form-check-label fw-bold">Dugaan Pelanggaran Ada?</label>
            </div>
            <label class="form-label text-muted">Jika ada, jelaskan:</label>
            <textarea name="dugaan_pelanggaran_ket" class="form-control" rows="2">{{ old('dugaan_pelanggaran_ket', $ba->dugaan_pelanggaran_ket ?? '') }}</textarea>
        </div>

        <div class="mb-3 p-3 border rounded">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="dugaan_kerusakan_ada" value="1" @checked(old('dugaan_kerusakan_ada', $ba->dugaan_kerusakan_ada ?? false))>
                <label class="form-check-label fw-bold">Dugaan Kerusakan/Pencemaran/Kerugian Masyarakat Ada?</label>
            </div>
            <label class="form-label text-muted">Jika ada, jelaskan:</label>
            <textarea name="dugaan_kerusakan_ket" class="form-control" rows="2">{{ old('dugaan_kerusakan_ket', $ba->dugaan_kerusakan_ket ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Kesimpulan</label>
            <textarea name="kesimpulan" class="form-control" rows="3">{{ old('kesimpulan', $ba->kesimpulan ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Rekomendasi</label>
            @php $savedRekom = old('rekomendasi_tindakan', $ba->rekomendasi_tindakan ?? []); if(!is_array($savedRekom)) $savedRekom = []; @endphp
            @foreach($rekomendasiOptions as $idx => $rek)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="rekomendasi_tindakan[]" value="{{ $rek }}" id="rek_{{$idx}}" @checked(in_array($rek, $savedRekom))>
                    <label class="form-check-label" for="rek_{{$idx}}">{{ $idx+1 }}. {{ $rek }}</label>
                </div>
            @endforeach
            <div class="mt-2">
                <label class="form-label text-muted">6. Lainnya:</label>
                <input type="text" name="rekomendasi_lainnya" class="form-control" value="{{ old('rekomendasi_lainnya', $ba->rekomendasi_lainnya ?? '') }}">
            </div>
        </div>
    </div>

    {{-- TAB 6: Pengesahan & File --}}
    <div class="tab-pane fade" id="tab-pengesahan">
        <div class="form-section-title"><i class="fas fa-signature me-1"></i> Tanda Tangan Pengesahan</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-12">
                <label class="form-label fw-semibold mb-2">Pelaku Usaha &mdash; <span class="text-primary sig-name-pj">{{ old('nama_pj', $ba->nama_pj ?? '') ?: 'belum diisi' }}</span></label>
                @include('ba-was-prl.partials.ttd-widget', ['name' => 'ttd_pelaku_usaha', 'existing' => $ba->ttd_pelaku_usaha ?? null, 'value' => old('ttd_pelaku_usaha', $ba->ttd_pelaku_usaha ?? '')])
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label fw-semibold mb-2">Polsus PWP3K / Pengawas</label>
                @include('ba-was-prl.partials.ttd-widget', ['name' => 'ttd_pengawas_1', 'existing' => $ba->ttd_pengawas_1 ?? null, 'value' => old('ttd_pengawas_1', $ba->ttd_pengawas_1 ?? '')])
            </div>
        </div>

        <div class="form-section-title mt-4"><i class="fas fa-check-circle me-1"></i> Status &amp; Berkas</div>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                <label class="form-label">Status Dokumen <span class="text-danger">*</span></label>
                <select name="status" class="form-select">
                    <option value="draft" @selected(old('status', $ba->status ?? 'draft') == 'draft')>Draft</option>
                    <option value="proses" @selected(old('status', $ba->status ?? '') == 'proses')>Proses</option>
                    <option value="selesai" @selected(old('status', $ba->status ?? '') == 'selesai')>Selesai</option>
                    <option value="tindak_lanjut" @selected(old('status', $ba->status ?? '') == 'tindak_lanjut')>Perlu Tindak Lanjut</option>
                </select>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Upload Foto Dokumentasi Lapangan</label>
                <input type="file" name="foto[]" class="form-control" multiple accept="image/*">
                <small class="text-muted">Bisa pilih lebih dari satu foto sekaligus.</small>
            </div>
        </div>

        @if(isset($ba) && $ba->fotos->count() > 0)
        <div class="mb-3 mt-3">
            <label class="form-label">Foto Terlampir:</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach($ba->fotos as $foto)
                    <div class="border rounded p-1 position-relative">
                        <img src="{{ asset('storage/' . $foto->path_foto) }}" alt="Foto" style="height:100px; width:auto; object-fit:cover; border-radius: 4px;">
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<div class="card fade-in mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('ba-ppk.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Simpan BA PPK</button>
        </div>
    </div>
</div>

@push('styles')
<style>
    .ttd-canvas { width: 100%; max-width: 420px; height: 140px; background: #fff; border: 1px dashed #adb5bd; border-radius: 6px; touch-action: none; cursor: crosshair; display: block; margin: 0 auto; }
</style>
@endpush

@push('scripts')
<script>
    $('#pelakuUsahaSelect').select2({ theme: 'bootstrap-5', width: '100%', tags: true, placeholder: '-- Pilih atau Ketik Nama Pelaku Usaha Baru --' });

    function initSignaturePad(wrap) {
        if (!wrap || wrap.dataset.ttdInit) return;
        wrap.dataset.ttdInit = '1';

        const canvas = wrap.querySelector('.ttd-canvas');
        const hiddenInput = wrap.querySelector('.ttd-hidden-input');
        const clearBtn = wrap.querySelector('.btn-clear-ttd');
        const fileInput = wrap.querySelector('.ttd-file-input');
        const previewBox = wrap.querySelector('.ttd-upload-preview');
        const modeBtns = wrap.querySelectorAll('.ttd-mode-btn');
        const drawSection = wrap.querySelector('.ttd-mode-draw');
        const uploadSection = wrap.querySelector('.ttd-mode-upload');
        if (!canvas || !hiddenInput) return;

        canvas.width = 500;
        canvas.height = 160;

        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.strokeStyle = '#1a1a1a'; ctx.lineWidth = 2.5; ctx.lineCap = 'round'; ctx.lineJoin = 'round';

        const existingUrl = wrap.dataset.existing;
        let hasSignature = !!existingUrl;
        let currentMode = 'draw';

        if (existingUrl) {
            const img = new Image();
            img.onload = function () { ctx.drawImage(img, 0, 0, canvas.width, canvas.height); };
            img.src = existingUrl;
            if (previewBox) {
                previewBox.innerHTML = '<img src="' + existingUrl + '" style="max-width:220px;max-height:130px;border-radius:6px;border:1px solid #dee2e6;"><button type="button" class="btn btn-sm btn-outline-danger mt-2 btn-hapus-upload-ttd"><i class="fas fa-times me-1"></i>Hapus Foto</button>';
            }
        }

        modeBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                currentMode = this.dataset.mode;
                modeBtns.forEach(function (b) { b.classList.toggle('active', b === btn); });
                if (drawSection) drawSection.style.display = currentMode === 'draw' ? '' : 'none';
                if (uploadSection) uploadSection.style.display = currentMode === 'upload' ? '' : 'none';
            });
        });

        let drawing = false, lastX = 0, lastY = 0;
        function getPos(evt) {
            const rect = canvas.getBoundingClientRect();
            const t = evt.touches && evt.touches[0];
            const clientX = t ? t.clientX : evt.clientX;
            const clientY = t ? t.clientY : evt.clientY;
            return [(clientX - rect.left) * (canvas.width / rect.width), (clientY - rect.top) * (canvas.height / rect.height)];
        }
        function start(evt) { drawing = true; hasSignature = true; [lastX, lastY] = getPos(evt); evt.preventDefault(); }
        function move(evt) { if (!drawing) return; const [x, y] = getPos(evt); ctx.beginPath(); ctx.moveTo(lastX, lastY); ctx.lineTo(x, y); ctx.stroke(); [lastX, lastY] = [x, y]; evt.preventDefault(); }
        function stop() { drawing = false; }

        canvas.addEventListener('mousedown', start); canvas.addEventListener('mousemove', move); document.addEventListener('mouseup', stop);
        canvas.addEventListener('touchstart', start, { passive: false }); canvas.addEventListener('touchmove', move, { passive: false }); canvas.addEventListener('touchend', stop);

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, canvas.width, canvas.height);
                hasSignature = false; hiddenInput.value = '';
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;
                if (!file.type.startsWith('image/')) { alert('File harus berupa gambar.'); this.value = ''; return; }
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = new Image();
                    img.onload = function () {
                        const tmp = document.createElement('canvas'); tmp.width = img.width; tmp.height = img.height;
                        const tctx = tmp.getContext('2d'); tctx.fillStyle = '#fff'; tctx.fillRect(0, 0, tmp.width, tmp.height); tctx.drawImage(img, 0, 0);
                        const dataUrl = tmp.toDataURL('image/jpeg', 0.85);
                        hiddenInput.value = dataUrl; hasSignature = true;
                        if (previewBox) {
                            previewBox.innerHTML = '<img src="' + dataUrl + '" style="max-width:220px;border-radius:6px;border:1px solid #dee2e6;"><button type="button" class="btn btn-sm btn-outline-danger mt-2 btn-hapus-upload-ttd"><i class="fas fa-times me-1"></i>Hapus Foto</button>';
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        wrap.addEventListener('click', function (evt) {
            if (evt.target.closest('.btn-hapus-upload-ttd')) {
                hiddenInput.value = ''; hasSignature = false;
                if (previewBox) previewBox.innerHTML = '';
                if (fileInput) fileInput.value = '';
            }
        });

        const form = canvas.closest('form');
        if (form) {
            form.addEventListener('submit', function () {
                if (currentMode === 'draw') { hiddenInput.value = hasSignature ? canvas.toDataURL('image/png') : ''; }
            });
        }
    }
    document.querySelectorAll('.signature-pad-wrap').forEach(initSignaturePad);

    $(document).on('input', '.pj-nama-input', function () { $('.sig-name-pj').text($(this).val().trim() || 'belum diisi'); });

    let pengawasIdx = {{ count($pengawasData ?: [0]) }};
    function buildPengawasRow(idx) {
        return `<div class="row g-2 mb-3 pengawas-row repeater-row" data-pengawas-id="${idx}">
            <div class="col-md-4 col-6"><input type="text" name="pengawas[${idx}][nama]" class="form-control form-control-sm pengawas-nama-input" placeholder="Nama Anggota"></div>
            <div class="col-md-3 col-6"><input type="text" name="pengawas[${idx}][nip]" class="form-control form-control-sm" placeholder="NIP / No. KTA"></div>
            <div class="col-md-4 col-10"><input type="text" name="pengawas[${idx}][jabatan]" class="form-control form-control-sm" placeholder="Jabatan"></div>
            <div class="col-md-1 col-2"><button type="button" class="btn btn-outline-danger btn-sm w-100 btn-hapus-pengawas" title="Hapus"><i class="fas fa-trash"></i></button></div>
        </div>`;
    }

    $('#btnTambahPengawas').on('click', function () {
        $('#pengawasWrapper').append($(buildPengawasRow(pengawasIdx)));
        pengawasIdx++;
    });
    $(document).on('click', '.btn-hapus-pengawas', function () {
        if ($('.pengawas-row').length > 1) { $(this).closest('.pengawas-row').remove(); }
        else { $(this).closest('.pengawas-row').find('input').val(''); }
    });
</script>
@endpush

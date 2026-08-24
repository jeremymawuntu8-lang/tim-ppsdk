@csrf

@php
    $pengawasData = old('pengawas', isset($baWasAlse) ? $baWasAlse->pengawas->toArray() : []);
    $saksiData = old('saksi', isset($baWasAlse) ? $baWasAlse->saksis->toArray() : []);
@endphp

<style>
    .form-section-title { font-weight: 700; font-size: .95rem; color: #444; margin-bottom: .75rem; padding-bottom: .35rem; border-bottom: 2px solid #e9ecef; }
    .nav-tabs .nav-link { font-weight: 600; color: #6c757d; }
    .nav-tabs .nav-link.active { color: var(--bs-primary, #0d6efd); }
    .ttd-canvas { width: 100%; max-width: 420px; height: 140px; background: #fff; border: 1px dashed #adb5bd; border-radius: 6px; touch-action: none; cursor: crosshair; display: block; }
    .signature-pad-wrap { background: #f8f9fa; border-radius: 8px; padding: 12px; }
    .repeater-row { background: #f8f9fa; border-radius: 8px; padding: 12px; }
    .ttd-pengawas-block, .ttd-saksi-block { background: #f8f9fa; border-radius: 8px; padding: 12px; margin-bottom: 1rem; }
    .ttd-mode-btn.active { background: var(--bs-primary,#0d6efd); color:#fff; border-color: var(--bs-primary,#0d6efd); }
    .ttd-upload-preview img { display:block; }
</style>

<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-utama" type="button"><i class="fas fa-info-circle me-1"></i> 1. Informasi Utama</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pengawas" type="button"><i class="fas fa-users me-1"></i> 2. Pengawas Bertugas</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pengawasan" type="button"><i class="fas fa-clipboard-check me-1"></i> 3. Form Pengawasan ALSE</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-usaha" type="button"><i class="fas fa-user-tie me-1"></i> 4. Pelaku Usaha &amp; Saksi</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pengesahan" type="button"><i class="fas fa-signature me-1"></i> 5. Pengesahan</button>
    </li>
</ul>

<div class="tab-content">

    {{-- =========================================================
         TAB 1 — INFORMASI UTAMA
    ========================================================== --}}
    <div class="tab-pane fade show active" id="tab-utama">
        <div class="row g-3">
            <div class="col-md-6 col-12">
                <label class="form-label">Nomor BA <span class="text-danger">*</span></label>
                <input type="text" name="nomor_ba" class="form-control" value="{{ old('nomor_ba', $baWasAlse->nomor_ba ?? '') }}" required placeholder="Nomor berita acara">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Pelaku Usaha <span class="text-danger">*</span></label>
                <select name="pelaku_usaha_id" id="pelakuUsahaSelect" class="form-select" required style="width: 100%;">
                    <option value="">-- Pilih atau Ketik Nama Baru --</option>
                    @foreach($pelakuUsahas as $p)
                        <option value="{{ $p->id }}" @selected(old('pelaku_usaha_id', $baWasAlse->pelaku_usaha_id ?? null) == $p->id)>{{ $p->nama_perusahaan }}</option>
                    @endforeach
                    @php
                        $oldPelakuUsaha = old('pelaku_usaha_id');
                        $pelakuUsahaFreeText = ($oldPelakuUsaha && !is_numeric($oldPelakuUsaha)) ? $oldPelakuUsaha : null;
                    @endphp
                    @if($pelakuUsahaFreeText)
                        <option value="{{ $pelakuUsahaFreeText }}" selected>{{ $pelakuUsahaFreeText }}</option>
                    @endif
                </select>
                <div class="form-text"><i class="fas fa-lightbulb me-1"></i> Belum ada di daftar? Ketik saja nama pelaku usaha baru — otomatis tersimpan.</div>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Tanggal Pengawasan <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_pengawasan" class="form-control" value="{{ old('tanggal_pengawasan', isset($baWasAlse) && $baWasAlse->tanggal_pengawasan ? $baWasAlse->tanggal_pengawasan->format('Y-m-d') : '') }}" required>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Jam (WITA)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                    <input type="time" name="jam_wita" class="form-control" value="{{ old('jam_wita', $baWasAlse->jam_wita ?? '') }}">
                </div>
            </div>
            <div class="col-md-8 col-12">
                <label class="form-label">Lokasi Pengawasan</label>
                <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $baWasAlse->lokasi ?? '') }}" placeholder="Alamat lengkap">
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label">Provinsi</label>
                <select name="provinsi_id" class="form-select select2" style="width: 100%;">
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach($provinsis as $prov)
                        <option value="{{ $prov->id }}" @selected(old('provinsi_id', $baWasAlse->provinsi_id ?? null) == $prov->id)>{{ $prov->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Titik Koordinat Geografis</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    <input type="text" name="titik_koordinat" class="form-control" placeholder="Koordinat lokasi" value="{{ old('titik_koordinat', $baWasAlse->titik_koordinat ?? '') }}">
                </div>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label text-muted">Latitude (Desimal)</label>
                <input type="text" name="latitude" class="form-control form-control-sm" value="{{ old('latitude', $baWasAlse->latitude ?? '') }}" placeholder="Latitude">
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label text-muted">Longitude (Desimal)</label>
                <input type="text" name="longitude" class="form-control form-control-sm" value="{{ old('longitude', $baWasAlse->longitude ?? '') }}" placeholder="Longitude">
            </div>
        </div>
    </div>

    {{-- =========================================================
         TAB 2 — PENGAWAS YANG BERTUGAS
    ========================================================== --}}
    <div class="tab-pane fade" id="tab-pengawas">
        <div class="row g-3 mb-3">
            <div class="col-md-6 col-12">
                <label class="form-label">Nomor Surat Tugas</label>
                <input type="text" name="no_surat_tugas" class="form-control" value="{{ old('no_surat_tugas', $baWasAlse->no_surat_tugas ?? '') }}" placeholder="Nomor surat tugas">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Unit Kerja</label>
                <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja', $baWasAlse->unit_kerja ?? 'Pangkalan PSDKP Bitung') }}">
            </div>
        </div>

        <div class="form-section-title"><i class="fas fa-user-shield me-1"></i> Ketua Tim Pengawas</div>
        <div class="row g-3 mb-2">
            <div class="col-md-4 col-12">
                <label class="form-label">Nama</label>
                <input type="text" name="ketua_tim_nama" class="form-control ketua-nama-input" value="{{ old('ketua_tim_nama', $baWasAlse->ketua_tim_nama ?? '') }}">
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label">NIP</label>
                <input type="text" name="ketua_tim_nip" class="form-control" value="{{ old('ketua_tim_nip', $baWasAlse->ketua_tim_nip ?? '') }}">
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label">Jabatan</label>
                <input type="text" name="ketua_tim_jabatan" class="form-control" value="{{ old('ketua_tim_jabatan', $baWasAlse->ketua_tim_jabatan ?? 'Ketua Tim') }}">
            </div>
        </div>
        <div class="form-text mb-4"><i class="fas fa-signature me-1"></i> Tanda tangan Ketua Tim diisi di tab <strong>5. Pengesahan</strong>.</div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label fw-bold mb-0">Anggota Tim Pengawas</label>
            <button type="button" id="btnTambahPengawas" class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="fas fa-plus"></i> Tambah Anggota</button>
        </div>

        <div id="pengawasWrapper">
            @forelse($pengawasData as $i => $pg)
                <div class="row g-2 mb-3 pengawas-row repeater-row" data-pengawas-id="{{ $i }}">
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[{{ $i }}][nama]" class="form-control form-control-sm pengawas-nama-input" placeholder="Nama Anggota" value="{{ $pg['nama'] ?? '' }}"></div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[{{ $i }}][nip]" class="form-control form-control-sm" placeholder="NIP" value="{{ $pg['nip'] ?? '' }}"></div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[{{ $i }}][jabatan]" class="form-control form-control-sm" placeholder="Jabatan" value="{{ $pg['jabatan'] ?? 'Anggota Tim' }}"></div>
                    <div class="col-md-2 col-6"><input type="text" name="pengawas[{{ $i }}][unit_kerja]" class="form-control form-control-sm" placeholder="Unit Kerja" value="{{ $pg['unit_kerja'] ?? '' }}"></div>
                    <div class="col-md-1 col-12"><button type="button" class="btn btn-outline-danger w-100 btn-hapus-pengawas" title="Hapus"><i class="fas fa-trash"></i></button></div>
                </div>
            @empty
                <div class="row g-2 mb-3 pengawas-row repeater-row" data-pengawas-id="0">
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[0][nama]" class="form-control form-control-sm pengawas-nama-input" placeholder="Nama Anggota"></div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[0][nip]" class="form-control form-control-sm" placeholder="NIP"></div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[0][jabatan]" class="form-control form-control-sm" placeholder="Jabatan" value="Anggota Tim"></div>
                    <div class="col-md-2 col-6"><input type="text" name="pengawas[0][unit_kerja]" class="form-control form-control-sm" placeholder="Unit Kerja"></div>
                    <div class="col-md-1 col-12"><button type="button" class="btn btn-outline-danger w-100 btn-hapus-pengawas" title="Hapus"><i class="fas fa-trash"></i></button></div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- =========================================================
         TAB 3 — FORM PENGAWASAN ALSE
    ========================================================== --}}
    <div class="tab-pane fade" id="tab-pengawasan">

        <div class="form-section-title"><i class="fas fa-tasks me-1"></i> Kegiatan &amp; Objek Pengawasan</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-12">
                <label class="form-label">Telah melakukan (Kategori Pengawasan)</label>
                <input type="text" name="kategori_pengawasan" class="form-control" value="{{ old('kategori_pengawasan', $baWasAlse->kategori_pengawasan ?? 'Pengawasan Pemanfaatan Air Laut Selain Energi') }}" placeholder="Kategori pengawasan">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Objek yang diawasi</label>
                <input type="text" name="objek_pengawasan" class="form-control" value="{{ old('objek_pengawasan', $baWasAlse->objek_pengawasan ?? 'Sarana Penampungan, Penjernihan dan Penyaluran Air Laut') }}" placeholder="Objek pengawasan">
            </div>
        </div>

        <div class="form-section-title"><i class="fas fa-file-contract me-1"></i> 2. Perizinan</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-12">
                <label class="form-label">a. Nomor NIB</label>
                <input type="text" name="nomor_nib" class="form-control" value="{{ old('nomor_nib', $baWasAlse->nomor_nib ?? '') }}" placeholder="Nomor NIB Pelaku Usaha">
            </div>
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-12 fw-semibold text-primary"><i class="fas fa-water me-1"></i> c. Perizinan Pemanfaatan ALSE</div>
            <div class="col-md-6 col-12">
                <label class="form-label">Jenis Kegiatan Usaha</label>
                <input type="text" name="jenis_kegiatan_usaha" class="form-control" value="{{ old('jenis_kegiatan_usaha', $baWasAlse->jenis_kegiatan_usaha ?? '') }}" placeholder="Jenis kegiatan usaha">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Penerbit Izin</label>
                <input type="text" name="penerbit_izin" class="form-control" value="{{ old('penerbit_izin', $baWasAlse->penerbit_izin ?? '') }}" placeholder="Penerbit izin">
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label">Nomor Izin</label>
                <input type="text" name="nomor_izin_alse" class="form-control" value="{{ old('nomor_izin_alse', $baWasAlse->nomor_izin_alse ?? '') }}">
            </div>
            <div class="col-md-4 col-6">
                <label class="form-label">Tanggal Terbit</label>
                <input type="date" name="tgl_terbit_izin_alse" class="form-control" value="{{ old('tgl_terbit_izin_alse', isset($baWasAlse) && $baWasAlse->tgl_terbit_izin_alse ? $baWasAlse->tgl_terbit_izin_alse->format('Y-m-d') : '') }}">
            </div>
            <div class="col-md-4 col-6">
                <label class="form-label">Masa Berlaku</label>
                <input type="text" name="masa_berlaku_izin_alse" class="form-control" value="{{ old('masa_berlaku_izin_alse', $baWasAlse->masa_berlaku_izin_alse ?? '') }}" placeholder="Masa berlaku">
            </div>

            <div class="col-12"><hr class="my-2"></div>
            <div class="col-12 fw-semibold text-primary"><i class="fas fa-folder-open me-1"></i> d. Dokumen Lain &amp; Kategori Kawasan</div>
            <div class="col-md-6 col-12">
                <label class="form-label">Nama Dokumen Lain</label>
                <input type="text" name="nama_dokumen_lain" class="form-control" value="{{ old('nama_dokumen_lain', $baWasAlse->nama_dokumen_lain ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Nomor Dokumen Lain</label>
                <input type="text" name="nomor_dokumen_lain" class="form-control" value="{{ old('nomor_dokumen_lain', $baWasAlse->nomor_dokumen_lain ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Kategori Kawasan Kegiatan</label>
                <input type="text" name="kategori_kawasan" class="form-control" value="{{ old('kategori_kawasan', $baWasAlse->kategori_kawasan ?? '') }}" placeholder="Kategori kawasan">
            </div>
        </div>

        <div class="form-section-title"><i class="fas fa-check-square me-1"></i> 3. Pemenuhan Ketentuan</div>
        <div class="row g-3 mb-4">
            <div class="col-12">
                <label class="form-label">Sub-Judul Pemenuhan Ketentuan</label>
                <input type="text" name="judul_pemenuhan_ketentuan" class="form-control" value="{{ old('judul_pemenuhan_ketentuan', $baWasAlse->judul_pemenuhan_ketentuan ?? 'Penampungan, Penjernihan dan Penyaluran Air Minum/Penampungan dan Penyaluran Air Baku') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Debit Volume Penggunaan Air Laut</label>
                <input type="text" name="debit_volume_air_laut" class="form-control" value="{{ old('debit_volume_air_laut', $baWasAlse->debit_volume_air_laut ?? '') }}" placeholder="Debit/volume air laut">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">a) Kesesuaian Kapasitas Pengambilan/Pemanfaatan Air Laut</label>
                <select name="kesesuaian_volume_air" class="form-select">
                    <option value="">-- Pilih Status Kesesuaian --</option>
                    @foreach(['Sesuai' => 'Sesuai', 'Tidak Sesuai' => 'Tidak Sesuai', 'Belum Beroperasi' => 'Belum Beroperasi'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('kesesuaian_volume_air', $baWasAlse->kesesuaian_volume_air ?? '') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">b) Kesesuaian Koordinat Inlet</label>
                <select name="kesesuaian_koordinat_inlet" class="form-select">
                    <option value="">-- Pilih Status Kesesuaian --</option>
                    @foreach(['Sesuai' => 'Sesuai', 'Tidak Sesuai' => 'Tidak Sesuai'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('kesesuaian_koordinat_inlet', $baWasAlse->kesesuaian_koordinat_inlet ?? '') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-section-title"><i class="fas fa-exclamation-triangle me-1"></i> 4, 5 &amp; 6. Dugaan Pelanggaran, Analisa &amp; Rekomendasi</div>
        <div class="row g-3 mb-3">
            <div class="col-md-6 col-12">
                <label class="form-label">4. Status Dugaan Pelanggaran</label>
                <input type="text" name="dugaan_pelanggaran" class="form-control" value="{{ old('dugaan_pelanggaran', $baWasAlse->dugaan_pelanggaran ?? 'Tidak Ada') }}" placeholder="Dugaan pelanggaran">
            </div>
            <div class="col-12">
                <label class="form-label">Penjelasan Dugaan Pelanggaran</label>
                <textarea name="penjelasan_dugaan_pelanggaran" class="form-control" rows="2" placeholder="Tulis rincian atau keterangan dugaan pelanggaran">{{ old('penjelasan_dugaan_pelanggaran', $baWasAlse->penjelasan_dugaan_pelanggaran ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">5. Analisa Pengawasan</label>
                <textarea name="analisa_pengawasan" class="form-control" rows="4" placeholder="Analisa hasil pengawasan lapangan">{{ old('analisa_pengawasan', $baWasAlse->analisa_pengawasan ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">6. Rekomendasi</label>
                <textarea name="rekomendasi" class="form-control" rows="4" placeholder="Tuliskan poin rekomendasi hasil pengawasan">{{ old('rekomendasi', $baWasAlse->rekomendasi ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- =========================================================
         TAB 4 — PELAKU USAHA & SAKSI
    ========================================================== --}}
    <div class="tab-pane fade" id="tab-usaha">
        <div class="form-section-title"><i class="fas fa-user-tie me-1"></i> 1. Identitas Pelaku Usaha / Kegiatan</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-12">
                <label class="form-label">a. Nama Usaha / Kegiatan</label>
                <input type="text" name="nama_usaha" class="form-control" placeholder="Isi jika berbeda dengan nama Pelaku Usaha" value="{{ old('nama_usaha', $baWasAlse->nama_usaha ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Nama Penanggung Jawab Usaha</label>
                <input type="text" name="penanggung_jawab_usaha" class="form-control pj-nama-input" value="{{ old('penanggung_jawab_usaha', $baWasAlse->penanggung_jawab_usaha ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Jabatan Penanggung Jawab</label>
                <input type="text" name="jabatan_pj_usaha" class="form-control" value="{{ old('jabatan_pj_usaha', $baWasAlse->jabatan_pj_usaha ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">b. No. Identitas (NIK / ID)</label>
                <input type="text" name="no_identitas" class="form-control" value="{{ old('no_identitas', $baWasAlse->no_identitas ?? '') }}" placeholder="NIK Penanggung Jawab / No Identitas Usaha">
            </div>
            <div class="col-12">
                <label class="form-label">c. Alamat Perusahaan / Kantor</label>
                <textarea name="alamat_kantor" class="form-control" rows="2">{{ old('alamat_kantor', $baWasAlse->alamat_kantor ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">d. Alamat Kegiatan</label>
                <textarea name="alamat_kegiatan" class="form-control" rows="2">{{ old('alamat_kegiatan', $baWasAlse->alamat_kegiatan ?? '') }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label fw-bold mb-0">Daftar Saksi (Opsional)</label>
            <button type="button" id="btnTambahSaksi" class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="fas fa-plus"></i> Tambah Saksi</button>
        </div>

        <div id="saksiWrapper">
            @forelse($saksiData as $i => $sk)
                <div class="saksi-row repeater-row mb-3" data-saksi-id="{{ $i }}">
                    <div class="row g-2">
                        <div class="col-md-6 col-12"><input type="text" name="saksi[{{ $i }}][nama]" class="form-control form-control-sm saksi-nama-input" placeholder="Nama Saksi" value="{{ $sk['nama'] ?? '' }}"></div>
                        <div class="col-md-5 col-10"><input type="text" name="saksi[{{ $i }}][pekerjaan]" class="form-control form-control-sm" placeholder="Pekerjaan" value="{{ $sk['pekerjaan'] ?? '' }}"></div>
                        <div class="col-md-1 col-2"><button type="button" class="btn btn-outline-danger w-100 btn-hapus-saksi" title="Hapus"><i class="fas fa-trash"></i></button></div>
                        <div class="col-12"><input type="text" name="saksi[{{ $i }}][alamat]" class="form-control form-control-sm mt-1" placeholder="Alamat" value="{{ $sk['alamat'] ?? '' }}"></div>
                    </div>
                </div>
            @empty
                <div class="saksi-row repeater-row mb-3" data-saksi-id="0">
                    <div class="row g-2">
                        <div class="col-md-6 col-12"><input type="text" name="saksi[0][nama]" class="form-control form-control-sm saksi-nama-input" placeholder="Nama Saksi"></div>
                        <div class="col-md-5 col-10"><input type="text" name="saksi[0][pekerjaan]" class="form-control form-control-sm" placeholder="Pekerjaan"></div>
                        <div class="col-md-1 col-2"><button type="button" class="btn btn-outline-danger w-100 btn-hapus-saksi" title="Hapus"><i class="fas fa-trash"></i></button></div>
                        <div class="col-12"><input type="text" name="saksi[0][alamat]" class="form-control form-control-sm mt-1" placeholder="Alamat"></div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- =========================================================
         TAB 5 — PENGESAHAN (Tanda tangan)
    ========================================================== --}}
    <div class="tab-pane fade" id="tab-pengesahan">

        <div class="form-section-title"><i class="fas fa-signature me-1"></i> Tanda Tangan Pengesahan Dokumen</div>
        <p class="text-muted small mb-3">Untuk tiap pihak, pilih cara <strong>Gambar</strong> langsung atau <strong>Upload Foto</strong> tanda tangan.</p>

        <div class="row g-3 mb-2">
            <div class="col-md-6 col-12">
                <label class="form-label fw-semibold mb-2">Polsus PWP3K / Ketua Tim &mdash; <span class="text-primary sig-name-ketua">{{ old('ketua_tim_nama', $baWasAlse->ketua_tim_nama ?? '') ?: 'belum diisi' }}</span></label>
                @include('ba-was-alse.partials.ttd-widget', ['name' => 'ketua_tim_tanda_tangan', 'existing' => $baWasAlse->ketua_tim_tanda_tangan ?? null, 'value' => old('ketua_tim_tanda_tangan', $baWasAlse->ketua_tim_tanda_tangan ?? '')])
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label fw-semibold mb-2">Pelaku Usaha / PJ Usaha &mdash; <span class="text-primary sig-name-pj">{{ old('penanggung_jawab_usaha', $baWasAlse->penanggung_jawab_usaha ?? '') ?: 'belum diisi' }}</span></label>
                @include('ba-was-alse.partials.ttd-widget', ['name' => 'pj_usaha_tanda_tangan', 'existing' => $baWasAlse->pj_usaha_tanda_tangan ?? null, 'value' => old('pj_usaha_tanda_tangan', $baWasAlse->pj_usaha_tanda_tangan ?? '')])
            </div>
        </div>

        <div class="form-section-title mt-4"><i class="fas fa-users me-1"></i> Tanda Tangan Anggota Pengawas</div>
        <div id="ttdPengawasWrapper" class="row g-3">
            @forelse($pengawasData as $i => $pg)
                <div class="col-md-6 col-12 ttd-pengawas-block" data-pengawas-id="{{ $i }}">
                    <label class="form-label fw-semibold mb-2 sig-name-label">{{ $pg['nama'] ?: 'Anggota Pengawas' }}</label>
                    @include('ba-was-alse.partials.ttd-widget', ['name' => "pengawas[{$i}][tanda_tangan]", 'existing' => $pg['tanda_tangan'] ?? null, 'value' => $pg['tanda_tangan'] ?? ''])
                </div>
            @empty
                <div class="col-md-6 col-12 ttd-pengawas-block" data-pengawas-id="0">
                    <label class="form-label fw-semibold mb-2 sig-name-label">Anggota Pengawas</label>
                    @include('ba-was-alse.partials.ttd-widget', ['name' => 'pengawas[0][tanda_tangan]', 'existing' => null, 'value' => ''])
                </div>
            @endforelse
        </div>

        <div class="form-section-title mt-4"><i class="fas fa-handshake me-1"></i> Tanda Tangan Saksi</div>
        <div id="ttdSaksiWrapper" class="row g-3 mb-2">
            @forelse($saksiData as $i => $sk)
                <div class="col-md-6 col-12 ttd-saksi-block" data-saksi-id="{{ $i }}">
                    <label class="form-label fw-semibold mb-2 sig-name-label">{{ $sk['nama'] ?: 'Saksi' }}</label>
                    @include('ba-was-alse.partials.ttd-widget', ['name' => "saksi[{$i}][tanda_tangan]", 'existing' => $sk['tanda_tangan'] ?? null, 'value' => $sk['tanda_tangan'] ?? ''])
                </div>
            @empty
                <div class="col-md-6 col-12 ttd-saksi-block" data-saksi-id="0">
                    <label class="form-label fw-semibold mb-2 sig-name-label">Saksi</label>
                    @include('ba-was-alse.partials.ttd-widget', ['name' => 'saksi[0][tanda_tangan]', 'existing' => null, 'value' => ''])
                </div>
            @endforelse
        </div>

        <div class="form-section-title mt-4"><i class="fas fa-check-circle me-1"></i> Status &amp; Berkas</div>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                <label class="form-label">Status BA <span class="text-danger">*</span></label>
                <select name="status" class="form-select">
                    @foreach(['draft'=>'Draft','proses'=>'Proses','selesai'=>'Selesai','tindak_lanjut'=>'Tindak Lanjut'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('status', $baWasAlse->status ?? 'draft') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Upload BA (PDF)</label>
                <input type="file" name="file_ba_pdf" class="form-control" accept="application/pdf">
                @if(!empty($baWasAlse) && $baWasAlse->file_ba_pdf)
                    <div class="mt-2 text-sm">
                        <a href="{{ asset('storage/'.$baWasAlse->file_ba_pdf) }}" target="_blank"><i class="fas fa-file-pdf me-1 text-danger"></i>Lihat File Saat Ini</a>
                    </div>
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Upload Foto Dokumentasi</label>
                <input type="file" name="foto[]" class="form-control" accept="image/*" multiple>
                <div class="form-text">Bisa pilih lebih dari 1 file gambar.</div>
                @if(!empty($baWasAlse) && $baWasAlse->fotos->count())
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach($baWasAlse->fotos as $foto)
                            <a href="{{ asset('storage/'.$foto->path_foto) }}" target="_blank">
                                <img src="{{ asset('storage/'.$foto->path_foto) }}" style="width:70px;height:70px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Catatan Pengesahan <span class="text-muted small">(catatan internal)</span></label>
                <textarea name="catatan_pengesahan" class="form-control" rows="2">{{ old('catatan_pengesahan', $baWasAlse->catatan_pengesahan ?? '') }}</textarea>
            </div>
        </div>
    </div>

</div>

<div class="card fade-in mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('ba-was-alse.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Simpan BA WAS ALSE</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ================= Select2 Pelaku Usaha =================
    $('#pelakuUsahaSelect').select2({
        theme: 'bootstrap-5',
        width: '100%',
        tags: true,
        placeholder: '-- Pilih atau Ketik Nama Pelaku Usaha Baru --',
        language: {
            noResults: function () { return 'Tidak ditemukan. Ketik nama baru lalu tekan Enter.'; },
        },
    });

    // ================= Signature Pad: Gambar & Upload =================
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
        ctx.fillStyle = '#fff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.strokeStyle = '#1a1a1a';
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        const existingUrl = wrap.dataset.existing;
        let hasSignature = !!existingUrl;
        let currentMode = 'draw';

        if (existingUrl) {
            const img = new Image();
            img.onload = function () { ctx.drawImage(img, 0, 0, canvas.width, canvas.height); };
            img.src = existingUrl;
            if (previewBox) {
                previewBox.innerHTML = '<img src="' + existingUrl + '" style="max-width:220px;max-height:130px;border-radius:6px;border:1px solid #dee2e6;">' +
                    '<button type="button" class="btn btn-sm btn-outline-danger mt-2 btn-hapus-upload-ttd"><i class="fas fa-times me-1"></i>Hapus Foto</button>';
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
            return [
                (clientX - rect.left) * (canvas.width / rect.width),
                (clientY - rect.top) * (canvas.height / rect.height),
            ];
        }
        function start(evt) { drawing = true; hasSignature = true; [lastX, lastY] = getPos(evt); evt.preventDefault(); }
        function move(evt) {
            if (!drawing) return;
            const [x, y] = getPos(evt);
            ctx.beginPath(); ctx.moveTo(lastX, lastY); ctx.lineTo(x, y); ctx.stroke();
            [lastX, lastY] = [x, y];
            evt.preventDefault();
        }
        function stop() { drawing = false; }

        canvas.addEventListener('mousedown', start);
        canvas.addEventListener('mousemove', move);
        document.addEventListener('mouseup', stop);
        canvas.addEventListener('touchstart', start, { passive: false });
        canvas.addEventListener('touchmove', move, { passive: false });
        canvas.addEventListener('touchend', stop);

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                ctx.fillStyle = '#fff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                hasSignature = false;
                hiddenInput.value = '';
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;
                if (!file.type.startsWith('image/')) {
                    alert('File harus berupa gambar (JPG/PNG).');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = new Image();
                    img.onload = function () {
                        const maxW = 700;
                        const scale = Math.min(1, maxW / img.width);
                        const tmp = document.createElement('canvas');
                        tmp.width = Math.round(img.width * scale);
                        tmp.height = Math.round(img.height * scale);
                        const tctx = tmp.getContext('2d');
                        tctx.fillStyle = '#fff';
                        tctx.fillRect(0, 0, tmp.width, tmp.height);
                        tctx.drawImage(img, 0, 0, tmp.width, tmp.height);
                        const dataUrl = tmp.toDataURL('image/jpeg', 0.85);
                        hiddenInput.value = dataUrl;
                        hasSignature = true;
                        if (previewBox) {
                            previewBox.innerHTML = '<img src="' + dataUrl + '" style="max-width:220px;max-height:130px;border-radius:6px;border:1px solid #dee2e6;">' +
                                '<button type="button" class="btn btn-sm btn-outline-danger mt-2 btn-hapus-upload-ttd"><i class="fas fa-times me-1"></i>Hapus Foto</button>';
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        wrap.addEventListener('click', function (evt) {
            if (evt.target.closest('.btn-hapus-upload-ttd')) {
                hiddenInput.value = '';
                hasSignature = false;
                if (previewBox) previewBox.innerHTML = '';
                if (fileInput) fileInput.value = '';
            }
        });

        const form = canvas.closest('form');
        if (form) {
            form.addEventListener('submit', function () {
                if (currentMode === 'draw') {
                    hiddenInput.value = hasSignature ? canvas.toDataURL('image/png') : '';
                }
            });
        }
    }

    document.querySelectorAll('.signature-pad-wrap').forEach(initSignaturePad);

    // ================= Sinkron Nama -> Tab Pengesahan =================
    $(document).on('input', '.ketua-nama-input', function () {
        $('.sig-name-ketua').text($(this).val().trim() || 'belum diisi');
    });
    $(document).on('input', '.pj-nama-input', function () {
        $('.sig-name-pj').text($(this).val().trim() || 'belum diisi');
    });

    // ================= Repeater: Anggota Pengawas =================
    let pengawasIdx = {{ count($pengawasData ?: [0]) }};

    function buildPengawasRow(idx) {
        return `<div class="row g-2 mb-3 pengawas-row repeater-row" data-pengawas-id="${idx}">
            <div class="col-md-3 col-6"><input type="text" name="pengawas[${idx}][nama]" class="form-control form-control-sm pengawas-nama-input" placeholder="Nama Anggota"></div>
            <div class="col-md-3 col-6"><input type="text" name="pengawas[${idx}][nip]" class="form-control form-control-sm" placeholder="NIP"></div>
            <div class="col-md-3 col-6"><input type="text" name="pengawas[${idx}][jabatan]" class="form-control form-control-sm" placeholder="Jabatan" value="Anggota Tim"></div>
            <div class="col-md-2 col-6"><input type="text" name="pengawas[${idx}][unit_kerja]" class="form-control form-control-sm" placeholder="Unit Kerja"></div>
            <div class="col-md-1 col-12"><button type="button" class="btn btn-outline-danger w-100 btn-hapus-pengawas" title="Hapus"><i class="fas fa-trash"></i></button></div>
        </div>`;
    }
    function buildTtdWidgetHtml(name) {
        return `<div class="signature-pad-wrap" data-existing="">
            <div class="btn-group btn-group-sm ttd-mode-tabs mb-2" role="group">
                <button type="button" class="btn btn-outline-primary active ttd-mode-btn" data-mode="draw"><i class="fas fa-pen me-1"></i>Gambar</button>
                <button type="button" class="btn btn-outline-primary ttd-mode-btn" data-mode="upload"><i class="fas fa-camera me-1"></i>Upload Foto</button>
            </div>
            <div class="ttd-mode-draw">
                <canvas class="ttd-canvas"></canvas>
                <button type="button" class="btn btn-sm btn-outline-secondary btn-clear-ttd mt-2"><i class="fas fa-eraser me-1"></i>Kosongkan</button>
            </div>
            <div class="ttd-mode-upload" style="display:none;">
                <input type="file" class="form-control form-control-sm ttd-file-input" accept="image/*">
                <div class="form-text">Foto tanda tangan di kertas putih, hasil scan, atau stempel digital.</div>
                <div class="ttd-upload-preview mt-2"></div>
            </div>
            <input type="hidden" name="${name}" class="ttd-hidden-input" value="">
        </div>`;
    }
    function buildPengawasSigBlock(idx) {
        return `<div class="col-md-6 col-12 ttd-pengawas-block" data-pengawas-id="${idx}">
            <label class="form-label fw-semibold mb-2 sig-name-label">Anggota Pengawas</label>
            ${buildTtdWidgetHtml('pengawas[' + idx + '][tanda_tangan]')}
        </div>`;
    }

    $('#btnTambahPengawas').on('click', function () {
        let $row = $(buildPengawasRow(pengawasIdx));
        $('#pengawasWrapper').append($row);
        let $sig = $(buildPengawasSigBlock(pengawasIdx));
        $('#ttdPengawasWrapper').append($sig);
        initSignaturePad($sig.find('.signature-pad-wrap')[0]);
        pengawasIdx++;
    });
    $(document).on('click', '.btn-hapus-pengawas', function () {
        if ($('.pengawas-row').length > 1) {
            let id = $(this).closest('.pengawas-row').data('pengawas-id');
            $(this).closest('.pengawas-row').remove();
            $(`#ttdPengawasWrapper .ttd-pengawas-block[data-pengawas-id="${id}"]`).remove();
        }
    });
    $(document).on('input', '.pengawas-nama-input', function () {
        let id = $(this).closest('.pengawas-row').data('pengawas-id');
        let val = $(this).val().trim() || 'Anggota Pengawas';
        $(`#ttdPengawasWrapper .ttd-pengawas-block[data-pengawas-id="${id}"] .sig-name-label`).text(val);
    });

    // ================= Repeater: Saksi =================
    let saksiIdx = {{ count($saksiData ?: [0]) }};

    function buildSaksiRow(idx) {
        return `<div class="saksi-row repeater-row mb-3" data-saksi-id="${idx}">
            <div class="row g-2">
                <div class="col-md-6 col-12"><input type="text" name="saksi[${idx}][nama]" class="form-control form-control-sm saksi-nama-input" placeholder="Nama Saksi"></div>
                <div class="col-md-5 col-10"><input type="text" name="saksi[${idx}][pekerjaan]" class="form-control form-control-sm" placeholder="Pekerjaan"></div>
                <div class="col-md-1 col-2"><button type="button" class="btn btn-outline-danger w-100 btn-hapus-saksi" title="Hapus"><i class="fas fa-trash"></i></button></div>
                <div class="col-12"><input type="text" name="saksi[${idx}][alamat]" class="form-control form-control-sm mt-1" placeholder="Alamat"></div>
            </div>
        </div>`;
    }
    function buildSaksiSigBlock(idx) {
        return `<div class="col-md-6 col-12 ttd-saksi-block" data-saksi-id="${idx}">
            <label class="form-label fw-semibold mb-2 sig-name-label">Saksi</label>
            ${buildTtdWidgetHtml('saksi[' + idx + '][tanda_tangan]')}
        </div>`;
    }

    $('#btnTambahSaksi').on('click', function () {
        let $row = $(buildSaksiRow(saksiIdx));
        $('#saksiWrapper').append($row);
        let $sig = $(buildSaksiSigBlock(saksiIdx));
        $('#ttdSaksiWrapper').append($sig);
        initSignaturePad($sig.find('.signature-pad-wrap')[0]);
        saksiIdx++;
    });
    $(document).on('click', '.btn-hapus-saksi', function () {
        if ($('.saksi-row').length > 1) {
            let id = $(this).closest('.saksi-row').data('saksi-id');
            $(this).closest('.saksi-row').remove();
            $(`#ttdSaksiWrapper .ttd-saksi-block[data-saksi-id="${id}"]`).remove();
        }
    });
    $(document).on('input', '.saksi-nama-input', function () {
        let id = $(this).closest('.saksi-row').data('saksi-id');
        let val = $(this).val().trim() || 'Saksi';
        $(`#ttdSaksiWrapper .ttd-saksi-block[data-saksi-id="${id}"] .sig-name-label`).text(val);
    });
</script>
@endpush

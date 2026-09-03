@csrf

@php
    $pengawasData = old('pengawas', isset($baWasPrl) ? $baWasPrl->pengawas->toArray() : []);
    $saksiData = old('saksi', isset($baWasPrl) ? $baWasPrl->saksis->toArray() : []);
    $storageUrl = fn ($path) => $path ? asset('storage/' . $path) : '';
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
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pengawasan" type="button"><i class="fas fa-clipboard-check me-1"></i> 3. Form Pengawasan</button>
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
                <label class="form-label">Nomor BA <span class="text-muted small">(Opsional)</span></label>
                <input type="text" name="nomor_ba" class="form-control" value="{{ old('nomor_ba', $baWasPrl->nomor_ba ?? '') }}" placeholder="Kosongkan untuk otomatis dibuat sistem">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Pelaku Usaha <span class="text-danger">*</span></label>
                <select name="pelaku_usaha_id" id="pelakuUsahaSelect" class="form-select" required style="width: 100%;">
                    <option value="">-- Pilih atau Ketik Nama Baru --</option>
                    @foreach($pelakuUsahas as $p)
                        <option value="{{ $p->id }}" @selected(old('pelaku_usaha_id', $baWasPrl->pelaku_usaha_id ?? null) == $p->id)>{{ $p->nama_perusahaan }}</option>
                    @endforeach
                    @php
                        $oldPelakuUsaha = old('pelaku_usaha_id');
                        $pelakuUsahaFreeText = ($oldPelakuUsaha && !is_numeric($oldPelakuUsaha)) ? $oldPelakuUsaha : null;
                    @endphp
                    @if($pelakuUsahaFreeText)
                        <option value="{{ $pelakuUsahaFreeText }}" selected>{{ $pelakuUsahaFreeText }}</option>
                    @endif
                </select>
                <div class="form-text"><i class="fas fa-lightbulb me-1"></i> Belum ada di daftar? Ketik saja nama pelaku usaha baru — otomatis tersimpan dan bisa dipilih lagi untuk BA berikutnya.</div>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Tanggal Pengawasan <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_pengawasan" class="form-control" value="{{ old('tanggal_pengawasan', isset($baWasPrl) ? $baWasPrl->tanggal_pengawasan->format('Y-m-d') : '') }}" required>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Jam (WITA)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                    <input type="time" name="jam_wita" class="form-control" value="{{ old('jam_wita', $baWasPrl->jam_wita ?? '') }}">
                </div>
                <div class="form-text">Dicetak sebagai "pukul .. WITA" pada dokumen.</div>
            </div>
            <div class="col-12">
                <label class="form-label">Lokasi Pengawasan</label>
                <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $baWasPrl->lokasi ?? '') }}" placeholder="Alamat lengkap">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Nama Penanggung Jawab</label>
                <input type="text" name="penanggung_jawab_usaha" class="form-control pj-nama-input" value="{{ old('penanggung_jawab_usaha', $baWasPrl->penanggung_jawab_usaha ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Jabatan Penanggung Jawab</label>
                <input type="text" name="jabatan_pj_usaha" class="form-control" value="{{ old('jabatan_pj_usaha', $baWasPrl->jabatan_pj_usaha ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label text-muted">Latitude (Desimal)</label>
                <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $baWasPrl->latitude ?? '') }}" placeholder="Contoh: 1.45567">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label text-muted">Longitude (Desimal)</label>
                <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $baWasPrl->longitude ?? '') }}" placeholder="Contoh: 125.1873">
            </div>
        </div>
    </div>

    {{-- =========================================================
         TAB 2 — PENGAWAS YANG BERTUGAS
    ========================================================== --}}
    <div class="tab-pane fade" id="tab-pengawas">
        <div class="row g-3 mb-3">
            <div class="col-12">
                <label class="form-label">No. Surat Tugas / Tanggal / Bulan / Tahun</label>
                <input type="text" name="no_surat_tugas" class="form-control" value="{{ old('no_surat_tugas', $baWasPrl->no_surat_tugas ?? '') }}" placeholder="Nomor surat tugas">
            </div>
        </div>

        <div class="form-section-title"><i class="fas fa-user-shield me-1"></i> Ketua Tim</div>
        <div class="row g-3 mb-2">
            <div class="col-md-3 col-6">
                <label class="form-label">Nama</label>
                <input type="text" name="ketua_tim_nama" class="form-control ketua-nama-input" value="{{ old('ketua_tim_nama', $baWasPrl->ketua_tim_nama ?? '') }}">
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">NIP</label>
                <input type="text" name="ketua_tim_nip" class="form-control" value="{{ old('ketua_tim_nip', $baWasPrl->ketua_tim_nip ?? '') }}">
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Jabatan</label>
                <input type="text" name="ketua_tim_jabatan" class="form-control" value="{{ old('ketua_tim_jabatan', $baWasPrl->ketua_tim_jabatan ?? '') }}">
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Unit Kerja</label>
                <input type="text" name="ketua_tim_unit_kerja" class="form-control" value="{{ old('ketua_tim_unit_kerja', $baWasPrl->ketua_tim_unit_kerja ?? '') }}" placeholder="Unit kerja">
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
                    <div class="col-md-2 col-6"><input type="text" name="pengawas[{{ $i }}][nip]" class="form-control form-control-sm" placeholder="NIP" value="{{ $pg['nip'] ?? '' }}"></div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[{{ $i }}][jabatan]" class="form-control form-control-sm" placeholder="Jabatan" value="{{ $pg['jabatan'] ?? '' }}"></div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[{{ $i }}][unit_kerja]" class="form-control form-control-sm" placeholder="Unit Kerja" value="{{ $pg['unit_kerja'] ?? '' }}"></div>
                    <div class="col-md-1 col-12"><button type="button" class="btn btn-outline-danger w-100 btn-hapus-pengawas" title="Hapus"><i class="fas fa-trash"></i></button></div>
                </div>
            @empty
                <div class="row g-2 mb-3 pengawas-row repeater-row" data-pengawas-id="0">
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[0][nama]" class="form-control form-control-sm pengawas-nama-input" placeholder="Nama Anggota"></div>
                    <div class="col-md-2 col-6"><input type="text" name="pengawas[0][nip]" class="form-control form-control-sm" placeholder="NIP"></div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[0][jabatan]" class="form-control form-control-sm" placeholder="Jabatan"></div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[0][unit_kerja]" class="form-control form-control-sm" placeholder="Unit Kerja"></div>
                    <div class="col-md-1 col-12"><button type="button" class="btn btn-outline-danger w-100 btn-hapus-pengawas" title="Hapus"><i class="fas fa-trash"></i></button></div>
                </div>
            @endforelse
        </div>
        <div class="form-text"><i class="fas fa-signature me-1"></i> Tanda tangan tiap anggota diisi di tab <strong>5. Pengesahan</strong> (mengikuti nama yang diisi di sini).</div>
    </div>

    {{-- =========================================================
         TAB 3 — FORM PENGAWASAN
    ========================================================== --}}
    <div class="tab-pane fade" id="tab-pengawasan">

        <div class="form-section-title"><i class="fas fa-building me-1"></i> Detail Pelaku Usaha</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-12">
                <label class="form-label">Jenis Usaha</label>
                <input type="text" name="jenis_usaha" class="form-control" placeholder="Isi jika berbeda dengan data Pelaku Usaha" value="{{ old('jenis_usaha', $baWasPrl->jenis_usaha ?? '') }}">
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Luas Area</label>
                <input type="text" name="luas_area" class="form-control" placeholder="Luas area" value="{{ old('luas_area', $baWasPrl->luas_area ?? '') }}">
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Provinsi</label>
                <select name="provinsi_id" class="form-select select2" style="width:100%;">
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach($provinsis as $prov)
                        <option value="{{ $prov->id }}" @selected(old('provinsi_id', $baWasPrl->provinsi_id ?? null) == $prov->id)>{{ $prov->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label d-block">Metode Pengamatan</label>
                <div class="btn-group" role="group">
                    @foreach(['langsung' => 'Langsung', 'tidak_langsung' => 'Tidak Langsung'] as $val => $label)
                        <input type="radio" class="btn-check" name="metode_pengamatan" id="metode_{{ $val }}" value="{{ $val }}" @checked(old('metode_pengamatan', $baWasPrl->metode_pengamatan ?? 'langsung') == $val)>
                        <label class="btn btn-outline-primary" for="metode_{{ $val }}">{{ $label }}</label>
                    @endforeach
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">KSN/KSNT/Perda RZWP3K</label>
                <textarea name="nomor_perda_rzwp3k" class="form-control" rows="2">{{ old('nomor_perda_rzwp3k', $baWasPrl->nomor_perda_rzwp3k ?? '') }}</textarea>
            </div>
        </div>

        <div class="form-section-title"><i class="fas fa-file-contract me-1"></i> Kesesuaian Kegiatan Pemanfaatan Ruang Laut (KKPRL)</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-12">
                <label class="form-label">Nomor PKKPRL</label>
                <input type="text" name="nomor_pkkprl" class="form-control" value="{{ old('nomor_pkkprl', $baWasPrl->nomor_pkkprl ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Tanggal Terbit PKKPRL</label>
                <input type="date" name="tgl_terbit_pkkprl" class="form-control" value="{{ old('tgl_terbit_pkkprl', isset($baWasPrl) && $baWasPrl->tgl_terbit_pkkprl ? $baWasPrl->tgl_terbit_pkkprl->format('Y-m-d') : '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Instansi Penerbit</label>
                <input type="text" name="kkprl_instansi_penerbit" class="form-control" value="{{ old('kkprl_instansi_penerbit', $baWasPrl->kkprl_instansi_penerbit ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Masa Berlaku</label>
                <input type="text" name="kkprl_masa_berlaku" class="form-control" placeholder="Masa berlaku" value="{{ old('kkprl_masa_berlaku', $baWasPrl->kkprl_masa_berlaku ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label d-block">Status Kesesuaian KKPRL</label>
                <div class="btn-group" role="group">
                    @foreach(['sesuai' => 'Ya, Sesuai', 'tidak_sesuai' => 'Tidak Sesuai'] as $val => $label)
                        <input type="radio" class="btn-check" name="status_kesesuaian_kkprl" id="status_kkprl_{{ $val }}" value="{{ $val }}" @checked(old('status_kesesuaian_kkprl', $baWasPrl->status_kesesuaian_kkprl ?? null) == $val)>
                        <label class="btn btn-outline-primary" for="status_kkprl_{{ $val }}">{{ $label }}</label>
                    @endforeach
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Catatan Dokumen PKKPRL <span class="text-muted small">— juga tercetak sebagai "Catatan" pada BA &amp; Formulir</span></label>
                <textarea name="catatan_dokumen_pkkprl" class="form-control" rows="2">{{ old('catatan_dokumen_pkkprl', $baWasPrl->catatan_dokumen_pkkprl ?? '') }}</textarea>
            </div>
        </div>

        <div class="form-section-title"><i class="fas fa-stamp me-1"></i> Izin Pengelolaan</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-12">
                <label class="form-label">Nomor Izin Pengelolaan</label>
                <input type="text" name="izin_pengelolaan_nomor" class="form-control" value="{{ old('izin_pengelolaan_nomor', $baWasPrl->izin_pengelolaan_nomor ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Tanggal Penerbitan</label>
                <input type="date" name="izin_pengelolaan_tanggal_penerbitan" class="form-control" value="{{ old('izin_pengelolaan_tanggal_penerbitan', isset($baWasPrl) && $baWasPrl->izin_pengelolaan_tanggal_penerbitan ? $baWasPrl->izin_pengelolaan_tanggal_penerbitan->format('Y-m-d') : '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Instansi Penerbit</label>
                <textarea name="izin_pengelolaan_instansi_penerbit" class="form-control" rows="2" placeholder="Instansi penerbit">{{ old('izin_pengelolaan_instansi_penerbit', $baWasPrl->izin_pengelolaan_instansi_penerbit ?? '') }}</textarea>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Masa Berlaku</label>
                <input type="text" name="izin_pengelolaan_masa_berlaku" class="form-control" placeholder="Masa berlaku" value="{{ old('izin_pengelolaan_masa_berlaku', $baWasPrl->izin_pengelolaan_masa_berlaku ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label d-block">Kesesuaian Surat Izin Pengelolaan</label>
                <div class="btn-group" role="group">
                    @foreach(['sesuai' => 'Ya, Sesuai', 'tidak_sesuai' => 'Tidak Sesuai'] as $val => $label)
                        <input type="radio" class="btn-check" name="kesesuaian_izin_pengelolaan" id="kesesuaian_izin_{{ $val }}" value="{{ $val }}" @checked(old('kesesuaian_izin_pengelolaan', $baWasPrl->kesesuaian_izin_pengelolaan ?? null) == $val)>
                        <label class="btn btn-outline-primary" for="kesesuaian_izin_{{ $val }}">{{ $label }}</label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="form-section-title"><i class="fas fa-tasks me-1"></i> Pemenuhan Kewajiban &amp; Formulir Pemenuhan Dokumen KKPRL</div>
        <div class="row g-3 mb-4">
            <div class="col-12">
                <label class="form-label d-block">Pemenuhan Kewajiban PKKPRL/KKPRL</label>
                <div class="btn-group" role="group">
                    @foreach(['terpenuhi' => 'Terpenuhi', 'tidak_terpenuhi' => 'Tidak Terpenuhi'] as $val => $label)
                        <input type="radio" class="btn-check" name="pemenuhan_kewajiban_pkkprl" id="pemenuhan_{{ $val }}" value="{{ $val }}" @checked(old('pemenuhan_kewajiban_pkkprl', $baWasPrl->pemenuhan_kewajiban_pkkprl ?? null) == $val)>
                        <label class="btn btn-outline-primary" for="pemenuhan_{{ $val }}">{{ $label }}</label>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label d-block">Penyampaian Laporan Tertulis Berkala</label>
                <div class="btn-group" role="group">
                    @foreach(['ada' => 'Ada', 'tidak_ada' => 'Tidak Ada'] as $val => $label)
                        <input type="radio" class="btn-check" name="penyampaian_laporan_tertulis" id="laporan_{{ $val }}" value="{{ $val }}" @checked(old('penyampaian_laporan_tertulis', $baWasPrl->penyampaian_laporan_tertulis ?? null) == $val)>
                        <label class="btn btn-outline-primary" for="laporan_{{ $val }}">{{ $label }}</label>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label d-block">Dampak Pelaksanaan PKKPRL thd Nelayan Kecil</label>
                <div class="btn-group" role="group">
                    @foreach(['ada' => 'Ada', 'tidak_ada' => 'Tidak Ada'] as $val => $label)
                        <input type="radio" class="btn-check" name="dampak_pelaksanaan_pkkprl" id="dampak_{{ $val }}" value="{{ $val }}" @checked(old('dampak_pelaksanaan_pkkprl', $baWasPrl->dampak_pelaksanaan_pkkprl ?? null) == $val)>
                        <label class="btn btn-outline-primary" for="dampak_{{ $val }}">{{ $label }}</label>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label small">Catatan Laporan Tahunan <span class="text-muted">(kewajiban H-1 tgl/bln terbit sesuai Kepdirjen 77 BAB III huruf A)</span></label>
                <textarea name="catatan_laporan_tahunan" class="form-control" rows="2">{{ old('catatan_laporan_tahunan', $baWasPrl->catatan_laporan_tahunan ?? '') }}</textarea>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label small">Catatan Dampak dari PRL <span class="text-muted">(akses nelayan kecil/tradisional &amp; pembudidaya ikan kecil)</span></label>
                <textarea name="catatan_dampak_prl" class="form-control" rows="2">{{ old('catatan_dampak_prl', $baWasPrl->catatan_dampak_prl ?? '') }}</textarea>
            </div>
        </div>

        <div class="form-section-title"><i class="fas fa-clipboard-list me-1"></i> Hasil, Kesimpulan &amp; Rekomendasi</div>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Hasil Pengawasan</label>
                <textarea name="hasil_pengawasan" class="form-control" rows="3">{{ old('hasil_pengawasan', $baWasPrl->hasil_pengawasan ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Kesimpulan</label>
                <textarea name="kesimpulan" class="form-control" rows="2">{{ old('kesimpulan', $baWasPrl->kesimpulan ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Rekomendasi / Tindak Lanjut <span class="text-muted small">— satu baris = satu poin pada "Kesimpulan Rekomendasi dan Tindakan"</span></label>
                <textarea name="rekomendasi" class="form-control" rows="5" placeholder="Tulis satu tindakan per baris">{{ old('rekomendasi', $baWasPrl->rekomendasi ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- =========================================================
         TAB 4 — PELAKU USAHA & SAKSI
    ========================================================== --}}
    <div class="tab-pane fade" id="tab-usaha">
        <div class="form-text mb-4"><i class="fas fa-info-circle me-1"></i> Nama Penanggung Jawab dan Jabatan dipindahkan ke <strong>Tab 1. Informasi Utama</strong>. Tanda tangan Penanggung Jawab tetap diisi di tab <strong>5. Pengesahan</strong>.</div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label fw-bold mb-0">Daftar Saksi</label>
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
        <div class="form-text mb-3">Saksi bersifat opsional. Kosongkan nama saksi jika memang tidak ada saksi saat pemeriksaan. Tanda tangan tiap saksi diisi di tab <strong>5. Pengesahan</strong>.</div>
    </div>

    {{-- =========================================================
         TAB 5 — PENGESAHAN (semua tanda tangan dikumpulkan di sini)
    ========================================================== --}}
    <div class="tab-pane fade" id="tab-pengesahan">

        <div class="form-section-title"><i class="fas fa-signature me-1"></i> Tanda Tangan Pengesahan Dokumen</div>
        <p class="text-muted small mb-3">Untuk tiap orang, pilih salah satu cara: <strong>Gambar</strong> langsung pakai jari/mouse, atau <strong>Upload Foto</strong> tanda tangan (lebih mudah kalau memakai laptop/mouse).</p>

        <div class="row g-3 mb-2">
            <div class="col-md-6 col-12">
                <label class="form-label fw-semibold mb-2">Ketua Tim &mdash; <span class="text-primary sig-name-ketua">{{ old('ketua_tim_nama', $baWasPrl->ketua_tim_nama ?? '') ?: 'belum diisi' }}</span></label>
                @include('ba-was-prl.partials.ttd-widget', ['name' => 'ketua_tim_tanda_tangan', 'existing' => $baWasPrl->ketua_tim_tanda_tangan ?? null, 'value' => old('ketua_tim_tanda_tangan', $baWasPrl->ketua_tim_tanda_tangan ?? '')])
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label fw-semibold mb-2">Penanggung Jawab Usaha &mdash; <span class="text-primary sig-name-pj">{{ old('penanggung_jawab_usaha', $baWasPrl->penanggung_jawab_usaha ?? '') ?: 'belum diisi' }}</span></label>
                @include('ba-was-prl.partials.ttd-widget', ['name' => 'pj_usaha_tanda_tangan', 'existing' => $baWasPrl->pj_usaha_tanda_tangan ?? null, 'value' => old('pj_usaha_tanda_tangan', $baWasPrl->pj_usaha_tanda_tangan ?? '')])
            </div>
        </div>

        <div class="form-section-title mt-4"><i class="fas fa-users me-1"></i> Tanda Tangan Anggota Pengawas</div>
        <div id="ttdPengawasWrapper" class="row g-3">
            @forelse($pengawasData as $i => $pg)
                <div class="col-md-6 col-12 ttd-pengawas-block" data-pengawas-id="{{ $i }}">
                    <label class="form-label fw-semibold mb-2 sig-name-label">{{ $pg['nama'] ?: 'Anggota Pengawas' }}</label>
                    @include('ba-was-prl.partials.ttd-widget', ['name' => "pengawas[{$i}][tanda_tangan]", 'existing' => $pg['tanda_tangan'] ?? null, 'value' => $pg['tanda_tangan'] ?? ''])
                </div>
            @empty
                <div class="col-md-6 col-12 ttd-pengawas-block" data-pengawas-id="0">
                    <label class="form-label fw-semibold mb-2 sig-name-label">Anggota Pengawas</label>
                    @include('ba-was-prl.partials.ttd-widget', ['name' => 'pengawas[0][tanda_tangan]', 'existing' => null, 'value' => ''])
                </div>
            @endforelse
        </div>

        <div class="form-section-title mt-4"><i class="fas fa-handshake me-1"></i> Tanda Tangan Saksi</div>
        <div id="ttdSaksiWrapper" class="row g-3 mb-2">
            @forelse($saksiData as $i => $sk)
                <div class="col-md-6 col-12 ttd-saksi-block" data-saksi-id="{{ $i }}">
                    <label class="form-label fw-semibold mb-2 sig-name-label">{{ $sk['nama'] ?: 'Saksi' }}</label>
                    @include('ba-was-prl.partials.ttd-widget', ['name' => "saksi[{$i}][tanda_tangan]", 'existing' => $sk['tanda_tangan'] ?? null, 'value' => $sk['tanda_tangan'] ?? ''])
                </div>
            @empty
                <div class="col-md-6 col-12 ttd-saksi-block" data-saksi-id="0">
                    <label class="form-label fw-semibold mb-2 sig-name-label">Saksi</label>
                    @include('ba-was-prl.partials.ttd-widget', ['name' => 'saksi[0][tanda_tangan]', 'existing' => null, 'value' => ''])
                </div>
            @endforelse
        </div>

        <div class="form-section-title mt-4"><i class="fas fa-check-circle me-1"></i> Status &amp; Berkas</div>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                <label class="form-label">Status BA <span class="text-danger">*</span></label>
                <select name="status" class="form-select">
                    @foreach(['draft'=>'Draft','proses'=>'Proses','selesai'=>'Selesai','tindak_lanjut'=>'Tindak Lanjut'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('status', $baWasPrl->status ?? 'draft') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Upload BA (PDF)</label>
                <input type="file" name="file_ba_pdf" class="form-control" accept="application/pdf">
                @if(!empty($baWasPrl) && $baWasPrl->file_ba_pdf)
                    <div class="mt-2 text-sm">
                        <a href="{{ asset('storage/'.$baWasPrl->file_ba_pdf) }}" target="_blank"><i class="fas fa-file-pdf me-1 text-danger"></i>Lihat File Saat Ini</a>
                    </div>
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Upload Foto Dokumentasi</label>
                <input type="file" name="foto[]" class="form-control" accept="image/*" multiple>
                <div class="form-text">Bisa pilih lebih dari 1 file gambar.</div>
                @if(!empty($baWasPrl) && $baWasPrl->fotos->count())
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach($baWasPrl->fotos as $foto)
                            <a href="{{ asset('storage/'.$foto->path_foto) }}" target="_blank">
                                <img src="{{ asset('storage/'.$foto->path_foto) }}" style="width:70px;height:70px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Catatan Pengesahan <span class="text-muted small">(catatan internal, tidak tercetak pada dokumen)</span></label>
                <textarea name="catatan_pengesahan" class="form-control" rows="2">{{ old('catatan_pengesahan', $baWasPrl->catatan_pengesahan ?? '') }}</textarea>
            </div>
        </div>
    </div>

</div>

<div class="card fade-in mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('ba-was-prl.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Simpan BA WAS PRL</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ================= Pelaku Usaha: bisa pilih dari daftar ATAU ketik nama baru =================
    $('#pelakuUsahaSelect').select2({
        theme: 'bootstrap-5',
        width: '100%',
        tags: true,
        placeholder: '-- Pilih atau Ketik Nama Pelaku Usaha Baru --',
        language: {
            noResults: function () { return 'Tidak ditemukan. Ketik nama baru lalu tekan Enter.'; },
        },
    });

    // ================= Signature Pad: mode Gambar (kanvas) & Upload Foto =================
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

        // Resolusi internal kanvas dibuat tetap supaya kualitas gambar konsisten,
        // sedangkan ukuran tampilan mengikuti CSS (responsif).
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

        // --- ganti mode Gambar <-> Upload Foto ---
        modeBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                currentMode = this.dataset.mode;
                modeBtns.forEach(function (b) { b.classList.toggle('active', b === btn); });
                if (drawSection) drawSection.style.display = currentMode === 'draw' ? '' : 'none';
                if (uploadSection) uploadSection.style.display = currentMode === 'upload' ? '' : 'none';
            });
        });

        // --- mode Gambar: gambar bebas pakai jari/mouse ---
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

        // --- mode Upload Foto: pilih file gambar, otomatis dikompres supaya ringan ---
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
                // Kalau lagi di mode Gambar, ambil isi kanvas. Kalau mode Upload Foto,
                // hiddenInput sudah diisi langsung oleh proses upload, jadi tidak disentuh di sini.
                if (currentMode === 'draw') {
                    hiddenInput.value = hasSignature ? canvas.toDataURL('image/png') : '';
                }
            });
        }
    }

    document.querySelectorAll('.signature-pad-wrap').forEach(initSignaturePad);

    // ================= Sinkron nama Ketua Tim & PJ Usaha -> tab Pengesahan =================
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
            <div class="col-md-2 col-6"><input type="text" name="pengawas[${idx}][nip]" class="form-control form-control-sm" placeholder="NIP"></div>
            <div class="col-md-3 col-6"><input type="text" name="pengawas[${idx}][jabatan]" class="form-control form-control-sm" placeholder="Jabatan"></div>
            <div class="col-md-3 col-6"><input type="text" name="pengawas[${idx}][unit_kerja]" class="form-control form-control-sm" placeholder="Unit Kerja"></div>
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
            $(`.ttd-pengawas-block[data-pengawas-id="${id}"]`).remove();
        }
    });
    $(document).on('input', '.pengawas-nama-input', function () {
        let id = $(this).closest('.pengawas-row').data('pengawas-id');
        let val = $(this).val().trim();
        $(`.ttd-pengawas-block[data-pengawas-id="${id}"] .sig-name-label`).text(val || 'Anggota Pengawas');
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
            $(`.ttd-saksi-block[data-saksi-id="${id}"]`).remove();
        }
    });
    $(document).on('input', '.saksi-nama-input', function () {
        let id = $(this).closest('.saksi-row').data('saksi-id');
        let val = $(this).val().trim();
        $(`.ttd-saksi-block[data-saksi-id="${id}"] .sig-name-label`).text(val || 'Saksi');
    });
</script>
@endpush

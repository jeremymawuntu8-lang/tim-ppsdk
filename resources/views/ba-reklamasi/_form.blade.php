@csrf

@php
    $pengawasData = old('pengawas', isset($baReklamasi) ? $baReklamasi->pengawas->toArray() : []);
    $storageUrl = fn ($path) => $path ? asset('storage/' . $path) : '';
@endphp

<style>
    .form-section-title { font-weight: 700; font-size: .95rem; color: #444; margin-bottom: .75rem; padding-bottom: .35rem; border-bottom: 2px solid #e9ecef; }
    .nav-tabs .nav-link { font-weight: 600; color: #6c757d; }
    .nav-tabs .nav-link.active { color: var(--bs-primary, #0d6efd); }
    .ttd-canvas { width: 100%; max-width: 420px; height: 140px; background: #fff; border: 1px dashed #adb5bd; border-radius: 6px; touch-action: none; cursor: crosshair; display: block; }
    .signature-pad-wrap { background: #f8f9fa; border-radius: 8px; padding: 12px; }
    .repeater-row { background: #f8f9fa; border-radius: 8px; padding: 12px; }
    .ttd-pengawas-block { background: #f8f9fa; border-radius: 8px; padding: 12px; margin-bottom: 1rem; }
    .ttd-mode-btn.active { background: var(--bs-primary,#0d6efd); color:#fff; border-color: var(--bs-primary,#0d6efd); }
    .ttd-upload-preview img { display:block; }
</style>

<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-utama" type="button"><i class="fas fa-info-circle me-1"></i> 1. Informasi Utama</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pengawas" type="button"><i class="fas fa-users me-1"></i> 2. Tim Pengawas</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-usaha" type="button"><i class="fas fa-building me-1"></i> 3. Detail Reklamasi</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-dokumen" type="button"><i class="fas fa-file-contract me-1"></i> 4. Pemeriksaan Dokumen</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pengesahan" type="button"><i class="fas fa-signature me-1"></i> 5. Pengesahan &amp; File</button>
    </li>
</ul>

<div class="tab-content">

    {{-- TAB 1: Informasi Utama --}}
    <div class="tab-pane fade show active" id="tab-utama">
        <div class="row g-3">
            <div class="col-md-6 col-12">
                <label class="form-label">Nomor BA <span class="text-danger">*</span></label>
                <input type="text" name="nomor_ba" class="form-control" value="{{ old('nomor_ba', $baReklamasi->nomor_ba ?? '') }}" required>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Pelaku Usaha Utama (Opsional)</label>
                <select name="pelaku_usaha_id" id="pelakuUsahaSelect" class="form-select" style="width: 100%;">
                    <option value="">-- Pilih atau Ketik Nama Baru --</option>
                    @foreach($pelakuUsahas as $p)
                        <option value="{{ $p->id }}" @selected(old('pelaku_usaha_id', $baReklamasi->pelaku_usaha_id ?? null) == $p->id)>{{ $p->nama_perusahaan }}</option>
                    @endforeach
                    @php
                        $oldPelakuUsaha = old('pelaku_usaha_id');
                        $pelakuUsahaFreeText = ($oldPelakuUsaha && !is_numeric($oldPelakuUsaha)) ? $oldPelakuUsaha : null;
                    @endphp
                    @if($pelakuUsahaFreeText)
                        <option value="{{ $pelakuUsahaFreeText }}" selected>{{ $pelakuUsahaFreeText }}</option>
                    @endif
                </select>
                <div class="form-text">Pilih agar terkoneksi dengan database, atau isi manual di Tab 3.</div>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Tanggal Pengawasan <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_pengawasan" class="form-control" value="{{ old('tanggal_pengawasan', isset($baReklamasi) ? $baReklamasi->tanggal_pengawasan->format('Y-m-d') : '') }}" required>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Jam (WITA)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                    <input type="time" name="jam_wita" class="form-control" value="{{ old('jam_wita', $baReklamasi->jam_wita ?? '') }}">
                </div>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label text-muted">Latitude (Desimal)</label>
                <input type="text" name="latitude" class="form-control form-control-sm" value="{{ old('latitude', $baReklamasi->latitude ?? '') }}" placeholder="Latitude">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label text-muted">Longitude (Desimal)</label>
                <input type="text" name="longitude" class="form-control form-control-sm" value="{{ old('longitude', $baReklamasi->longitude ?? '') }}" placeholder="Longitude">
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
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[{{ $i }}][nama]" class="form-control form-control-sm pengawas-nama-input" placeholder="Nama Anggota" value="{{ $pg['nama'] ?? '' }}"></div>
                    <div class="col-md-2 col-6"><input type="text" name="pengawas[{{ $i }}][nip]" class="form-control form-control-sm" placeholder="NIP" value="{{ $pg['nip'] ?? '' }}"></div>
                    <div class="col-md-3 col-6">
                        <select name="pengawas[{{ $i }}][jabatan]" class="form-select form-select-sm">
                            <option value="Ketua Tim" @selected(($pg['jabatan'] ?? '') === 'Ketua Tim')>Ketua Tim</option>
                            <option value="Anggota Tim" @selected(($pg['jabatan'] ?? '') === 'Anggota Tim')>Anggota Tim</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[{{ $i }}][unit_kerja]" class="form-control form-control-sm" placeholder="Unit Kerja" value="{{ $pg['unit_kerja'] ?? '' }}"></div>
                    <div class="col-md-1 col-12"><button type="button" class="btn btn-outline-danger w-100 btn-hapus-pengawas" title="Hapus"><i class="fas fa-trash"></i></button></div>
                </div>
            @empty
                <div class="row g-2 mb-3 pengawas-row repeater-row" data-pengawas-id="0">
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[0][nama]" class="form-control form-control-sm pengawas-nama-input" placeholder="Nama Anggota"></div>
                    <div class="col-md-2 col-6"><input type="text" name="pengawas[0][nip]" class="form-control form-control-sm" placeholder="NIP"></div>
                    <div class="col-md-3 col-6">
                        <select name="pengawas[0][jabatan]" class="form-select form-select-sm">
                            <option value="Ketua Tim">Ketua Tim</option>
                            <option value="Anggota Tim">Anggota Tim</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6"><input type="text" name="pengawas[0][unit_kerja]" class="form-control form-control-sm" placeholder="Unit Kerja"></div>
                    <div class="col-md-1 col-12"><button type="button" class="btn btn-outline-danger w-100 btn-hapus-pengawas" title="Hapus"><i class="fas fa-trash"></i></button></div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- TAB 3: Detail Reklamasi --}}
    <div class="tab-pane fade" id="tab-usaha">
        <div class="row g-3">
            <div class="col-md-6 col-12">
                <label class="form-label">Nama Penanggung Jawab</label>
                <input type="text" name="penanggung_jawab_usaha" class="form-control pj-nama-input" value="{{ old('penanggung_jawab_usaha', $baReklamasi->penanggung_jawab_usaha ?? '') }}">
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">NIK Penanggung Jawab</label>
                <input type="text" name="nik_pj" class="form-control" value="{{ old('nik_pj', $baReklamasi->nik_pj ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat (Sesuai Identitas)</label>
                <textarea name="alamat_pj" class="form-control" rows="2">{{ old('alamat_pj', $baReklamasi->alamat_pj ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Pelaksana Reklamasi</label>
                <input type="text" name="pelaksana_reklamasi" class="form-control" value="{{ old('pelaksana_reklamasi', $baReklamasi->pelaksana_reklamasi ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Lokasi Reklamasi</label>
                <textarea name="lokasi_reklamasi" class="form-control" rows="2">{{ old('lokasi_reklamasi', $baReklamasi->lokasi_reklamasi ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Jenis Pemanfaatan Reklamasi</label>
                <input type="text" name="jenis_pemanfaatan_reklamasi" class="form-control" value="{{ old('jenis_pemanfaatan_reklamasi', $baReklamasi->jenis_pemanfaatan_reklamasi ?? '') }}">
            </div>
        </div>
    </div>

    {{-- TAB 4: Dokumen --}}
    <div class="tab-pane fade" id="tab-dokumen">
        <div class="form-section-title">1. Kesesuaian Kegiatan Pemanfaatan Ruang Laut (KKPRL)</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-12"><label class="form-label">Nomor Izin</label><input type="text" name="kkprl_nomor_izin" class="form-control" value="{{ old('kkprl_nomor_izin', $baReklamasi->kkprl_nomor_izin ?? '') }}"></div>
            <div class="col-md-6 col-12"><label class="form-label">Terbit Izin</label><input type="date" name="kkprl_terbit_izin" class="form-control" value="{{ old('kkprl_terbit_izin', isset($baReklamasi) && $baReklamasi->kkprl_terbit_izin ? $baReklamasi->kkprl_terbit_izin->format('Y-m-d') : '') }}"></div>
            <div class="col-md-6 col-12"><label class="form-label">Pemberi Izin</label><input type="text" name="kkprl_pemberi_izin" class="form-control" value="{{ old('kkprl_pemberi_izin', $baReklamasi->kkprl_pemberi_izin ?? '') }}"></div>
            <div class="col-md-6 col-12"><label class="form-label">Peruntukan</label><input type="text" name="kkprl_peruntukan" class="form-control" value="{{ old('kkprl_peruntukan', $baReklamasi->kkprl_peruntukan ?? '') }}"></div>
        </div>
        
        <div class="form-section-title">2. Izin Pelaksanaan Reklamasi / Perizinan Berusaha</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-12"><label class="form-label">Nomor Izin</label><input type="text" name="izin_reklamasi_nomor" class="form-control" value="{{ old('izin_reklamasi_nomor', $baReklamasi->izin_reklamasi_nomor ?? '') }}"></div>
            <div class="col-md-6 col-12"><label class="form-label">Terbit Izin</label><input type="date" name="izin_reklamasi_terbit" class="form-control" value="{{ old('izin_reklamasi_terbit', isset($baReklamasi) && $baReklamasi->izin_reklamasi_terbit ? $baReklamasi->izin_reklamasi_terbit->format('Y-m-d') : '') }}"></div>
            <div class="col-md-6 col-12"><label class="form-label">Pemberi Izin</label><input type="text" name="izin_reklamasi_pemberi" class="form-control" value="{{ old('izin_reklamasi_pemberi', $baReklamasi->izin_reklamasi_pemberi ?? '') }}"></div>
            <div class="col-md-6 col-12"><label class="form-label">Peruntukan</label><input type="text" name="izin_reklamasi_peruntukan" class="form-control" value="{{ old('izin_reklamasi_peruntukan', $baReklamasi->izin_reklamasi_peruntukan ?? '') }}"></div>
        </div>
        
        <div class="form-section-title">3. Izin Pelaksanaan Reklamasi / Perizinan Berusaha Lainnya</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-12"><label class="form-label">Nomor Izin</label><input type="text" name="izin_lainnya_nomor" class="form-control" value="{{ old('izin_lainnya_nomor', $baReklamasi->izin_lainnya_nomor ?? '') }}"></div>
            <div class="col-md-6 col-12"><label class="form-label">Terbit Izin</label><input type="date" name="izin_lainnya_terbit" class="form-control" value="{{ old('izin_lainnya_terbit', isset($baReklamasi) && $baReklamasi->izin_lainnya_terbit ? $baReklamasi->izin_lainnya_terbit->format('Y-m-d') : '') }}"></div>
            <div class="col-md-6 col-12"><label class="form-label">Pemberi Izin</label><input type="text" name="izin_lainnya_pemberi" class="form-control" value="{{ old('izin_lainnya_pemberi', $baReklamasi->izin_lainnya_pemberi ?? '') }}"></div>
            <div class="col-md-6 col-12"><label class="form-label">Peruntukan</label><input type="text" name="izin_lainnya_peruntukan" class="form-control" value="{{ old('izin_lainnya_peruntukan', $baReklamasi->izin_lainnya_peruntukan ?? '') }}"></div>
        </div>
    </div>

    {{-- TAB 5: Pengesahan & File --}}
    <div class="tab-pane fade" id="tab-pengesahan">
        <div class="form-section-title"><i class="fas fa-signature me-1"></i> Tanda Tangan Pengesahan</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-12">
                <label class="form-label fw-semibold mb-2">Pelaku Usaha &mdash; <span class="text-primary sig-name-pj">{{ old('penanggung_jawab_usaha', $baReklamasi->penanggung_jawab_usaha ?? '') ?: 'belum diisi' }}</span></label>
                @include('ba-was-prl.partials.ttd-widget', ['name' => 'ttd_pelaku_usaha', 'existing' => $baReklamasi->ttd_pelaku_usaha ?? null, 'value' => old('ttd_pelaku_usaha', $baReklamasi->ttd_pelaku_usaha ?? '')])
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label fw-semibold mb-2">Polsus PWP3K / Pengawas</label>
                @include('ba-was-prl.partials.ttd-widget', ['name' => 'ttd_pengawas_1', 'existing' => $baReklamasi->ttd_pengawas_1 ?? null, 'value' => old('ttd_pengawas_1', $baReklamasi->ttd_pengawas_1 ?? '')])
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label fw-semibold mb-2">Paraf Pengesahan (Opsional)</label>
                @include('ba-was-prl.partials.ttd-widget', ['name' => 'ttd_pengawas_2', 'existing' => $baReklamasi->ttd_pengawas_2 ?? null, 'value' => old('ttd_pengawas_2', $baReklamasi->ttd_pengawas_2 ?? '')])
            </div>
        </div>

        <div class="form-section-title mt-4"><i class="fas fa-check-circle me-1"></i> Status &amp; Berkas</div>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                <label class="form-label">Status BA <span class="text-danger">*</span></label>
                <select name="status" class="form-select">
                    @foreach(['draft'=>'Draft','proses'=>'Proses','selesai'=>'Selesai','tindak_lanjut'=>'Tindak Lanjut'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('status', $baReklamasi->status ?? 'draft') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-12">
                <label class="form-label">Upload BA (PDF)</label>
                <input type="file" name="file_ba_pdf" class="form-control" accept="application/pdf">
                @if(!empty($baReklamasi) && $baReklamasi->file_ba_pdf)
                    <div class="mt-2 text-sm"><a href="{{ asset('storage/'.$baReklamasi->file_ba_pdf) }}" target="_blank"><i class="fas fa-file-pdf me-1 text-danger"></i>Lihat File Saat Ini</a></div>
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Upload Foto Dokumentasi</label>
                <input type="file" name="foto[]" class="form-control" accept="image/*" multiple>
                @if(!empty($baReklamasi) && $baReklamasi->fotos->count())
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach($baReklamasi->fotos as $foto)
                            <a href="{{ asset('storage/'.$foto->path_foto) }}" target="_blank">
                                <img src="{{ asset('storage/'.$foto->path_foto) }}" style="width:70px;height:70px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card fade-in mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('ba-reklamasi.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Simpan BA Reklamasi</button>
        </div>
    </div>
</div>

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
            <div class="col-md-3 col-6"><input type="text" name="pengawas[${idx}][nama]" class="form-control form-control-sm pengawas-nama-input" placeholder="Nama Anggota"></div>
            <div class="col-md-2 col-6"><input type="text" name="pengawas[${idx}][nip]" class="form-control form-control-sm" placeholder="NIP"></div>
            <div class="col-md-3 col-6">
                <select name="pengawas[${idx}][jabatan]" class="form-select form-select-sm">
                    <option value="Ketua Tim">Ketua Tim</option>
                    <option value="Anggota Tim" selected>Anggota Tim</option>
                </select>
            </div>
            <div class="col-md-3 col-6"><input type="text" name="pengawas[${idx}][unit_kerja]" class="form-control form-control-sm" placeholder="Unit Kerja"></div>
            <div class="col-md-1 col-12"><button type="button" class="btn btn-outline-danger w-100 btn-hapus-pengawas" title="Hapus"><i class="fas fa-trash"></i></button></div>
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

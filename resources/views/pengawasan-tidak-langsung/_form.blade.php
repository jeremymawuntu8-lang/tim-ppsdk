<div class="row">
    <div class="col-md-6 mb-3">
        <label for="nomor" class="form-label">Nomor <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nomor') is-invalid @enderror" id="nomor" name="nomor" value="{{ old('nomor', $model->nomor ?? '') }}" placeholder="Biarkan kosong untuk auto-generate">
        @error('nomor')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="nama_unit_kerja" class="form-label">Nama Unit Kerja <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama_unit_kerja') is-invalid @enderror" id="nama_unit_kerja" name="nama_unit_kerja" value="{{ old('nama_unit_kerja', $model->nama_unit_kerja ?? 'UPT PSDKP') }}" required>
        @error('nama_unit_kerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<h5 class="mt-4 mb-3 fw-bold text-secondary border-bottom pb-2">A. Informasi</h5>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="lokasi" class="form-label">Lokasi</label>
        <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi', $model->lokasi ?? '') }}">
        @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="pelaku_usaha_id" class="form-label">Pelaku Usaha <span class="text-danger">*</span></label>
        <select class="form-select @error('pelaku_usaha_id') is-invalid @enderror select2" id="pelaku_usaha_id" name="pelaku_usaha_id" required>
            <option value="">-- Pilih Pelaku Usaha --</option>
            @foreach($pelakuUsahas as $pu)
                <option value="{{ $pu->id }}" {{ (old('pelaku_usaha_id', $model->pelaku_usaha_id ?? '') == $pu->id) ? 'selected' : '' }}>
                    {{ $pu->nama_perusahaan }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Ketik untuk memilih atau menambahkan pelaku usaha baru jika tidak ada di daftar.</small>
        @error('pelaku_usaha_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="kegiatan_usaha" class="form-label">Kegiatan Usaha</label>
        <input type="text" class="form-control @error('kegiatan_usaha') is-invalid @enderror" id="kegiatan_usaha" name="kegiatan_usaha" value="{{ old('kegiatan_usaha', $model->kegiatan_usaha ?? '') }}">
        @error('kegiatan_usaha')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="pelapor_sumber" class="form-label">Pelapor / Sumber</label>
        <input type="text" class="form-control @error('pelapor_sumber') is-invalid @enderror" id="pelapor_sumber" name="pelapor_sumber" value="{{ old('pelapor_sumber', $model->pelapor_sumber ?? '') }}">
        @error('pelapor_sumber')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="tanggal_laporan" class="form-label">Tanggal Laporan</label>
        <input type="date" class="form-control @error('tanggal_laporan') is-invalid @enderror" id="tanggal_laporan" name="tanggal_laporan" value="{{ old('tanggal_laporan', isset($model) && $model->tanggal_laporan ? $model->tanggal_laporan->format('Y-m-d') : '') }}">
        @error('tanggal_laporan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="tanggal_telaah" class="form-label">Tanggal Telaah</label>
        <input type="date" class="form-control @error('tanggal_telaah') is-invalid @enderror" id="tanggal_telaah" name="tanggal_telaah" value="{{ old('tanggal_telaah', isset($model) && $model->tanggal_telaah ? $model->tanggal_telaah->format('Y-m-d') : '') }}">
        @error('tanggal_telaah')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<h5 class="mt-4 mb-3 fw-bold text-secondary border-bottom pb-2">B. Gambar</h5>
<div class="alert alert-info">Unggah gambar sesuai kategori (Opsional). Jika sudah ada gambar, mengunggah file baru akan menggantikan yang lama.</div>

@php
    $labels = [
        1 => 'Pengamatan Lokasi berdasarkan Citra Google Earth',
        2 => 'Pengamatan Lokasi berdasarkan Citra Google Earth yang berisi time series pelaksanaan kegiatan yang terindikasi melakukan pelanggaran pamanfaatan ruang laut',
        3 => 'Overlay Basemap dengan Garis Pantai sesuai dengan peraturan perundangan yang berlaku',
        4 => 'Menggunakan citra sentinel yang terbaru dengan kondisi citra yang terbaik',
        5 => 'Delineasi area indikasi pelanggaran pemanfaatan ruang laut',
        6 => 'Overlay dengan peta sebaran ekosistem laut berdasarkan situs Allen Coral Atlas atau situs yang lainnya',
        7 => 'Overlay dengan RTR/RZWP-3-K',
        8 => 'Overlay dengan perizinan yang ada'
    ];
@endphp

<div class="row">
    @for($i = 1; $i <= 8; $i++)
        <div class="col-md-12 mb-3">
            <label for="gambar_{{ $i }}" class="form-label fw-bold">{{ $i }}. {{ $labels[$i] }}</label>
            @if(isset($model))
                @php
                    $gambar = $model->gambars->where('kategori', (string)$i)->first();
                @endphp
                @if($gambar)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $gambar->path_file) }}" alt="Gambar {{ $i }}" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                @endif
            @endif
            <input type="file" class="form-control @error('gambar_'.$i) is-invalid @enderror" id="gambar_{{ $i }}" name="gambar_{{ $i }}" accept="image/*">
            @error('gambar_'.$i)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @endfor
</div>

<h5 class="mt-4 mb-3 fw-bold text-secondary border-bottom pb-2">C. Hasil Telaah</h5>

<div class="mb-3">
    <label for="hasil_telaah_penjelasan" class="form-label fw-bold">a. Berupa penjelasan dari gambar</label>
    <textarea class="form-control summernote @error('hasil_telaah_penjelasan') is-invalid @enderror" id="hasil_telaah_penjelasan" name="hasil_telaah_penjelasan" rows="4">{{ old('hasil_telaah_penjelasan', $model->hasil_telaah_penjelasan ?? '') }}</textarea>
    @error('hasil_telaah_penjelasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label fw-bold">b. Kesesuaian Pelaksanaan Kegiatan (RTR/Zonasi/KKPRL)</label>
    <div class="table-responsive">
        <table class="table table-bordered" id="table-kesesuaian">
            <thead class="table-light">
                <tr>
                    <th rowspan="2" class="align-middle text-center" style="width: 25%">Alokasi Ruang</th>
                    <th rowspan="2" class="align-middle text-center" style="width: 25%">Kegiatan</th>
                    <th colspan="3" class="text-center">Kesesuaian</th>
                    <th rowspan="2" class="align-middle text-center" style="width: 20%">Keterangan</th>
                    <th rowspan="2" class="align-middle text-center" style="width: 5%">Aksi</th>
                </tr>
                <tr>
                    <th class="text-center">Diperbolehkan</th>
                    <th class="text-center">Tidak diperbolehkan</th>
                    <th class="text-center">Diperbolehkan dengan Izin</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $kesesuaianData = old('kesesuaian', isset($model) ? $model->kesesuaian->toArray() : [[]]);
                    if(empty($kesesuaianData)) $kesesuaianData = [[]];
                @endphp
                
                @foreach($kesesuaianData as $index => $item)
                <tr>
                    <td><input type="text" name="kesesuaian[{{ $index }}][alokasi_ruang]" class="form-control" value="{{ $item['alokasi_ruang'] ?? '' }}"></td>
                    <td><input type="text" name="kesesuaian[{{ $index }}][kegiatan]" class="form-control" value="{{ $item['kegiatan'] ?? '' }}"></td>
                    <td class="text-center"><input type="radio" name="kesesuaian[{{ $index }}][kesesuaian]" value="diperbolehkan" {{ ($item['kesesuaian'] ?? '') == 'diperbolehkan' ? 'checked' : '' }}></td>
                    <td class="text-center"><input type="radio" name="kesesuaian[{{ $index }}][kesesuaian]" value="tidak_diperbolehkan" {{ ($item['kesesuaian'] ?? '') == 'tidak_diperbolehkan' ? 'checked' : '' }}></td>
                    <td class="text-center"><input type="radio" name="kesesuaian[{{ $index }}][kesesuaian]" value="diperbolehkan_dengan_izin" {{ ($item['kesesuaian'] ?? '') == 'diperbolehkan_dengan_izin' ? 'checked' : '' }}></td>
                    <td><input type="text" name="kesesuaian[{{ $index }}][keterangan]" class="form-control" value="{{ $item['keterangan'] ?? '' }}"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-kesesuaian"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-success" id="btn-add-kesesuaian"><i class="fas fa-plus"></i> Tambah Baris</button>
    </div>
</div>

<h5 class="mt-4 mb-3 fw-bold text-secondary border-bottom pb-2">D. Rekomendasi</h5>

<div class="mb-3">
    <label for="rekomendasi" class="form-label">Berisi rekomendasi berdasarkan hasil telaahan</label>
    <textarea class="form-control summernote @error('rekomendasi') is-invalid @enderror" id="rekomendasi" name="rekomendasi" rows="4">{{ old('rekomendasi', $model->rekomendasi ?? '') }}</textarea>
    @error('rekomendasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<h5 class="mt-4 mb-3 fw-bold text-secondary border-bottom pb-2">Pengesahan</h5>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="kepala_upt_nama" class="form-label">Nama Kepala UPT PSDKP <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('kepala_upt_nama') is-invalid @enderror" id="kepala_upt_nama" name="kepala_upt_nama" value="{{ old('kepala_upt_nama', $model->kepala_upt_nama ?? '') }}" required>
        @error('kepala_upt_nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanda Tangan Kepala UPT</label>
        <div class="signature-wrapper mb-2 border rounded p-2 bg-light">
            <canvas id="canvas-kepala" class="signature-pad w-100 bg-white" style="height: 200px; border: 1px dashed #ccc;"></canvas>
            <input type="hidden" name="kepala_upt_ttd" id="kepala_upt_ttd" value="{{ old('kepala_upt_ttd', $model->kepala_upt_ttd ?? '') }}">
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSignature('canvas-kepala', 'kepala_upt_ttd')">Bersihkan Tanda Tangan</button>
        
        @if(isset($model) && $model->kepala_upt_ttd)
        <div class="mt-2 text-success">
            <i class="fas fa-check-circle"></i> Tanda tangan sudah tersimpan
        </div>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12 mb-3">
        <label for="status" class="form-label">Status Form <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="proses" {{ old('status', $model->status ?? 'proses') == 'proses' ? 'selected' : '' }}>Proses</option>
            <option value="selesai" {{ old('status', $model->status ?? '') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="tindak_lanjut" {{ old('status', $model->status ?? '') == 'tindak_lanjut' ? 'selected' : '' }}>Tindak Lanjut</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            tags: true // Allow adding new Pelaku Usaha
        });

        $('.summernote').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
            ]
        });

        // Dynamic Table for Kesesuaian
        let kesesuaianIndex = {{ count($kesesuaianData) }};
        $('#btn-add-kesesuaian').click(function() {
            let row = `
                <tr>
                    <td><input type="text" name="kesesuaian[${kesesuaianIndex}][alokasi_ruang]" class="form-control"></td>
                    <td><input type="text" name="kesesuaian[${kesesuaianIndex}][kegiatan]" class="form-control"></td>
                    <td class="text-center"><input type="radio" name="kesesuaian[${kesesuaianIndex}][kesesuaian]" value="diperbolehkan"></td>
                    <td class="text-center"><input type="radio" name="kesesuaian[${kesesuaianIndex}][kesesuaian]" value="tidak_diperbolehkan"></td>
                    <td class="text-center"><input type="radio" name="kesesuaian[${kesesuaianIndex}][kesesuaian]" value="diperbolehkan_dengan_izin"></td>
                    <td><input type="text" name="kesesuaian[${kesesuaianIndex}][keterangan]" class="form-control"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-kesesuaian"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
            $('#table-kesesuaian tbody').append(row);
            kesesuaianIndex++;
        });

        $(document).on('click', '.btn-remove-kesesuaian', function() {
            $(this).closest('tr').remove();
        });

        // Setup Signature Pad
        setupSignaturePad('canvas-kepala', 'kepala_upt_ttd');
    });

    function setupSignaturePad(canvasId, inputId) {
        var canvas = document.getElementById(canvasId);
        if (!canvas) return;

        // Resize canvas untuk mendukung high DPI screens
        function resizeCanvas() {
            var ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        var signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        // Update hidden input saat tanda tangan selesai
        signaturePad.addEventListener("endStroke", () => {
            document.getElementById(inputId).value = signaturePad.toDataURL('image/png');
        });

        // Load existing signature jika ada form error dan old input exists
        var existingData = document.getElementById(inputId).value;
        if (existingData && existingData.startsWith('data:image')) {
            signaturePad.fromDataURL(existingData);
        }

        // Simpan instance di elemen agar bisa diakses clearSignature
        canvas.signaturePad = signaturePad;
    }

    function clearSignature(canvasId, inputId) {
        var canvas = document.getElementById(canvasId);
        if (canvas && canvas.signaturePad) {
            canvas.signaturePad.clear();
            document.getElementById(inputId).value = '';
        }
    }
</script>
@endpush

@csrf
<div class="row g-4">
    {{-- KIRI: Info Utama & Lokasi --}}
    <div class="col-lg-8">
        {{-- Section: Informasi Perusahaan --}}
        <div class="card card-primary card-outline fade-in mb-4">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-building me-2 text-primary"></i>Informasi Perusahaan</h3></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-12">
                        <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                               value="{{ old('nama_perusahaan', $pelakuUsaha->nama_perusahaan ?? '') }}" required placeholder="Masukkan nama perusahaan">
                        @error('nama_perusahaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Nomor PKKPRL</label>
                        <input type="text" name="nomor_pkkprl" class="form-control @error('nomor_pkkprl') is-invalid @enderror"
                               value="{{ old('nomor_pkkprl', $pelakuUsaha->nomor_pkkprl ?? '') }}" placeholder="Kosongkan jika belum ada">
                        @error('nomor_pkkprl') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                
                    <div class="col-md-6 col-12">
                        <label class="form-label">Jenis Usaha <span class="text-danger">*</span></label>
                        <select name="jenis_usaha_id" class="form-select select2 @error('jenis_usaha_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Usaha --</option>
                            @foreach($jenisUsahas as $j)
                                <option value="{{ $j->id }}" @selected(old('jenis_usaha_id', $pelakuUsaha->jenis_usaha_id ?? null) == $j->id)>{{ $j->nama }}</option>
                            @endforeach
                        </select>
                        @error('jenis_usaha_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Luas PKKPRL (m²)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="luas_pkkprl" class="form-control @error('luas_pkkprl') is-invalid @enderror"
                                   value="{{ old('luas_pkkprl', $pelakuUsaha->luas_pkkprl ?? '') }}" placeholder="Luas area">
                            <span class="input-group-text">m²</span>
                        </div>
                        @error('luas_pkkprl') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Data Lokasi --}}
        <div class="card card-primary card-outline fade-in mb-4" style="animation-delay: 0.1s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-map-location-dot me-2 text-primary"></i>Wilayah & Koordinat</h3></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-12">
                        <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                        <select name="provinsi_id" id="provinsi_id" class="form-select select2 @error('provinsi_id') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            @foreach($provinsis as $p)
                                <option value="{{ $p->id }}" @selected(old('provinsi_id', $pelakuUsaha->provinsi_id ?? null) == $p->id)>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Kabupaten <span class="text-danger">*</span></label>
                        <select name="kabupaten_id" id="kabupaten_id" class="form-select select2 @error('kabupaten_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Provinsi Dahulu --</option>
                            @foreach($kabupatens ?? [] as $k)
                                <option value="{{ $k->id }}" @selected(old('kabupaten_id', $pelakuUsaha->kabupaten_id ?? null) == $k->id)>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                        <select name="kecamatan_id" id="kecamatan_id" class="form-select select2 @error('kecamatan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kabupaten Dahulu --</option>
                            @foreach($kecamatans ?? [] as $k)
                                <option value="{{ $k->id }}" @selected(old('kecamatan_id', $pelakuUsaha->kecamatan_id ?? null) == $k->id)>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">Kelurahan <span class="text-danger">*</span></label>
                        <select name="kelurahan_id" id="kelurahan_id" class="form-select select2 @error('kelurahan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kecamatan Dahulu --</option>
                            @foreach($kelurahans ?? [] as $k)
                                <option value="{{ $k->id }}" @selected(old('kelurahan_id', $pelakuUsaha->kelurahan_id ?? null) == $k->id)>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Jalan, RT/RW, Blok...">{{ old('alamat', $pelakuUsaha->alamat ?? '') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label mb-1">Titik Koordinat Peta</label>
                        <div class="text-muted text-xs mb-2"><i class="fas fa-info-circle me-1"></i>Klik pada peta untuk mengambil titik lokasi otomatis, atau geser marker.</div>
                        <div id="pickerMap" class="map-container mb-2" style="height: 300px; z-index: 1;"></div>
                        <div class="row g-2">
                            <div class="col-md-6 col-12">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-arrows-alt-v"></i></span>
                                    <input type="text" name="latitude" id="latitude" class="form-control text-sm" value="{{ old('latitude', $pelakuUsaha->latitude ?? '') }}" placeholder="Latitude">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-arrows-alt-h"></i></span>
                                    <input type="text" name="longitude" id="longitude" class="form-control text-sm" value="{{ old('longitude', $pelakuUsaha->longitude ?? '') }}" placeholder="Longitude">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: PIC, Status, & Dokumen --}}
    <div class="col-lg-4">
        {{-- Section: PIC --}}
        <div class="card card-primary card-outline fade-in mb-4" style="animation-delay: 0.15s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-user-tie me-2 text-primary"></i>Kontak Person (PIC)</h3></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nama PIC</label>
                        <input type="text" name="nama_pic" class="form-control" value="{{ old('nama_pic', $pelakuUsaha->nama_pic ?? '') }}" placeholder="Nama penanggung jawab">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Jabatan PIC</label>
                        <input type="text" name="jabatan_pic" class="form-control" value="{{ old('jabatan_pic', $pelakuUsaha->jabatan_pic ?? '') }}" placeholder="Jabatan">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Nomor HP / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" name="nomor_hp" class="form-control" value="{{ old('nomor_hp', $pelakuUsaha->nomor_hp ?? '') }}" placeholder="Nomor telepon">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $pelakuUsaha->email ?? '') }}" placeholder="Alamat email">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Status & Foto --}}
        <div class="card card-primary card-outline fade-in mb-4" style="animation-delay: 0.2s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-tags me-2 text-primary"></i>Status & Lampiran</h3></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Status Pelaku Usaha <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['aktif' => 'Aktif', 'tidak_aktif' => 'Tidak Aktif', 'dalam_proses' => 'Dalam Proses', 'bermasalah' => 'Bermasalah'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('status', $pelakuUsaha->status ?? 'aktif') == $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Foto Lokasi Usaha</label>
                    <input type="file" name="foto_lokasi" class="form-control" accept="image/*">
                    <div class="form-text">Format: JPG, PNG. Maks 2MB.</div>
                    @if(!empty($pelakuUsaha) && $pelakuUsaha->foto_lokasi)
                        <div class="mt-2 position-relative" style="width: 100px;">
                            <img src="{{ asset('storage/'.$pelakuUsaha->foto_lokasi) }}" class="img-thumbnail rounded shadow-sm" style="height:100px; width:100px; object-fit:cover;">
                        </div>
                    @endif
                </div>

                <hr class="my-4">
                
                <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                    <span>Dokumen Pendukung</span>
                    <button type="button" id="btnTambahDokumen" class="btn btn-sm btn-outline-primary py-0 px-2 rounded-pill"><i class="fas fa-plus"></i> Tambah</button>
                </label>
                
                <div id="dokumenWrapper">
                    <div class="repeater-row dokumen-row">
                        <div class="mb-2">
                            <input type="text" name="jenis_dokumen[]" class="form-control form-control-sm" placeholder="Jenis dokumen">
                        </div>
                        <div class="d-flex gap-2">
                            <input type="file" name="dokumen[]" class="form-control form-control-sm">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-dokumen"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card fade-in" style="animation-delay: 0.25s">
    <div class="card-body">
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('pelaku-usaha.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Simpan Data Pelaku Usaha</button>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Fix leaflet z-index to not overlap with select2 dropdowns */
    .leaflet-container { z-index: 1 !important; }
</style>
@endpush

@push('scripts')
<script>
    $('#btnTambahDokumen').on('click', function () {
        $('#dokumenWrapper').append($('.dokumen-row').first().clone().find('input').val('').end().prop('outerHTML'));
    });
    $(document).on('click', '.btn-hapus-dokumen', function () {
        if ($('.dokumen-row').length > 1) $(this).closest('.dokumen-row').remove();
    });

    function loadWilayah(url, target, placeholder) {
        $.get(url, function (data) {
            let $t = $(target).empty().append(`<option value="">-- ${placeholder} --</option>`);
            data.forEach(d => $t.append(`<option value="${d.id}">${d.nama}</option>`));
            $t.trigger('change');
        });
    }

    $('#provinsi_id').on('change', function () {
        if ($(this).val()) loadWilayah(`/wilayah/kabupaten-by-provinsi/${$(this).val()}`, '#kabupaten_id', 'Pilih Kabupaten');
    });
    $('#kabupaten_id').on('change', function () {
        if ($(this).val()) loadWilayah(`/wilayah/kecamatan-by-kabupaten/${$(this).val()}`, '#kecamatan_id', 'Pilih Kecamatan');
    });
    $('#kecamatan_id').on('change', function () {
        if ($(this).val()) loadWilayah(`/wilayah/kelurahan-by-kecamatan/${$(this).val()}`, '#kelurahan_id', 'Pilih Kelurahan');
    });

    // Map Init
    setTimeout(() => {
        const pickerMap = L.map('pickerMap').setView([
            {{ old('latitude', $pelakuUsaha->latitude ?? -1.4748) }},
            {{ old('longitude', $pelakuUsaha->longitude ?? 124.8421) }}
        ], 10);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(pickerMap);
        let marker = null;
        
        @if(!empty($pelakuUsaha) && $pelakuUsaha->latitude)
            marker = L.marker([{{ $pelakuUsaha->latitude }}, {{ $pelakuUsaha->longitude }}], {draggable: true}).addTo(pickerMap);
            
            // Listen to marker drag
            marker.on('dragend', function(e) {
                $('#latitude').val(e.target.getLatLng().lat.toFixed(7));
                $('#longitude').val(e.target.getLatLng().lng.toFixed(7));
            });
        @endif
        
        pickerMap.on('click', function (e) {
            if (marker) pickerMap.removeLayer(marker);
            marker = L.marker(e.latlng, {draggable: true}).addTo(pickerMap);
            
            // Listen to marker drag
            marker.on('dragend', function(ev) {
                $('#latitude').val(ev.target.getLatLng().lat.toFixed(7));
                $('#longitude').val(ev.target.getLatLng().lng.toFixed(7));
            });
            
            $('#latitude').val(e.latlng.lat.toFixed(7));
            $('#longitude').val(e.latlng.lng.toFixed(7));
        });
        
        pickerMap.invalidateSize();
    }, 500);
</script>
@endpush

@csrf

<div class="card-header p-0 border-bottom-0">
    <ul class="nav nav-tabs custom-tabs pt-3 px-3" id="baPencemaranTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-utama-tab" data-bs-toggle="tab" data-bs-target="#tab-utama" type="button" role="tab" aria-selected="true"><i class="fas fa-info-circle me-1"></i> Utama & Pengawas</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-profil-tab" data-bs-toggle="tab" data-bs-target="#tab-profil" type="button" role="tab" aria-selected="false"><i class="fas fa-building me-1"></i> Profil Usaha</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-sektor-tab" data-bs-toggle="tab" data-bs-target="#tab-sektor" type="button" role="tab" aria-selected="false"><i class="fas fa-file-contract me-1"></i> Sektor & Izin</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-hasil-tab" data-bs-toggle="tab" data-bs-target="#tab-hasil" type="button" role="tab" aria-selected="false"><i class="fas fa-clipboard-list me-1"></i> Hasil Pengawasan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-dugaan-tab" data-bs-toggle="tab" data-bs-target="#tab-dugaan" type="button" role="tab" aria-selected="false"><i class="fas fa-search me-1"></i> Dugaan & Sampel</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-kesimpulan-tab" data-bs-toggle="tab" data-bs-target="#tab-kesimpulan" type="button" role="tab" aria-selected="false"><i class="fas fa-check-double me-1"></i> Kesimpulan & TTD</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-lampiran-tab" data-bs-toggle="tab" data-bs-target="#tab-lampiran" type="button" role="tab" aria-selected="false"><i class="fas fa-paperclip me-1"></i> Lampiran Form E</button>
        </li>
    </ul>
</div>
<div class="card-body">
    <div class="tab-content" id="baPencemaranTabsContent">
        
        <!-- TAB 1: Utama & Pengawas -->
        <div class="tab-pane fade show active" id="tab-utama" role="tabpanel" aria-labelledby="tab-utama-tab">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Unit Kerja</label>
                    <input type="text" class="form-control" name="unit_kerja" value="{{ old('unit_kerja', $baPencemaran->unit_kerja ?? 'Pangkalan Pengawasan Sumber Daya Kelautan dan Perikanan Bitung') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor BA</label>
                    <input type="text" class="form-control" name="nomor_ba" value="{{ old('nomor_ba', $baPencemaran->nomor_ba ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Pengawasan</label>
                    <input type="date" class="form-control" name="tanggal_pengawasan" value="{{ old('tanggal_pengawasan', optional($baPencemaran ?? null)->tanggal_pengawasan?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jam WITA</label>
                    <input type="time" class="form-control" name="jam_wita" value="{{ old('jam_wita', $baPencemaran->jam_wita ?? '') }}">
                </div>
                
                <div class="col-12 mt-4">
                    <h6 class="section-title">Jenis Pengawasan</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="jenis_pengawasan" id="rutin" value="rutin" {{ old('jenis_pengawasan', $baPencemaran->jenis_pengawasan ?? '') == 'rutin' ? 'checked' : '' }}>
                        <label class="form-check-label" for="rutin">Pengawasan rutin</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="jenis_pengawasan" id="insidental" value="insidental" {{ old('jenis_pengawasan', $baPencemaran->jenis_pengawasan ?? '') == 'insidental' ? 'checked' : '' }}>
                        <label class="form-check-label" for="insidental">Pengawasan insidental</label>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Berdasarkan laporan pengaduan nomor</label>
                            <input type="text" class="form-control" name="laporan_pengaduan_nomor" value="{{ old('laporan_pengaduan_nomor', $baPencemaran->laporan_pengaduan_nomor ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Laporan</label>
                            <input type="date" class="form-control" name="laporan_pengaduan_tgl" value="{{ old('laporan_pengaduan_tgl', optional($baPencemaran ?? null)->laporan_pengaduan_tgl?->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <h6 class="section-title">A. Lokasi Usaha/Kegiatan</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Alamat Lokasi (secara administratif)</label>
                    <textarea class="form-control" name="lokasi_pengawasan" rows="3">{{ old('lokasi_pengawasan', $baPencemaran->lokasi_pengawasan ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Koordinat Titik Lokasi</label>
                    <textarea class="form-control" name="koordinat" rows="3" placeholder="Koordinat lokasi">{{ old('koordinat', $baPencemaran->koordinat ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input type="text" class="form-control" name="latitude" value="{{ old('latitude', $baPencemaran->latitude ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input type="text" class="form-control" name="longitude" value="{{ old('longitude', $baPencemaran->longitude ?? '') }}">
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2 mt-4 border-top pt-3">
                <h6 class="section-title mb-0 border-0 pb-0">Tim Pengawas</h6>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btn-add-pengawas">
                    <i class="fas fa-plus me-1"></i> Tambah Pengawas
                </button>
            </div>
            
            <div id="pengawas-container">
                @php 
                    $pengawas = old('pengawas', isset($baPencemaran) ? $baPencemaran->pengawas->toArray() : [['nama' => '', 'nip' => '', 'jabatan' => '', 'unit_kerja' => '']]); 
                    if(empty($pengawas)) $pengawas = [['nama' => '', 'nip' => '', 'jabatan' => '', 'unit_kerja' => '']];
                @endphp
                @foreach($pengawas as $index => $p)
                <div class="repeater-row pengawas-row mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="pengawas[{{ $index }}][nama]" value="{{ $p['nama'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">NIP</label>
                            <input type="text" class="form-control" name="pengawas[{{ $index }}][nip]" value="{{ $p['nip'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" class="form-control" name="pengawas[{{ $index }}][jabatan]" value="{{ $p['jabatan'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Unit Kerja</label>
                            <input type="text" class="form-control" name="pengawas[{{ $index }}][unit_kerja]" value="{{ $p['unit_kerja'] ?? '' }}">
                        </div>
                        <div class="col-md-1 text-center">
                            <button type="button" class="btn btn-outline-danger btn-remove-pengawas w-100" {{ count($pengawas) == 1 ? 'disabled' : '' }}><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- TAB 2: Profil Usaha -->
        <div class="tab-pane fade" id="tab-profil" role="tabpanel" aria-labelledby="tab-profil-tab">
            <h6 class="section-title mb-3">B. Informasi Pelaku Usaha / Pelaku Kegiatan</h6>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Pelaku Usaha (Pilih dari Master Data)</label>
                    <select class="form-select" id="pelaku_usaha_id" name="pelaku_usaha_id">
                        <option value="">-- Ketik untuk mencari / input manual --</option>
                        @foreach($pelakuUsahas as $pu)
                            <option value="{{ $pu->id }}" data-nib="{{ $pu->nib }}" data-pj="{{ $pu->nama_pj }}" {{ old('pelaku_usaha_id', $baPencemaran->pelaku_usaha_id ?? '') == $pu->id ? 'selected' : '' }}>
                                {{ $pu->nama_perusahaan }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Jika tidak ada, nama yang diketik akan otomatis ditambahkan ke Master Data.</div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Nama Usaha / Nama Kegiatan (opsional)</label>
                    <input type="text" class="form-control" name="nama_usaha_kegiatan" value="{{ old('nama_usaha_kegiatan', $baPencemaran->nama_usaha_kegiatan ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Induk Berusaha (NIB)</label>
                    <input type="text" class="form-control" name="nib" id="nib" value="{{ old('nib', $baPencemaran->nib ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Luas pemanfaatan ruang darat (Ha)</label>
                    <input type="text" class="form-control" name="luas_darat" value="{{ old('luas_darat', $baPencemaran->luas_darat ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Luas pemanfaatan ruang laut (Ha)</label>
                    <input type="text" class="form-control" name="luas_laut" value="{{ old('luas_laut', $baPencemaran->luas_laut ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Zona / Sub Zona</label>
                    <input type="text" class="form-control" name="zona_sub_zona" value="{{ old('zona_sub_zona', $baPencemaran->zona_sub_zona ?? '') }}">
                </div>

                <div class="col-12 mt-3"><h6 class="section-title">Penanggung Jawab</h6></div>
                <div class="col-md-6">
                    <label class="form-label">Nama Penanggung Jawab</label>
                    <input type="text" class="form-control pj-nama-input" name="nama_pj" id="nama_pj" value="{{ old('nama_pj', $baPencemaran->nama_pj ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Identitas</label>
                    <input type="text" class="form-control" name="nik_pj" value="{{ old('nik_pj', $baPencemaran->nik_pj ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jabatan</label>
                    <input type="text" class="form-control" name="jabatan_pj" value="{{ old('jabatan_pj', $baPencemaran->jabatan_pj ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Alamat Kantor</label>
                    <textarea class="form-control" name="alamat_kantor" rows="2">{{ old('alamat_kantor', $baPencemaran->alamat_kantor ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" class="form-control" name="email_pj" value="{{ old('email_pj', $baPencemaran->email_pj ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. Telp / HP</label>
                    <input type="text" class="form-control" name="no_telp_pj" value="{{ old('no_telp_pj', $baPencemaran->no_telp_pj ?? '') }}">
                </div>
            </div>
        </div>

        <!-- TAB 3: Sektor & Izin -->
        <div class="tab-pane fade" id="tab-sektor" role="tabpanel" aria-labelledby="tab-sektor-tab">
            <h6 class="section-title mb-3">C. Jenis Usaha / Kegiatan</h6>
            @php 
                $jUsaha = old('jenis_usaha', isset($baPencemaran) ? $baPencemaran->jenis_usaha : []); 
                if(!is_array($jUsaha)) $jUsaha = [];
            @endphp
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <strong>1. Sektor Kelautan</strong>
                    <div class="form-check ms-3 mt-1">
                        <input class="form-check-input" type="checkbox" name="jenis_usaha[kel_wisata_alam]" value="1" {{ isset($jUsaha['kel_wisata_alam']) ? 'checked' : '' }}>
                        <label class="form-check-label">a. pengusahaan pariwisata alam perairan di Kawasan Konservasi</label>
                    </div>
                    <div class="ms-5 small">
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_wisata_akomodasi]" value="1" {{ isset($jUsaha['kel_wisata_akomodasi']) ? 'checked' : '' }}> 1) akomodasi wisata</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_wisata_makanan]" value="1" {{ isset($jUsaha['kel_wisata_makanan']) ? 'checked' : '' }}> 2) makanan dan minuman</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_wisata_mangrove]" value="1" {{ isset($jUsaha['kel_wisata_mangrove']) ? 'checked' : '' }}> 3) wisata mangrove</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_wisata_marina]" value="1" {{ isset($jUsaha['kel_wisata_marina']) ? 'checked' : '' }}> 4) marina</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_wisata_tirta]" value="1" {{ isset($jUsaha['kel_wisata_tirta']) ? 'checked' : '' }}> 5) usaha wisata tirta</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_wisata_transportasi]" value="1" {{ isset($jUsaha['kel_wisata_transportasi']) ? 'checked' : '' }}> 6) transportasi wisata</label>
                    </div>

                    <div class="form-check ms-3 mt-2"><input class="form-check-input" type="checkbox" name="jenis_usaha[kel_kapal_tenggelam]" value="1" {{ isset($jUsaha['kel_kapal_tenggelam']) ? 'checked' : '' }}> <label class="form-check-label">b. pengangkatan benda muatan kapal tenggelam</label></div>
                    
                    <div class="form-check ms-3 mt-2">
                        <input class="form-check-input" type="checkbox" name="jenis_usaha[kel_garam]" value="1" {{ isset($jUsaha['kel_garam']) ? 'checked' : '' }}>
                        <label class="form-check-label">c. produksi garam</label>
                    </div>
                    <div class="ms-5 small">
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_garam_pra]" value="1" {{ isset($jUsaha['kel_garam_pra']) ? 'checked' : '' }}> 1) pra produksi</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_garam_produksi]" value="1" {{ isset($jUsaha['kel_garam_produksi']) ? 'checked' : '' }}> 2) produksi</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_garam_pasca]" value="1" {{ isset($jUsaha['kel_garam_pasca']) ? 'checked' : '' }}> 3) pasca produksi</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_garam_pengolahan]" value="1" {{ isset($jUsaha['kel_garam_pengolahan']) ? 'checked' : '' }}> 4) pengolahan</label>
                    </div>

                    <div class="form-check ms-3 mt-2"><input class="form-check-input" type="checkbox" name="jenis_usaha[kel_biofarmakologi]" value="1" {{ isset($jUsaha['kel_biofarmakologi']) ? 'checked' : '' }}> <label class="form-check-label">d. biofarmakologi</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[kel_bioteknologi]" value="1" {{ isset($jUsaha['kel_bioteknologi']) ? 'checked' : '' }}> <label class="form-check-label">e. bioteknologi</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[kel_air_laut]" value="1" {{ isset($jUsaha['kel_air_laut']) ? 'checked' : '' }}> <label class="form-check-label">f. pemanfaatan air laut selain energi</label></div>
                    
                    <div class="form-check ms-3 mt-2">
                        <input class="form-check-input" type="checkbox" name="jenis_usaha[kel_reklamasi]" value="1" {{ isset($jUsaha['kel_reklamasi']) ? 'checked' : '' }}>
                        <label class="form-check-label">g. pelaksanaan reklamasi</label>
                    </div>
                    <div class="ms-5 small">
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_reklamasi_pelaksanaan]" value="1" {{ isset($jUsaha['kel_reklamasi_pelaksanaan']) ? 'checked' : '' }}> 1) pelaksanaan reklamasi</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_reklamasi_material]" value="1" {{ isset($jUsaha['kel_reklamasi_material']) ? 'checked' : '' }}> 2) pengambilan sumber material reklamasi</label>
                    </div>

                    <div class="form-check ms-3 mt-2">
                        <input class="form-check-input" type="checkbox" name="jenis_usaha[kel_ppk_pma]" value="1" {{ isset($jUsaha['kel_ppk_pma']) ? 'checked' : '' }}>
                        <label class="form-check-label">h. pemanfaatan pulau-pulau kecil dan perairan di sekitarnya dalam rangka penanaman modal asing</label>
                    </div>
                    <div class="ms-5 small">
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_ppk_pma_budidaya]" value="1" {{ isset($jUsaha['kel_ppk_pma_budidaya']) ? 'checked' : '' }}> 1) budidaya laut</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_ppk_pma_wisata]" value="1" {{ isset($jUsaha['kel_ppk_pma_wisata']) ? 'checked' : '' }}> 2) usaha wisata tirta</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_ppk_pma_industri]" value="1" {{ isset($jUsaha['kel_ppk_pma_industri']) ? 'checked' : '' }}> 3) usaha perikanan dan kelautan serta industri perikanan secara lestari</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_ppk_pma_organik]" value="1" {{ isset($jUsaha['kel_ppk_pma_organik']) ? 'checked' : '' }}> 4) pertanian organik</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_ppk_pma_peternakan]" value="1" {{ isset($jUsaha['kel_ppk_pma_peternakan']) ? 'checked' : '' }}> 5) peternakan</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_ppk_pma_storage]" value="1" {{ isset($jUsaha['kel_ppk_pma_storage']) ? 'checked' : '' }}> 6) fasilitas penyimpanan minyak (oil storage)</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kel_ppk_pma_pemukiman]" value="1" {{ isset($jUsaha['kel_ppk_pma_pemukiman']) ? 'checked' : '' }}> 7) permukiman di atas air</label>
                    </div>

                    <div class="form-check ms-3 mt-2"><input class="form-check-input" type="checkbox" name="jenis_usaha[kel_ppk]" value="1" {{ isset($jUsaha['kel_ppk']) ? 'checked' : '' }}> <label class="form-check-label">i. pemanfaatan pulau-pulau kecil di bawah 100 km2 (seratus kilometer persegi)</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[kel_pasir]" value="1" {{ isset($jUsaha['kel_pasir']) ? 'checked' : '' }}> <label class="form-check-label">j. pemanfaatan pasir laut</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[kel_bangunan]" value="1" {{ isset($jUsaha['kel_bangunan']) ? 'checked' : '' }}> <label class="form-check-label">k. bangunan laut dalam kegiatan wisata tirta lainnya</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[kel_pipa]" value="1" {{ isset($jUsaha['kel_pipa']) ? 'checked' : '' }}> <label class="form-check-label">l. pipa dan/atau kabel bawah Laut.</label></div>
                </div>
                
                <div class="col-md-6">
                    <strong>2. Sektor Perikanan</strong>
                    <div class="form-check ms-3 mt-1"><input class="form-check-input" type="checkbox" name="jenis_usaha[kan_kapal]" value="1" {{ isset($jUsaha['kan_kapal']) ? 'checked' : '' }}> <label class="form-check-label">a. kapal perikanan</label></div>
                    
                    <div class="form-check ms-3 mt-2">
                        <input class="form-check-input" type="checkbox" name="jenis_usaha[kan_budidaya]" value="1" {{ isset($jUsaha['kan_budidaya']) ? 'checked' : '' }}> 
                        <label class="form-check-label">b. pembudidayaan ikan</label>
                    </div>
                    <div class="ms-5 small">
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_b_kja]" value="1" {{ isset($jUsaha['kan_b_kja']) ? 'checked' : '' }}> 1) keramba jaring apung (KJA)</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_b_tambak]" value="1" {{ isset($jUsaha['kan_b_tambak']) ? 'checked' : '' }}> 2) kolam/tambak pembudidayaan ikan</label>
                        <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_b_lain]" value="1" {{ isset($jUsaha['kan_b_lain']) ? 'checked' : '' }}> 3) tempat pembudidayaan ikan lainnya</label>
                    </div>
                    
                    <div class="form-check ms-3 mt-2">
                        <input class="form-check-input" type="checkbox" name="jenis_usaha[kan_olah]" value="1" {{ isset($jUsaha['kan_olah']) ? 'checked' : '' }}> 
                        <label class="form-check-label">c. unit pengolahan ikan</label>
                    </div>
                    <div class="ms-5 small row">
                        <div class="col-6">
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_1]" value="1" {{ isset($jUsaha['kan_olah_1']) ? 'checked' : '' }}> 1) penggaraman ikan</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_2]" value="1" {{ isset($jUsaha['kan_olah_2']) ? 'checked' : '' }}> 2) pengeringan ikan</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_3]" value="1" {{ isset($jUsaha['kan_olah_3']) ? 'checked' : '' }}> 3) pengasapan/pemanggangan</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_4]" value="1" {{ isset($jUsaha['kan_olah_4']) ? 'checked' : '' }}> 4) pembekuan ikan</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_5]" value="1" {{ isset($jUsaha['kan_olah_5']) ? 'checked' : '' }}> 5) pemindangan ikan</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_6]" value="1" {{ isset($jUsaha['kan_olah_6']) ? 'checked' : '' }}> 6) peragian/fermentasi ikan</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_7]" value="1" {{ isset($jUsaha['kan_olah_7']) ? 'checked' : '' }}> 7) pengolahan berbasis daging lumatan</label>
                        </div>
                        <div class="col-6">
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_8]" value="1" {{ isset($jUsaha['kan_olah_8']) ? 'checked' : '' }}> 8) pendinginan ikan</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_9]" value="1" {{ isset($jUsaha['kan_olah_9']) ? 'checked' : '' }}> 9) pengalengan ikan</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_10]" value="1" {{ isset($jUsaha['kan_olah_10']) ? 'checked' : '' }}> 10) pengolahan rumput laut</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_11]" value="1" {{ isset($jUsaha['kan_olah_11']) ? 'checked' : '' }}> 11) pembuatan minyak ikan</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_12]" value="1" {{ isset($jUsaha['kan_olah_12']) ? 'checked' : '' }}> 12) pencucian ikan dan tepung ikan</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_13]" value="1" {{ isset($jUsaha['kan_olah_13']) ? 'checked' : '' }}> 13) pengolahan kerupuk, keripik</label>
                            <label class="d-block"><input type="checkbox" name="jenis_usaha[kan_olah_14]" value="1" {{ isset($jUsaha['kan_olah_14']) ? 'checked' : '' }}> 14) pengolahan dan pengawetan lainnya</label>
                        </div>
                    </div>

                    <div class="form-check ms-3 mt-2"><input class="form-check-input" type="checkbox" name="jenis_usaha[kan_pelabuhan]" value="1" {{ isset($jUsaha['kan_pelabuhan']) ? 'checked' : '' }}> <label class="form-check-label">d. pelabuhan perikanan</label></div>

                    <strong class="d-block mt-4">3. Kegiatan dan/atau Usaha Lain</strong>
                    <div class="form-check ms-3 mt-1"><input class="form-check-input" type="checkbox" name="jenis_usaha[lain_pariwisata]" value="1" {{ isset($jUsaha['lain_pariwisata']) ? 'checked' : '' }}> <label class="form-check-label">a. pariwisata</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[lain_pelabuhan]" value="1" {{ isset($jUsaha['lain_pelabuhan']) ? 'checked' : '' }}> <label class="form-check-label">b. pelabuhan umum</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[lain_tambang]" value="1" {{ isset($jUsaha['lain_tambang']) ? 'checked' : '' }}> <label class="form-check-label">c. pertambangan minyak, gas, mineral dan batubara</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[lain_transport]" value="1" {{ isset($jUsaha['lain_transport']) ? 'checked' : '' }}> <label class="form-check-label">d. transportasi laut</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[lain_industri]" value="1" {{ isset($jUsaha['lain_industri']) ? 'checked' : '' }}> <label class="form-check-label">e. industri</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[lain_listrik]" value="1" {{ isset($jUsaha['lain_listrik']) ? 'checked' : '' }}> <label class="form-check-label">f. ketenagalistrikan</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[lain_sampah]" value="1" {{ isset($jUsaha['lain_sampah']) ? 'checked' : '' }}> <label class="form-check-label">g. kebocoran sampah padat dan limbah cair rumah tangga/ kegiatan permukiman dari darat ke perairan laut</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[lain_tani]" value="1" {{ isset($jUsaha['lain_tani']) ? 'checked' : '' }}> <label class="form-check-label">h. pertanian, perkebunan, dan/atau peternakan</label></div>
                    <div class="form-check ms-3"><input class="form-check-input" type="checkbox" name="jenis_usaha[lain_dampak]" value="1" {{ isset($jUsaha['lain_dampak']) ? 'checked' : '' }}> <label class="form-check-label">i. kegiatan berdampak/usaha lain pada Sumber Daya Ikan dan lingkungannya</label></div>
                </div>
            </div>

            <hr>
            <h6 class="section-title mb-3">D. Pemeriksaan Perizinan Sesuai Kegiatan</h6>
            @php 
                $pDasar = old('perizinan_dasar', isset($baPencemaran) ? $baPencemaran->perizinan_dasar : []); 
                if(!is_array($pDasar)) $pDasar = [];
                $dPencegah = old('dokumen_pencegahan', isset($baPencemaran) ? $baPencemaran->dokumen_pencegahan : []); 
                if(!is_array($dPencegah)) $dPencegah = [];
            @endphp
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-sm align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Persyaratan Dasar</th>
                            <th>Nomor</th>
                            <th>Tanggal Terbit & Masa Berlaku</th>
                            <th>Instansi Penerbit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>a. PKKPRL/KKRL<br>
                                <label><input type="radio" name="perizinan_dasar[pkkprl][status]" value="ada" {{ ($pDasar['pkkprl']['status'] ?? '') == 'ada' ? 'checked' : '' }}> ada</label>
                                <label class="ms-2"><input type="radio" name="perizinan_dasar[pkkprl][status]" value="tidak ada" {{ ($pDasar['pkkprl']['status'] ?? '') == 'tidak ada' ? 'checked' : '' }}> tidak ada</label>
                            </td>
                            <td><input type="text" class="form-control" name="perizinan_dasar[pkkprl][nomor]" value="{{ $pDasar['pkkprl']['nomor'] ?? '' }}"></td>
                            <td><input type="text" class="form-control" name="perizinan_dasar[pkkprl][tgl]" value="{{ $pDasar['pkkprl']['tgl'] ?? '' }}"></td>
                            <td><input type="text" class="form-control" name="perizinan_dasar[pkkprl][instansi]" value="{{ $pDasar['pkkprl']['instansi'] ?? '' }}"></td>
                        </tr>
                        <tr>
                            <td>b. Persetujuan Lingkungan<br>
                                <label><input type="radio" name="perizinan_dasar[lingkungan][status]" value="ada" {{ ($pDasar['lingkungan']['status'] ?? '') == 'ada' ? 'checked' : '' }}> ada</label>
                                <label class="ms-2"><input type="radio" name="perizinan_dasar[lingkungan][status]" value="tidak ada" {{ ($pDasar['lingkungan']['status'] ?? '') == 'tidak ada' ? 'checked' : '' }}> tidak ada</label>
                            </td>
                            <td><input type="text" class="form-control" name="perizinan_dasar[lingkungan][nomor]" value="{{ $pDasar['lingkungan']['nomor'] ?? '' }}"></td>
                            <td><input type="text" class="form-control" name="perizinan_dasar[lingkungan][tgl]" value="{{ $pDasar['lingkungan']['tgl'] ?? '' }}"></td>
                            <td><input type="text" class="form-control" name="perizinan_dasar[lingkungan][instansi]" value="{{ $pDasar['lingkungan']['instansi'] ?? '' }}"></td>
                        </tr>
                        <tr>
                            <td>c. Izin Mendirikan Bangunan<br>
                                <label><input type="radio" name="perizinan_dasar[imb][status]" value="ada" {{ ($pDasar['imb']['status'] ?? '') == 'ada' ? 'checked' : '' }}> ada</label>
                                <label class="ms-2"><input type="radio" name="perizinan_dasar[imb][status]" value="tidak ada" {{ ($pDasar['imb']['status'] ?? '') == 'tidak ada' ? 'checked' : '' }}> tidak ada</label>
                            </td>
                            <td><input type="text" class="form-control" name="perizinan_dasar[imb][nomor]" value="{{ $pDasar['imb']['nomor'] ?? '' }}"></td>
                            <td><input type="text" class="form-control" name="perizinan_dasar[imb][tgl]" value="{{ $pDasar['imb']['tgl'] ?? '' }}"></td>
                            <td><input type="text" class="form-control" name="perizinan_dasar[imb][instansi]" value="{{ $pDasar['imb']['instansi'] ?? '' }}"></td>
                        </tr>
                        <tr>
                            <td>2. Dokumen Rencana Pencegahan Pencemaran<br>
                                <label><input type="checkbox" name="dokumen_pencegahan[amdal]" value="1" {{ isset($dPencegah['amdal']) ? 'checked' : '' }}> a. AMDAL</label><br>
                                <label><input type="checkbox" name="dokumen_pencegahan[uklupl]" value="1" {{ isset($dPencegah['uklupl']) ? 'checked' : '' }}> b. UKL-UPL</label><br>
                                <label><input type="checkbox" name="dokumen_pencegahan[sppl]" value="1" {{ isset($dPencegah['sppl']) ? 'checked' : '' }}> c. SPPL</label><br>
                                <div class="d-flex align-items-center mt-1">
                                    <label class="me-2 text-nowrap"><input type="checkbox" name="dokumen_pencegahan[lain_check]" value="1" {{ isset($dPencegah['lain_check']) ? 'checked' : '' }}> d. Lainnya:</label>
                                    <input type="text" class="form-control form-control-sm" name="dokumen_pencegahan[lain_text]" value="{{ $dPencegah['lain_text'] ?? '' }}">
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3. Perizinan Berusaha<br>
                                a. Sebutkan:
                                @php
                                    $pBerusaha = old('perizinan_berusaha', isset($baPencemaran) ? $baPencemaran->perizinan_berusaha : []);
                                    if(!is_array($pBerusaha)) $pBerusaha = [];
                                @endphp
                                <textarea class="form-control mt-1" name="perizinan_berusaha[sebutkan]" rows="3">{{ $pBerusaha['sebutkan'] ?? '' }}</textarea>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 4: Hasil Pengawasan -->
        <div class="tab-pane fade" id="tab-hasil" role="tabpanel" aria-labelledby="tab-hasil-tab">
            <h6 class="section-title mb-3">E. Hasil Pengawasan</h6>
            <div class="alert alert-info py-2"><small><i class="fas fa-info-circle"></i> Hasil pengawasan diisi dengan memilih Sesuai / Tidak Sesuai untuk tiap sektor.</small></div>
            
            @php 
                $hp = old('hasil_pengawasan', isset($baPencemaran) ? $baPencemaran->hasil_pengawasan : []); 
                if(!is_array($hp)) $hp = [];
            @endphp
            
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th style="width:80%">1. Kesesuaian Pelaksanaan Kegiatan Pencegahan Pencemaran (Sektor Kelautan)</th>
                            <th class="text-center">Sesuai</th>
                            <th class="text-center">Tidak Sesuai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="3"><strong>a. Pengusahaan pariwisata alam perairan di Kawasan Konservasi</strong></td></tr>
                        <tr><td colspan="3"><strong>b. Pengangkatan benda muatan kapal tenggelam</strong></td></tr>
                        <tr><td colspan="3"><strong>c. Biofarmakologi</strong></td></tr>
                        <tr><td colspan="3"><strong>d. Bioteknologi</strong></td></tr>
                        <tr><td colspan="3"><strong>e. Pemanfaatan Air Laut Selain Energi</strong><br>Hasil Pemeriksaan:</td></tr>
                        <tr>
                            <td class="ps-4">1) dokumen rencana pencegahan pencemaran (Form E.1)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[e_1]" value="sesuai" {{ ($hp['e_1'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[e_1]" value="tidak" {{ ($hp['e_1'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">2) ketersediaan fasilitas pencegahan pencemaran (Form E.2)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[e_2]" value="sesuai" {{ ($hp['e_2'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[e_2]" value="tidak" {{ ($hp['e_2'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">3) sistem pengolahan dan pembuangan limbah (Form E.3)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[e_3]" value="sesuai" {{ ($hp['e_3'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[e_3]" value="tidak" {{ ($hp['e_3'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">4) standar pengelolaan bahan berpotensi penyebab pencemaran (Form E.4)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[e_4]" value="sesuai" {{ ($hp['e_4'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[e_4]" value="tidak" {{ ($hp['e_4'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>

                        <tr><td colspan="3"><strong>f. Produksi garam</strong><br>Hasil Pemeriksaan:</td></tr>
                        <tr>
                            <td class="ps-4">1) dokumen rencana pencegahan pencemaran (Form E.1)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[f_1]" value="sesuai" {{ ($hp['f_1'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[f_1]" value="tidak" {{ ($hp['f_1'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">2) kesesuaian tempat pengelolaan seluruh bahan (layout eksisting)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[f_2]" value="sesuai" {{ ($hp['f_2'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[f_2]" value="tidak" {{ ($hp['f_2'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">3) proses daur ulang seluruh bahan yang digunakan</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[f_3]" value="sesuai" {{ ($hp['f_3'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[f_3]" value="tidak" {{ ($hp['f_3'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>

                        <tr><td colspan="3"><strong>g. Reklamasi</strong><br>Hasil Pemeriksaan:</td></tr>
                        <tr>
                            <td class="ps-4">1) dokumen rencana pencegahan pencemaran (Form E.1)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[g_1]" value="sesuai" {{ ($hp['g_1'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[g_1]" value="tidak" {{ ($hp['g_1'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">2) dokumen PKKPRL atau KKRL (Form E.5)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[g_2]" value="sesuai" {{ ($hp['g_2'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[g_2]" value="tidak" {{ ($hp['g_2'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">3) kesesuaian pelaksanaan reklamasi / pengambilan sumber material</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[g_3]" value="sesuai" {{ ($hp['g_3'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[g_3]" value="tidak" {{ ($hp['g_3'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">4) kesesuaian tahapan pelaksanaan reklamasi sesuai standar</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[g_4]" value="sesuai" {{ ($hp['g_4'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[g_4]" value="tidak" {{ ($hp['g_4'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">5) material reklamasi tidak mengandung bahan beracun dan berbahaya</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[g_5]" value="sesuai" {{ ($hp['g_5'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[g_5]" value="tidak" {{ ($hp['g_5'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        
                        <tr><td colspan="3"><strong>h. Pemanfaatan pulau-pulau kecil PMA dan di bawah 100 km2 (budidaya laut, dll)</strong><br>Hasil Pemeriksaan:</td></tr>
                        <tr>
                            <td class="ps-4">1) dokumen rencana pencegahan pencemaran (Form E.1)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[h_1]" value="sesuai" {{ ($hp['h_1'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[h_1]" value="tidak" {{ ($hp['h_1'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">2) ketersediaan fasilitas pencegahan pencemaran (Form E.2)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[h_2]" value="sesuai" {{ ($hp['h_2'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[h_2]" value="tidak" {{ ($hp['h_2'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">3) sistem pengolahan dan pembuangan limbah (Form E.3)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[h_3]" value="sesuai" {{ ($hp['h_3'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[h_3]" value="tidak" {{ ($hp['h_3'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">4) standar pengelolaan bahan berpotensi penyebab pencemaran (Form E.4)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[h_4]" value="sesuai" {{ ($hp['h_4'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[h_4]" value="tidak" {{ ($hp['h_4'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>

                        <tr><td colspan="3"><strong>i. Pemanfaatan pulau-pulau kecil PMA dan < 100 km2 (pertanian organik, peternakan, storage, permukiman)</strong></td></tr>
                        <tr><td colspan="3"><strong>j. Bangunan laut dalam kegiatan wisata tirta lainnya</strong></td></tr>
                        <tr><td colspan="3"><strong>k. Pipa dan/atau kabel bawah laut</strong><br>Hasil Pemeriksaan:</td></tr>
                        <tr>
                            <td class="ps-4">a) dokumen rencana pencegahan pencemaran (Form E.1)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_a]" value="sesuai" {{ ($hp['k_a'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_a]" value="tidak" {{ ($hp['k_a'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">b) kesesuaian pelaksanaan dengan dokumen AMDAL/UKL-UPL/SPPL</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_b]" value="sesuai" {{ ($hp['k_b'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_b]" value="tidak" {{ ($hp['k_b'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">c) ketersediaan sarana sanitasi</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_c]" value="sesuai" {{ ($hp['k_c'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_c]" value="tidak" {{ ($hp['k_c'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">d) ketersediaan papan informasi pencegahan pencemaran</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_d]" value="sesuai" {{ ($hp['k_d'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_d]" value="tidak" {{ ($hp['k_d'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">e) pengaturan sistem pengolahan dan pembuangan limbah (Form E.3)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_e]" value="sesuai" {{ ($hp['k_e'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_e]" value="tidak" {{ ($hp['k_e'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">f) penggunaan bahan-bahan berpotensi pencemaran (Form E.4)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_f]" value="sesuai" {{ ($hp['k_f'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[k_f]" value="tidak" {{ ($hp['k_f'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>

                        <tr><td colspan="3"><strong>l. Pemanfaatan Pasir Laut</strong><br>Hasil Pemeriksaan:</td></tr>
                        <tr>
                            <td class="ps-4">1) dokumen rencana pencegahan pencemaran (Form E.1)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[l_1]" value="sesuai" {{ ($hp['l_1'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[l_1]" value="tidak" {{ ($hp['l_1'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">2) kesesuaian pelaksanaan dengan dokumen AMDAL/UKL-UPL/SPPL</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[l_2]" value="sesuai" {{ ($hp['l_2'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[l_2]" value="tidak" {{ ($hp['l_2'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">3) sistem pengolahan dan pembuangan limbah (Form E.3)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[l_3]" value="sesuai" {{ ($hp['l_3'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[l_3]" value="tidak" {{ ($hp['l_3'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">4) memeriksa material pasir laut tidak mengandung B3</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[l_4]" value="sesuai" {{ ($hp['l_4'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[l_4]" value="tidak" {{ ($hp['l_4'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                    </tbody>
                </table>
                
                <table class="table table-bordered table-sm mt-3">
                    <thead class="table-light">
                        <tr>
                            <th style="width:80%">2. Kesesuaian Pelaksanaan Kegiatan Pencegahan Pencemaran (Sektor Perikanan)</th>
                            <th class="text-center">Sesuai</th>
                            <th class="text-center">Tidak Sesuai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="3"><strong>a. Kegiatan kapal perikanan</strong><br>Hasil Pemeriksaan:</td></tr>
                        <tr>
                            <td class="ps-4">1) kondisi mesin yang berpotensi menimbulkan pencemaran</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_a_1]" value="sesuai" {{ ($hp['kan_a_1'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_a_1]" value="tidak" {{ ($hp['kan_a_1'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">2) ketersediaan fasilitas pencegahan pencemaran (Form E.2)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_a_2]" value="sesuai" {{ ($hp['kan_a_2'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_a_2]" value="tidak" {{ ($hp['kan_a_2'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">3) penanganan limbah oli bekas, sampah, dan/atau limbah lainnya (Form E.6)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_a_3]" value="sesuai" {{ ($hp['kan_a_3'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_a_3]" value="tidak" {{ ($hp['kan_a_3'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">4) kondisi perairan di sekitar area kapal perikanan yang diperiksa</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_a_4]" value="sesuai" {{ ($hp['kan_a_4'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_a_4]" value="tidak" {{ ($hp['kan_a_4'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>

                        <tr><td colspan="3"><strong>b. Kegiatan pembudidayaan ikan</strong><br>Hasil Pemeriksaan:</td></tr>
                        <tr>
                            <td class="ps-4">1) dokumen rencana pencegahan pencemaran (Form E.1)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_b_1]" value="sesuai" {{ ($hp['kan_b_1'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_b_1]" value="tidak" {{ ($hp['kan_b_1'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">2) ketersediaan fasilitas pencegahan pencemaran (Form E.2)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_b_2]" value="sesuai" {{ ($hp['kan_b_2'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_b_2]" value="tidak" {{ ($hp['kan_b_2'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">3) sistem pengolahan dan pembuangan limbah (Form E.3)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_b_3]" value="sesuai" {{ ($hp['kan_b_3'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_b_3]" value="tidak" {{ ($hp['kan_b_3'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">4) standar pengelolaan bahan-bahan yang berpotensi penyebab pencemaran (Form E.4)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_b_4]" value="sesuai" {{ ($hp['kan_b_4'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_b_4]" value="tidak" {{ ($hp['kan_b_4'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>

                        <tr><td colspan="3"><strong>c. Kegiatan pengolahan ikan</strong><br>Hasil Pemeriksaan:</td></tr>
                        <tr>
                            <td class="ps-4">1) dokumen rencana pencegahan pencemaran (Form E.1)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_c_1]" value="sesuai" {{ ($hp['kan_c_1'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_c_1]" value="tidak" {{ ($hp['kan_c_1'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">2) ketersediaan fasilitas pencegahan pencemaran (Form E.2)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_c_2]" value="sesuai" {{ ($hp['kan_c_2'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_c_2]" value="tidak" {{ ($hp['kan_c_2'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">3) pengambilan sampel air di outlet/saluran pembuangan air limbah</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_c_3]" value="sesuai" {{ ($hp['kan_c_3'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_c_3]" value="tidak" {{ ($hp['kan_c_3'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>

                        <tr><td colspan="3"><strong>d. Kegiatan pelabuhan perikanan</strong><br>Hasil Pemeriksaan:</td></tr>
                        <tr>
                            <td class="ps-4">1) dokumen rencana pencegahan pencemaran (Form E.1)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_1]" value="sesuai" {{ ($hp['kan_d_1'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_1]" value="tidak" {{ ($hp['kan_d_1'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">2) ketersediaan fasilitas pencegahan pencemaran (Form E.2)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_2]" value="sesuai" {{ ($hp['kan_d_2'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_2]" value="tidak" {{ ($hp['kan_d_2'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">3) hasil uji kualitas air di wilayah pelabuhan</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_3]" value="sesuai" {{ ($hp['kan_d_3'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_3]" value="tidak" {{ ($hp['kan_d_3'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">4) kesesuaian tempat penyimpanan dan fasilitas pengisian bahan bakar dengan standar</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_4]" value="sesuai" {{ ($hp['kan_d_4'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_4]" value="tidak" {{ ($hp['kan_d_4'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">5) kesesuaian pengelolaan limbah cair dan sampah dari TPI dengan standar</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_5]" value="sesuai" {{ ($hp['kan_d_5'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_5]" value="tidak" {{ ($hp['kan_d_5'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">6) kesesuaian pengelolaan limbah domestik pelabuhan dengan standar</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_6]" value="sesuai" {{ ($hp['kan_d_6'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_6]" value="tidak" {{ ($hp['kan_d_6'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">7) kesesuaian pengelolaan sampah yang berasal dari kapal dengan standar</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_7]" value="sesuai" {{ ($hp['kan_d_7'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_7]" value="tidak" {{ ($hp['kan_d_7'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">8) API dan ABPI yang rusak telah ditempatkan di tempat khusus</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_8]" value="sesuai" {{ ($hp['kan_d_8'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_8]" value="tidak" {{ ($hp['kan_d_8'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">9) ketersediaan tempat pengumpul sampah terpilah di dalam pelabuhan</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_9]" value="sesuai" {{ ($hp['kan_d_9'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_9]" value="tidak" {{ ($hp['kan_d_9'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">10) ketersediaan tempat penampungan sampah sementara (TPS)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_10]" value="sesuai" {{ ($hp['kan_d_10'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_10]" value="tidak" {{ ($hp['kan_d_10'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">11) ketersediaan alat pengangkut sampah</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_11]" value="sesuai" {{ ($hp['kan_d_11'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[kan_d_11]" value="tidak" {{ ($hp['kan_d_11'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                    </tbody>
                </table>
                
                <table class="table table-bordered table-sm mt-3">
                    <thead class="table-light">
                        <tr>
                            <th style="width:80%">3. Kesesuaian Pelaksanaan Kegiatan Pencegahan Pencemaran (Kegiatan dan Usaha Lain)</th>
                            <th class="text-center">Sesuai</th>
                            <th class="text-center">Tidak Sesuai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="3">Hasil Pemeriksaan:</td></tr>
                        <tr>
                            <td class="ps-4">1) dokumen rencana pencegahan pencemaran (Form E.1)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[lain_1]" value="sesuai" {{ ($hp['lain_1'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[lain_1]" value="tidak" {{ ($hp['lain_1'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">2) ketersediaan fasilitas pencegahan pencemaran (Form E.2)</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[lain_2]" value="sesuai" {{ ($hp['lain_2'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[lain_2]" value="tidak" {{ ($hp['lain_2'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">3) sistem pengolahan dan pembuangan limbah</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[lain_3]" value="sesuai" {{ ($hp['lain_3'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[lain_3]" value="tidak" {{ ($hp['lain_3'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="ps-4">4) standar pengelolaan bahan-bahan berpotensi pencemaran</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[lain_4]" value="sesuai" {{ ($hp['lain_4'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[lain_4]" value="tidak" {{ ($hp['lain_4'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                        <tr class="table-secondary">
                            <td class="fw-bold text-end">4. Hasil Akhir Kesesuaian Pelaksanaan Kegiatan Pencegahan Pencemaran</td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[akhir_sesuai]" value="sesuai" {{ ($hp['akhir_sesuai'] ?? '') == 'sesuai' ? 'checked' : '' }}></td>
                            <td class="text-center"><input type="radio" name="hasil_pengawasan[akhir_sesuai]" value="tidak" {{ ($hp['akhir_sesuai'] ?? '') == 'tidak' ? 'checked' : '' }}></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

<!-- TAB 5: Dugaan & Sampel -->
        <div class="tab-pane fade" id="tab-dugaan" role="tabpanel" aria-labelledby="tab-dugaan-tab">
            <div class="row g-3">
                <div class="col-12">
                    <h6 class="section-title">Dugaan Pencemaran</h6>
                </div>
                <div class="col-md-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="dugaan_pencemaran_ada" value="1" {{ old('dugaan_pencemaran_ada', $baPencemaran->dugaan_pencemaran_ada ?? 0) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">Ada</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="dugaan_pencemaran_ada" value="0" {{ old('dugaan_pencemaran_ada', $baPencemaran->dugaan_pencemaran_ada ?? 0) == 0 ? 'checked' : '' }}>
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi Kondisi Pencemaran (Bau, Kekeruhan, dll)</label>
                    <textarea class="form-control" name="dugaan_pencemaran_ket" rows="3">{{ old('dugaan_pencemaran_ket', $baPencemaran->dugaan_pencemaran_ket ?? '') }}</textarea>
                </div>
                
                <div class="col-md-4"><label class="form-label">Perkiraan luas area tercemar (Ha)</label><input type="text" class="form-control" name="luas_area_tercemar" value="{{ old('luas_area_tercemar', $baPencemaran->luas_area_tercemar ?? '') }}"></div>
                <div class="col-md-4"><label class="form-label">Luas Ekosistem Mangrove (Ha)</label><input type="text" class="form-control" name="luas_mangrove" value="{{ old('luas_mangrove', $baPencemaran->luas_mangrove ?? '') }}"></div>
                <div class="col-md-4"><label class="form-label">Luas Padang Lamun (Ha)</label><input type="text" class="form-control" name="luas_lamun" value="{{ old('luas_lamun', $baPencemaran->luas_lamun ?? '') }}"></div>
                <div class="col-md-6"><label class="form-label">Luas Terumbu Karang (Ha)</label><input type="text" class="form-control" name="luas_terumbu_karang" value="{{ old('luas_terumbu_karang', $baPencemaran->luas_terumbu_karang ?? '') }}"></div>
                <div class="col-md-6"><label class="form-label">Luas Habitat Populasi Ikan (Ha)</label><input type="text" class="form-control" name="luas_habitat_ikan" value="{{ old('luas_habitat_ikan', $baPencemaran->luas_habitat_ikan ?? '') }}"></div>
                
                <div class="col-12 mt-4">
                    <h6 class="section-title">Indikasi Ketidakpatuhan & Tindakan</h6>
                    @php 
                        $ind = old('indikasi_ketidakpatuhan', isset($baPencemaran) ? $baPencemaran->indikasi_ketidakpatuhan : []); 
                        if(!is_array($ind)) $ind = [];
                    @endphp
                    <div class="form-check ms-2"><input class="form-check-input" type="checkbox" name="indikasi_ketidakpatuhan[a]" value="1" {{ isset($ind['a']) ? 'checked' : '' }}> <label class="form-check-label">a. menghentikan kegiatan yang tidak sesuai perundang-undangan</label></div>
                    <div class="form-check ms-2"><input class="form-check-input" type="checkbox" name="indikasi_ketidakpatuhan[b]" value="1" {{ isset($ind['b']) ? 'checked' : '' }}> <label class="form-check-label">b. memaksa pelaku usaha melakukan pencegahan kegiatan</label></div>
                    <div class="form-check ms-2"><input class="form-check-input" type="checkbox" name="indikasi_ketidakpatuhan[c]" value="1" {{ isset($ind['c']) ? 'checked' : '' }}> <label class="form-check-label">c. penyegelan</label></div>
                    <div class="form-check ms-2"><input class="form-check-input" type="checkbox" name="indikasi_ketidakpatuhan[d]" value="1" {{ isset($ind['d']) ? 'checked' : '' }}> <label class="form-check-label">d. pemasangan garis Pengawas Perikanan</label></div>
                </div>

                <div class="col-12 mt-4">
                    <h6 class="section-title">Pengambilan Sampel</h6>
                </div>
                <div class="col-md-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sampel_ada" value="1" {{ old('sampel_ada', $baPencemaran->sampel_ada ?? 0) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">Ada</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sampel_ada" value="0" {{ old('sampel_ada', $baPencemaran->sampel_ada ?? 0) == 0 ? 'checked' : '' }}>
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                </div>
                <div class="col-md-6"><label class="form-label">Tanggal Pengambilan</label><input type="date" class="form-control" name="sampel_tgl" value="{{ old('sampel_tgl', optional($baPencemaran ?? null)->sampel_tgl?->format('Y-m-d')) }}"></div>
                <div class="col-md-6"><label class="form-label">Jumlah Titik</label><input type="number" class="form-control" name="sampel_jumlah_titik" value="{{ old('sampel_jumlah_titik', $baPencemaran->sampel_jumlah_titik ?? '') }}"></div>
                <div class="col-md-12"><label class="form-label">Koordinat Sampel</label><textarea class="form-control" name="sampel_koordinat" rows="2">{{ old('sampel_koordinat', $baPencemaran->sampel_koordinat ?? '') }}</textarea></div>
                <div class="col-md-4"><label class="form-label">Nama Laboratorium</label><input type="text" class="form-control" name="sampel_nama_lab" value="{{ old('sampel_nama_lab', $baPencemaran->sampel_nama_lab ?? '') }}"></div>
                <div class="col-md-4"><label class="form-label">Tanggal Hasil Uji</label><input type="date" class="form-control" name="sampel_lab_tgl" value="{{ old('sampel_lab_tgl', optional($baPencemaran ?? null)->sampel_lab_tgl?->format('Y-m-d')) }}"></div>
                <div class="col-md-4">
                    <label class="form-label">Hasil Uji</label>
                    <select class="form-select" name="sampel_hasil_uji">
                        <option value="">-</option>
                        <option value="melampaui" {{ old('sampel_hasil_uji', $baPencemaran->sampel_hasil_uji ?? '') == 'melampaui' ? 'selected' : '' }}>Melampaui baku mutu</option>
                        <option value="di_bawah" {{ old('sampel_hasil_uji', $baPencemaran->sampel_hasil_uji ?? '') == 'di_bawah' ? 'selected' : '' }}>Di bawah baku mutu</option>
                    </select>
                </div>
                
                <div class="col-12 mt-4">
                    <h6 class="section-title">Kronologis</h6>
                    <textarea class="form-control" name="kronologis" rows="4" placeholder="Kronologis Apabila Terjadi Pencemaran...">{{ old('kronologis', $baPencemaran->kronologis ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- TAB 6: Kesimpulan & TTD -->
        <div class="tab-pane fade" id="tab-kesimpulan" role="tabpanel" aria-labelledby="tab-kesimpulan-tab">
            <h6 class="section-title mb-3">7. Kesimpulan Akhir</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold">a. Pemenuhan Dokumen Pencegahan</label>
                    <div>
                        <label class="me-3"><input type="radio" name="kesimpulan_dokumen" value="sesuai" {{ old('kesimpulan_dokumen', $baPencemaran->kesimpulan_dokumen ?? '') == 'sesuai' ? 'checked' : '' }}> Sesuai</label>
                        <label><input type="radio" name="kesimpulan_dokumen" value="tidak_sesuai" {{ old('kesimpulan_dokumen', $baPencemaran->kesimpulan_dokumen ?? '') == 'tidak_sesuai' ? 'checked' : '' }}> Tidak Sesuai</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">b. Indikasi Pencemaran</label>
                    <div>
                        <label class="me-3"><input type="radio" name="kesimpulan_indikasi_pencemaran" value="1" {{ old('kesimpulan_indikasi_pencemaran', $baPencemaran->kesimpulan_indikasi_pencemaran ?? 0) == 1 ? 'checked' : '' }}> Ada</label>
                        <label><input type="radio" name="kesimpulan_indikasi_pencemaran" value="0" {{ old('kesimpulan_indikasi_pencemaran', $baPencemaran->kesimpulan_indikasi_pencemaran ?? 0) == 0 ? 'checked' : '' }}> Tidak Ada</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">c. Indikasi Pelanggaran</label>
                    <div>
                        <label class="me-3"><input type="radio" name="kesimpulan_indikasi_pelanggaran" value="1" {{ old('kesimpulan_indikasi_pelanggaran', $baPencemaran->kesimpulan_indikasi_pelanggaran ?? 0) == 1 ? 'checked' : '' }}> Ada</label>
                        <label><input type="radio" name="kesimpulan_indikasi_pelanggaran" value="0" {{ old('kesimpulan_indikasi_pelanggaran', $baPencemaran->kesimpulan_indikasi_pelanggaran ?? 0) == 0 ? 'checked' : '' }}> Tidak Ada</label>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label fw-bold">d. Keterangan Singkat</label>
                    <textarea class="form-control" name="kesimpulan_keterangan" rows="3">{{ old('kesimpulan_keterangan', $baPencemaran->kesimpulan_keterangan ?? '') }}</textarea>
                </div>
            </div>
            
            <hr>
            <h6 class="section-title mb-3 mt-4">Tanda Tangan</h6>
            <div class="row g-4">
                <div class="col-md-6 text-center">
                    <label class="fw-bold d-block mb-3">Pelaku Usaha <br><span class="text-primary sig-name-pj">{{ old('nama_pj', $baPencemaran->nama_pj ?? '') ?: 'belum diisi' }}</span></label>
                    @include('ba-was-prl.partials.ttd-widget', ['id' => 'ttd_pelaku_usaha', 'name' => 'ttd_pelaku_usaha', 'existing' => isset($baPencemaran) ? $baPencemaran->ttd_pelaku_usaha : null, 'value' => old('ttd_pelaku_usaha', $baPencemaran->ttd_pelaku_usaha ?? '')])
                </div>
                <div class="col-md-6 text-center">
                    <label class="fw-bold d-block mb-3">Pengawas Perikanan</label>
                    @include('ba-was-prl.partials.ttd-widget', ['id' => 'ttd_pengawas_1', 'name' => 'ttd_pengawas_1', 'existing' => isset($baPencemaran) ? $baPencemaran->ttd_pengawas_1 : null, 'value' => old('ttd_pengawas_1', $baPencemaran->ttd_pengawas_1 ?? '')])
                </div>
                <div class="col-md-6 text-center">
                    <label class="fw-bold d-block mb-3">Saksi 1</label>
                    @include('ba-was-prl.partials.ttd-widget', ['id' => 'ttd_saksi_1', 'name' => 'ttd_saksi_1', 'existing' => isset($baPencemaran) ? $baPencemaran->ttd_saksi_1 : null, 'value' => old('ttd_saksi_1', $baPencemaran->ttd_saksi_1 ?? '')])
                </div>
                <div class="col-md-6 text-center">
                    <label class="fw-bold d-block mb-3">Saksi 2</label>
                    @include('ba-was-prl.partials.ttd-widget', ['id' => 'ttd_saksi_2', 'name' => 'ttd_saksi_2', 'existing' => isset($baPencemaran) ? $baPencemaran->ttd_saksi_2 : null, 'value' => old('ttd_saksi_2', $baPencemaran->ttd_saksi_2 ?? '')])
                </div>
            </div>

            <div class="row mt-4 pt-3 border-top">
                <div class="col-md-6">
                    <label class="form-label">Foto Dokumentasi (Bisa lebih dari 1)</label>
                    <input class="form-control" type="file" name="foto[]" multiple accept="image/*">
                    @if(isset($baPencemaran) && $baPencemaran->fotos->count() > 0)
                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            @foreach($baPencemaran->fotos as $f)
                                <img src="{{ asset('storage/'.$f->path_foto) }}" class="img-thumbnail" style="height: 80px;">
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status BA <span class="text-danger">*</span></label>
                    <select name="status" class="form-select form-select-lg" required>
                        <option value="draft" {{ old('status', $baPencemaran->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="proses" {{ old('status', $baPencemaran->status ?? '') == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ old('status', $baPencemaran->status ?? '') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="tindak_lanjut" {{ old('status', $baPencemaran->status ?? '') == 'tindak_lanjut' ? 'selected' : '' }}>Tindak Lanjut</option>
                    </select>
                </div>
            </div>
        </div>

        
<!-- TAB 7: Lampiran E -->
        <div class="tab-pane fade" id="tab-lampiran" role="tabpanel" aria-labelledby="tab-lampiran-tab">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="section-title mb-0 border-0 pb-0">Lampiran Form E</h6>
            </div>
            @php 
                $e1 = old('lampiran_e1', isset($baPencemaran) ? $baPencemaran->lampiran_e1 : []); if(!is_array($e1)) $e1 = [];
                $e2 = old('lampiran_e2', isset($baPencemaran) ? $baPencemaran->lampiran_e2 : []); if(!is_array($e2)) $e2 = [];
                $e3 = old('lampiran_e3', isset($baPencemaran) ? $baPencemaran->lampiran_e3 : []); if(!is_array($e3)) $e3 = [];
                $e4 = old('lampiran_e4', isset($baPencemaran) ? $baPencemaran->lampiran_e4 : []); if(!is_array($e4)) $e4 = [];
                $e5 = old('lampiran_e5', isset($baPencemaran) ? $baPencemaran->lampiran_e5 : []); if(!is_array($e5)) $e5 = [];
                $e6 = old('lampiran_e6', isset($baPencemaran) ? $baPencemaran->lampiran_e6 : []); if(!is_array($e6)) $e6 = [];
            @endphp
            
            <div class="accordion" id="accordionLampiran">
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#colE1">Form E.1: Kesesuaian Dokumen Rencana Pencegahan</button></h2>
                    <div id="colE1" class="accordion-collapse collapse show" data-bs-parent="#accordionLampiran"><div class="accordion-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light text-center"><tr><th>No</th><th>Yang Diperiksa</th><th>Ya</th><th>Tidak</th></tr></thead>
                            <tbody>
                                <tr><td class="text-center">1</td><td>Nama Pelaku Usaha</td><td class="text-center"><input type="radio" name="lampiran_e1[1]" value="ya" {{ ($e1[1] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e1[1]" value="tidak" {{ ($e1[1] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">2</td><td>Nama Usaha/Kegiatan</td><td class="text-center"><input type="radio" name="lampiran_e1[2]" value="ya" {{ ($e1[2] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e1[2]" value="tidak" {{ ($e1[2] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">3</td><td>Jenis Usaha/Kegiatan</td><td class="text-center"><input type="radio" name="lampiran_e1[3]" value="ya" {{ ($e1[3] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e1[3]" value="tidak" {{ ($e1[3] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">4</td><td>Lokasi Pelaksanaan Usaha/Kegiatan</td><td class="text-center"><input type="radio" name="lampiran_e1[4]" value="ya" {{ ($e1[4] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e1[4]" value="tidak" {{ ($e1[4] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">5</td><td>Keabsahan Dokumen</td><td class="text-center"><input type="radio" name="lampiran_e1[5]" value="ya" {{ ($e1[5] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e1[5]" value="tidak" {{ ($e1[5] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">6</td><td>Pelaksanaan Ketentuan Dokumen</td><td class="text-center"><input type="radio" name="lampiran_e1[6]" value="ya" {{ ($e1[6] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e1[6]" value="tidak" {{ ($e1[6] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr class="table-secondary"><td colspan="2" class="text-end fw-bold">Kesimpulan Akhir</td><td class="text-center"><input type="radio" name="lampiran_e1[kesimpulan]" value="ya" {{ ($e1['kesimpulan'] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e1[kesimpulan]" value="tidak" {{ ($e1['kesimpulan'] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                            </tbody>
                        </table>
                    </div></div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colE2">Form E.2: Ketersediaan Fasilitas Pencegahan</button></h2>
                    <div id="colE2" class="accordion-collapse collapse" data-bs-parent="#accordionLampiran"><div class="accordion-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light text-center"><tr><th>No</th><th>Yang Diperiksa</th><th>Ada</th><th>Tidak</th></tr></thead>
                            <tbody>
                                <tr><td class="text-center">1</td><td>Jaringan air limbah</td><td class="text-center"><input type="radio" name="lampiran_e2[1]" value="ada" {{ ($e2[1] ?? '') == 'ada' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e2[1]" value="tidak" {{ ($e2[1] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">2</td><td>Sarana pengolahan limbah cair</td><td class="text-center"><input type="radio" name="lampiran_e2[2]" value="ada" {{ ($e2[2] ?? '') == 'ada' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e2[2]" value="tidak" {{ ($e2[2] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">3</td><td>Tempat sampah</td><td class="text-center"><input type="radio" name="lampiran_e2[3]" value="ada" {{ ($e2[3] ?? '') == 'ada' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e2[3]" value="tidak" {{ ($e2[3] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">4</td><td>Tempat penampungan sampah sementara</td><td class="text-center"><input type="radio" name="lampiran_e2[4]" value="ada" {{ ($e2[4] ?? '') == 'ada' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e2[4]" value="tidak" {{ ($e2[4] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">5</td><td>Toilet</td><td class="text-center"><input type="radio" name="lampiran_e2[5]" value="ada" {{ ($e2[5] ?? '') == 'ada' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e2[5]" value="tidak" {{ ($e2[5] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr class="table-secondary"><td colspan="2" class="text-end fw-bold">Kesimpulan Akhir</td><td class="text-center"><input type="radio" name="lampiran_e2[kesimpulan]" value="ada" {{ ($e2['kesimpulan'] ?? '') == 'ada' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e2[kesimpulan]" value="tidak" {{ ($e2['kesimpulan'] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                            </tbody>
                        </table>
                    </div></div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colE3">Form E.3: Sistem Pengolahan Limbah</button></h2>
                    <div id="colE3" class="accordion-collapse collapse" data-bs-parent="#accordionLampiran"><div class="accordion-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light text-center"><tr><th>No</th><th>Yang Diperiksa</th><th>Ya</th><th>Tidak</th></tr></thead>
                            <tbody>
                                <tr><td class="text-center">1</td><td>Persetujuan Lingkungan/Izin Lingkungan yang dimiliki</td><td class="text-center"><input type="radio" name="lampiran_e3[1]" value="ya" {{ ($e3[1] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e3[1]" value="tidak" {{ ($e3[1] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">2</td><td>Izin pembuangan limbah cair ke laut yang dimiliki</td><td class="text-center"><input type="radio" name="lampiran_e3[2]" value="ya" {{ ($e3[2] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e3[2]" value="tidak" {{ ($e3[2] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">3</td><td>Operasional sistem pengolahan dan pembuangan limbah</td><td class="text-center"><input type="radio" name="lampiran_e3[3]" value="ya" {{ ($e3[3] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e3[3]" value="tidak" {{ ($e3[3] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">4</td><td>Dimensi dan kapasitas sistem pengolahan limbah</td><td class="text-center"><input type="radio" name="lampiran_e3[4]" value="ya" {{ ($e3[4] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e3[4]" value="tidak" {{ ($e3[4] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">5</td><td>Pelaporan berkala hasil sistem pengolahan limbah</td><td class="text-center"><input type="radio" name="lampiran_e3[5]" value="ya" {{ ($e3[5] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e3[5]" value="tidak" {{ ($e3[5] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr class="table-secondary"><td colspan="2" class="text-end fw-bold">Kesimpulan Akhir</td><td class="text-center"><input type="radio" name="lampiran_e3[kesimpulan]" value="ya" {{ ($e3['kesimpulan'] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e3[kesimpulan]" value="tidak" {{ ($e3['kesimpulan'] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                            </tbody>
                        </table>
                    </div></div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colE4">Form E.4: Pengelolaan Bahan Pencemar</button></h2>
                    <div id="colE4" class="accordion-collapse collapse" data-bs-parent="#accordionLampiran"><div class="accordion-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light text-center"><tr><th>No</th><th>Yang Diperiksa</th><th>Ya</th><th>Tidak</th></tr></thead>
                            <tbody>
                                <tr><td class="text-center">1</td><td>Prosedur pengelolaan bahan pencemar</td><td class="text-center"><input type="radio" name="lampiran_e4[1]" value="ya" {{ ($e4[1] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e4[1]" value="tidak" {{ ($e4[1] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">2</td><td>Pemilahan bahan pencemar</td><td class="text-center"><input type="radio" name="lampiran_e4[2]" value="ya" {{ ($e4[2] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e4[2]" value="tidak" {{ ($e4[2] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">3</td><td>Penanganan daur ulang bahan pencemar</td><td class="text-center"><input type="radio" name="lampiran_e4[3]" value="ya" {{ ($e4[3] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e4[3]" value="tidak" {{ ($e4[3] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr class="table-secondary"><td colspan="2" class="text-end fw-bold">Kesimpulan Akhir</td><td class="text-center"><input type="radio" name="lampiran_e4[kesimpulan]" value="ya" {{ ($e4['kesimpulan'] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e4[kesimpulan]" value="tidak" {{ ($e4['kesimpulan'] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                            </tbody>
                        </table>
                    </div></div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colE5">Form E.5: Kesesuaian Dokumen PKKPRL</button></h2>
                    <div id="colE5" class="accordion-collapse collapse" data-bs-parent="#accordionLampiran"><div class="accordion-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light text-center"><tr><th>No</th><th>Yang Diperiksa</th><th>Ya</th><th>Tidak</th></tr></thead>
                            <tbody>
                                <tr><td class="text-center">1</td><td>Nama Pelaku Usaha/Kegiatan</td><td class="text-center"><input type="radio" name="lampiran_e5[1]" value="ya" {{ ($e5[1] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e5[1]" value="tidak" {{ ($e5[1] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">2</td><td>Jenis Kegiatan</td><td class="text-center"><input type="radio" name="lampiran_e5[2]" value="ya" {{ ($e5[2] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e5[2]" value="tidak" {{ ($e5[2] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">3</td><td>Lokasi Usaha/Kegiatan</td><td class="text-center"><input type="radio" name="lampiran_e5[3]" value="ya" {{ ($e5[3] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e5[3]" value="tidak" {{ ($e5[3] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">4</td><td>Luas Area Pemanfaatan</td><td class="text-center"><input type="radio" name="lampiran_e5[4]" value="ya" {{ ($e5[4] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e5[4]" value="tidak" {{ ($e5[4] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">5</td><td>Kesesuaian Peruntukan/Zonasi</td><td class="text-center"><input type="radio" name="lampiran_e5[5]" value="ya" {{ ($e5[5] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e5[5]" value="tidak" {{ ($e5[5] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">6</td><td>Keabsahan Dokumen</td><td class="text-center"><input type="radio" name="lampiran_e5[6]" value="ya" {{ ($e5[6] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e5[6]" value="tidak" {{ ($e5[6] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">7</td><td>Penyampaian Kewajiban Pelaporan</td><td class="text-center"><input type="radio" name="lampiran_e5[7]" value="ya" {{ ($e5[7] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e5[7]" value="tidak" {{ ($e5[7] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">8</td><td>Pemenuhan Hak dan Kewajiban</td><td class="text-center"><input type="radio" name="lampiran_e5[8]" value="ya" {{ ($e5[8] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e5[8]" value="tidak" {{ ($e5[8] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr class="table-secondary"><td colspan="2" class="text-end fw-bold">Kesimpulan Akhir</td><td class="text-center"><input type="radio" name="lampiran_e5[kesimpulan]" value="ya" {{ ($e5['kesimpulan'] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e5[kesimpulan]" value="tidak" {{ ($e5['kesimpulan'] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                            </tbody>
                        </table>
                    </div></div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colE6">Form E.6: Penanganan Limbah Kapal</button></h2>
                    <div id="colE6" class="accordion-collapse collapse" data-bs-parent="#accordionLampiran"><div class="accordion-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light text-center"><tr><th>No</th><th>Yang Diperiksa</th><th>Ya</th><th>Tidak</th></tr></thead>
                            <tbody>
                                <tr><td class="text-center">1</td><td>Tempat penampungan sampah padat sementara</td><td class="text-center"><input type="radio" name="lampiran_e6[1]" value="ya" {{ ($e6[1] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e6[1]" value="tidak" {{ ($e6[1] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">2</td><td>Tempat penampungan oli bekas sementara</td><td class="text-center"><input type="radio" name="lampiran_e6[2]" value="ya" {{ ($e6[2] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e6[2]" value="tidak" {{ ($e6[2] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">3</td><td>Catatan logistik perbekalan kapal</td><td class="text-center"><input type="radio" name="lampiran_e6[3]" value="ya" {{ ($e6[3] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e6[3]" value="tidak" {{ ($e6[3] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">4</td><td>Catatan logistik oli dan jadwal pergantian oli</td><td class="text-center"><input type="radio" name="lampiran_e6[4]" value="ya" {{ ($e6[4] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e6[4]" value="tidak" {{ ($e6[4] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr><td class="text-center">5</td><td>Penanggung jawab penanganan limbah kapal</td><td class="text-center"><input type="radio" name="lampiran_e6[5]" value="ya" {{ ($e6[5] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e6[5]" value="tidak" {{ ($e6[5] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                                <tr class="table-secondary"><td colspan="2" class="text-end fw-bold">Kesimpulan Akhir</td><td class="text-center"><input type="radio" name="lampiran_e6[kesimpulan]" value="ya" {{ ($e6['kesimpulan'] ?? '') == 'ya' ? 'checked' : '' }}></td><td class="text-center"><input type="radio" name="lampiran_e6[kesimpulan]" value="tidak" {{ ($e6['kesimpulan'] ?? '') == 'tidak' ? 'checked' : '' }}></td></tr>
                            </tbody>
                        </table>
                    </div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-footer bg-light border-top shadow-sm position-sticky bottom-0 z-1 d-flex justify-content-between align-items-center p-3">
    <a href="{{ route('ba-pencemaran.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Batal / Kembali</a>
    <div>
        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" style="font-size: 1.1rem;"><i class="fas fa-save me-2"></i> Simpan BA Pencemaran</button>
    </div>
</div>

@push('styles')
<style>
    .custom-tabs .nav-link {
        color: #495057; border: none; border-bottom: 3px solid transparent; font-weight: 600; padding: 1rem 1.25rem; font-size: 0.95rem;
    }
    .custom-tabs .nav-link:hover { border-color: #e9ecef; color: #228be6; }
    .custom-tabs .nav-link.active { color: #228be6; border-bottom: 3px solid #228be6; background: transparent; }
    .section-title { font-weight: 700; color: #2c3e50; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 0.5px; border-bottom: 2px dashed #dee2e6; padding-bottom: 0.5rem; }
    .repeater-row { background: #f8f9fa; border: 1px solid #e9ecef; padding: 15px; border-radius: 8px; }
    .ttd-canvas { width: 100%; max-width: 420px; height: 140px; background: #fff; border: 1px dashed #adb5bd; border-radius: 6px; touch-action: none; cursor: crosshair; display: block; margin: 0 auto; }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#pelaku_usaha_id').select2({ theme: 'bootstrap-5', tags: true, width: '100%' });
        
        $('#pelaku_usaha_id').on('change', function() {
            let option = $(this).find(':selected');
            if(option.val() && !option.val().startsWith('new_')) {
                let nib = option.data('nib');
                let pj = option.data('pj');
                if(nib) $('#nib').val(nib);
                if(pj) $('#nama_pj').val(pj).trigger('input');
            }
        });
        
        $(document).on('input', '.pj-nama-input', function () { $('.sig-name-pj').text($(this).val().trim() || 'belum diisi'); });

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

        // Repeater Pengawas
        let pengawasIdx = {{ count($pengawas) }};
        $('#btn-add-pengawas').click(function() {
            let tmpl = `
                <div class="repeater-row pengawas-row mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="pengawas[${pengawasIdx}][nama]">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">NIP</label>
                            <input type="text" class="form-control" name="pengawas[${pengawasIdx}][nip]">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" class="form-control" name="pengawas[${pengawasIdx}][jabatan]">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Unit Kerja</label>
                            <input type="text" class="form-control" name="pengawas[${pengawasIdx}][unit_kerja]">
                        </div>
                        <div class="col-md-1 text-center">
                            <button type="button" class="btn btn-outline-danger btn-remove-pengawas w-100"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            `;
            $('#pengawas-container').append(tmpl);
            pengawasIdx++;
            updateRemoveBtn();
        });

        $(document).on('click', '.btn-remove-pengawas', function() {
            $(this).closest('.pengawas-row').remove();
            updateRemoveBtn();
        });

        function updateRemoveBtn() {
            let count = $('.pengawas-row').length;
            $('.btn-remove-pengawas').prop('disabled', count <= 1);
        }
    });
</script>
@endpush

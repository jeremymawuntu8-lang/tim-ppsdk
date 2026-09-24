@php
    $data = $data ?? null;
@endphp

<div class="row">
    <!-- Kolom Kiri: Data Dasar & Izin -->
    <div class="col-md-6">
        <div class="card card-outline card-info">
            <div class="card-header"><h3 class="card-title">1. Data Dasar & Izin</h3></div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label>ID Penerbitan</label>
                    <input type="text" name="id_penerbitan" class="form-control" value="{{ old('id_penerbitan', $data->id_penerbitan ?? '') }}">
                </div>
                
                <div class="form-group mb-3">
                    <label>Subjek Hukum / Perusahaan</label>
                    <input type="text" name="pelaku_usaha_nama" class="form-control" value="{{ old('pelaku_usaha_nama', $data->pelakuUsaha->nama_perusahaan ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label>Contact Person (Nama dan No HP / Email)</label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $data->contact_person ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $data->alamat ?? '') }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label>Jenis Permohonan</label>
                    <input type="text" name="jenis_permohonan" class="form-control" value="{{ old('jenis_permohonan', $data->jenis_permohonan ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label>Berusaha / Non Berusaha</label>
                    <select name="status_berusaha" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="BERUSAHA" {{ old('status_berusaha', $data->status_berusaha ?? '') == 'BERUSAHA' ? 'selected' : '' }}>BERUSAHA</option>
                        <option value="NON BERUSAHA" {{ old('status_berusaha', $data->status_berusaha ?? '') == 'NON BERUSAHA' ? 'selected' : '' }}>NON BERUSAHA</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Provinsi</label>
                    <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $data->provinsi ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label>Nama Perairan</label>
                    <input type="text" name="nama_perairan" class="form-control" value="{{ old('nama_perairan', $data->nama_perairan ?? '') }}">
                </div>
                
                <div class="form-group mb-3">
                    <label>UPT</label>
                    <input type="text" name="upt" class="form-control" value="{{ old('upt', $data->upt ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label>Detil Kegiatan</label>
                    <textarea name="detil_kegiatan" class="form-control" rows="3">{{ old('detil_kegiatan', $data->detil_kegiatan ?? '') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label>Luas (Ha)</label>
                        <input type="number" step="0.01" name="luas" class="form-control" value="{{ old('luas', $data->luas ?? '') }}">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label>Panjang (Km)</label>
                        <input type="number" step="0.01" name="panjang" class="form-control" value="{{ old('panjang', $data->panjang ?? '') }}">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>Nomor KKPRL</label>
                    <input type="text" name="nomor_kkprl" class="form-control" value="{{ old('nomor_kkprl', $data->nomor_kkprl ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label>Tanggal Penerbitan</label>
                    <input type="date" name="tanggal_penerbitan" class="form-control" value="{{ old('tanggal_penerbitan', isset($data->tanggal_penerbitan) ? $data->tanggal_penerbitan->format('Y-m-d') : '') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Pelaporan & Sanksi -->
    <div class="col-md-6">
        
        <!-- Pelaporan -->
        <div class="card card-outline card-warning">
            <div class="card-header"><h3 class="card-title">2. Laporan Pelaksanaan KKPRL</h3></div>
            <div class="card-body">
                @for($i=1; $i<=5; $i++)
                <div class="row mb-2 border-bottom pb-2">
                    <div class="col-md-6 form-group">
                        <label>Laporan {{ $i }} (Tgl)</label>
                        @php $fld_tgl = "laporan_{$i}_tgl"; @endphp
                        <input type="date" name="{{ $fld_tgl }}" class="form-control" value="{{ old($fld_tgl, isset($data->$fld_tgl) && $data->$fld_tgl ? $data->$fld_tgl->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Status L{{ $i }}</label>
                        @php $fld_status = "laporan_{$i}_status"; @endphp
                        <select name="{{ $fld_status }}" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="Tepat Waktu" {{ old($fld_status, $data->$fld_status ?? '') == 'Tepat Waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                            <option value="Terlambat" {{ old($fld_status, $data->$fld_status ?? '') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="Tidak Menyampaikan Laporan" {{ old($fld_status, $data->$fld_status ?? '') == 'Tidak Menyampaikan Laporan' ? 'selected' : '' }}>Tidak Menyampaikan Laporan</option>
                        </select>
                    </div>
                </div>
                @endfor
                
                <div class="form-group mt-3">
                    <label class="text-danger fw-bold">Status Akhir Sanksi</label>
                    <select name="sanksi" class="form-select border-danger">
                        <option value="">-- Pilih --</option>
                        <option value="SP1" {{ old('sanksi', $data->sanksi ?? '') == 'SP1' ? 'selected' : '' }}>SP1</option>
                        <option value="SP2" {{ old('sanksi', $data->sanksi ?? '') == 'SP2' ? 'selected' : '' }}>SP2</option>
                        <option value="SP3" {{ old('sanksi', $data->sanksi ?? '') == 'SP3' ? 'selected' : '' }}>SP3</option>
                        <option value="PENCABUTAN" {{ old('sanksi', $data->sanksi ?? '') == 'PENCABUTAN' ? 'selected' : '' }}>PENCABUTAN</option>
                    </select>
                    <small class="text-muted">Isi SP1, SP2, SP3, atau biarkan kosong jika belum ada sanksi.</small>
                </div>
            </div>
        </div>

        <!-- Accordion untuk SP -->
        <div class="accordion" id="accordionSanksi">
            
            <!-- SP1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSp1">
                    <button class="accordion-button collapsed bg-light text-danger" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSp1" aria-expanded="false" aria-controls="collapseSp1">
                        <b>3. Data Surat Peringatan 1 (SP 1)</b>
                    </button>
                </h2>
                <div id="collapseSp1" class="accordion-collapse collapse" aria-labelledby="headingSp1" data-bs-parent="#accordionSanksi">
                    <div class="accordion-body">
                        <div class="form-group mb-3">
                            <label>Keterangan SP 1</label>
                            <textarea name="sp1_keterangan" class="form-control" rows="2">{{ old('sp1_keterangan', $data->sp1_keterangan ?? '') }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label>Nomor Surat & Tanggal SP 1</label>
                            <input type="text" name="sp1_nomor_surat_tgl" class="form-control" value="{{ old('sp1_nomor_surat_tgl', $data->sp1_nomor_surat_tgl ?? '') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label>Status Terkirim</label>
                            <input type="text" name="sp1_status_terkirim" class="form-control" value="{{ old('sp1_status_terkirim', $data->sp1_status_terkirim ?? '') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label>Upload Data SP (Link)</label>
                            <input type="url" name="sp1_link_upload" class="form-control" value="{{ old('sp1_link_upload', $data->sp1_link_upload ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SP2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSp2">
                    <button class="accordion-button collapsed bg-light text-danger" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSp2" aria-expanded="false" aria-controls="collapseSp2">
                        <b>4. Data Surat Peringatan 2 (SP 2)</b>
                    </button>
                </h2>
                <div id="collapseSp2" class="accordion-collapse collapse" aria-labelledby="headingSp2" data-bs-parent="#accordionSanksi">
                    <div class="accordion-body">
                        <div class="form-group mb-3">
                            <label>Keterangan SP 2</label>
                            <textarea name="sp2_keterangan" class="form-control" rows="2">{{ old('sp2_keterangan', $data->sp2_keterangan ?? '') }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label>Nomor Surat & Tanggal SP 2</label>
                            <input type="text" name="sp2_nomor_surat_tgl" class="form-control" value="{{ old('sp2_nomor_surat_tgl', $data->sp2_nomor_surat_tgl ?? '') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label>Status Terkirim</label>
                            <input type="text" name="sp2_status_terkirim" class="form-control" value="{{ old('sp2_status_terkirim', $data->sp2_status_terkirim ?? '') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label>Upload Data SP (Link)</label>
                            <input type="url" name="sp2_link_upload" class="form-control" value="{{ old('sp2_link_upload', $data->sp2_link_upload ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SP3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSp3">
                    <button class="accordion-button collapsed bg-light text-danger" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSp3" aria-expanded="false" aria-controls="collapseSp3">
                        <b>5. Data Surat Peringatan 3 (SP 3)</b>
                    </button>
                </h2>
                <div id="collapseSp3" class="accordion-collapse collapse" aria-labelledby="headingSp3" data-bs-parent="#accordionSanksi">
                    <div class="accordion-body">
                        <div class="form-group mb-3">
                            <label>Keterangan SP 3</label>
                            <textarea name="sp3_keterangan" class="form-control" rows="2">{{ old('sp3_keterangan', $data->sp3_keterangan ?? '') }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label>Nomor Surat & Tanggal SP 3</label>
                            <input type="text" name="sp3_nomor_surat_tgl" class="form-control" value="{{ old('sp3_nomor_surat_tgl', $data->sp3_nomor_surat_tgl ?? '') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label>Status Terkirim</label>
                            <input type="text" name="sp3_status_terkirim" class="form-control" value="{{ old('sp3_status_terkirim', $data->sp3_status_terkirim ?? '') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label>Upload Data SP (Link)</label>
                            <input type="url" name="sp3_link_upload" class="form-control" value="{{ old('sp3_link_upload', $data->sp3_link_upload ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End Accordion -->

        <!-- Link Dokumen & Lainnya -->
        <div class="card card-outline card-success mt-3">
            <div class="card-header"><h3 class="card-title">6. Dokumen Tambahan</h3></div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label>Dokumen Laporan Tahunan (Link Drive)</label>
                    <input type="url" name="link_dokumen_laporan_tahunan" class="form-control" value="{{ old('link_dokumen_laporan_tahunan', $data->link_dokumen_laporan_tahunan ?? '') }}">
                </div>
                <div class="form-group mb-3">
                    <label>Dokumen KKPRL (Link Drive)</label>
                    <input type="url" name="link_dokumen_kkprl" class="form-control" value="{{ old('link_dokumen_kkprl', $data->link_dokumen_kkprl ?? '') }}">
                </div>
                <div class="form-group mb-3">
                    <label>Pemutakhiran</label>
                    <input type="text" name="pemutakhiran" class="form-control" value="{{ old('pemutakhiran', $data->pemutakhiran ?? '') }}">
                </div>
            </div>
        </div>

    </div>
</div>

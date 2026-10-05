@props([
    'arsipable',
    'tipeBa' => '', // 'ba-was-prl', 'ba-was-alse', 'ba-reklamasi', 'ba-ppk', 'ba-pencemaran'
])

@php
    $arsipList = $arsipable->arsipDokumen()->with('uploader')->latest()->get();
    $linkArchives = $arsipList->where('tipe', 'link');
    $fileArchives = $arsipList->where('tipe', 'file');
    $isAdmin = auth()->check() && auth()->user()->hasAnyRole(['super-admin', 'admin']);
@endphp

<div class="card card-primary card-outline mb-4 shadow-sm" id="section-arsip-dokumen">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center">
            <div class="bg-primary-soft text-primary rounded-circle p-2 me-2 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fas fa-archive"></i>
            </div>
            <div>
                <h3 class="card-title fw-bold mb-0">{{ $tipeBa === 'surat-peringatan' ? 'Arsip Surat Peringatan Lama & Google Drive' : 'Arsip Dokumen Lama & Google Drive' }}</h3>
                <small class="text-muted d-block">Dokumen dan tautan arsip sebelum sistem ini dirilis</small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary rounded-pill px-3 py-2">
                <i class="fas fa-paperclip me-1"></i> {{ $arsipList->count() }} Arsip
            </span>

            @if($isAdmin)
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle fw-semibold" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-plus me-1"></i> Tambah Arsip
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li>
                            <button type="button" class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#modalUploadFileArsip">
                                <i class="fas fa-file-upload text-primary me-2"></i> Unggah File Dokumen
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#modalTambahLinkGdrive">
                                <i class="fab fa-google-drive text-success me-2"></i> Tambah Link Google Drive
                            </button>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="card-body">
        {{-- Section 1: Google Drive Links --}}
        @if($linkArchives->isNotEmpty())
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center">
                    <i class="fab fa-google-drive text-success me-2 fa-lg"></i>
                    Tautan Google Drive Arsip
                </h6>
                <div class="row g-3">
                    @foreach($linkArchives as $item)
                        <div class="col-12" id="arsip-item-{{ $item->id }}">
                            <div class="border rounded-3 p-3 bg-light d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 transition-hover">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="bg-white text-success rounded-3 p-3 shadow-xs border d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fab fa-google-drive fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">{{ $item->judul }}</h6>
                                        @if($item->keterangan)
                                            <p class="text-muted small mb-1">{{ $item->keterangan }}</p>
                                        @endif
                                        <div class="text-muted" style="font-size: 0.78rem;">
                                            <i class="far fa-user me-1"></i> {{ $item->uploader->name ?? 'Admin' }}
                                            <span class="mx-1">•</span>
                                            <i class="far fa-clock me-1"></i> {{ $item->created_at->format('d M Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-shrink-0 align-self-end align-self-md-center">
                                    <a href="{{ $item->link_gdrive }}" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-sm px-3 fw-semibold shadow-xs">
                                        <i class="fab fa-google-drive me-1"></i> Buka Google Drive
                                        <i class="fas fa-external-link-alt ms-1 small"></i>
                                    </a>
                                    @if($isAdmin)
                                        <button type="button" class="btn btn-outline-danger btn-sm" title="Hapus Tautan" onclick="hapusArsipDokumen('{{ route('arsip-dokumen-ba.destroy', $item->id) }}', '{{ $item->id }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Section 2: Uploaded Files --}}
        <div>
            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center">
                <i class="fas fa-folder-open text-primary me-2"></i>
                Berkas Dokumen Arsip
            </h6>

            @if($fileArchives->isEmpty() && $linkArchives->isEmpty())
                <div class="text-center py-5 border rounded-3 bg-light">
                    <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center p-3 shadow-xs mb-3 text-muted" style="width: 60px; height: 60px;">
                        <i class="fas fa-folder-open fa-2x"></i>
                    </div>
                    <h6 class="text-secondary fw-bold mb-1">Belum Ada Arsip Dokumen Lama</h6>
                    <p class="text-muted small mb-3">Dokumen lama (sebelum sistem dirilis) atau tautan Google Drive belum ditambahkan ke {{ $tipeBa === 'surat-peringatan' ? 'Surat Peringatan' : 'Berita Acara' }} ini.</p>
                    @if($isAdmin)
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalUploadFileArsip">
                                <i class="fas fa-file-upload me-1"></i> Unggah File Arsip
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambahLinkGdrive">
                                <i class="fab fa-google-drive me-1"></i> Tautkan Google Drive
                            </button>
                        </div>
                    @endif
                </div>
            @elseif($fileArchives->isEmpty())
                <p class="text-muted small fst-italic mb-0">Belum ada file dokumen fisik yang diunggah. Semua arsip diarahkan melalui tautan Google Drive di atas.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 border">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 45px;" class="text-center">#</th>
                                <th>Nama Dokumen</th>
                                <th style="width: 110px;">Format / Ukuran</th>
                                <th style="width: 170px;">Pengunggah</th>
                                <th style="width: 140px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fileArchives as $index => $file)
                                <tr id="arsip-item-{{ $file->id }}">
                                    <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="{{ $file->icon_class }} fa-2x mt-1 flex-shrink-0"></i>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $file->judul }}</div>
                                                <div class="text-muted small text-break">{{ $file->nama_file }}</div>
                                                @if($file->keterangan)
                                                    <div class="text-secondary small mt-1"><i class="fas fa-info-circle me-1 text-info"></i>{{ $file->keterangan }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 text-uppercase fw-semibold">{{ $file->ekstensi ?: 'FILE' }}</span>
                                        @if($file->ukuran_formatted)
                                            <div class="text-muted small mt-1">{{ $file->ukuran_formatted }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark">{{ $file->uploader->name ?? 'Admin' }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $file->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            @if(in_array($file->ekstensi, ['pdf', 'jpg', 'jpeg', 'png']))
                                                <a href="{{ route('arsip-dokumen-ba.download', ['arsipDokumenBa' => $file->id, 'preview' => 1]) }}" target="_blank" class="btn btn-outline-primary" title="Buka / Lihat Dokumen">
                                                    <i class="fas fa-eye me-1"></i> Buka
                                                </a>
                                            @endif
                                            <a href="{{ route('arsip-dokumen-ba.download', $file->id) }}" class="btn btn-primary" title="Unduh Berkas">
                                                <i class="fas fa-download me-1"></i> Unduh
                                            </a>
                                            @if($isAdmin)
                                                <button type="button" class="btn btn-outline-danger" title="Hapus Dokumen" onclick="hapusArsipDokumen('{{ route('arsip-dokumen-ba.destroy', $file->id) }}', '{{ $file->id }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@if($isAdmin)
@push('modals')
{{-- MODAL UPLOAD FILE ARSIP --}}
<div class="modal fade" id="modalUploadFileArsip" tabindex="-1" aria-labelledby="modalUploadFileArsipLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('arsip-dokumen-ba.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="arsipable_type" value="{{ $tipeBa }}">
                <input type="hidden" name="arsipable_id" value="{{ $arsipable->id }}">
                <input type="hidden" name="tipe" value="file">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalUploadFileArsipLabel">
                        <i class="fas fa-file-upload me-2"></i> Unggah Dokumen Arsip Lama
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 small d-flex align-items-center mb-3">
                        <i class="fas fa-info-circle me-2 fa-lg"></i>
                        <div>Unggah berkas dokumen lama (PDF scan BA, laporan uji, berita acara lama, lampiran surat, dsb).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Berkas Dokumen <span class="text-danger">*</span></label>
                        <input type="file" name="files_arsip[]" class="form-control" multiple required accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar">
                        <div class="form-text text-muted">Bisa memilih beberapa berkas sekaligus (PDF, Word, Excel, JPG, PNG, ZIP). Maksimal 100MB per file.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul / Kategori Dokumen <small class="text-muted fw-normal">(Opsional)</small></label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Scan BA Lama 2022 / Dokumen Uji Teknis">
                        <div class="form-text text-muted">Jika dikosongkan, nama berkas asli akan otomatis digunakan.</div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold">Catatan / Keterangan <small class="text-muted fw-normal">(Opsional)</small></label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan mengenai berkas arsip ini..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-semibold">
                        <i class="fas fa-cloud-upload-alt me-1"></i> Mulai Unggah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH LINK GOOGLE DRIVE --}}
<div class="modal fade" id="modalTambahLinkGdrive" tabindex="-1" aria-labelledby="modalTambahLinkGdriveLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('arsip-dokumen-ba.store') }}" method="POST">
                @csrf
                <input type="hidden" name="arsipable_type" value="{{ $tipeBa }}">
                <input type="hidden" name="arsipable_id" value="{{ $arsipable->id }}">
                <input type="hidden" name="tipe" value="link">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="modalTambahLinkGdriveLabel">
                        <i class="fab fa-google-drive me-2"></i> Tautkan Link Google Drive
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="alert alert-success py-2 small d-flex align-items-center mb-3">
                        <i class="fab fa-google-drive me-2 fa-lg"></i>
                        <div>Petugas dan pengawas dapat langsung mengklik tautan ini untuk membuka arsip di Google Drive.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">URL Link Google Drive <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fab fa-google-drive text-success"></i></span>
                            <input type="text" name="link_gdrive" class="form-control" placeholder="https://drive.google.com/drive/folders/..." required>
                        </div>
                        <div class="form-text text-muted">Pastikan akses share link Google Drive telah disetel agar dapat dibuka oleh petugas.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Tautan <small class="text-muted fw-normal">(Opsional)</small></label>
                        <input type="text" name="judul" class="form-control" placeholder="Folder Arsip Lengkap Google Drive (2020-2023)">
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold">Keterangan Folder <small class="text-muted fw-normal">(Opsional)</small></label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Berisi rekapan BA lapangan, hasil analisa, dan foto pra-rilis"></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-semibold">
                        <i class="fas fa-link me-1"></i> Simpan Tautan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
function hapusArsipDokumen(url, id) {
    if (typeof confirmDelete === 'function') {
        confirmDelete(url, function() {
            $('#arsip-item-' + id).fadeOut(300, function() { $(this).remove(); });
        });
    } else {
        if (confirm('Apakah Anda yakin ingin menghapus arsip ini?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    $('#arsip-item-' + id).fadeOut(300, function() { $(this).remove(); });
                },
                error: function() {
                    alert('Gagal menghapus arsip.');
                }
            });
        }
    }
}
</script>
@endpush
@endif

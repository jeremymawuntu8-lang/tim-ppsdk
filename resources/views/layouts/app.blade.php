<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0A3D6B">
    <title>@yield('title', 'Dashboard') | TIM IPSDK</title>

    <link rel="icon" href="{{ asset('images/favicon.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- CSS Libraries --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    @include('layouts.partials.navbar')
    @include('layouts.partials.sidebar')

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">@yield('page-title', 'Dashboard')</h3>
                        <nav class="d-none d-md-block mt-1">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content fade-in">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="alertSuccess">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if ($errors->any() && !request()->routeIs('login'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Terdapat kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    @include('layouts.partials.footer')
</div>

@stack('modals')

{{-- JS Libraries --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
    // CSRF Setup
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // Select2 Init
    $('.select2').select2({ theme: 'bootstrap-5', width: '100%', placeholder: function(){ return $(this).data('placeholder') || '-- Pilih --'; } });

    // Close sidebar on nav-link click (mobile)
    $(document).on('click', '.nav-sidebar .nav-link:not([href="#"])', function() {
        if (window.innerWidth < 992) {
            $('body').removeClass('sidebar-open');
        }
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('#alertSuccess').alert('close');
    }, 5000);

    // Global delete confirmation
    function confirmDelete(url, callback) {
        Swal.fire({
            title: 'Hapus data ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#C62828',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, hapus',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function (res) {
                        Swal.fire({ title: 'Berhasil!', text: res.message ?? 'Data berhasil dihapus.', icon: 'success', timer: 2000, showConfirmButton: false });
                        if (callback) callback();
                    },
                    error: function () {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
                    }
                });
            }
        });
    }

    // Global DataTable defaults
    $.extend(true, $.fn.dataTable.defaults, {
        responsive: true,
        language: {
            processing: '<div class="d-flex align-items-center gap-2"><div class="loading-spinner"></div> Memuat data...</div>',
            emptyTable: '<div class="empty-state py-3"><i class="fas fa-inbox empty-state-icon"></i><div class="empty-state-title">Belum ada data</div><div class="empty-state-text">Data akan muncul di sini setelah ditambahkan.</div></div>',
            zeroRecords: '<div class="empty-state py-3"><i class="fas fa-search empty-state-icon"></i><div class="empty-state-title">Tidak ditemukan</div><div class="empty-state-text">Data yang Anda cari tidak ditemukan.</div></div>',
            info: 'Menampilkan _START_-_END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_ total)',
            lengthMenu: 'Tampilkan _MENU_ data',
            search: '',
            searchPlaceholder: 'Cari...',
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        },
        dom: "<'row mb-3'<'col-sm-6 col-12 mb-2 mb-sm-0'l><'col-sm-6 col-12'f>>" +
             "<'table-responsive'tr>" +
             "<'row mt-3'<'col-sm-5 col-12 mb-2 mb-sm-0 text-sm'i><'col-sm-7 col-12 d-flex justify-content-center justify-content-sm-end'p>>"
    });

    // Submit button loading state
    $(document).on('submit', 'form:not(.no-loading)', function() {
        const btn = $(this).find('button[type="submit"]');
        if (!btn.hasClass('btn-loading')) {
            btn.addClass('btn-loading').prop('disabled', true);
        }
    });
</script>
@stack('scripts')
</body>
</html>

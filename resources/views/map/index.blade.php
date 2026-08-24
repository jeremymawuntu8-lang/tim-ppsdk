@extends('layouts.app')
@section('title', 'Map')
@section('page-title', 'Peta Persebaran Pelaku Usaha')
@section('breadcrumb')<li class="breadcrumb-item active">Map</li>@endsection

@push('styles')
<style>
    .map-wrapper {
        position: relative;
        height: calc(100vh - 200px);
        min-height: 600px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .map-filter-panel {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1000;
        width: 320px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border: 1px solid rgba(255,255,255,0.8);
        transition: all 0.3s ease;
    }

    .filter-toggle-btn {
        display: none;
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1001;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* Custom Leaflet Popups */
    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        border: 0;
    }
    .leaflet-popup-content {
        margin: 18px;
        font-family: 'Inter', sans-serif;
    }
    .leaflet-popup-tip {
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .popup-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ppsdk-primary);
        margin-bottom: 8px;
        line-height: 1.3;
    }
    .popup-meta {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 12px;
    }
    .popup-img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    @media (max-width: 768px) {
        .map-filter-panel {
            left: -350px; /* Hide by default on mobile */
            top: 70px;
        }
        .map-filter-panel.show {
            left: 20px;
        }
        .filter-toggle-btn {
            display: block;
        }
        .map-wrapper { height: calc(100vh - 150px); }
    }
</style>
@endpush

@section('content')
<div class="fade-in">
    <div class="map-wrapper">
        <button class="btn btn-primary filter-toggle-btn rounded-circle" style="width: 45px; height: 45px;" onclick="document.getElementById('filterPanel').classList.toggle('show')">
            <i class="fas fa-filter"></i>
        </button>

        <div id="filterPanel" class="map-filter-panel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-filter me-2"></i>Filter Peta</h5>
                <button class="btn-close d-md-none" onclick="document.getElementById('filterPanel').classList.remove('show')"></button>
            </div>
            
            <div class="mb-3">
                <label class="form-label text-sm fw-semibold">Pencarian</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="fSearch" class="form-control border-start-0 ps-0" placeholder="Cari nama perusahaan...">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label text-sm fw-semibold">Provinsi</label>
                <select id="fProvinsi" class="form-select select2">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinsis as $p)<option value="{{ $p->id }}">{{ $p->nama }}</option>@endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label text-sm fw-semibold">Jenis Usaha</label>
                <select id="fJenis" class="form-select select2">
                    <option value="">Semua Jenis Usaha</option>
                    @foreach($jenisUsahas as $j)<option value="{{ $j->id }}">{{ $j->nama }}</option>@endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label text-sm fw-semibold">Status Operasional</label>
                <select id="fStatus" class="form-select select2">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="tidak_aktif">Tidak Aktif</option>
                    <option value="dalam_proses">Dalam Proses</option>
                    <option value="bermasalah">Bermasalah</option>
                </select>
            </div>
            
            <div class="d-grid mt-4">
                <button class="btn btn-outline-secondary btn-sm" onclick="resetFilters()"><i class="fas fa-undo me-1"></i> Reset Filter</button>
            </div>
        </div>

        <div id="mapUtama" style="height: 100%; width: 100%; z-index: 1;"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const mapUtama = L.map('mapUtama', {
        zoomControl: false // Move zoom control
    }).setView([-1.4748, 124.8421], 7);
    
    L.control.zoom({ position: 'bottomright' }).addTo(mapUtama);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { 
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/attributions">CARTO</a>' 
    }).addTo(mapUtama);

    let clusterGroup = L.markerClusterGroup({
        chunkedLoading: true,
        maxClusterRadius: 50,
        iconCreateFunction: function(cluster) {
            return L.divIcon({ 
                html: '<div><span>' + cluster.getChildCount() + '</span></div>', 
                className: 'marker-cluster marker-cluster-primary', 
                iconSize: L.point(40, 40) 
            });
        }
    });
    mapUtama.addLayer(clusterGroup);

    function muatData() {
        clusterGroup.clearLayers();
        
        // Show loading state (could add a spinner here)
        
        $.get("{{ route('map.data') }}", {
            provinsi_id: $('#fProvinsi').val(), 
            jenis_usaha_id: $('#fJenis').val(),
            status: $('#fStatus').val(), 
            search: $('#fSearch').val(),
        }, function (data) {
            let markers = [];
            data.forEach(p => {
                let badgeColor = p.status === 'aktif' ? 'success' : (p.status === 'bermasalah' ? 'danger' : 'warning');
                let statusText = p.status ? p.status.replace('_', ' ').toUpperCase() : 'UNKNOWN';
                
                const popup = `
                    <div class="popup-title">${p.nama_perusahaan}</div>
                    <div class="popup-meta">
                        <span class="badge bg-${badgeColor}-soft text-${badgeColor} mb-2">${statusText}</span><br>
                        <i class="fas fa-tag me-1 text-muted"></i> ${p.jenis_usaha}<br>
                        <i class="fas fa-file-alt me-1 text-muted"></i> PKKPRL: ${p.nomor_pkkprl ?? 'Belum ada'}
                    </div>
                    ${p.foto_lokasi ? `<img src="${p.foto_lokasi}" class="popup-img">` : ''}
                    <div class="text-sm mb-3">
                        <i class="fas fa-map-marker-alt me-1 text-danger"></i> ${p.alamat ?? '-'}
                    </div>
                    <div class="d-grid">
                        <a href="${p.detail_url}" class="btn btn-primary text-white" style="border-radius:8px;">Lihat Detail Pelaku Usaha</a>
                    </div>
                `;
                
                // Custom Icon
                const customIcon = L.divIcon({
                    className: 'custom-map-marker',
                    html: `<div style="background:var(--ppsdk-${badgeColor}); width:24px; height:24px; border-radius:50%; border:3px solid white; box-shadow:0 3px 10px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12],
                    popupAnchor: [0, -12]
                });

                let marker = L.marker([p.latitude, p.longitude], {icon: customIcon}).bindPopup(popup, {minWidth: 280});
                markers.push(marker);
            });
            
            clusterGroup.addLayers(markers);
            
            if(markers.length > 0) {
                mapUtama.fitBounds(clusterGroup.getBounds(), {padding: [50, 50], maxZoom: 14});
            }
        });
    }

    muatData();
    $('#fProvinsi, #fJenis, #fStatus').on('change', muatData);
    let t; 
    $('#fSearch').on('keyup', () => { 
        clearTimeout(t); 
        t = setTimeout(muatData, 500); 
    });

    function resetFilters() {
        $('#fSearch').val('');
        $('#fProvinsi, #fJenis, #fStatus').val('').trigger('change');
    }

    // Fix map rendering on load
    setTimeout(() => { mapUtama.invalidateSize(); }, 300);
</script>

<style>
/* Marker Cluster Custom Style */
.marker-cluster-primary {
    background-color: rgba(10, 61, 107, 0.6);
}
.marker-cluster-primary div {
    background-color: rgba(10, 61, 107, 0.9);
    color: white;
    font-weight: bold;
    font-family: 'Inter', sans-serif;
}
</style>
@endpush

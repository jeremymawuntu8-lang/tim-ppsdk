{{-- Guard global: tolak file > batas upload (config/upload.php) sebelum form dikirim --}}
<script>
(function () {
    const MAX_DOC_KB = {{ (int) config('upload.max_file_size_kb', 51200) }};
    const MAX_DOC_BYTES = MAX_DOC_KB * 1024;
    const MAX_DOC_LABEL = Math.round(MAX_DOC_KB / 1024) + ' MB';

    const MAX_PHOTO_KB = {{ (int) config('upload.max_photo_size_kb', 10240) }};
    const MAX_PHOTO_BYTES = MAX_PHOTO_KB * 1024;
    const MAX_PHOTO_LABEL = Math.round(MAX_PHOTO_KB / 1024) + ' MB';

    window.UPLOAD_MAX_BYTES = MAX_DOC_BYTES;
    window.UPLOAD_MAX_PHOTO_BYTES = MAX_PHOTO_BYTES;

    function notify(message) {
        if (window.Swal && typeof window.Swal.fire === 'function') {
            window.Swal.fire({ icon: 'warning', title: 'File terlalu besar', text: message });
        } else {
            alert(message);
        }
    }

    function isPhotoInput(input) {
        const accept = (input.getAttribute('accept') || '').toLowerCase();
        const name = (input.getAttribute('name') || '').toLowerCase();
        const id = (input.getAttribute('id') || '').toLowerCase();

        // Jika hanya menerima image (tidak menerima pdf/doc)
        if (accept.includes('image') && !accept.includes('.pdf') && !accept.includes('pdf')) {
            return true;
        }
        // Jika nama field foto atau gambar
        if (/(foto|gambar)/i.test(name) || /(foto|gambar)/i.test(id)) {
            return true;
        }
        return false;
    }

    // Capture phase: dijalankan sebelum handler 'change' lain di halaman.
    document.addEventListener('change', function (e) {
        const input = e.target;
        if (!input || input.tagName !== 'INPUT' || input.type !== 'file' || !input.files) return;

        const isPhoto = isPhotoInput(input);
        const limitBytes = isPhoto ? MAX_PHOTO_BYTES : MAX_DOC_BYTES;
        const limitLabel = isPhoto ? MAX_PHOTO_LABEL : MAX_DOC_LABEL;
        const fileTypeLabel = isPhoto ? 'foto / gambar' : 'dokumen';

        const tooBig = Array.from(input.files).filter(function (f) { return f.size > limitBytes; });
        if (tooBig.length === 0) return;

        const names = tooBig.map(function (f) {
            return f.name + ' (' + (f.size / 1048576).toFixed(1) + ' MB)';
        }).join(', ');

        input.value = '';
        e.stopImmediatePropagation();
        notify('Ukuran maksimal untuk ' + fileTypeLabel + ' adalah ' + limitLabel + '. File berikut melebihi batas: ' + names);
    }, true);
})();
</script>

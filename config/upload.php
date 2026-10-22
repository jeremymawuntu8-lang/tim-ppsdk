<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Batas Maksimal Ukuran File Upload
    |--------------------------------------------------------------------------
    |
    | - max_file_size_kb: Berlaku untuk dokumen/berkas (PDF, Word, Excel, ZIP, dll)
    |   Default: 51200 KB = 50 MB.
    | - max_photo_size_kb: Berlaku untuk foto/gambar (JPG, JPEG, PNG, Citra, Spasial, dll)
    |   Default: 10240 KB = 10 MB.
    |
    */

    'max_file_size_kb' => (int) env('UPLOAD_MAX_FILE_SIZE_KB', 51200),

    'max_photo_size_kb' => (int) env('UPLOAD_MAX_PHOTO_SIZE_KB', 10240),

];

<?php

/*
|--------------------------------------------------------------------------
| Override Pesan Validasi Upload
|--------------------------------------------------------------------------
|
| Hanya key di bawah ini yang di-override; sisanya tetap memakai pesan
| default bawaan Laravel (di-merge otomatis).
|
*/

return [

    'max' => [
        'file' => 'Ukuran file :attribute tidak boleh lebih dari :max.',
    ],

    'uploaded' => 'File :attribute gagal diunggah karena melebihi batas ukuran maksimal.',

];

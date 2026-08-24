<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Halaman Beranda (Landing Page)
     */
    public function home()
    {
        return view('public.home');
    }

    /**
     * Halaman Tentang (Placeholder)
     */
    public function tentang()
    {
        return view('public.tentang');
    }
}

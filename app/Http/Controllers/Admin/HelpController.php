<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    /**
     * Tampilkan halaman pusat bantuan
     */
    public function index()
    {
        return view('admin.help.index');
    }
}

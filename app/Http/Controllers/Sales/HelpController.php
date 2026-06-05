<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    /**
     * Tampilkan halaman pusat bantuan khusus sales
     */
    public function index()
    {
        return view('sales.help');
    }
}

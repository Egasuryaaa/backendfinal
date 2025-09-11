<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('checkout.index');
    }
}

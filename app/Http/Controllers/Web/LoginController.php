<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('theme.xtremez.login');
    }
}

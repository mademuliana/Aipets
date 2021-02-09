<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaController extends Controller
{
    public function superadmin()
    {
        return view('layouts/superadmin');
    }
    public function admin()
    {
        return view('layouts/admin');
    }
    public function front()
    {
        return view('layouts/front');
    }
}

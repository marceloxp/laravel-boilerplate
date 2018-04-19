<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class SobreController extends Controller
{
    public function index()
    {
        return view('site/pages/sobre');
    }

    public function empresa()
    {
        return view('site/pages/sobre_empresa');
    }

    public function tradicao()
    {
        return view('site/pages/sobre_tradicao');
    }
}
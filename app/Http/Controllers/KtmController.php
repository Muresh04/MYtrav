<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KtmController extends Controller
{
    public function show()
    {
        return view('ktm_live_schedule');
    }
}

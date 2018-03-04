<?php

namespace App\Http\Controllers;

use Route;
use Illuminate\Http\Request;

class ReactController extends Controller
{
    public function show(Request $request) {
        $path = array_first(Route::current()->parameters);
        if ($request->is('about')) { 
            dd($path);
        }
        return view('react');
    }
}

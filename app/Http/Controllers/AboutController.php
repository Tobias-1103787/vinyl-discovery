<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    //
    public function index ()
    {
        $greeting = "Welkom gast";

        return view('test', compact('greeting'));
    }
}



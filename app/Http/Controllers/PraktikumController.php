<?php

namespace App\Http\Controllers;

use App\Models\Praktikum;
use Illuminate\Http\Request;

class PraktikumController extends Controller
{
    public function getPraktikums()
    {
        $praktikums = Praktikum::get();
        return view('pages.praktikum.index', compact('praktikums'));
    }
}

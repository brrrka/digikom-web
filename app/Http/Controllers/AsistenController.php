<?php

namespace App\Http\Controllers;

use App\Models\Asisten;
use App\Models\User;
use Illuminate\Http\Request;

class AsistenController extends Controller
{
    public function getAsistens()
    {
        $asistens = Asisten::whereHas('user', function ($query) {
            $query->where('id_roles', 2);
        })->with('user')->get();

        return response()->json($asistens);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    function kategori(){
        $response = [];
        $data = Kategori::all();
        $response['sukses'] = 1;
        $response['data'] = $data;
        return json_encode($response);
    }
}

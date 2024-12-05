<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Buku;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    function tambahBuku(Request $request)
    {
        $response = [];
        $rules = [
            "nama" => "required",
            "kategori" => "required", //isi sesuai id kategori 
            "tanggal" => "required|after_or_equal:now|date", //yyyy-mm-dd
            "foto" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "stok" => "required|numeric",
        ];

        $attributes = [
            "nama" => "Nama Buku",
            "kategori" => "Kategori",
            "tanggal" => "Tanggal Pinjam",
            "foto" => "Foto Buku",
            "stok" => "Stok Buku",
        ];

        $message = [
            "required" => ":attribute harus diisi",
            "after_or_equal" => ":attribute harus setelah tanggal sekarang atau sekarang",
            "image" => ":attribute harus gambar",
            "mimes" => ":attribute harus berupa :value",
            "max" => ":attribute maksimal :max kb",
            "date" => ":attribute harus tanggal",
        ];

        $val = Validator::make($request->all(), $rules, $message, $attributes);
        if ($val->fails()) {
            $response['sukses'] = 0;
            $response['message'] = $val->errors();
        } else {
            $foto = $request->file('foto');
            $nama_file = $foto->getClientOriginalName();
            $foto->move('img/buku', $nama_file);
            Buku::create([
                'id_kategori' => $request->kategori,
                'nama_buku' => $request->nama,
                'tanggal_pinjam' => Carbon::parse($request->tanggal),
                'foto_buku' => $nama_file,
                'stok_buku' => $request->stok
            ]);
            $response['sukses'] = 1;
            $response['message'] = "Buku berhasil ditambahkan";
        }
        return json_encode($response);
    }

    function listBuku()
    {
        $response = [];
        $data = DB::table('buku as b')->join('kategori as k', 'b.id_kategori', '=', 'k.id_kategori')->get();
        $response['sukses'] = 1;
        $response['data'] = $data;
        return json_encode($response);
    }

    function detailBuku($id)
    {
        $response = [];
        $data = DB::table('buku as b')->join('kategori as k', 'b.id_kategori', '=', 'k.id_kategori')->where('b.id_buku', '=', $id)->first();
        $response['sukses'] = 1;
        $response['data'] = $data;
        return json_encode($response);
    }

    function hapusBuku($id)
    {
        $response = [];
        $data = Buku::find($id);
        $data->delete();
        $response['sukses'] = 1;
        $response['message'] = "Buku berhasil dihapus";
        return json_encode($response);
    }

    function ubahBuku(Request $request, $id)
    {
        $response = [];

        $rules = [
            "nama" => "required",
            "kategori" => "required", //isi sesuai id kategori 
            "tanggal" => "required|date", //yyyy-mm-dd
            "foto" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "stok" => "required|numeric",
        ];

        $attributes = [
            "nama" => "Nama Buku",
            "kategori" => "Kategori",
            "tanggal" => "Tanggal Pinjam",
            "foto" => "Foto Buku",
            "stok" => "Stok Buku",
        ];

        $message = [
            "required" => ":attribute harus diisi",
            "image" => ":attribute harus gambar",
            "mimes" => ":attribute harus berupa :value",
            "max" => ":attribute maksimal :max kb",
            "date" => ":attribute harus tanggal",
        ];

        $val = Validator::make($request->all(), $rules, $message, $attributes);
        if ($val->fails()) {
            $response['sukses'] = 0;
            $response['message'] = $val->errors();
        } else {
            $buku = Buku::find($id);
            $buku->id_kategori = $request->kategori;
            $buku->nama_buku = $request->nama;
            $buku->tanggal_pinjam = Carbon::parse($request->tanggal);
            $buku->stok_buku = $request->stok;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $nama_file = $foto->getClientOriginalName();
                $foto->move('img/buku', $nama_file);
                $buku->foto_buku = $nama_file;
            }
            $buku->save();
            $response['sukses'] = 1;
            $response['message'] = "Buku berhasil diubah";
        }
        return json_encode($response);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function halo()
    {
        $response = [];
        $response['sukses'] = 1;
        $response['message'] = "Halo Dunia dari laravel";
        return json_encode($response);
    }

    function register(Request $request)
    {
        $response = [];
        $rules = [
            "nama" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required",
            "ulangi" => "required|same:password",
            "telp" => "required|digits_between:10,12"
        ];
        $attributes = [
            "nama" => "Nama Anda",
            "email" => "Email Anda",
            "password" => "Pasword Anda",
            "ulangi" => "Ulangi Password",
            "telp" => "Telp anda"
        ];
        $message = [
            "unique" => ":attribute sudah terdaftar",
            "required" => ":attribute harus diisi",
            "email" => ":attribute harus email",
            "password" => ":attribute harus password",
            "same" => ":attribute harus sama",
            "digits_between" => ":attribute harus antara 10 sampai 12 digit"
        ];

        $val = Validator::make($request->all(), $rules, $message, $attributes);
        if ($val->fails()) {
            $response['sukses'] = 0;
            $response['message'] = $val->errors();
        } else {
            User::create([
                "name" => $request->input('nama'),
                "email" => $request->input('email'),
                "telp" => $request->input('telp'),
                "password" => bcrypt($request->input('password')),
            ]);
            $response['sukses'] = 1;
            $response['message'] = "Registrasi berhasil, terima kasih";
        }
        return json_encode($response);
    }

    function login(Request $request)
    {
        $response = [];
        $data = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);
        if ($data) {
            $user = Auth::user();
            $response['sukses'] = 1;
            $response['message'] = "Selamat datang, ". $user->name;
            $response['data'] = [
                'access_token' => "Bearer " . $user->createToken('bebas')->plainTextToken,
                'user' => $user
            ];
        } else {
            $response['sukses'] = 0;
            $response['message'] = "Email atau password salah";
        }
        return json_encode($response);
    }
}

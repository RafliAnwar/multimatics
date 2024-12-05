<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    function addGame(Request $request)
    {
        $response = [];
        $rules = [
            "title" => "required",
            "id_publisher" => "required|exists:publishers,id_publisher",
            "id_genre" => "required|exists:genres,id_genre",
            "desc" => "required",
            "review" => "required",
            "rating" => "required|numeric|min:1|max:10",
            "photo" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "video_url" => "required|url",
            "release_date" => "required|date",
        ];

        $attributes = [
            "title" => "Judul Game",
            "id_publisher" => "Publisher",
            "id_genre" => "Genre",
            "desc" => "Deskripsi",
            "review" => "Ulasan",
            "rating" => "Rating",
            "photo" => "Foto",
            "video_url" => "URL Video",
            "release_date" => "Tanggal Rilis",
        ];

        $message = [
            "required" => ":attribute harus diisi",
            "exists" => ":attribute tidak valid",
            "numeric" => ":attribute harus berupa angka",
            "min" => ":attribute minimal :min",
            "max" => ":attribute maksimal :max",
            "image" => ":attribute harus berupa gambar",
            "mimes" => ":attribute harus berformat :values",
            "url" => ":attribute harus berupa URL yang valid",
            "date" => ":attribute harus berupa tanggal",
            "before_or_equal" => ":attribute harus sebelum atau sama dengan hari ini",
        ];

        $val = Validator::make($request->all(), $rules, $message, $attributes);
        if ($val->fails()) {
            $response['sukses'] = 0;
            $response['message'] = $val->errors();
        } else {
            $photo = $request->file('photo');
            $photo_name = $photo->getClientOriginalName();
            $photo->move('img/games', $photo_name);

            Game::create([
                'title' => $request->title,
                'id_publisher' => $request->id_publisher,
                'id_genre' => $request->id_genre,
                'desc' => $request->desc,
                'review' => $request->review,
                'rating' => $request->rating,
                'photo' => $photo_name,
                'video_url' => $request->video_url,
                'release_date' => Carbon::parse($request->release_date),
            ]);

            $response['sukses'] = 1;
            $response['message'] = "Game berhasil ditambahkan";
        }
        return json_encode($response);
    }

    function listGame()
    {
        $response = [];
        $data = DB::table('games as g')
            ->join('publishers as p', 'g.id_publisher', '=', 'p.id_publisher')
            ->join('genres as ge', 'g.id_genre', '=', 'ge.id_genre')
            ->select('g.*', 'p.publisher_name', 'ge.genre_name')
            ->get();
        $response['sukses'] = 1;
        $response['data'] = $data;
        return json_encode($response);
    }

    function detailGame($id)
    {
        $response = [];
        $data = DB::table('games as g')
            ->join('publishers as p', 'g.id_publisher', '=', 'p.id_publisher')
            ->join('genres as ge', 'g.id_genre', '=', 'ge.id_genre')
            ->select('g.*', 'p.publisher_name', 'ge.genre_name')
            ->where('g.id_game', '=', $id)
            ->first();
        if ($data) {
            $response['sukses'] = 1;
            $response['data'] = $data;
        } else {
            $response['sukses'] = 0;
            $response['message'] = "Game tidak ditemukan";
        }
        return json_encode($response);
    }

    function deleteGame($id)
    {
        $response = [];
        $game = Game::find($id);
        if ($game) {
            $game->delete();
            $response['sukses'] = 1;
            $response['message'] = "Game berhasil dihapus";
        } else {
            $response['sukses'] = 0;
            $response['message'] = "Game tidak ditemukan";
        }
        return json_encode($response);
    }

    function updateGame(Request $request, $id)
    {
        $response = [];
        $rules = [
            "title" => "required",
            "id_publisher" => "required|exists:publishers,id_publisher",
            "id_genre" => "required|exists:genres,id_genre",
            "desc" => "required",
            "review" => "required",
            "rating" => "required|numeric|min:1|max:10",
            "photo" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "release_date" => "required|date",
        ];

        $val = Validator::make($request->all(), $rules);
        if ($val->fails()) {
            $response['sukses'] = 0;
            $response['message'] = $val->errors();
        } else {
            $game = Game::find($id);
            if ($game) {
                $game->title = $request->title;
                $game->id_publisher = $request->id_publisher;
                $game->id_genre = $request->id_genre;
                $game->desc = $request->desc;
                $game->review = $request->review;
                $game->rating = $request->rating;
                $game->video_url = $request->video_url;
                $game->release_date = Carbon::parse($request->release_date);

                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $photo_name = $photo->getClientOriginalName();
                    $photo->move('img/games', $photo_name);
                    $game->photo = $photo_name;
                }

                $game->save();
                $response['sukses'] = 1;
                $response['message'] = "Game berhasil diubah";
            } else {
                $response['sukses'] = 0;
                $response['message'] = "Game tidak ditemukan";
            }
        }
        return json_encode($response);
    }

    //search, sort by, update
}

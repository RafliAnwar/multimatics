<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $table = 'games';
    protected $primaryKey = 'id_game';
    protected $fillable = [
        'id_game',
        'id_publisher',
        'id_genre',
        'title',
        'desc',
        'review',
        'rating',
        'photo',
        'video_url',
        'release_date'
    ];
}

<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = ["Novel", "Manga", "Buku Fiksi", "Buku Non Fiksi"];
        foreach ($kategori as $value) {
            Kategori::create([
                'nama_kategori' => $value
            ]);
        }
    }
}

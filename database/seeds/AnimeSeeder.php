<?php

use App\Anime;
use Illuminate\Database\Seeder;

class AnimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Anime::create([
           'title' => 'My Hero Academia',
           'genre'=>'shounen',
           'episode' => '50'
        ]);

        Anime::create([
            'title' => 'Fairy Tail',
            'genre'=>'magic',
            'episode' => '50'
        ]);

        Anime::create([
            'title' => 'Black Clover',
            'genre'=>'wizards',
            'episode' => '50'
        ]);

        Anime::create([
            'title' => 'Fire Force',
            'genre'=>'shounen',
            'episode' => '50'
        ]);

        Anime::create([
            'title' => 'Fullmetal Alchemist',
            'genre'=>'alchemy',
            'episode' => '50'
        ]);

        Anime::create([
            'title' => 'Yu Yu Hakusho',
            'genre'=>'action',
            'episode' => '50'
        ]);
    }
}

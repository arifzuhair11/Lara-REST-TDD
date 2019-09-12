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
           'runtime' => '30min'
        ]);

        Anime::create([
            'title' => 'Fairy Tail',
            'genre'=>'magic',
            'runtime' => '30min'
        ]);

        Anime::create([
            'title' => 'Black Clover',
            'genre'=>'wizards',
            'runtime' => '30min'
        ]);

        Anime::create([
            'title' => 'Fire Force',
            'genre'=>'shounen',
            'runtime' => '30min'
        ]);

        Anime::create([
            'title' => 'Fullmetal Alchemist',
            'genre'=>'alchemy',
            'runtime' => '30min'
        ]);

        Anime::create([
            'title' => 'Yu Yu Hakusho',
            'genre'=>'action',
            'runtime' => '30min'
        ]);
    }
}

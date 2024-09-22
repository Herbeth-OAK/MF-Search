<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rank;

class RankSeeder extends Seeder
{
    public function run()
    {
        Rank::create(['name' => 'Almirante (Alm)', 'required_links' => 100, 'required_likes' => 500]);
        Rank::create(['name' => 'Vice-Almirante (Valm)', 'required_links' => 80, 'required_likes' => 400]);
        Rank::create(['name' => 'Comodoro (CmD)', 'required_links' => 60, 'required_likes' => 300]);
        Rank::create(['name' => 'Capitão (Cap.)', 'required_links' => 40, 'required_likes' => 200]);
        Rank::create(['name' => 'Comandante (Com)', 'required_links' => 30, 'required_likes' => 150]);
        Rank::create(['name' => 'Tenente-Comandante (TnC)', 'required_links' => 20, 'required_likes' => 100]);
        Rank::create(['name' => 'Tenente (TnT)', 'required_links' => 10, 'required_likes' => 50]);
        Rank::create(['name' => 'Tenente-Júnior (Tnjr)', 'required_links' => 5, 'required_likes' => 20]);
        Rank::create(['name' => 'Alferes (Alfs)', 'required_links' => 2, 'required_likes' => 10]);
        Rank::create(['name' => 'Cadete (Cad)', 'required_links' => 1, 'required_likes' => 0]);
    }
}

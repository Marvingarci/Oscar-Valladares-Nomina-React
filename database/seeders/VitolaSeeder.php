<?php

namespace Database\Seeders;

use App\Models\Vitola;
use Illuminate\Database\Seeder;

class VitolaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Vitola::factory(20)->create();

    }
}

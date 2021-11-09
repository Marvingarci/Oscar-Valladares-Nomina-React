<?php

namespace Database\Seeders;
use App\Models\Companies;

use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Companies::factory(15)->create(); 
    }
}

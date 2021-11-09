<?php

namespace Database\Seeders;
use App\Models\Department;

use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::factory()->create([
            'name' => 'Produccion',
            'dep_code' => '1503'  
        ]);
        Department::factory()->create([
            'name' => 'Rezago',
            'dep_code' => '1504'
        ]);
        Department::factory()->create([
            'name' => 'Despalillo',
            'dep_code' => '1505'
        ]);
        Department::factory()->create([
            'name' => 'Materiales',
            'dep_code' => '1506'
        ]);
        Department::factory()->create([
            'name' => 'Pilones',
            'dep_code' => '1507'
        ]);
        Department::factory()->create([
            'name' => 'Aseo',
            'dep_code' => '1508'
        ]);
        Department::factory()->create([
            'name' => 'Empaque',
            'dep_code' => '1509'
        ]);
        Department::factory()->create([
            'name' => 'Secado',
            'dep_code' => '1500'
        ]);
    }
}

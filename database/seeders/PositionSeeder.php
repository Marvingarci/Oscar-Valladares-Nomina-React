<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::factory()->create(
            [
                'name' => 'ROLERO',
                'department_id' => 1,
            ]);
        Position::factory()->create(
            [
                'name' => 'BONCHERO',
                'department_id' => 1,
            ]);
        Position::factory()->create(
            [
                    'name' => 'SUPERVISOR',
                    'department_id' => 1,
            ]); 
                
        Position::factory()->create(
                [
                        'name' => 'HALADOR DE PUROS',
                        'department_id' => 1,
                    ]);
        Position::factory()->create(
            [
                'name' => 'REZAGADOR',
                'department_id' => 2,
            ]);
        Position::factory()->create(
            [
                'name' => 'COMPLETADOR',
                'department_id' => 3,
            ]);
        Position::factory()->create(
            [
                'name' => 'DISTRIBUIDOR',
                'department_id' => 4,
            ]);
    
    }
}

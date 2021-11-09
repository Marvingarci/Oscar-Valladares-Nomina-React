<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SalaryRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\SalaryRule::factory()->create([
            'concept' => 'Proporción',
            'code' => 'propor',
            'type' => 'INGRESO',
        ]);

        \App\Models\SalaryRule::factory()->create([
            'concept' => 'Séptimo Día',
            'code' => 'sepmo',
            'type' => 'INGRESO',
        ]);

        \App\Models\SalaryRule::factory()->create([
            'concept' => 'Feriados',
            'code' => 'fer',
            'type' => 'INGRESO',
        ]);

        \App\Models\SalaryRule::factory()->create([
            'concept' => 'Ajuste',
            'code' => 'ajus',
            'type' => 'INGRESO',
        ]);

        \App\Models\SalaryRule::factory()->create([
            'concept' => 'Prestamos Fábrica',
            'code' => 'presfab',
            'type' => 'DEDUCCION',
        ]);

        \App\Models\SalaryRule::factory()->create([
            'concept' => 'Provición',
            'code' => 'provi',
            'type' => 'DEDUCCION',
        ]);

        \App\Models\SalaryRule::factory()->create([
            'concept' => 'Lentes',
            'code' => 'lent',
            'type' => 'DEDUCCION',
        ]);

        \App\Models\SalaryRule::factory()->create([
            'concept' => 'Otras Deducciones',
            'code' => 'odeduc',
            'type' => 'DEDUCCION',
        ]);

        \App\Models\SalaryRule::factory()->create([
            'concept' => 'Bonos',
            'code' => 'bons',
            'type' => 'INGRESO',
        ]);

        \App\Models\SalaryRule::factory()->create([
            'concept' => 'Incapacidades',
            'code' => 'incaps',
            'type' => 'INGRESO',
        ]);

        \App\Models\SalaryRule::factory()->create([
            'concept' => 'Seguro Social',
            'code' => 'ihss',
            'type' => 'DEDUCCION',
        ]);
      
    }
}

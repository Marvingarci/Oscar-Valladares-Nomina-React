<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'Marvin Addiel Garcia Trejo',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'status' => 1,
        ])->givePermissionTo('Universales');

        
    }
}

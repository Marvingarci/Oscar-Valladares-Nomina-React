<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PositionSeeder::class,
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            PermissionTableSeeder::class,
            RolSeeder::class,
            UserSeeder::class,
            CompaniesSeeder::class,
            VitolaSeeder::class,
            CategorySeeder::class,
            SalaryRuleSeeder::class,
        ]);
        
    }
}

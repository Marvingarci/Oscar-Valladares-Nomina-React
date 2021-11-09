<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Universales',

            'Administración',

            'Ver Compañias',
            'Crear Compañias',
            'Actualizar Compañias',
            
            'Ver Usuarios',
            'Crear Usuarios',
            'Actualizar Usuarios',

            'Inventario',

            'Ver Categorias Vitolas',
            'Crear Categorias Vitolas',
            'Actualizar Categorias Vitolas',

            'Ver Productos',
            'Crear Productos',
            'Actualizar Productos',

            'Empleados',

            'Ver Departamentos',
            'Crear Departamentos',
            'Actualizar Departamentos',
            
            'Ver Empleados',
            'Crear Empleados',
            'Actualizar Empleados',
            
            'Ver Puestos',
            'Crear Puestos',
            'Actualizar Puestos',
            
            'Ver Vitolas',
            'Crear Vitolas',
            'Actualizar Vitolas',

            
            
            'Producción',

            'Ver Tickets',
            'Crear Tickets',
            'Actulizar Tickets',
            'Ver Resumen',
            
            'Nómina',

            'Ver Estructuras Salariales',
            'Crear Estructuras Salariales',
            'Actualizar Estructuras Salariales',
            'Ver Reglas Salariales',
            'Crear Reglas Salariales',
            'Actualizar Reglas Salariales',
            'Ver Pagos',
            'Crear Nómina',
            'Actualizar Nómina'
         ];
      
         foreach ($permissions as $permission) {
              Permission::create(['guard_name' => 'sanctum', 'name' => $permission]);
         }
    }
}

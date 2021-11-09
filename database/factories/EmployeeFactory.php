<?php

namespace Database\Factories;

use App\Models\Companies;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;
class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

    $departments = Department::pluck('id')->toArray();
    $positions = Position::pluck('id')->toArray();
    // $companies = Companies::pluck('id')->toArray();

        return [
            'department_id' => $this->faker->randomElement($departments),
            'position_id' => $this->faker->randomElement($positions),
            'company_id' => 1,
            'full_name' => $this->faker->name,
            'gender' => $this->faker->randomElement(['f','m']),
            'date_of_birth' => $this->faker->dateTimeBetween('-40 years', now()),
            'identy' => '0704'.$this->faker->numberBetween(1950,2021).$this->faker->unique()->numerify('#####'),
            'address' => $this->faker->address,
            'phone_number' => $this->faker->unique()->numerify('########'),
            'employee_code' => $this->faker->unique()->numerify('##########'),
        ];
    }
}

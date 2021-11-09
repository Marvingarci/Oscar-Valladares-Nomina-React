<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('position_id')->constrained('positions')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('full_name', 150);
            $table->enum('gender', ['f', 'm']);
            $table->date('date_of_birth');
            $table->string('identy', 13)->unique();
            $table->string('address', 200)->nullable();
            $table->string('phone_number', 8);
            $table->string('employee_code', 20)->unique();
            $table->timestamps();
        });
        Schema::disableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}

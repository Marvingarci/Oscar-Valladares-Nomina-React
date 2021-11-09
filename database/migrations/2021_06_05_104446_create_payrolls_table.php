<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('salary_structure_id')->constrained('salary_structures')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->date('start_date');
            $table->date('final_date');
            $table->float('ordinary_salary', 8, 2);
            $table->float('total_deduc', 8, 2);
            $table->float('total_inc', 8, 2);
            $table->float('total_to_pay', 8, 2);
            $table->integer('days_worked');
            $table->enum('status', ['borrador', 'pagado']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
}

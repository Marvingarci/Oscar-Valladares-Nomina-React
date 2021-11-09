<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_deductions', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('employee_id')->constrained('employees')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('name');
            $table->float('monto', 8, 2);
            $table->float('cuota', 8, 2);
            $table->float('pend',8,2);          
            $table->enum('status', ['active', 'inactive']);
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
        Schema::dropIfExists('other_deductions');
    }
}
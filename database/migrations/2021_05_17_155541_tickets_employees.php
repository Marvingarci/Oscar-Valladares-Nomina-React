<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TicketsEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_employees', function (Blueprint $table) {
            $table->foreignId('ticket_id')->constrained('tickets')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets_employees');
    }
}

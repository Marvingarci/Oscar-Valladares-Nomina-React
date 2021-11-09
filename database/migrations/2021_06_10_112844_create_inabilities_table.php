<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('type', ['ec', 'ecs'])->default('ec');
            $table->string('caption');
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('inabilities');
    }
}

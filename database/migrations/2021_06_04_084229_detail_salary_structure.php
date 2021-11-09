<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DetailSalaryStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_structure_detail', function (Blueprint $table) {
            $table->foreignId('salary_rule_id')->constrained('salary_rules')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('salary_structure_id')->constrained('salary_structures')
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
        Schema::dropIfExists('salary_structure_detail');
    }
}

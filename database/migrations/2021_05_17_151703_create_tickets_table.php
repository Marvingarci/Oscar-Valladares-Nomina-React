<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');
           
            $table->foreignId('generate_tickets_id')->constrained('generate_tickets')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->float('peso', 8, 2)->default('0');
            $table->string('observations')->nullable();
            $table->integer('supervisor_id')->default(0);

            $table->integer('trancados')->default(0);
            $table->integer('pelados')->default(0);
            $table->integer('botados')->default(0);
            $table->integer('amount_of_cigars');
            $table->enum('status', ['creado', 'pesado', 'en bodega', 'finalizado', 'cancelado']);
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
        Schema::dropIfExists('tickets');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsSanctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_sanctions', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('sanction_id');

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('sanction_id')->references('id')->on('sanctions')->onDelete('cascade');

            //SETTING THE PRIMARY KEYS
            $table->primary(['client_id', 'sanction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients_sanctions');
    }
}

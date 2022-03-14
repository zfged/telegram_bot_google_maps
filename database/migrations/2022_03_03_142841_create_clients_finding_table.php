<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsFindingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_finding', function (Blueprint $table) {
            $table->increments('id');
            $table->string("lastname")->nullable();
            $table->string("firstname")->nullable();
            $table->string('patronymic')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('passport')->nullable();
            $table->string('militaryUnit')->nullable();
            $table->string('militaryRank')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('clients_finding');
    }
}

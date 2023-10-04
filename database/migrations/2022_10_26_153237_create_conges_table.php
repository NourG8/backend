<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('raison')->nullable($value = true);
            $table->longText('dates')->nullable();
            $table->string('status');
            $table->date('date');
            $table->integer('level');
            $table->integer('is_deleted');
            $table->integer('user_id')->unsigned()->nullable($value = true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('conges');
    }
};

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
        Schema::create('teletravails', function (Blueprint $table) {
                $table->increments('id');
                $table->string('raison');
                $table->longText('date');
                $table->string('status');
                $table->integer('level');
                $table->integer('is_deleted');
                // $table->longText('user_reject')->nullable($value = true);
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
        Schema::dropIfExists('teletravails');
    }
};

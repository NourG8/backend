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
        Schema::create('history_remote_works', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_responsable');
            $table->string('status'); 
            $table->integer('is_rejected_prov');        // (0 cas accepté)  et (1 cas rejeté)  
            $table->integer('level');
            $table->integer('is_archive');
            $table->longText('raison_reject')->nullable($value = true);
            $table->integer('teletravail_id')->unsigned()->nullable($value = true);
            $table->foreign('teletravail_id')->references('id')->on('teletravails')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('history_remote_works');
    }
};

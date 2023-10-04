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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('country')->nullable();
            $table->string('logo')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('creation_date')->nullable();
            $table->string('status')->nullable();
            $table->string('description',5000)->nullable();
            $table->integer('min_cin')->nullable();
            $table->integer('max_cin')->nullable();
            $table->integer('min_passport')->nullable();
            $table->integer('max_passport')->nullable();
            $table->integer('is_deleted')->nullable();
            $table->string('nationality')->nullable();
            $table->string('regimeSocial')->nullable();
            $table->string('type')->nullable();
            $table->string('color')->nullable();
            $table->string('color2')->nullable();
            $table->integer('max_teletravail')->nullable();     // nombre de time de teletravail
            $table->string('typeTeletravail')->nullable();
            $table->string('startTime')->nullable();
            $table->string('endTime')->nullable();
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
        Schema::dropIfExists('companies');
    }
};

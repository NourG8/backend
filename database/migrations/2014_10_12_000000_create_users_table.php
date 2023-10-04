<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lastName');
            $table->string('firstName');
            $table->string('image')->nullable();
            $table->enum('sex',['Women','Man']);
            $table->string('email')->unique();
            $table->string('emailProf')->unique()->nullable();
            $table->string('address');
            $table->date('dateBirth')->format('d/m/Y');
            $table->string('placeBirth');
            $table->string('status');
            $table->string('nationality');
            $table->string('phone');
            $table->string('phoneEmergency')->nullable();
            $table->enum('FamilySituation',['Single','Married','Divorce','Widow']);
            $table->integer('nbChildren');
            $table->string('levelStudies')->nullable();
            $table->string('specialty')->nullable();
            $table->enum('sivp',['Yes','No'])->nullable();
            $table->string('matricule')->nullable();
            $table->string('carteId')->nullable();
            $table->string('durationSivp')->nullable();
            $table->integer('cin')->unique()->nullable();
            $table->date('deliveryDateCin')->nullable();
            $table->string('deliveryPlaceCin')->nullable();
            $table->string('numPassport')->unique()->nullable();
            $table->date('integrationDate');
            $table->string('password');
            $table->longText('motivation')->nullable();
            $table->integer('pwd_reset_admin');
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('is_deleted');
            $table->string('regimeSocial')->nullable();
            $table->string('text')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};

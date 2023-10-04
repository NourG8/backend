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
        Schema::create('user_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('startDate')->format('d/m/Y')->nullable();
            $table->date('endDate')->format('d/m/Y')->nullable();
            $table->double('salary')->nullable();
            $table->string('placeOfWork')->nullable();
            $table->string('startTimeWork')->nullable();
            $table->string('endTimeWork')->nullable();
            $table->string('trialPeriod')->nullable();  //periode d'essai
            $table->string('fileContract')->nullable();  // le contrat final
            // $table->enum('status', ['Pending', 'Wait', 'Active']);
            $table->enum('status', ['Draft', 'Edited', 'Delivered','Signed','Canceled','Ended']);
            $table->date('date_status')->nullable($value = true);
            $table->integer('is_deleted');
            $table->string('raison')->nullable($value = true);
            $table->integer('OnlyPhysical');
            $table->integer('contract_id')->unsigned()->nullable($value = true);
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('users_contracts');
    }
};

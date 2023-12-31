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
        Schema::create('faq_departments', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('is_deleted');
                $table->timestamps();
                $table->integer('faq_id')->unsigned()->nullable($value = true);
                $table->foreign('faq_id')->references('id')->on('faqs')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('department_id')->unsigned()->nullable($value = true);
                $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade')->onUpdate('cascade');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq_departments');
    }
};

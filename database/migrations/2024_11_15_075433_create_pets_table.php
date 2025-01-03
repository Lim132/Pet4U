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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age');
            $table->string('species');
            $table->string('breed');
            $table->string('gender');
            $table->string('color');
            $table->string('size');
            $table->boolean('vaccinated');
            $table->json('healthStatus');
            $table->string('personality');
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
            $table->json('videos')->nullable();
            $table->unsignedBigInteger('addedBy');
            $table->string('addedByRole');
            $table->boolean('verified')->default(false);
            $table->boolean('adopted')->default(false);
            $table->timestamps();

            $table->foreign('addedBy')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pets');
    }
};

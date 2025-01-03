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
        Schema::create('my_pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('adoption_id')->constrained('adoptions')->onDelete('cascade');
            $table->json('pet_photos')->nullable();
            $table->string('pet_name');
            $table->string('pet_breed');
            $table->string('pet_gender');
            $table->string('pet_age');
            $table->string('pet_size');
            $table->string('pet_color');
            $table->text('pet_description')->nullable();
            $table->string('pet_area')->nullable();
            $table->string('owner_name');
            $table->string('owner_email');
            $table->string('owner_phone');
            $table->string('qr_code_path');
            $table->boolean('show')->default(true);
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
        Schema::dropIfExists('my_pets');
    }
};

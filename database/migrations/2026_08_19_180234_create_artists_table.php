<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            //Coluna obrigatória
            $table->string("name");
            $table->integer("monthly_listeners")->default(0);

            //Coluna opcional de Gênero
            $table->foreignId("genre_id")->constrained()->cascadeOnDelete();

            // Foto de perfil
            $table->string("profile_pic_url")->default("default.jpg");

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};

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
        //
        Schema::create('_artistas_', function (Blueprint $table) {
            $table->id();

            //Coluna obrigatória
            $table->string("name");
            $table->integer("monthly_listeners")->default(0);

            //Coluna opcional de Gênero
            $table->string("genre")->nullable();

            // Foto de perfil 
            $table->string("profile_pic_url")->default("default.jpg");
            
            // Cria created_at e updated
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists("_artistas_");
    }
};

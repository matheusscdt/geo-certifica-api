<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_perfil', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->index();
            $table->uuid('perfil_id')->index();
            $table->boolean('perfil_principal');
            $table->timestamps();

            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('RESTRICT');
            $table->foreign(['perfil_id'])->references(['id'])->on('perfil')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_perfil');
    }
};

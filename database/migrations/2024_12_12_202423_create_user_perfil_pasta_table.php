<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_perfil_pasta', function (Blueprint $table) {
            $table->id();
            $table->integer('user_perfil_id');
            $table->integer('pasta_id');

            $table->foreign(['user_perfil_id'])->references(['id'])->on('user_perfil')->onDelete('RESTRICT');
            $table->foreign(['pasta_id'])->references(['id'])->on('pasta')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_perfil_pasta');
    }
};

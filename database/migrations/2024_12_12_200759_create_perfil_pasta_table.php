<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfil_pasta', function (Blueprint $table) {
            $table->id();
            $table->uuid('perfil_id')->index();
            $table->integer('pasta_id');

            $table->foreign(['perfil_id'])->references(['id'])->on('perfil')->onDelete('RESTRICT');
            $table->foreign(['pasta_id'])->references(['id'])->on('pasta')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfil_pasta');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfil', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->caseInsensitiveText('nome');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfil');
    }
};

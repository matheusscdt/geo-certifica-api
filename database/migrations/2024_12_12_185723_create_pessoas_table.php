<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pessoa', function (Blueprint $table) {
            $table->id();
            $table->caseInsensitiveText('nome');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pessoa');
    }
};

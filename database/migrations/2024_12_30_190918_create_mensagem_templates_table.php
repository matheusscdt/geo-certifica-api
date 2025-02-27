<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensagem_template', function (Blueprint $table) {
            $table->id();
            $table->uuid('perfil_id')->index();
            $table->caseInsensitiveText('nome');
            $table->caseInsensitiveText('assunto');
            $table->caseInsensitiveText('mensagem');
            $table->timestamps();

            $table->foreign(['perfil_id'])->references(['id'])->on('perfil')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento');
    }
};

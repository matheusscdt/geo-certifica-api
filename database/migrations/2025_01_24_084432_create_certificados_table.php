<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificado', function (Blueprint $table) {
            $table->id();
            $table->integer('pessoa_id');
            $table->caseInsensitiveText('nome');
            $table->caseInsensitiveText('organizacao');
            $table->caseInsensitiveText('unidade_organizacional');
            $table->dateTime('data_validade_inicio');
            $table->dateTime('data_validade_fim');
            $table->string('password');
            $table->json('info');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign(['pessoa_id'])->references(['id'])->on('pessoa')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificado');
    }
};

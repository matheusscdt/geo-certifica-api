<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracao', function (Blueprint $table) {
            $table->id();
            $table->uuid('documento_id')->index();
            $table->date('data_limite_assinatura');
            $table->integer('lembrete_documento')->index();
            $table->timestamps();

            $table->foreign(['documento_id'])->references(['id'])->on('documento')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinatario');
    }
};

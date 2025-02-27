<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_assinatura_interna', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('documento_id');
            $table->integer('certificado_id');
            $table->uuid('arquivo_original_id');
            $table->string('hash');
            $table->timestamps();

            $table->foreign(['documento_id'])->references(['id'])->on('documento')->onDelete('RESTRICT');
            $table->foreign(['certificado_id'])->references(['id'])->on('certificado')->onDelete('RESTRICT');
            $table->foreign(['arquivo_original_id'])->references(['id'])->on('arquivo')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_assinatura_interna');
    }
};

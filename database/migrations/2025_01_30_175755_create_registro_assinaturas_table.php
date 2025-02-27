<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_assinatura', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('documento_id');
            $table->uuid('assinatura_id');
            $table->integer('ordem');
            $table->string('hash');
            $table->timestamps();

            $table->foreign(['documento_id'])->references(['id'])->on('documento')->onDelete('RESTRICT');
            $table->foreign(['assinatura_id'])->references(['id'])->on('assinatura')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_assinatura');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autorizacao', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('destinatario_id')->index();
            $table->integer('codigo');
            $table->dateTime('data_validade');
            $table->boolean('autorizado');
            $table->timestamps();

            $table->foreign(['destinatario_id'])->references(['id'])->on('destinatario')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autorizacao');
    }
};

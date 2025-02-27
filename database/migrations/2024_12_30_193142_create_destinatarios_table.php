<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinatario', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('documento_id')->index();
            $table->integer('agenda_id');
            $table->integer('tipo_id');
            $table->timestamps();

            $table->foreign(['documento_id'])->references(['id'])->on('documento')->onDelete('RESTRICT');
            $table->foreign(['agenda_id'])->references(['id'])->on('agenda')->onDelete('RESTRICT');
            $table->foreign(['tipo_id'])->references(['id'])->on('tipo')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinatario');
    }
};

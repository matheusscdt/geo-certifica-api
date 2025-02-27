<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assinatura', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('autorizacao_id')->index();
            $table->string('cpf');
            $table->date('data_nascimento');
            $table->dateTime('data_assinatura');
            $table->timestamps();

            $table->foreign(['autorizacao_id'])->references(['id'])->on('autorizacao')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assinatura');
    }
};

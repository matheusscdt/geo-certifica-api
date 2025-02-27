<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pessoa_fisica', function (Blueprint $table) {
            $table->id();
            $table->integer('pessoa_id');
            $table->string('cpf');
            $table->date('data_nascimento');
            $table->timestamps();

            $table->foreign(['pessoa_id'])->references(['id'])->on('pessoa')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pessoa_fisica');
    }
};

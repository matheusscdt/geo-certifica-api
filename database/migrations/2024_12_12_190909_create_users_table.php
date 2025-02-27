<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('pessoa_id');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('ativo');
            $table->boolean('gestor');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign(['pessoa_id'])->references(['id'])->on('pessoa')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

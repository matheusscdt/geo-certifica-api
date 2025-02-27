<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('convite', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('perfil_id')->index();
            $table->caseInsensitiveText('nome');
            $table->string('email');
            $table->boolean('aceite');
            $table->dateTime('data_aceite')->nullable();
            $table->timestamps();

            $table->foreign(['perfil_id'])->references(['id'])->on('perfil')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convite');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arquivo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome', 250);
            $table->string('arquivo', 250);
            $table->string('extensao');
            $table->string('mime_type');
            $table->integer('tamanho');
            $table->string('related_type')->index();
            $table->string('related_id')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arquivo');
    }
};

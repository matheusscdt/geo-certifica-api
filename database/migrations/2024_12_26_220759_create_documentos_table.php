<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documento', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('pasta_id');
            $table->integer('status_documento')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign(['pasta_id'])->references(['id'])->on('pasta')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento');
    }
};

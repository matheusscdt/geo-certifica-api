<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('convite_pasta', function (Blueprint $table) {
            $table->id();
            $table->uuid('convite_id')->index();
            $table->integer('pasta_id');

            $table->foreign(['convite_id'])->references(['id'])->on('convite')->onDelete('RESTRICT');
            $table->foreign(['pasta_id'])->references(['id'])->on('pasta')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convite_pasta');
    }
};

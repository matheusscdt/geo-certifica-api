<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_ativacao', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->index();
            $table->integer('codigo');
            $table->dateTime('data_ativacao')->nullable();
            $table->timestamps();

            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convite_pasta');
    }
};

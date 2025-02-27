<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfil_agenda', function (Blueprint $table) {
            $table->id();
            $table->uuid('perfil_id')->index();
            $table->integer('agenda_id');

            $table->foreign(['perfil_id'])->references(['id'])->on('perfil')->onDelete('RESTRICT');
            $table->foreign(['agenda_id'])->references(['id'])->on('agenda')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfil_agenda');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_agenda', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->index();
            $table->integer('agenda_id');

            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('RESTRICT');
            $table->foreign(['agenda_id'])->references(['id'])->on('agenda')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_agenda');
    }
};

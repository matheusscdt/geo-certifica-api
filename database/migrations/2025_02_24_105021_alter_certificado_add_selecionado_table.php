<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificado', function (Blueprint $table) {
            $table->boolean('selecionado')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('certificado', function (Blueprint $table) {
            $table->dropColumn('selecionado');
        });
    }
};

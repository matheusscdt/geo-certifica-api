<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registro_assinatura_interna', function (Blueprint $table) {
            $table->string('hash')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('registro_assinatura_interna', function (Blueprint $table) {
            $table->string('hash')->change();
        });
    }
};

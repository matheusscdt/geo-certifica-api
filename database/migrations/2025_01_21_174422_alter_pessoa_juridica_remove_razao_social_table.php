<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pessoa_juridica', function (Blueprint $table) {
            $table->dropColumn('razao_social');
        });
    }

    public function down(): void
    {
        Schema::table('pessoa_juridica', function (Blueprint $table) {
            $table->caseInsensitiveText('razao_social');
        });
    }
};

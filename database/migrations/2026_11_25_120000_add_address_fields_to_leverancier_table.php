<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('Leverancier', function (Blueprint $table): void {
            $table->string('Straatnaam', 100)->nullable();
            $table->string('Huisnummer', 10)->nullable();
            $table->string('Postcode', 10)->nullable();
            $table->string('Stad', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('Leverancier', function (Blueprint $table): void {
            $table->dropColumn(['Straatnaam', 'Huisnummer', 'Postcode', 'Stad']);
        });
    }
};

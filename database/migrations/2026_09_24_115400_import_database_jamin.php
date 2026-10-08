<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Product', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Naam', 100);
            $table->string('Barcode', 13)->unique();
        });

        Schema::create('Allergeen', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Naam', 100);
            $table->string('Omschrijving', 255);
        });

        Schema::create('Leverancier', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Naam', 100);
            $table->string('ContactPersoon', 100);
            $table->string('LeverancierNummer', 20)->unique();
            $table->string('Mobiel', 20);
        });

        Schema::create('Magazijn', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('ProductId');
            $table->decimal('VerpakkingsEenheid', 5, 2);
            $table->unsignedInteger('AantalAanwezig')->nullable();

            $table->foreign('ProductId')->references('Id')->on('Product');
        });

        Schema::create('ProductPerAllergeen', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('ProductId');
            $table->unsignedInteger('AllergeenId');

            $table->foreign('ProductId')->references('Id')->on('Product');
            $table->foreign('AllergeenId')->references('Id')->on('Allergeen');
        });

        Schema::create('ProductPerLeverancier', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('LeverancierId');
            $table->unsignedInteger('ProductId');
            $table->date('DatumLevering');
            $table->unsignedInteger('Aantal');
            $table->date('DatumEerstVolgendeLevering')->nullable();

            $table->foreign('LeverancierId')->references('Id')->on('Leverancier');
            $table->foreign('ProductId')->references('Id')->on('Product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ProductPerLeverancier');
        Schema::dropIfExists('ProductPerAllergeen');
        Schema::dropIfExists('Magazijn');
        Schema::dropIfExists('Leverancier');
        Schema::dropIfExists('Allergeen');
        Schema::dropIfExists('Product');
    }
};

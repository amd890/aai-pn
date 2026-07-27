<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_unit_id')->constrained('organization_units')->cascadeOnDelete();
            $table->string('format_template'); // e.g. "{no}/{unit}/{bulan}/{tahun}"
            $table->unsignedInteger('last_number')->default(0);
            $table->unsignedSmallInteger('year');
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->timestamps();

            $table->unique(['organization_unit_id', 'prefix', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_numbers');
    }
};

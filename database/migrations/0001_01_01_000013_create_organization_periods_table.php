<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_unit_id')->constrained('organization_units')->cascadeOnDelete();
            $table->string('period_name'); // e.g. "Periode 2024-2028"
            $table->unsignedSmallInteger('start_year');
            $table->unsignedSmallInteger('end_year');
            $table->string('sk_number')->nullable();
            $table->string('sk_document_path')->nullable();
            $table->string('status', 20)->default('active'); // active, ended
            $table->timestamps();

            $table->index(['organization_unit_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_periods');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters_in', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number', 100);
            $table->string('sender');
            $table->string('sender_institution')->nullable();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->date('received_date');
            $table->date('letter_date')->nullable();
            $table->string('file_path')->nullable();
            $table->string('classification')->nullable(); // rahasia, biasa, penting
            $table->text('disposition')->nullable();
            $table->foreignId('organization_unit_id')->nullable()->constrained('organization_units')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_unit_id', 'received_date']);
            $table->index('letter_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters_in');
    }
};

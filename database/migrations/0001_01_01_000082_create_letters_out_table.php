<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters_out', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number', 100)->unique();
            $table->string('recipient');
            $table->string('recipient_institution')->nullable();
            $table->string('subject');
            $table->longText('content')->nullable();
            $table->date('letter_date');
            $table->string('template_id')->nullable(); // letter template
            $table->string('file_path')->nullable();
            $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('signer_position')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('classification')->nullable();
            $table->foreignId('organization_unit_id')->nullable()->constrained('organization_units')->nullOnDelete();
            $table->string('status', 20)->default('draft'); // draft, signed, sent
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_unit_id', 'letter_date']);
            $table->index(['status', 'letter_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters_out');
    }
};

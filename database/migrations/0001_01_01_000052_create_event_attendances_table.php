<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_registration_id')->constrained('event_registrations')->cascadeOnDelete();
            $table->string('qr_code')->nullable();
            $table->timestamp('checkin_at')->nullable();
            $table->timestamp('checkout_at')->nullable();
            $table->string('checkin_method', 20)->default('qr'); // qr, manual
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('event_registration_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_attendances');
    }
};

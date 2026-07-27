<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('type', 30)->nullable(); // seminar, workshop, webinar, pelatihan, etc
            $table->string('format', 20)->default('offline'); // offline, online, hybrid
            $table->string('location')->nullable();
            $table->string('map_url')->nullable();
            $table->string('zoom_link')->nullable();
            $table->string('zoom_id')->nullable();
            $table->string('zoom_password')->nullable();
            $table->unsignedInteger('quota')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_free')->default(true);
            $table->string('featured_image')->nullable();
            $table->dateTime('registration_start')->nullable();
            $table->dateTime('registration_end')->nullable();
            $table->dateTime('event_start');
            $table->dateTime('event_end')->nullable();
            $table->string('status', 20)->default('draft'); // draft, published, registration_open, registration_closed, ongoing, completed, cancelled
            $table->foreignId('organization_unit_id')->nullable()->constrained('organization_units')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'event_start']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

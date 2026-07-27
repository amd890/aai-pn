<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50)->default('general'); // general, smtp, payment, seo, social, membership, etc
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('string'); // string, number, boolean, json, text
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false); // exposed to front-end?
            $table->timestamps();

            $table->index('group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

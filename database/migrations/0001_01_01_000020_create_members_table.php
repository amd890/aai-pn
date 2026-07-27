<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('member_number', 30)->unique()->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('nip', 20)->nullable();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('gender', 2)->nullable(); // L, P
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->foreignId('institution_id')->nullable()->constrained('institutions')->nullOnDelete();
            $table->string('position')->nullable(); // jabatan
            $table->string('jenjang_arsiparis')->nullable(); // terampil, ahli, etc
            $table->string('golongan', 10)->nullable(); // III/a, III/b, etc
            $table->string('education')->nullable(); // pendidikan terakhir
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('status', 20)->default('pending'); // pending, active, inactive, suspended, expired, rejected
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('organization_unit_id')->nullable()->constrained('organization_units')->nullOnDelete();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'region_id']);
            $table->index('member_number');
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};

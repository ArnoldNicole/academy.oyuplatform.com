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
        Schema::create('dormitories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('gender', ['male', 'female', 'mixed'])->default('mixed');
            $table->integer('capacity');
            $table->integer('occupied')->default(0);
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->json('facilities')->nullable(); // JSON array for facilities like WiFi, AC, etc.
            $table->decimal('monthly_fee', 10, 2)->default(0);
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_phone')->nullable();
            $table->string('supervisor_email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('school_id');
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->index(['school_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dormitories');
    }
};

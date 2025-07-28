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
        Schema::create('dormitory_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('dormitory_id');
            $table->unsignedBigInteger('school_id');
            $table->string('bed_number')->nullable();
            $table->date('allocated_date');
            $table->date('expected_checkout_date')->nullable();
            $table->date('actual_checkout_date')->nullable();
            $table->enum('status', ['active', 'checked_out', 'transferred', 'suspended'])->default('active');
            $table->text('allocation_notes')->nullable();
            $table->text('checkout_notes')->nullable();
            $table->decimal('total_fees', 10, 2)->default(0);
            $table->decimal('paid_fees', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->unsignedBigInteger('allocated_by'); // staffs who allocated
            $table->unsignedBigInteger('checked_out_by')->nullable(); // staffs who processed checkout
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('dormitory_id')->references('id')->on('dormitories')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('allocated_by')->references('id')->on('staffs')->onDelete('restrict');
            $table->foreign('checked_out_by')->references('id')->on('staffs')->onDelete('restrict');
            
            $table->index(['student_id', 'status']);
            $table->index(['dormitory_id', 'status']);
            $table->index(['school_id', 'allocated_date']);
            $table->unique(['student_id', 'dormitory_id', 'allocated_date'], 'unique_student_dorm_allocation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dormitory_allocations');
    }
};

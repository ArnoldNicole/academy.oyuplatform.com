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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_item_id');
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer', 'loss', 'damage']); // transaction types
            $table->integer('quantity');
            $table->integer('balance_after'); // stock balance after this transaction
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->string('reference_number')->nullable(); // PO number, invoice, etc.
            $table->enum('reason', ['purchase', 'issue', 'return', 'adjustment', 'transfer_in', 'transfer_out', 'loss', 'damage', 'expired', 'donation', 'sale'])->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('recipient_id')->nullable(); // who received/issued to (user_id)
            $table->string('recipient_type')->nullable(); // student, teacher, staff, department
            $table->string('department')->nullable();
            $table->date('transaction_date');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
            
            $table->index(['inventory_item_id', 'transaction_date']);
            $table->index(['school_id', 'type']);
            $table->index(['school_id', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};

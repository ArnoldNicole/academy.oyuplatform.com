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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('category', ['stationery', 'books', 'equipment', 'furniture', 'electronics', 'sports', 'medical', 'cleaning', 'food', 'uniform', 'other'])->default('other');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('unit')->default('piece'); // piece, box, pack, kg, liter, etc.
            $table->integer('current_stock')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->integer('maximum_stock')->nullable();
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->string('supplier_contact')->nullable();
            $table->string('location')->nullable(); // where it's stored
            $table->json('specifications')->nullable(); // additional specs as JSON
            $table->string('barcode')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('condition', ['new', 'good', 'fair', 'poor', 'damaged'])->default('new');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            
            $table->index(['school_id', 'category']);
            $table->index(['school_id', 'is_active']);
            $table->index(['current_stock', 'minimum_stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};

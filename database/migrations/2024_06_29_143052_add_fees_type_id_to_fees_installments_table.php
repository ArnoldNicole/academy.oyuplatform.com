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
        Schema::table('fees_installments', function (Blueprint $table) {
            $table->unsignedBigInteger('fees_type_id')->nullable();
            $table->foreign('fees_type_id')->references('id')->on('fees_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fees_installments', function (Blueprint $table) {
            $table->dropForeign(['fees_type_id']);
            $table->dropColumn('fees_type_id');
        });
    }
};

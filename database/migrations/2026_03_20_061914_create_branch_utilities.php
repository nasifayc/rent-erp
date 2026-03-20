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
        Schema::create('branch_utilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('utility_type');
            $table->string('provider');
            $table->string('account_number');
            $table->string('payment_cycle')->default('monthly');
            $table->date('next_due_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->unique(['branch_id', 'utility_type', 'account_number']);
            $table->index(['next_due_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_utilities');
    }
};

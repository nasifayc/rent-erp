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
        Schema::create('office_rent_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('agreement_id')->unique();
            $table->string('landlord_name');
            $table->string('property_address');
            $table->decimal('monthly_rent', 12, 2);
            $table->string('payment_schedule')->default('monthly');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('draft');
            $table->string('contract_file')->nullable();
            $table->date('approved_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_rent_agreements');
    }
};

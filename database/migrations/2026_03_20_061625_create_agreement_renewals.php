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
        Schema::create('agreement_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_rent_agreement_id')->constrained()->cascadeOnDelete();
            $table->decimal('old_rent', 12, 2);
            $table->decimal('new_rent', 12, 2);
            $table->date('old_end_date');
            $table->date('new_start_date');
            $table->date('new_end_date');
            $table->string('decision')->default('renew');
            $table->string('status')->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'new_end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreement_renewals');
    }
};

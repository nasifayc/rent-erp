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
        Schema::create('vehicle_maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_service_request_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('mileage_at_service');
            $table->date('service_date');
            $table->string('service_type');
            $table->string('provider')->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->date('next_due_date')->nullable();
            $table->unsignedInteger('next_due_mileage')->nullable();
            $table->timestamps();

            $table->index(['service_date', 'next_due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenance_records');
    }
};

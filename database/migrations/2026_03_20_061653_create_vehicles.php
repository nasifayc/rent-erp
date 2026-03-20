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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('plate_number')->unique();
            $table->string('registration_number')->unique();
            $table->string('make');
            $table->string('model');
            $table->string('color')->nullable();
            $table->year('manufacture_year')->nullable();
            $table->unsignedInteger('current_mileage')->default(0);
            $table->date('last_service_date')->nullable();
            $table->unsignedInteger('service_interval_km')->default(5000);
            $table->unsignedInteger('inspection_interval_days')->default(90);
            $table->unsignedInteger('major_service_interval_days')->default(180);
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['status', 'current_mileage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};

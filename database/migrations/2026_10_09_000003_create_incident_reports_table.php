<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number', 30)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('incident_type_id')->constrained('incident_types');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->string('location', 255);
            $table->string('purok', 100);
            $table->string('water_level', 30)->nullable();
            $table->string('severity', 20)->nullable();
            $table->string('status', 30)->default('SUBMITTED');
            $table->foreignId('assigned_admin_id')->nullable()->constrained('users');
            $table->timestamp('submitted_at')->nullable()->useCurrent();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};

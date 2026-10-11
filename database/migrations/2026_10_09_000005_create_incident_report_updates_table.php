<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incident_report_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('incident_reports');
            $table->foreignId('user_id')->constrained('users');
            $table->string('status', 30);
            $table->text('message')->nullable();
            $table->text('action_taken')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_report_updates');
    }
};

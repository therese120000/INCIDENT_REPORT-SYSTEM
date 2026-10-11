<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('incident_reports');
            $table->foreignId('user_id')->constrained('users');
            $table->text('contact_number');
            $table->text('message');
            $table->string('type', 30);
            $table->string('status', 20);
            $table->timestamp('sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};

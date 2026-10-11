<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incident_report_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('incident_reports');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('file_name', 255);
            $table->string('file_path', 255);
            $table->string('file_type', 50)->nullable();
            $table->string('attachment_type', 30);
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_report_attachments');
    }
};

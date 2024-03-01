<?php

use App\Enums\ExtractionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('extractions', function (Blueprint $table) {
            $table->id();
            $table->string('job_id')->comment('The AWS job id returned from the Textract API.');
            $table->longText('text')->nullable();
            $table->string('status')->default(ExtractionStatus::Pending);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extractions');
    }
};

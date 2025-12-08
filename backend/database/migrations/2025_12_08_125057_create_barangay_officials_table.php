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
        Schema::create('barangay_officials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')
                ->constrained('barangays')
                ->onDelete('cascade');
            $table->string('official_name');
            $table->enum('position', [
                'captain',
                'councilor',
                'skchairman',
                'secretary',
                'treasurer'
            ]);
            $table->string('email')->unique();
            $table->string('contact_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_officials');
    }
};

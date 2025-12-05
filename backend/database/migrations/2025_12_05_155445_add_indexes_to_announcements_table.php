<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE INDEX idx_ann_category ON announcements(category_id)");
        DB::statement("CREATE INDEX idx_ann_created_at ON announcements(created_at)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP INDEX idx_ann_category ON announcements");
        DB::statement("DROP INDEX idx_ann_created_at ON announcements");
    }
};

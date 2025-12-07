<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Announcements
        DB::statement("CREATE INDEX idx_ann_category ON announcements(category_id)");
        DB::statement("CREATE INDEX idx_ann_created_at ON announcements(created_at)");

        // Reports
        DB::statement("CREATE INDEX idx_rprt_status ON reports(status)");
        DB::statement("CREATE FULLTEXT INDEX idx_rprt_fulltext ON reports(title, description)");
    }

    public function down(): void
    {
        // Announcements
        DB::statement("DROP INDEX idx_ann_category ON announcements");
        DB::statement("DROP INDEX idx_ann_created_at ON announcements");

        // Reports
        DB::statement("DROP INDEX idx_rprt_status ON reports");
        DB::statement("DROP INDEX idx_rprt_fulltext ON reports");
    }
};

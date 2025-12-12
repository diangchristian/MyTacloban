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
        // Drop old indexes if they exist to avoid conflicts
        DB::statement("DROP INDEX IF EXISTS idx_ann_category ON announcements");
        DB::statement("DROP INDEX IF EXISTS idx_ann_created_at ON announcements");
        DB::statement("DROP INDEX IF EXISTS idx_ann_fulltext ON announcements");

        // Create new indexes
        DB::statement("CREATE FULLTEXT INDEX idx_ann_fulltext ON announcements(title)");
        DB::statement("CREATE INDEX idx_ann_category_status_created ON announcements(category_id, status, created_at)");

        // Reports
        DB::statement("DROP INDEX IF EXISTS idx_rprt_status ON reports");
        DB::statement("DROP INDEX IF EXISTS idx_rprt_fulltext ON reports");
        DB::statement("DROP INDEX IF EXISTS idx_rprt_created_at ON reports");
        DB::statement("DROP INDEX IF EXISTS idx_rprt_status_created_at ON reports");

        DB::statement("CREATE INDEX idx_rprt_status ON reports(status)");
        DB::statement("CREATE FULLTEXT INDEX idx_rprt_fulltext ON reports(title, description)");

        // Barangays
        DB::statement("DROP INDEX IF EXISTS idx_brgy_name ON barangays");
        DB::statement("CREATE INDEX idx_brgy_name ON barangays(name)");
    }

    public function down(): void
    {
        // Announcements
        DB::statement("DROP INDEX IF EXISTS idx_ann_fulltext ON announcements");
        DB::statement("DROP INDEX IF EXISTS idx_ann_category_status_created ON announcements");

        // Recreate old indexes (if needed)
        DB::statement("CREATE INDEX idx_ann_category ON announcements(category_id)");
        DB::statement("CREATE INDEX idx_ann_created_at ON announcements(created_at)");

        // Reports
        DB::statement("DROP INDEX IF EXISTS idx_rprt_status ON reports");
        DB::statement("DROP INDEX IF EXISTS idx_rprt_fulltext ON reports");
        DB::statement("DROP INDEX IF EXISTS idx_rprt_created_at ON reports");
        DB::statement("DROP INDEX IF EXISTS idx_rprt_status_created_at ON reports");

        // Recreate old indexes
        DB::statement("CREATE INDEX idx_rprt_status ON reports(status)");
        DB::statement("CREATE FULLTEXT INDEX idx_rprt_fulltext ON reports(title, description)");

        // Barangays
        DB::statement("DROP INDEX IF EXISTS idx_brgy_name ON barangays");
        DB::statement("CREATE INDEX idx_brgy_name ON barangays(name)");
    }
};

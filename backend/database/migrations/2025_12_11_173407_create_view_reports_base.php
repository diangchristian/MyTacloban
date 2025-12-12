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
        DB::statement("
            CREATE VIEW view_reports_base AS
            SELECT 
                r.id,
                r.user_id,
                r.category_id,
                r.title,
                r.coordinates,
                r.description,
                r.status,
                r.created_at,
                r.updated_at,
                rc.category_name,
                GROUP_CONCAT(DISTINCT rm.file_path) AS images
            FROM reports r
            JOIN report_categories rc ON r.category_id = rc.id
            LEFT JOIN report_images rm ON rm.report_id = r.id
            GROUP BY 
                r.id, r.user_id, r.category_id, r.title, 
                r.description, r.status, r.created_at, r.updated_at, 
                r.coordinates, rc.category_name
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_reports_base");
    }
};

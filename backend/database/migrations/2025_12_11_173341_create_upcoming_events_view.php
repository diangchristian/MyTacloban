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
            CREATE VIEW view_events_upcoming AS
            SELECT 
                e.id,
                e.title,
                e.location,
                e.description,
                e.image,
                e.event_date,
                ec.category_name
            FROM events e
            JOIN event_categories ec ON e.category_id = ec.id
            WHERE e.event_date >= CURDATE();

        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_events_upcoming");
    }
};

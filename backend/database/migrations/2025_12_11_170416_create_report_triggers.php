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
        // UPDATE trigger
        DB::unprepared("
        CREATE TRIGGER trg_reports_update
        AFTER UPDATE ON reports
        FOR EACH ROW
        BEGIN
            -- Only log if status has changed
            IF OLD.status <> NEW.status THEN
                INSERT INTO audit_logs(user_id, table_name, record_id, action, old_values, new_values)
                VALUES (
                    @current_user_id,
                    'reports',
                    NEW.id,
                    'UPDATE',
                    JSON_OBJECT('status', OLD.status),
                    JSON_OBJECT('status', NEW.status)
                );
            END IF;     
        END
    ");


        // DELETE trigger
        DB::unprepared("
        CREATE TRIGGER trg_reports_delete
        AFTER DELETE ON reports
        FOR EACH ROW
        BEGIN
            INSERT INTO audit_logs(user_id, table_name, record_id, action, old_values)
            VALUES (
                @current_user_id, 
                'reports',
                OLD.id,
                'DELETE',
                JSON_OBJECT(
                    'status', OLD.status,
                    'title', OLD.title,
                    'description', OLD.description
                )
            );
        END
    ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_reports_insert;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_reports_update;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_reports_delete;");
    }
};

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
        DB::unprepared("
            CREATE TRIGGER trg_announcements_insert
            AFTER INSERT ON announcements
            FOR EACH ROW
            INSERT INTO audit_logs(user_id, table_name, record_id, action, new_values)
            VALUES (
                NEW.user_id,
                'announcements',
                NEW.id,
                'INSERT',
                JSON_OBJECT(
                    'title', NEW.title,
                    'body', NEW.body,
                    'status', NEW.status,
                    'isHighlight', NEW.isHighlight,
                    'category_id', NEW.category_id,
                    'image', NEW.image
                )
            );
        ");

        // UPDATE
        DB::unprepared("
            CREATE TRIGGER trg_announcements_update
            AFTER UPDATE ON announcements
            FOR EACH ROW
            INSERT INTO audit_logs(user_id, table_name, record_id, action, old_values, new_values)
            VALUES (
                 NEW.user_id,
                'announcements',
                NEW.id,
                'UPDATE',
                JSON_OBJECT(
                    'title', OLD.title,
                    'body', OLD.body,
                    'status', OLD.status,
                    'isHighlight', OLD.isHighlight,
                    'category_id', OLD.category_id,
                    'image', OLD.image
                ),
                JSON_OBJECT(
                    'title', NEW.title,
                    'body', NEW.body,
                    'status', NEW.status,
                    'isHighlight', NEW.isHighlight,
                    'category_id', NEW.category_id,
                    'image', NEW.image
                )
            );
        ");

        // DELETE
        DB::unprepared("
            CREATE TRIGGER trg_announcements_delete
            AFTER DELETE ON announcements
            FOR EACH ROW
            INSERT INTO audit_logs(user_id, table_name, record_id, action, old_values)
            VALUES (
                @current_user_id,
                'announcements',
                OLD.id,
                'DELETE',
                JSON_OBJECT(
                    'title', OLD.title,
                    'body', OLD.body,
                    'status', OLD.status,
                    'isHighlight', OLD.isHighlight,
                    'category_id', OLD.category_id,
                    'image', OLD.image
                )
            );
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_announcements_insert;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_announcements_update;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_announcements_delete;");
    }
};

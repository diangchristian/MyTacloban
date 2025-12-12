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
        CREATE TRIGGER trg_events_insert
        AFTER INSERT ON events
        FOR EACH ROW
        INSERT INTO audit_logs(user_id, table_name, record_id, action, new_values)
        VALUES (
            NEW.user_id,
            'events',
            NEW.id,
            'INSERT',
            JSON_OBJECT(
                'title', NEW.title,
                'description', NEW.description,
                'content', NEW.content,
                'location', NEW.location,
                'event_date', NEW.event_date,
                'event_time', NEW.event_time,
                'category_id', NEW.category_id,
                'image', NEW.image
            )
            );
        ");

        // UPDATE
        DB::unprepared("
            CREATE TRIGGER trg_events_update
            AFTER UPDATE ON events
            FOR EACH ROW
            INSERT INTO audit_logs(user_id, table_name, record_id, action, old_values, new_values)
            VALUES (
                NEW.user_id,
                'events',
                NEW.id,
                'UPDATE',
                JSON_OBJECT(
                    'title', OLD.title,
                    'description', OLD.description,
                    'content', OLD.content,
                    'location', OLD.location,
                    'event_date', OLD.event_date,
                    'event_time', OLD.event_time,
                    'category_id', OLD.category_id,
                    'image', OLD.image
                ),
                JSON_OBJECT(
                    'title', NEW.title,
                    'description', NEW.description,
                    'content', NEW.content,
                    'location', NEW.location,
                    'event_date', NEW.event_date,
                    'event_time', NEW.event_time,
                    'category_id', NEW.category_id,
                    'image', NEW.image
                )
            );
        ");

        // DELETE
        DB::unprepared("
            CREATE TRIGGER trg_events_delete
            AFTER DELETE ON events
            FOR EACH ROW
            INSERT INTO audit_logs(user_id, table_name, record_id, action, old_values)
            VALUES (
                @current_user_id,
                'events',
                OLD.id,
                'DELETE',
                JSON_OBJECT(
                    'title', OLD.title,
                    'description', OLD.description,
                    'content', OLD.content,
                    'location', OLD.location,
                    'event_date', OLD.event_date,
                    'event_time', OLD.event_time,
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
        DB::unprepared("DROP TRIGGER IF EXISTS trg_events_insert;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_events_update;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_events_delete;");
    }
};

<?php
// Save as: database/migrations/2025_12_12_000000_setup_database_user_permissions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates three database users with different permission levels:
     * 1. mytacloban_readonly - SELECT only (read-only access)
     * 2. mytacloban_app - SELECT, INSERT, UPDATE, DELETE (standard app user)
     * 3. mytacloban_admin - Full schema permissions (admin operations)
     */
    public function up(): void
    {
        try {
            $database = env('DB_DATABASE', 'backend');
            
            echo "\n🔐 Creating database users with GRANT statements...\n";
            
            // 1. Create READ-ONLY user
            echo "Creating mytacloban_readonly user...\n";
            DB::statement("CREATE USER IF NOT EXISTS 'mytacloban_readonly'@'localhost' IDENTIFIED BY 'readonly_pass123'");
            DB::statement("GRANT SELECT ON `{$database}`.* TO 'mytacloban_readonly'@'localhost'");
            echo "✓ READ-ONLY user created with SELECT permission\n";
            
            // 2. Create APP user (CRUD operations)
            echo "Creating mytacloban_app user...\n";
            DB::statement("CREATE USER IF NOT EXISTS 'mytacloban_app'@'localhost' IDENTIFIED BY 'app_pass123'");
            DB::statement("GRANT SELECT, INSERT, UPDATE, DELETE ON `{$database}`.* TO 'mytacloban_app'@'localhost'");
            echo "✓ APP user created with SELECT, INSERT, UPDATE, DELETE permissions\n";
            
            // 3. Create ADMIN user (Schema modifications)
            echo "Creating mytacloban_admin user...\n";
            DB::statement("CREATE USER IF NOT EXISTS 'mytacloban_admin'@'localhost' IDENTIFIED BY 'admin_pass123'");
            DB::statement("GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER ON `{$database}`.* TO 'mytacloban_admin'@'localhost'");
            echo "✓ ADMIN user created with full schema permissions\n";
            
            // Apply the permissions
            DB::statement("FLUSH PRIVILEGES");
            echo "✓ Permissions flushed successfully\n\n";
            
            echo "✅ All database users created successfully!\n";
            echo "Run 'php artisan db:permissions list' to verify.\n\n";
            
        } catch (\Exception $e) {
            Log::error("Failed to create database users: " . $e->getMessage());
            echo "\n❌ Error: " . $e->getMessage() . "\n";
            echo "Make sure your DB_USERNAME has GRANT OPTION privilege.\n\n";
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     * Revokes all permissions and drops the created users.
     */
    public function down(): void
    {
        try {
            $database = env('DB_DATABASE', 'backend');
            
            echo "\n🔓 Revoking permissions with REVOKE statements...\n";
            
            // Revoke all permissions
            DB::statement("REVOKE ALL PRIVILEGES ON `{$database}`.* FROM 'mytacloban_readonly'@'localhost'");
            echo "✓ Revoked permissions from mytacloban_readonly\n";
            
            DB::statement("REVOKE ALL PRIVILEGES ON `{$database}`.* FROM 'mytacloban_app'@'localhost'");
            echo "✓ Revoked permissions from mytacloban_app\n";
            
            DB::statement("REVOKE ALL PRIVILEGES ON `{$database}`.* FROM 'mytacloban_admin'@'localhost'");
            echo "✓ Revoked permissions from mytacloban_admin\n";
            
            // Drop users
            DB::statement("DROP USER IF EXISTS 'mytacloban_readonly'@'localhost'");
            DB::statement("DROP USER IF EXISTS 'mytacloban_app'@'localhost'");
            DB::statement("DROP USER IF EXISTS 'mytacloban_admin'@'localhost'");
            echo "✓ Dropped all users\n";
            
            DB::statement("FLUSH PRIVILEGES");
            echo "✓ Permissions flushed\n\n";
            
            echo "✅ All database users removed successfully!\n\n";
            
        } catch (\Exception $e) {
            Log::warning("Failed to drop database users: " . $e->getMessage());
            echo "\n⚠️  Warning: " . $e->getMessage() . "\n\n";
        }
    }
};
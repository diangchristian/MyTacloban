<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ManageDatabasePermissions extends Command
{
    protected $signature = 'db:permissions 
                            {action : Action to perform: grant, revoke, list, or test}
                            {--user= : Specific user to manage}
                            {--role= : Role type: readonly, app, or admin}';
    
    protected $description = 'Manage MySQL database user permissions using GRANT and REVOKE';

    private $database;

    public function __construct()
    {
        parent::__construct();
        $this->database = env('DB_DATABASE', 'backend');
    }

    public function handle()
    {
        $action = $this->argument('action');

        try {
            switch ($action) {
                case 'grant':
                    return $this->grantPermissions();
                case 'revoke':
                    return $this->revokePermissions();
                case 'list':
                    return $this->listUsers();
                case 'test':
                    return $this->testPermissions();
                default:
                    $this->error('Invalid action. Use: grant, revoke, list, or test');
                    $this->line('');
                    $this->line('Available commands:');
                    $this->line('  php artisan db:permissions grant    - Grant permissions to a user');
                    $this->line('  php artisan db:permissions revoke   - Revoke permissions from a user');
                    $this->line('  php artisan db:permissions list     - List all database users');
                    $this->line('  php artisan db:permissions test     - Test all created users');
                    return 1;
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }

    private function grantPermissions()
    {
        $user = $this->option('user');
        $role = $this->option('role');

        $this->info('🔐 MySQL GRANT Command Tool');
        $this->line('');

        if (!$user) {
            $user = $this->ask('Enter username to grant permissions to');
        }

        if (!$role) {
            $role = $this->choice('Select role type', ['readonly', 'app', 'admin'], 1);
        }

        $password = $this->secret('Enter password for the user');

        $this->line('');
        $this->info("Creating user '{$user}' with role '{$role}'...");

        // Create user if not exists
        DB::statement("CREATE USER IF NOT EXISTS '{$user}'@'localhost' IDENTIFIED BY '{$password}'");
        $this->line("✓ User created/verified");

        // Grant permissions based on role
        switch ($role) {
            case 'readonly':
                $sql = "GRANT SELECT ON `{$this->database}`.* TO '{$user}'@'localhost'";
                DB::statement($sql);
                $this->line("✓ Executed: {$sql}");
                $this->info("✓ Granted READ-ONLY permissions (SELECT) to '{$user}'");
                break;
            case 'app':
                $sql = "GRANT SELECT, INSERT, UPDATE, DELETE ON `{$this->database}`.* TO '{$user}'@'localhost'";
                DB::statement($sql);
                $this->line("✓ Executed: {$sql}");
                $this->info("✓ Granted APP permissions (SELECT, INSERT, UPDATE, DELETE) to '{$user}'");
                break;
            case 'admin':
                $sql = "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER ON `{$this->database}`.* TO '{$user}'@'localhost'";
                DB::statement($sql);
                $this->line("✓ Executed: {$sql}");
                $this->info("✓ Granted ADMIN permissions (all schema operations) to '{$user}'");
                break;
        }

        DB::statement("FLUSH PRIVILEGES");
        $this->line("✓ Executed: FLUSH PRIVILEGES");
        $this->line('');
        $this->info("✅ Permissions granted successfully!");

        return 0;
    }

    private function revokePermissions()
    {
        $user = $this->option('user');

        $this->info('🔓 MySQL REVOKE Command Tool');
        $this->line('');

        if (!$user) {
            $user = $this->ask('Enter username to revoke permissions from');
        }

        if ($this->confirm("Are you sure you want to revoke all permissions from '{$user}'?", false)) {
            $sql = "REVOKE ALL PRIVILEGES ON `{$this->database}`.* FROM '{$user}'@'localhost'";
            DB::statement($sql);
            $this->line("✓ Executed: {$sql}");
            
            DB::statement("FLUSH PRIVILEGES");
            $this->line("✓ Executed: FLUSH PRIVILEGES");
            
            $this->info("✓ Revoked all permissions from '{$user}'");
            $this->line('');

            if ($this->confirm("Do you want to drop the user '{$user}' completely?", false)) {
                $sql = "DROP USER IF EXISTS '{$user}'@'localhost'";
                DB::statement($sql);
                $this->line("✓ Executed: {$sql}");
                $this->info("✓ Dropped user '{$user}'");
            }
            
            $this->line('');
            $this->info("✅ Revoke operation completed!");
        } else {
            $this->info("Operation cancelled");
        }

        return 0;
    }

    private function listUsers()
    {
        $this->info("📋 Database Users with Access to '{$this->database}'");
        $this->line('');

        try {
            $users = DB::select("
                SELECT DISTINCT grantee, privilege_type, table_schema
                FROM information_schema.schema_privileges
                WHERE table_schema = ?
                ORDER BY grantee, privilege_type
            ", [$this->database]);

            if (empty($users)) {
                $this->warn("No specific grants found for database '{$this->database}'");
                $this->line('');
                $this->line("Checking all MySQL users...");
                
                $allUsers = DB::select("SELECT user, host FROM mysql.user WHERE user LIKE 'mytacloban%'");
                
                if (empty($allUsers)) {
                    $this->error("No mytacloban users found. Run migration first: php artisan migrate");
                } else {
                    $this->line('');
                    $this->info("Found users (but no specific database grants yet):");
                    foreach ($allUsers as $u) {
                        $this->line("  • {$u->user}@{$u->host}");
                    }
                }
                
                return 0;
            }

            $grouped = [];
            foreach ($users as $user) {
                $grantee = str_replace(['`', "'"], '', $user->grantee);
                $grouped[$grantee][] = $user->privilege_type;
            }

            foreach ($grouped as $user => $privileges) {
                $this->line("📌 {$user}");
                $this->line("   Database: {$this->database}");
                $this->line("   Privileges: " . implode(', ', $privileges));
                $this->line('');
            }

            $this->info("Total users: " . count($grouped));

        } catch (\Exception $e) {
            $this->error("Error listing users: " . $e->getMessage());
        }

        return 0;
    }

   private function testPermissions()
    {
        $this->info("🧪 Testing Database Permissions");
        $this->line('');
        $this->line("This will test each user's ability to perform various operations.");
        $this->line('');

        $testUsers = [
            'mytacloban_readonly' => [
                'password' => 'readonly_pass123',
                'expected' => ['SELECT' => true, 'INSERT' => false, 'UPDATE' => false, 'DELETE' => false, 'CREATE' => false]
            ],
            'mytacloban_app' => [
                'password' => 'app_pass123',
                'expected' => ['SELECT' => true, 'INSERT' => true, 'UPDATE' => true, 'DELETE' => true, 'CREATE' => false]
            ],
            'mytacloban_admin' => [
                'password' => 'admin_pass123',
                'expected' => ['SELECT' => true, 'INSERT' => true, 'UPDATE' => true, 'DELETE' => true, 'CREATE' => true]
            ],
        ];

        foreach ($testUsers as $username => $config) {
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("Testing: {$username}");
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            
            try {
                // Try to connect with the user
                $pdo = new \PDO(
                    "mysql:host=" . env('DB_HOST') . ";dbname={$this->database}",
                    $username,
                    $config['password']
                );
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                $this->line("✓ Connection successful");
                $this->line('');

                // Test SELECT
                try {
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
                    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                    $expected = $config['expected']['SELECT'] ? '✓' : '✗';
                    $this->line("{$expected} SELECT: Can read {$result['count']} records from users table");
                } catch (\Exception $e) {
                    $this->line("✗ SELECT: " . $e->getMessage());
                }

                // Get a valid barangay_id for testing
                $barangayId = null;
                try {
                    $stmt = $pdo->query("SELECT id FROM barangays LIMIT 1");
                    $barangay = $stmt->fetch(\PDO::FETCH_ASSOC);
                    $barangayId = $barangay['id'] ?? null;
                } catch (\Exception $e) {
                    // Ignore if can't read barangays
                }

                // Test INSERT with valid foreign key
                $testEmail = "test_" . time() . "@test.com";
                $testUsername = "test_user_" . time();
                try {
                    $stmt = $pdo->prepare("INSERT INTO users (username, full_name, email, password, role, status, barangay_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $testUsername, 
                        "Test User", 
                        $testEmail, 
                        password_hash('password', PASSWORD_DEFAULT), 
                        'User', 
                        'active',
                        $barangayId  // Use valid barangay_id
                    ]);
                    $expected = $config['expected']['INSERT'] ? '✓' : '✗';
                    $this->line("{$expected} INSERT: Successfully inserted test record");
                    
                    $insertId = $pdo->lastInsertId();

                    // Test UPDATE
                    try {
                        $stmt = $pdo->prepare("UPDATE users SET full_name = ? WHERE id = ?");
                        $stmt->execute(["Updated User", $insertId]);
                        $expected = $config['expected']['UPDATE'] ? '✓' : '✗';
                        $this->line("{$expected} UPDATE: Successfully updated test record");
                    } catch (\Exception $e) {
                        $this->line("✗ UPDATE: Access denied (expected for readonly)");
                    }

                    // Test DELETE
                    try {
                        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                        $stmt->execute([$insertId]);
                        $expected = $config['expected']['DELETE'] ? '✓' : '✗';
                        $this->line("{$expected} DELETE: Successfully deleted test record");
                    } catch (\Exception $e) {
                        $this->line("✗ DELETE: Access denied (expected for readonly)");
                    }

                } catch (\Exception $e) {
                    if (strpos($e->getMessage(), 'INSERT command denied') !== false) {
                        $this->line("✗ INSERT: Access denied (expected for readonly)");
                        $this->line("✗ UPDATE: Not tested (INSERT required)");
                        $this->line("✗ DELETE: Not tested (INSERT required)");
                    } else {
                        $this->line("✗ INSERT: " . $e->getMessage());
                    }
                }

                // Test CREATE TABLE
                try {
                    $pdo->exec("CREATE TABLE IF NOT EXISTS test_permissions_table (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(100))");
                    $expected = $config['expected']['CREATE'] ? '✓' : '✗';
                    $this->line("{$expected} CREATE: Successfully created test table");
                    
                    // Cleanup
                    $pdo->exec("DROP TABLE IF EXISTS test_permissions_table");
                } catch (\Exception $e) {
                    $expected = $config['expected']['CREATE'] ? '✗' : '✓';
                    $this->line("{$expected} CREATE: Access denied (expected for non-admin)");
                }

            } catch (\Exception $e) {
                $this->error("✗ Connection failed: " . $e->getMessage());
            }

            $this->line('');
        }

        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✅ Testing completed!");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        return 0;
    }
}
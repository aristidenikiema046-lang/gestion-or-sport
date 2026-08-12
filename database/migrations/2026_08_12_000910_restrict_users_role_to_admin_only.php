<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('role', 'livreur')->update(['role' => 'admin']);

        // The enum is only tightened at the DB level on MySQL — other drivers
        // (e.g. SQLite in tests) don't support altering CHECK/ENUM constraints
        // in place, and the application layer already rejects 'livreur' everywhere.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin') NOT NULL DEFAULT 'admin'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'livreur') NOT NULL DEFAULT 'admin'");
        }
    }
};

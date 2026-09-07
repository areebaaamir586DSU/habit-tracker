<?php
require_once __DIR__ . '/config.php';

$db = getDb();

$db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        email_verified INTEGER NOT NULL DEFAULT 0,
        verify_token TEXT,
        reset_token TEXT,
        reset_expires TEXT,
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    )
");

// Migration: add new columns if missing
$cols = [];
$result = $db->query("PRAGMA table_info(users)");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $cols[] = $row['name'];
}
if (!in_array('email_verified', $cols)) {
    $db->exec("ALTER TABLE users ADD COLUMN email_verified INTEGER NOT NULL DEFAULT 0");
    $db->exec("ALTER TABLE users ADD COLUMN verify_token TEXT");
    $db->exec("ALTER TABLE users ADD COLUMN reset_token TEXT");
    $db->exec("ALTER TABLE users ADD COLUMN reset_expires TEXT");
}

$db->exec("
    CREATE TABLE IF NOT EXISTS user_data (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        data_key TEXT NOT NULL,
        data_value TEXT NOT NULL DEFAULT '',
        updated_at TEXT NOT NULL DEFAULT (datetime('now')),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE(user_id, data_key)
    )
");

$db->exec("
    CREATE INDEX IF NOT EXISTS idx_user_data_lookup 
    ON user_data(user_id, data_key)
");

$db->close();

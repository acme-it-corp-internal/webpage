<?php
// ACME Corp - Site Configuration
// Last updated by fong - see git history for deployment notes

define('DB_HOST', 'localhost');
define('DB_NAME', 'acme_db');
define('DB_USER', 'acme_app');
define('DB_PASS', 'Acm3C0rp2024!');

// Flask API config - internal use only
define('API_BASE', 'http://192.168.56.21:5000');
define('API_KEY',  'sk-acme-dGhpcyBpcyBhIHRlc3Qga2V5');

// Internal paths (FIXME: restrict before go-live - bob)
// Admin panel: /admin/
// Backup storage: /backups/
// File uploads: /uploads/

function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>

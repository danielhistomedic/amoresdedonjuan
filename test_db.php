<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test 1: Configured remote connection
echo "--- Probing Remote (histoclin.mx) ---\n";
try {
    $conn = new PDO("mysql:host=histoclin.mx;dbname=histocli_amores_sandbox;charset=utf8", "histocli_amores", "k-%sh9SJbsvT", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 3
    ]);
    echo "Successfully connected to Remote!\n";
} catch (Exception $e) {
    echo "Remote connection failed: " . $e->getMessage() . "\n";
}

// Test 2: Localhost connection with Configured user
echo "\n--- Probing Localhost (Configured User) ---\n";
try {
    $conn = new PDO("mysql:host=localhost;dbname=histocli_amores_sandbox;charset=utf8", "histocli_amores", "k-%sh9SJbsvT", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 3
    ]);
    echo "Successfully connected to Localhost with configured user!\n";
    list_databases($conn);
} catch (Exception $e) {
    echo "Localhost connection with configured user failed: " . $e->getMessage() . "\n";
}

// Test 3: Localhost connection with root/empty
echo "\n--- Probing Localhost (root/no password) ---\n";
try {
    $conn = new PDO("mysql:host=localhost;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 3
    ]);
    echo "Successfully connected to Localhost with root!\n";
    list_databases($conn);
} catch (Exception $e) {
    echo "Localhost connection with root failed: " . $e->getMessage() . "\n";
}

function list_databases($conn) {
    $stmt = $conn->query("SHOW DATABASES");
    $dbs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Databases found: " . implode(", ", $dbs) . "\n";
}

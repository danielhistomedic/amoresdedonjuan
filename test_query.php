<?php
$_SERVER['HTTP_HOST'] = 'localhost';
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('Config/Config.php');
require_once('Helpers/Helpers.php');
require_once('Helpers/LogErrors.php');
require_once('Libraries/Core/Conexion.php');
require_once('Libraries/Core/Mysql.php');

echo "Instantiating Mysql class...\n";
try {
    $db = new Mysql();
    echo "Running query...\n";
    $query = "SELECT id, usuario FROM usuarios LIMIT 2";
    $result = $db->select($query, []);
    echo "Result: " . json_encode($result) . "\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

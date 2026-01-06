<?php
// db_connect.php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// clear all cached output
while (ob_get_level()) {
    ob_end_clean();
}

// 数据库配置
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crowdedu";

// declarer connect 
$conn = new mysqli($servername, $username, $password, $dbname);

// check conncection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    if (php_sapi_name() !== 'cli') {
        die("Database connection error. Please try again later.");
    }
    exit(1);
}


$conn->set_charset("utf8mb4");
?>
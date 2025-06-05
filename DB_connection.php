<?php  

$sName = "localhost";
$uName = "root";
$uPort = "3307";
$pass  = "";
$db_name = "project_db";

try {
    $conn = new PDO("mysql:host=$sName;port=$uPort;dbname=$db_name", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: ". $e->getMessage();
    exit;
}
<?php
// การตั้งค่าฐานข้อมูล
$host = 'localhost';
$dbname = 'devclub_db';
$username = 'root';
$password = ''; // XAMPP ปกติไม่มีรหัส

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
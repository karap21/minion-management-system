<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default Laragon password kosong
$db   = 'despicable_db';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // Set error mode ke exception biar ketahuan kalau ada error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}
?>
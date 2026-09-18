<?php

$host = "localhost";
$nama_database = "remidi_php";
$username = "root";
$password = "";

try {
    $koneksi = new PDO(
        "mysql:host=$host;dbname=$nama_database;charset=utf8mb4",
        $username,
        $password
    );

    $koneksi->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
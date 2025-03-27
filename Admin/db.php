<?php
// Thông tin kết nối
$serverName = "localhost\\SQLEXPRESS"; // Tên server hoặc IP (vd: "127.0.0.1" hoặc "DESKTOP-12345")
$database = "quanlybandongho"; // Tên cơ sở dữ liệu

try {
    // Kết nối với SQL Server sử dụng Windows Authentication
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database;TrustServerCertificate=YES");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Lỗi kết nối: " . $e->getMessage();
    exit;
}

<?php
// File: public/cek_koneksi.php

$start = microtime(true);

$host = '127.0.0.1'; // Kita paksa pakai IP
$db   = 'kafe'; // GANTI DENGAN NAMA DATABASE DI .ENV KAMU
$user = 'root'; // Default XAMPP
$pass = '';     // Default XAMPP kosong

echo "<h1>Tes Kecepatan Database Murni</h1>";
echo "Mulai koneksi...<br>";

try {
    // 1. Tes Koneksi
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $connTime = microtime(true) - $start;
    echo "✅ Berhasil Konek! Waktu: " . number_format($connTime, 4) . " detik.<br>";

    // 2. Tes Query Sederhana
    echo "Mengambil data kategori...<br>";
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll();
    
    $queryTime = microtime(true) - $start;
    echo "✅ Berhasil Query! Total data: " . count($categories) . " baris.<br>";
    echo "<h2>⏱️ Total Waktu Eksekusi: " . number_format($queryTime, 4) . " detik</h2>";

    if ($queryTime > 1.0) {
        echo "<h3 style='color:red'>KESIMPULAN: Masalah ada di Server/XAMPP (Bukan Laravel)</h3>";
    } else {
        echo "<h3 style='color:green'>KESIMPULAN: Server Cepat. Masalah ada di dalam Laravel.</h3>";
    }

} catch(PDOException $e) {
    echo "❌ Gagal: " . $e->getMessage();
}
?>
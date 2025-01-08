<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../../config/database.php'; // Koneksi database

$id = $_GET['id'];

$sql = "DELETE FROM jadwal_kuliah WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: jadwal_kuliah.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
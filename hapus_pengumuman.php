<?php
session_start();
include 'db.php';

// Pastikan hanya user login yang bisa hapus
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil dulu nama file foto (jika ada)
    $result = $conn->query("SELECT foto FROM pengumuman WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        if (!empty($row['foto']) && file_exists("uploads/" . $row['foto'])) {
            unlink("uploads/" . $row['foto']); // hapus file dari folder
        }
    }

    // Hapus dari database
    $conn->query("DELETE FROM pengumuman WHERE id = $id");
}

header("Location: pengumuman.php");
exit;

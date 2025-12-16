<?php
require_once 'auth.php';
require_login();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: index.php?status=deleted");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
}
?>
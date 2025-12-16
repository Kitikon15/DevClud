<?php
require_once 'auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // รับ ID (ถ้าว่าง = เพิ่มใหม่, ถ้ามีค่า = แก้ไข)
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $major = $_POST['major'];
    $academic_year = $_POST['academic_year'];

    try {
        if ($id) {
            // --- กรณีแก้ไข (Update) ---
            $sql = "UPDATE members SET fullname = ?, email = ?, major = ?, academic_year = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$fullname, $email, $major, $academic_year, $id]);
        } else {
            // --- กรณีเพิ่มใหม่ (Insert) ---
            $sql = "INSERT INTO members (fullname, email, major, academic_year) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$fullname, $email, $major, $academic_year]);
        }

        // บันทึกเสร็จ กลับไปหน้าแรก
        header("Location: index.php?status=success");

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
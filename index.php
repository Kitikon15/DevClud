<?php 
require_once 'auth.php';
require_login(); 
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevClub Member System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php $display = $_SESSION['display_name'] ?? $_SESSION['username'] ?? 'ผู้ใช้'; ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">🚀 DevClub</a>
            <div class="d-flex align-items-center">
                <span class="me-3 text-muted">สวัสดี, <?= htmlspecialchars($display); ?></span>
                <a href="logout.php" class="btn btn-outline-secondary btn-sm">ออกจากระบบ</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">🚀 สมาชิกชมรม DevClub</h4>
                <a href="form.php" class="btn btn-light btn-sm fw-bold">+ เพิ่มสมาชิก</a>
            </div>
            <div class="card-body">
                <?php
                // แจ้งเตือนสถานะการบันทึก (ถ้ามี)
                if (isset($_GET['status'])) {
                    if ($_GET['status'] == 'success') {
                        $msg = 'บันทึกข้อมูลสำเร็จ!';
                        $color = 'success';
                    } elseif ($_GET['status'] == 'deleted') {
                        $msg = 'ลบข้อมูลสำเร็จ!';
                        $color = 'warning';
                    } else {
                        $msg = 'เกิดข้อผิดพลาด!';
                        $color = 'danger';
                    }
                    echo "<div class='alert alert-$color alert-dismissible fade show' role='alert'>" . htmlspecialchars($msg) . "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                }

                // ดึงข้อมูล
                $stmt = $conn->query("SELECT * FROM members ORDER BY id DESC");
                $members = $stmt->fetchAll();
                ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>รหัส</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>อีเมล</th>
                                <th>สาขา</th>
                                <th>ปีการศึกษา</th>
                                <th style="width: 150px;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($members): ?>
                                <?php foreach ($members as $row): ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= htmlspecialchars($row['fullname']); ?></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= htmlspecialchars($row['major']); ?></td>
                                        <td><?= $row['academic_year']; ?></td>
                                        <td>
                                            <a href="form.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                            <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('ยืนยันลบข้อมูลนี้หรือไม่?');">ลบ</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">ไม่พบข้อมูลสมาชิก</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
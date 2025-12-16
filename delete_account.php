<?php
require_once 'auth.php';
// ไม่จำเป็นต้องล็อกอินล่วงหน้า — ผู้ใช้จะยืนยันตัวด้วย username + password

$error = '';
$success = '';
$prefill = $_SESSION['username'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'โปรดกรอกชื่อผู้ใช้และรหัสผ่านเพื่อยืนยัน';
    } else {
        // ยืนยันรหัสผ่านโดยไม่เปลี่ยน session
        if (verify_credentials($username, $password)) {
            // ลบบัญชี
            if (delete_user_by_username($username)) {
                $success = 'ลบบัญชีสำเร็จแล้ว — ขอบคุณที่ใช้บริการ';
            } else {
                $error = 'ไม่พบบัญชีที่ต้องการลบ';
            }
        } else {
            $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลบบัญชีผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container" style="max-width:520px; margin-top:6rem;">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">🗑️ ยกเลิกการสมัคร (ลบบัญชี)</h5>
            </div>
            <div class="card-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <a href="login.php" class="btn btn-primary">กลับไปที่หน้าเข้าสู่ระบบ</a>
                <?php else: ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <p>กรอกชื่อผู้ใช้และรหัสผ่านของบัญชีที่ต้องการลบ (ระบบจะยืนยันตัวตนก่อนลบ)</p>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" name="username" class="form-control" required
                                value="<?= htmlspecialchars($prefill) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">ยกเลิก</a>
                            <button class="btn btn-danger">ยืนยันลบบัญชี</button>
                        </div>
                    </form>
                <?php endif; ?>
                <hr>
                <p class="small text-muted">หมายเหตุ: การลบบัญชีจะลบสิทธิ์การเข้าใช้งาน;
                    ถ้าต้องการให้เก็บข้อมูลสมาชิกไว้ ให้ติดต่อผู้ดูแลระบบ</p>
            </div>
        </div>
    </div>
</body>

</html>
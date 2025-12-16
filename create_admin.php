<?php
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $display_name = trim($_POST['display_name'] ?? '');
    
    // Validation
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'กรุณากรอกข้อมูลให้ครบทุกช่อง';
    } elseif ($password !== $confirm_password) {
        $error = 'รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน';
    } elseif (strlen($password) < 6) {
        $error = 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร';
    } else {
        try {
            // Check if username already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = 'ชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว';
            } else {
                // Create new user
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password_hash, display_name) VALUES (?, ?, ?)");
                $stmt->execute([$username, $password_hash, $display_name]);
                
                $success = 'สร้างผู้ใช้เรียบร้อยแล้ว! คุณสามารถเข้าสู่ระบบได้เลย';
            }
        } catch (PDOException $e) {
            $error = 'เกิดข้อผิดพลาดในการสร้างผู้ใช้: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้างผู้ใช้ผู้ดูแลระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container" style="max-width:420px; margin-top:6rem;">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">👤 สร้างผู้ใช้ผู้ดูแลระบบ</h5>
            </div>
            <div class="card-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <div class="d-grid">
                        <a href="login.php" class="btn btn-primary">เข้าสู่ระบบ</a>
                    </div>
                <?php else: ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">ชื่อแสดง (ไม่จำเป็น)</label>
                            <input type="text" name="display_name" class="form-control" value="<?= htmlspecialchars($_POST['display_name'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน (อย่างน้อย 6 ตัวอักษร)</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">ยืนยันรหัสผ่าน</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        
                        <div class="d-grid">
                            <button class="btn btn-success">สร้างผู้ใช้</button>
                        </div>
                    </form>
                    
                    <hr>
                    <div class="text-center">
                        <a href="login.php" class="btn btn-link">กลับไปหน้าเข้าสู่ระบบ</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
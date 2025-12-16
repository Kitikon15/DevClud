<?php
require_once 'auth.php';
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}
$error = '';
$status = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'logged_out')
        $status = 'ออกจากระบบเรียบร้อยแล้ว';
    if ($_GET['status'] == 'created')
        $status = 'สร้างผู้ใช้เรียบร้อยแล้ว';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $next = $_POST['next'] ?? $_GET['next'] ?? 'index.php';

    if ($username === '' || $password === '') {
        $error = 'โปรดกรอกชื่อผู้ใช้และรหัสผ่าน';
    } else {
        if (login_user($username, $password)) {
            header('Location: ' . ($next ?: 'index.php'));
            exit;
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
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="assets/img/devclub-favicon.svg" type="image/svg+xml">
</head>

<body class="bg-light">
    <div class="container" style="max-width:420px; margin-top:6rem;">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><img src="assets/img/devclub-logo.svg" alt="" class="logo me-2">เข้าสู่ระบบ</h5>
            </div>
            <div class="card-body">
                <?php if ($status): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($status) ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="next" value="<?= htmlspecialchars($_GET['next'] ?? '') ?>">
                    <div class="mb-3">
                        <label class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">รหัสผ่าน</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="create_admin.php" class="btn btn-link">สร้างผู้ดูแลระบบ</a>
                        <button class="btn btn-primary">เข้าสู่ระบบ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
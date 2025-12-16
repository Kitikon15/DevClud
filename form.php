<?php
require_once 'auth.php';
require_login();

// ตรวจสอบว่าเป็นการแก้ไขหรือไม่
$id = isset($_GET['id']) ? $_GET['id'] : null;
$member = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
    $stmt->execute([$id]);
    $member = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'แก้ไขข้อมูล' : 'เพิ่มสมาชิกใหม่'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="assets/img/devclub-favicon.svg" type="image/svg+xml">
</head>

<body class="bg-light">
    <?php $display = $_SESSION['display_name'] ?? $_SESSION['username'] ?? 'ผู้ใช้'; ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-3">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php"><img
                    src="assets/img/devclub-logo.svg" alt="DevClub" class="logo"> <span
                    class="brand-text">DevClub</span></a>
            <div class="d-flex align-items-center">
                <span class="me-3 text-muted">สวัสดี, <?= htmlspecialchars($display); ?></span>
                <a href="logout.php" class="btn btn-outline-secondary btn-sm">ออกจากระบบ</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5" style="max-width: 600px;">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">
                    <?= $id ? '<img src="assets/img/devclub-logo.svg" alt="" class="logo me-2">✏️ แก้ไขข้อมูลสมาชิก' : '<img src="assets/img/devclub-logo.svg" alt="" class="logo me-2">➕ เพิ่มสมาชิกใหม่'; ?>
                </h4>
            </div>
            <div class="card-body">
                <form action="save.php" method="POST">
                    <input type="hidden" name="id" value="<?= $member['id'] ?? ''; ?>">

                    <div class="mb-3">
                        <label class="form-label">ชื่อ-นามสกุล</label>
                        <input type="text" name="fullname" class="form-control" required
                            value="<?= $member['fullname'] ?? ''; ?>" placeholder="เช่น สมชาย ใจดี">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">อีเมล</label>
                        <input type="email" name="email" class="form-control" required
                            value="<?= $member['email'] ?? ''; ?>" placeholder="name@example.com">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">สาขาวิชา</label>
                            <select name="major" class="form-select" required>
                                <option value="">-- เลือกสาขา --</option>
                                <?php
                                $majors = ['วิศวกรรมซอฟต์แวร์', 'วิทยาการคอมพิวเตอร์', 'เทคโนโลยีสารสนเทศ'];
                                foreach ($majors as $m) {
                                    $selected = ($member['major'] ?? '') == $m ? 'selected' : '';
                                    echo "<option value='$m' $selected>$m</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ปีการศึกษา (พ.ศ.)</label>
                            <input type="number" name="academic_year" class="form-control" required
                                value="<?= $member['academic_year'] ?? date('Y') + 543; ?>">
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-secondary">ยกเลิก</a>
                        <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
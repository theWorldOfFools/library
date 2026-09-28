<?php
// admin/login.php
session_start();
require_once __DIR__ . '/config.php';
if (isset($_SESSION['admin_id'])) {
    redirect('index');
}
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $err = 'Username dan password wajib diisi.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT id, username, password_hash, nama, role FROM admin WHERE username=? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        if ($row && password_verify($password, $row['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            $_SESSION['admin_nama'] = $row['nama'];
            $_SESSION['admin_role'] = $row['role'] ?? 'admin';
            $_SESSION['admin_last'] = time();
            if (password_needs_rehash($row['password_hash'], PASSWORD_DEFAULT)) {
                $new = password_hash($password, PASSWORD_DEFAULT);
                $u = mysqli_prepare($conn, 'UPDATE admin SET password_hash=? WHERE id=?');
                mysqli_stmt_bind_param($u, 'si', $new, $row['id']);
                mysqli_stmt_execute($u);
                mysqli_stmt_close($u);
            }
            redirect('index');
        } else {
            $err = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin — E-Book</title>
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../fontawesome/css/all.min.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="login-bg">
<div class="login-card">
  <div class="login-top">
    <div class="mark"><i class="fas fa-book-open"></i></div>
    <h4 class="mb-1" style="font-weight:800">Admin E-Book</h4>
    <div style="color:#e0e7ff;font-size:13px">Kelola dokumen, kategori & user katalog</div>
  </div>
  <div class="login-body">
    <?php if ($err): ?><div class="alert alert-danger py-2" style="border-radius:12px"><?= e($err) ?></div><?php endif; ?>
    <form method="POST" action="">
      <div class="form-group">
        <label class="small font-weight-bold">USERNAME</label>
        <div class="input-icon"><i class="fas fa-user"></i><input class="form-control" name="username" required autofocus placeholder="mis. admin" value="<?= e($_POST['username'] ?? '') ?>"></div>
      </div>
      <div class="form-group">
        <label class="small font-weight-bold">PASSWORD</label>
        <div class="input-icon"><i class="fas fa-lock"></i><input class="form-control" type="password" name="password" required placeholder="••••••••"></div>
      </div>
      <button class="btn btn-primary btn-block btn-login" type="submit"><i class="fas fa-sign-in-alt mr-1"></i> Masuk Dashboard</button>
    </form>
    <div class="d-flex justify-content-between mt-3">
      <a href="../folder" class="small">&larr; Kembali ke katalog</a>
      <span class="small text-muted">default: admin / admin</span>
    </div>
  </div>
</div>
</body>
</html>

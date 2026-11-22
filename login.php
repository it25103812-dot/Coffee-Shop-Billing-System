<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
if (!empty($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $stmt = $pdo->prepare('SELECT id,full_name,password_hash,role FROM users WHERE email=? LIMIT 1');
    $stmt->execute([strtolower(trim($_POST['email'] ?? ''))]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Incorrect email or password.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sign in | Brew & Bean</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="auth-page"><section class="auth-card"><div class="logo-large">☕</div><span class="eyebrow">BREW & BEAN POS</span><h1>Welcome back</h1><p>Sign in to manage your coffee shop.</p>
<?php if ($error): ?><div class="error"><?= h($error) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><label>Email address</label><input type="email" name="email" autocomplete="username" required><label>Password</label><input type="password" name="password" autocomplete="current-password" required><button class="btn full">Sign in</button></form><p class="muted small">First time? Open <a href="setup_admin.php">initial admin setup</a>.</p></section></body></html>

<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
$count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $count === 0) {
    verify_csrf();
    $name = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $message = 'Enter a name, valid email, and password with at least 8 characters.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO users (full_name,email,password_hash,role) VALUES (?,?,?,?)');
        $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),'admin']);
        $message = 'Admin account created. Delete setup_admin.php now, then sign in.';
        $count = 1;
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Initial setup</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="auth-page"><section class="auth-card"><div class="logo-large">☕</div><h1>Initial admin setup</h1>
<?php if ($message): ?><div class="notice"><?= h($message) ?></div><?php endif; ?>
<?php if ($count > 0): ?><p>An account already exists. For security, remove <code>setup_admin.php</code> from your project.</p><a class="btn" href="login.php">Go to login</a>
<?php else: ?><p>Create the first administrator account.</p><form method="post"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><label>Full name</label><input name="full_name" required><label>Email</label><input type="email" name="email" required><label>Password (8+ characters)</label><input type="password" name="password" minlength="8" required><button class="btn full">Create administrator</button></form><?php endif; ?></section></body></html>

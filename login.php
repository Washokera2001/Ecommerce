<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../config/admin.php';

if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: products.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === $adminUsername && $password === $adminPassword) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $adminUsername;

        header('Location: products.php');
        exit;
    }

    $error = 'Username or password is incorrect.';
}

include '../includes/header.php';
?>

<h1>Admin Login</h1>

<?php if ($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form class="form-box login-box" method="post">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>

    <button type="submit">Login</button>
</form>

<?php include '../includes/footer.php'; ?>

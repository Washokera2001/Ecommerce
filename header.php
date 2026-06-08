<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$basePath = '';
if (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false || strpos($_SERVER['SCRIPT_NAME'], '\\admin\\') !== false) {
    $basePath = '../';
}

$cartCount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gau Shop</title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="logo" href="<?php echo $basePath; ?>index.php">Gau Shop</a>
            <nav>
                <a href="<?php echo $basePath; ?>index.php">Products</a>
                <a href="<?php echo $basePath; ?>cart.php">Cart (<?php echo $cartCount; ?>)</a>
                <?php if (!empty($_SESSION['admin_logged_in'])): ?>
                    <a href="<?php echo $basePath; ?>admin/products.php">Admin</a>
                    <a href="<?php echo $basePath; ?>admin/logout.php">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $basePath; ?>admin/login.php">Admin Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container">

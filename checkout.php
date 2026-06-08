<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config/database.php';

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($name === '' || $email === '' || $phone === '' || $address === '') {
        $error = 'Please fill in all required information.';
    } else {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$name, $email, $phone, $address, $total]);
        $orderId = $pdo->lastInsertId();

        $itemStmt = $pdo->prepare(
            'INSERT INTO order_items (order_id, product_id, quantity, price)
             VALUES (?, ?, ?, ?)'
        );
        $stockStmt = $pdo->prepare(
            'UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?'
        );

        foreach ($_SESSION['cart'] as $item) {
            $itemStmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
            $stockStmt->execute([$item['quantity'], $item['id'], $item['quantity']]);
        }

        $pdo->commit();
        $_SESSION['cart'] = [];

        header('Location: order_success.php?id=' . $orderId);
        exit;
    }
}

include 'includes/header.php';
?>

<h1>Checkout</h1>

<?php if ($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form class="form-box" method="post">
    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" required>
    </div>

    <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address" required></textarea>
    </div>

    <button type="submit">Place Order</button>
</form>

<?php include 'includes/footer.php'; ?>

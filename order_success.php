<?php
include 'includes/header.php';
$orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
?>

<div class="notice">
    <h1>Order Received!</h1>
    <p>Thank you for your purchase. Your order number is #<?php echo $orderId; ?>.</p>
</div>

<a class="btn" href="index.php">Back to Shop</a>

<?php include 'includes/footer.php'; ?>

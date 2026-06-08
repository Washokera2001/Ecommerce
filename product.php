<?php
require 'config/database.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php if (!$product): ?>
    <div class="error">Product not found.</div>
<?php else: ?>
    <section class="grid">
        <div class="card">
            <div class="product-image">
                <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </div>
        </div>
        <div>
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="price">TZS <?php echo number_format($product['price'], 2); ?></p>
            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            <p class="muted">Stock: <?php echo (int) $product['stock']; ?></p>
            <div class="actions">
                <a class="btn" href="cart.php?action=add&id=<?php echo $product['id']; ?>">Add to Cart</a>
                <a class="btn secondary" href="index.php">Back</a>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

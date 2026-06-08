<?php
require 'config/database.php';
include 'includes/header.php';

$stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="hero">
    <h1>Welcome to Gau Shop</h1>
    <p>Buy your products easily in our online store.</p>
</section>

<section class="grid">
    <?php foreach ($products as $product): ?>
        <article class="card">
            <div class="product-image">
                <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </div>
            <div class="card-body">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="muted"><?php echo htmlspecialchars(substr($product['description'], 0, 80)); ?>...</p>
                <p class="price">TZS <?php echo number_format($product['price'], 2); ?></p>
                <p class="muted">Stock: <?php echo (int) $product['stock']; ?></p>
                <div class="actions">
                    <a class="btn" href="product.php?id=<?php echo $product['id']; ?>">View</a>
                    <a class="btn secondary" href="cart.php?action=add&id=<?php echo $product['id']; ?>">Add to Cart</a>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<?php include 'includes/footer.php'; ?>

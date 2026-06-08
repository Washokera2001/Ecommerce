<?php
require 'auth.php';
require '../config/database.php';
include '../includes/header.php';

$stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Admin - Products</h1>

<div class="actions">
    <a class="btn" href="product_form.php">Add Product</a>
    <a class="btn secondary" href="orders.php">View Orders</a>
    <a class="btn secondary" href="../index.php">Back to Shop</a>
    <a class="btn danger" href="logout.php">Logout</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>TZS <?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo (int) $product['stock']; ?></td>
                    <td>
                        <a class="btn secondary" href="product_form.php?id=<?php echo $product['id']; ?>">Edit</a>
                        <a class="btn danger" href="delete_product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>

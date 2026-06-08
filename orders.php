<?php
require 'auth.php';
require '../config/database.php';
include '../includes/header.php';

$stmt = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC');
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$itemStmt = $pdo->query(
    'SELECT oi.*, p.name AS product_name FROM order_items oi JOIN products p ON oi.product_id = p.id ORDER BY oi.order_id'
);
$orderItems = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

$itemsByOrder = [];
foreach ($orderItems as $item) {
    $itemsByOrder[$item['order_id']][] = $item;
}
?>

<h1>Admin - Customer Orders</h1>

<div class="actions">
    <a class="btn" href="products.php">Products</a>
    <a class="btn secondary" href="logout.php">Logout</a>
</div>

<?php if (empty($orders)): ?>
    <div class="notice">No orders placed yet.</div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Total</th>
                    <th>Created</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_email']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_phone']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></td>
                        <td>TZS <?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td>
                            <?php if (!empty($itemsByOrder[$order['id']])): ?>
                                <ul class="order-items-list">
                                    <?php foreach ($itemsByOrder[$order['id']] as $item): ?>
                                        <li><?php echo htmlspecialchars($item['product_name']); ?> x <?php echo (int) $item['quantity']; ?> (TZS <?php echo number_format($item['price'], 2); ?>)</li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                No items
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
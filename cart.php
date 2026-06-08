<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config/database.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($action === 'add' && $id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product && $product['stock'] > 0) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
    }

    header('Location: cart.php');
    exit;
}

if ($action === 'remove' && $id > 0) {
    unset($_SESSION['cart'][$id]);
    header('Location: cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantities'] as $productId => $quantity) {
        $productId = (int) $productId;
        $quantity = max(1, (int) $quantity);

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        }
    }

    header('Location: cart.php');
    exit;
}

$total = 0;
include 'includes/header.php';
?>

<h1>Shopping Cart</h1>

<?php if (empty($_SESSION['cart'])): ?>
    <div class="notice">Your cart is empty.</div>
    <a class="btn" href="index.php">Continue Shopping</a>
<?php else: ?>
    <form method="post">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <?php
                        $subTotal = $item['price'] * $item['quantity'];
                        $total += $subTotal;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>TZS <?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input type="number" name="quantities[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                            </td>
                            <td>TZS <?php echo number_format($subTotal, 2); ?></td>
                            <td><a class="btn danger" href="cart.php?action=remove&id=<?php echo $item['id']; ?>">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2>Total: TZS <?php echo number_format($total, 2); ?></h2>
        <div class="actions">
            <button type="submit">Update Cart</button>
            <a class="btn secondary" href="checkout.php">Checkout</a>
            <a class="btn secondary" href="index.php">Continue Shopping</a>
        </div>
    </form>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

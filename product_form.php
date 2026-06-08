<?php
require 'auth.php';
require '../config/database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = [
    'name' => '',
    'description' => '',
    'price' => '',
    'image' => '',
    'stock' => ''
];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die('Product not found.');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float) $_POST['price'];
    $image = $product['image'];
    $stock = (int) $_POST['stock'];

    if (!empty($_FILES['image']['name'])) {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $originalName = $_FILES['image']['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            $error = 'Image must be JPG, JPEG, or PNG only.';
        } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Image upload failed. Please try again.';
        } else {
            $uploadFolder = '../assets/uploads/';

            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder, 0777, true);
            }

            $newFileName = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            $uploadPath = $uploadFolder . $newFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image = 'assets/uploads/' . $newFileName;
            } else {
                $error = 'Image could not be saved to uploads folder.';
            }
        }
    }

    if ($error === '') {
        if ($id > 0) {
            $stmt = $pdo->prepare(
                'UPDATE products SET name = ?, description = ?, price = ?, image = ?, stock = ? WHERE id = ?'
            );
            $stmt->execute([$name, $description, $price, $image, $stock, $id]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO products (name, description, price, image, stock) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$name, $description, $price, $image, $stock]);
        }

        header('Location: products.php');
        exit;
    }

    $product = [
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'image' => $image,
        'stock' => $stock
    ];
}

include '../includes/header.php';
?>

<h1><?php echo $id > 0 ? 'Edit Product' : 'Add Product'; ?></h1>

<?php if ($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form class="form-box" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Product Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
    </div>

    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
    </div>

    <?php if (!empty($product['image'])): ?>
        <div class="form-group">
            <label>Current Image</label>
            <div class="admin-image-preview">
                <img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="image">Product Image</label>
        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
        <p class="muted">Select a JPG, JPEG, or PNG image.</p>
    </div>

    <div class="form-group">
        <label for="stock">Stock</label>
        <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
    </div>

    <button type="submit">Save</button>
    <a class="btn secondary" href="products.php">Cancel</a>
</form>

<?php include '../includes/footer.php'; ?>

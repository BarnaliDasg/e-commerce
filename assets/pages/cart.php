<?php

include_once __DIR__ . '/../php/configu.php';
$db = getDbConnection();

$userId = $_SESSION['userdata']['id'];  // Assume user is logged in and session data exists

// Fetch cart items joined with product details
$product_query = "
    SELECT p.id, p.name, p.price, p.image_url, c.quantity 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
";

$stmt = $db->prepare($product_query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$product_result = $stmt->get_result();
?>

<h1 class="text-center mb-4">Your Cart Products</h1>

<div class="product-grid">
    <?php if ($product_result && $product_result->num_rows > 0): ?>
        <?php while ($row = $product_result->fetch_assoc()): ?>
            <div class="product-card">
                <img class="product-image" src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <div class="product-info">
                    <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
                    <div class="product-price">₹<?php echo htmlspecialchars($row['price']); ?></div>
                    <div class="product-quantity">Quantity: <?php echo htmlspecialchars($row['quantity']); ?></div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">No products found in your cart.</p>
    <?php endif; ?>
</div>

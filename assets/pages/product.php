<?php
// Connect to DB
include_once __DIR__ . '/../php/configu.php';
$db = getDbConnection();

// Fetch slides
$slide_query = "SELECT * FROM slides";
$slide_result = mysqli_query($db, $slide_query);

// Fetch products
$product_query = "SELECT * FROM products";
$product_result = mysqli_query($db, $product_query);
?>

<!-- Carousel Section -->
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="height: 200px;">
  <div class="carousel-indicators">
    <?php
    $i = 0;
    foreach ($slide_result as $row) {
        $active = ($i === 0) ? 'active' : '';
        echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="' . $i . '" class="' . $active . '" aria-label="Slide ' . ($i + 1) . '"></button>';
        $i++;
    }
    mysqli_data_seek($slide_result, 0); // Reset pointer for reuse
    ?>
  </div>

  <div class="carousel-inner">
    <?php
    $i = 0;
    while ($row = mysqli_fetch_assoc($slide_result)) {
        $active = ($i === 0) ? 'active' : '';
        echo '<div class="carousel-item ' . $active . '">';
        echo '<img src="' . htmlspecialchars($row['image']) . '" class="d-block w-100 slide-image" alt="Slide">';
        echo '</div>';
        $i++;
    }
    ?>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- Product Section -->
<h1 class="text-center mb-4">Our Products</h1>
    <div class="product-grid">
        <?php if ($product_result && mysqli_num_rows($product_result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($product_result)): ?>
                <div class="product-card">
                    <!-- Use the image_url as is, no extra ../uploads/ prefix -->
                    <img style="width: 325px; height: 400px; object-fit: cover;" class="product-image" src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <div class="product-info">
                        <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
                        <div class="product-price">₹<?php echo htmlspecialchars($row['price']); ?></div>
                        <button class="addcart-btn" data-product-id="<?= $row['id']; ?>">Add to Cart</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No products found.</p>
        <?php endif; ?>
    </div>


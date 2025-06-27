<?php
/**
 * Product card partial
 * 
 * @var object $product - Product data object
 * @var bool $showCategory - Whether to show category name
 * @var bool $showDescription - Whether to show product description
 * @var bool $showAction - Whether to show action buttons
 */

// Set default values
$showCategory = $showCategory ?? true;
$showDescription = $showDescription ?? false;
$showAction = $showAction ?? true;

// Get first product image
$images = !empty($product->images) ? json_decode($product->images, true) : [];
$mainImage = !empty($images[0]) ? $images[0] : '/assets/images/placeholder-product.jpg';

// Format price
$price = number_format($product->price, 0, ',', '.');
$originalPrice = $product->original_price > $product->price 
    ? number_format($product->original_price, 0, ',', '.') 
    : null;

// Calculate discount percentage
$discount = null;
if ($product->original_price > $product->price) {
    $discount = round((($product->original_price - $product->price) / $product->original_price) * 100);
}
?>

<div class="product-card h-100 d-flex flex-column">
    <!-- Product Image -->
    <div class="product-img-container position-relative">
        <a href="/product/<?= htmlspecialchars($product->slug) ?>">
            <img src="<?= htmlspecialchars($mainImage) ?>" class="product-img" alt="<?= htmlspecialchars($product->name) ?>">
        </a>
        
        <!-- Product Labels -->
        <div class="position-absolute top-0 start-0 p-2">
            <?php if ($product->is_new): ?>
                <span class="badge bg-success me-1">Mới</span>
            <?php endif; ?>
            <?php if ($discount): ?>
                <span class="badge bg-danger">-<?= $discount ?>%</span>
            <?php endif; ?>
        </div>
        
        <!-- Wishlist Button -->
        <button type="button" class="btn btn-sm p-0 border-0 bg-transparent position-absolute top-0 end-0 m-2 add-to-wishlist" 
                data-product-id="<?= $product->id ?>"
                data-bs-toggle="tooltip" 
                data-bs-placement="left" 
                title="Thêm vào yêu thích">
            <i class="bi bi-heart"></i>
        </button>
        
        <!-- Quick View Button (shown on hover) -->
        <div class="position-absolute w-100 text-center">
            <a href="#" 
               class="btn btn-sm btn-primary btn-quick-view quick-view" 
               data-product-id="<?= $product->id ?>"
               data-bs-toggle="modal" 
               data-bs-target="#quickViewModal">
                <i class="bi bi-eye me-1"></i> Xem nhanh
            </a>
        </div>
    </div>
    
    <!-- Product Body -->
    <div class="product-card-body flex-grow-1 d-flex flex-column">
        <?php if ($showCategory && !empty($product->category_name)): ?>
            <a href="/category/<?= htmlspecialchars($product->category_slug ?? '') ?>" class="product-category text-muted small">
                <?= htmlspecialchars($product->category_name) ?>
            </a>
        <?php endif; ?>
        
        <h3 class="product-title">
            <a href="/product/<?= htmlspecialchars($product->slug) ?>" class="text-dark text-decoration-none">
                <?= htmlspecialchars($product->name) ?>
            </a>
        </h3>
        
        <?php if ($showDescription && !empty($product->short_description)): ?>
            <p class="text-muted small mb-2">
                <?= htmlspecialchars($product->short_description) ?>
            </p>
        <?php endif; ?>
        
        <!-- Rating -->
        <div class="product-rating mb-2">
            <?php
            $rating = $product->average_rating ?? 0;
            $fullStars = floor($rating);
            $hasHalfStar = $rating - $fullStars >= 0.5;
            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
            
            // Full stars
            for ($i = 0; $i < $fullStars; $i++) {
                echo '<i class="bi bi-star-fill text-warning"></i>';
            }
            
            // Half star
            if ($hasHalfStar) {
                echo '<i class="bi bi-star-half text-warning"></i>';
            }
            
            // Empty stars
            for ($i = 0; $i < $emptyStars; $i++) {
                echo '<i class="bi bi-star text-warning"></i>';
            }
            
            // Review count if available
            if (isset($product->review_count) && $product->review_count > 0) {
                echo '<span class="product-rating-count small text-muted ms-1">(' . $product->review_count . ')</span>';
            }
            ?>
        </div>
        
        <!-- Price -->
        <div class="product-price mt-auto">
            <span class="fw-bold text-primary"><?= $price ?> ₫</span>
            <?php if ($originalPrice): ?>
                <span class="text-muted text-decoration-line-through ms-2 small"><?= $originalPrice ?> ₫</span>
            <?php endif; ?>
        </div>
        
        <!-- Action Buttons -->
        <?php if ($showAction): ?>
        <div class="d-grid gap-2 mt-3">
            <button type="button" class="btn btn-primary add-to-cart" data-product-id="<?= $product->id ?>">
                <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

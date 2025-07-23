<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>

    <link rel="icon" type="image/png" href="images/sari-sari.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=1.0">
</head>

<body>
    <?php include 'components/user_header.php'; ?>
    
    <div class="filter-section" data-aos="fade-up">
    <div class="filter-container">
        <div class="filter-group">
            <select id="alphabeticalFilter" class="filter-input">
                <option value="">Sort by Name</option>
                <option value="a-z">A to Z</option>
                <option value="z-a">Z to A</option>
            </select>
        </div>
        
        <div class="filter-group">
            <select id="priceFilter" class="filter-input">
                <option value="">Price Range</option>
                <option value="0-100">₱0 - ₱100</option>
                <option value="101-500">₱101 - ₱500</option>
                <option value="501-1000">₱501 - ₱1000</option>
                <option value="1001+">₱1001+</option>
            </select>
        </div>
        
        <div class="filter-group">
            <select id="stockFilter" class="filter-input">
                <option value="">Stock Status</option>
                <option value="in-stock">In Stock</option>
                <option value="out-of-stock">Out of Stock</option>
            </select>
        </div>
        
        <button id="resetFilter" class="filter-reset">
            <i class="fas fa-sync-alt"></i> Reset Filters
        </button>
    </div>
</div>
    <section class="products">
        <h1 class="title" data-aos="fade-up">All Products</h1>
        <div class="box-container" data-aos="fade-up">
            <!-- Display all products -->
            <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();

            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <form action="" method="post" class="box">
                        <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                        <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                        <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                        <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
                        <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>

                        <?php if ($fetch_products['stock'] > 0) { ?>
                            <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
                        <?php } else { ?>
                            <button type="button" class="fas fa-shopping-cart" onclick="showOutOfStockAlert()" title="Out of Stock"></button>
                        <?php } ?>

                        <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                        <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
                        <div class="name"><?= $fetch_products['name']; ?></div>

                        <div class="flex">
                            <div class="price">
                                <span>Php: </span>
                                <?php if ($fetch_products['discount'] > 0): ?>
                                    <span style="text-decoration: line-through; color: red;"><?= $fetch_products['price']; ?></span>
                                    <span> → <?= $fetch_products['discount_price']; ?> </span>
                                <?php else: ?>
                                    <span class="product-price"><?= $fetch_products['price']; ?></span>
                                <?php endif; ?>
                            </div>
                            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2" onchange="updatePrice(this, <?= $fetch_products['price']; ?>)">
                        </div>
                        <div class="stock">
                            <?= $fetch_products['stock'] > 0 ? '<span style="color:green;">In Stock</span>' : '<span style="color:red;">Out of Stock</span>'; ?>
                        </div>
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
            ?>
        </div>
    </section>

    

    <button class="scroll-to-top" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script>
        function updatePrice(input, price) {
            var quantity = input.value;
            var totalPrice = price * quantity;

            var priceElement = input.closest('.box').querySelector('.product-price');
            priceElement.textContent = totalPrice;
        }

        function showOutOfStockAlert() {
            alert("This product is out of stock.");
        }

        AOS.init({
            duration: 1000,
            once: true,
        });
        
        document.addEventListener('DOMContentLoaded', function() {
    const alphabeticalFilter = document.getElementById('alphabeticalFilter');
    const priceFilter = document.getElementById('priceFilter');
    const stockFilter = document.getElementById('stockFilter');
    const resetFilter = document.getElementById('resetFilter');
    const productsContainer = document.querySelector('.box-container');
    
    function getProducts() {
        return Array.from(document.querySelectorAll('.box'));
    }

    function sortProducts() {
        const products = getProducts();
        const sortOrder = alphabeticalFilter.value;
        
        if (sortOrder) {
            products.sort((a, b) => {
                const nameA = a.querySelector('.name').textContent.toLowerCase();
                const nameB = b.querySelector('.name').textContent.toLowerCase();
                
                if (sortOrder === 'a-z') {
                    return nameA.localeCompare(nameB);
                } else {
                    return nameB.localeCompare(nameA);
                }
            });

            // Clear and reappend sorted products
            const container = document.querySelector('.box-container');
            products.forEach(product => container.appendChild(product));
        }
    }

    function filterProducts() {
        const products = getProducts();
        const priceRange = priceFilter.value;
        const stockStatus = stockFilter.value;

        products.forEach(product => {
            const price = parseFloat(product.querySelector('.price').textContent.replace('Php: ', '').replace(',', ''));
            const inStock = product.querySelector('.stock span').textContent === 'In Stock';
            
            let showProduct = true;

            // Price filter
            if (priceRange) {
                const [min, max] = priceRange.split('-').map(num => num.replace('+', ''));
                if (max) {
                    if (price < parseInt(min) || price > parseInt(max)) {
                        showProduct = false;
                    }
                } else {
                    if (price < parseInt(min)) {
                        showProduct = false;
                    }
                }
            }

            // Stock filter
            if (stockStatus) {
                if ((stockStatus === 'in-stock' && !inStock) || 
                    (stockStatus === 'out-of-stock' && inStock)) {
                    showProduct = false;
                }
            }

            product.style.display = showProduct ? '' : 'none';
        });
    }

    alphabeticalFilter.addEventListener('change', function() {
        sortProducts();
        filterProducts(); // Apply any active filters after sorting
    });
    
    priceFilter.addEventListener('change', filterProducts);
    stockFilter.addEventListener('change', filterProducts);
    
    resetFilter.addEventListener('click', function() {
        alphabeticalFilter.value = '';
        priceFilter.value = '';
        stockFilter.value = '';
        
        // Reset display of all products
        getProducts().forEach(product => {
            product.style.display = '';
        });
        
        // Reset the original order
        const products = getProducts();
        products.sort((a, b) => {
            const indexA = parseInt(a.dataset.originalOrder || '0');
            const indexB = parseInt(b.dataset.originalOrder || '0');
            return indexA - indexB;
        });
        
        const container = document.querySelector('.box-container');
        products.forEach(product => container.appendChild(product));
    });

    // Store original order
    getProducts().forEach((product, index) => {
        product.dataset.originalOrder = index;
    });
});

    </script>

    <?php include 'components/footer.php'; ?>
</body>

</html>

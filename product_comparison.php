<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

$product1 = null;
$product2 = null;

if(isset($_GET['product1']) && !empty($_GET['product1'])) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['product1']]);
    $product1 = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(isset($_GET['product2']) && !empty($_GET['product2'])) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['product2']]);
    $product2 = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Products</title>
    
    <link rel="icon" type="image/png" href="images/sari-sari.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">    
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
    <?php include 'components/user_header.php'; ?>
    <h1 class="title" data-aos="fade-up">Compare Products</h1>

    <section class="comparison-container">
    

        <div class="product-selector" data-aos="fade-up">
            <form action="" method="GET" class="selector">
                <label class="select-label">Select First Product</label>
                <select name="product1" required onchange="this.form.submit()">
                    <option value="">Choose a product...</option>
                    <?php
                    $select_products = $conn->prepare("SELECT * FROM products ORDER BY name");
                    $select_products->execute();
                    while($row = $select_products->fetch(PDO::FETCH_ASSOC)) {
                        $selected = (isset($_GET['product1']) && $_GET['product1'] == $row['id']) ? 'selected' : '';
                        echo "<option value='".$row['id']."' ".$selected.">".$row['name']."</option>";
                    }
                    ?>
                </select>
                
                <label class="select-label">Select Second Product</label>
                <select name="product2" required onchange="this.form.submit()">
                    <option value="">Choose a product...</option>
                    <?php
                    $select_products = $conn->prepare("SELECT * FROM products ORDER BY name");
                    $select_products->execute();
                    while($row = $select_products->fetch(PDO::FETCH_ASSOC)) {
                        $selected = (isset($_GET['product2']) && $_GET['product2'] == $row['id']) ? 'selected' : '';
                        echo "<option value='".$row['id']."' ".$selected.">".$row['name']."</option>";
                    }
                    ?>
                </select>
            </form>
        </div>

        <?php if($product1 && $product2): ?>
            <div class="comparison-results" data-aos="fade-up">
                <table class="comparison-table">
                    <tr>
                        <td>Product Image</td>
                        <td><img src="uploaded_img/<?= $product1['image'] ?>" alt="" class="product-image"></td>
                        <td><img src="uploaded_img/<?= $product2['image'] ?>" alt="" class="product-image"></td>
                    </tr>
                    <tr>
                        <td>Product Name</td>
                        <td><?= $product1['name'] ?></td>
                        <td><?= $product2['name'] ?></td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td><?= $product1['category'] ?></td>
                        <td><?= $product2['category'] ?></td>
                    </tr>
                    <tr>
                        <td>Regular Price</td>
                        <td>Php: <?= $product1['price'] ?>
                            <?php if($product1['discount'] > 0): ?>
                                <span class="discount-badge"><?= $product1['discount'] ?>% OFF</span>
                            <?php endif; ?>
                        </td>
                        <td>Php: <?= $product2['price'] ?>
                            <?php if($product2['discount'] > 0): ?>
                                <span class="discount-badge"><?= $product2['discount'] ?>% OFF</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Final Price</td>
                        <td class="<?= ($product1['discount_price'] < $product2['discount_price']) ? 'better-price' : '' ?>">
                            Php: <?= $product1['discount_price'] ?>
                        </td>
                        <td class="<?= ($product2['discount_price'] < $product1['discount_price']) ? 'better-price' : '' ?>">
                            Php: <?= $product2['discount_price'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Availability</td>
                        <td>
                            <span class="stock-status <?= $product1['stock'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                                <?= $product1['stock'] > 0 ? 'In Stock ('.$product1['stock'].')' : 'Out of Stock' ?>
                            </span>
                        </td>
                        <td>
                            <span class="stock-status <?= $product2['stock'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                                <?= $product2['stock'] > 0 ? 'In Stock ('.$product2['stock'].')' : 'Out of Stock' ?>
                            </span>
                        </td>
                    </tr>
                </table>

                <div class="price-difference" data-aos="fade-up">
                    <?php
                    $difference = abs($product1['discount_price'] - $product2['discount_price']);
                    $cheaper = $product1['discount_price'] < $product2['discount_price'] ? $product1['name'] : $product2['name'];
                    if($difference > 0) {
                        echo "Price Difference: ₱" . number_format($difference, 2) . "<br>";
                        echo "<strong>" . $cheaper . "</strong> is cheaper by ₱" . number_format($difference, 2) . "!";
                    } else {
                        echo "Both products have the same price!";
                    }
                    ?>
                </div>

                <div class="button-container">
                <a href="quick_view.php?pid=<?= $product1['id'] ?>" class="compare-btn">
                    <i class="fas fa-eye"></i> View <?= $product1['name'] ?>
                </a>
                <a href="quick_view.php?pid=<?= $product2['id'] ?>" class="compare-btn">
                    <i class="fas fa-eye"></i> View <?= $product2['name'] ?>
                </a>
            </div>
            </div>
        <?php endif; ?>
    </section>

    <?php include 'components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>
<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Featured Products</title>

   <link rel="icon" type="image/png" href="images/sari-sari.png">
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
   <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css?v=1.0">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="products">

        <h1 class="title" data-aos="fade-up">All Products</h1>

        <div class="box-container" data-aos="fade-up">

            <?php
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE `discount` = 0");
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
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>      
        </div>
    </section>

<button class="scroll-to-top" onclick="scrollToTop()">
   <i class="fas fa-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
   // Initialize AOS
   AOS.init({
      duration: 1000,  
      once: true,      
   });

   // Show an alert for out-of-stock items
   function showOutOfStockAlert() {
      alert("This product is out of stock.");
   }

   // Update the price dynamically based on quantity
   function updatePrice(qtyInput, unitPrice) {
      const productBox = qtyInput.closest('.box');
      const priceElement = productBox.querySelector('.product-price');
      const totalPrice = unitPrice * qtyInput.value;
      priceElement.textContent = totalPrice.toFixed(2);
   }

   // Scroll to the top of the page
   function scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
   }
</script>

<?php include 'components/footer.php'; ?>

</body>
</html>

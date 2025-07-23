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
   <title>Sari-Tech - Experience the Taste of the Philippines</title>

   <link rel="icon" type="image/png" href="images/sari-sari.png" style="height: auto; width: auto; vertical-align: middle;">

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

 
   <link rel="stylesheet" href="css/style.css?v=1.0">
</head>

<body>

<?php include 'components/user_header.php'; ?>

<div class="wrapper swiper" data-aos="fade-up">
    <div class="wrapper-inner swiper-wrapper" data-aos="fade-up">
        <div class="slide swiper-slide" data-aos="fade-up">
            <img src="images/promo2.png" class="image">
            <div class="bg-info" data-aos="fade-up">
                <a href="products.php"><button>Shop now!</button></a>
            </div>
        </div>
        <div class="slide swiper-slide" data-aos="fade-up">
            <img src="images/promo4.png" class="image">
            <div class="bg-info" data-aos="fade-up">
                <a href="products.php"><button>Shop now!</button></a>
            </div>
        </div>
        <div class="slide swiper-slide" data-aos="fade-up">
            <img src="images/promo1.png" class="image">
            <div class="bg-info" data-aos="fade-up">
                <a href="products.php"><button>Shop now!</button></a>
            </div>
        </div>
        <div class="slide swiper-slide" data-aos="fade-up">
            <img src="images/promo3.png" class="image">
            <div class="bg-info" data-aos="fade-up">
                <a href="products.php"><button>Shop now!</button></a>
            </div>
        </div>
    </div>
    <div class="swiper-pagination" data-aos="fade-up"></div>
    <div class="swiper-button-prev" data-aos="fade-up"></div>
    <div class="swiper-button-next" data-aos="fade-up"></div>
</div>



<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


<script src="js/script.js"></script>

<div class="category" data-aos="fade-up">
   <div class="box-container">
      <a href="category.php?category=Beverages" class="box">
         <img src="images/drinks.png" alt="">
      </a>

      <a href="category.php?category=Snacks" class="box">
         <img src="images/snack.png" alt="">
      </a>

      <a href="category.php?category=Essentials" class="box">
         <img src="images/must-have.png" alt="">
      </a>

      <a href="category.php?category=Personal Care" class="box">
         <img src="images/personal-care.png" alt="">
      </a>
   </div>
</div>

<section class="products" data-aos="fade-up">
   <h1 class="title">Featured Collection</h1>

   <div class="box-container" data-aos="fade-up">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE `discount` = 0 > 0 LIMIT 6");
         $select_products->execute();

         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <?php 
         if ($fetch_products['stock'] > 0) { ?>
            <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <?php } else { ?>
            <button type="button" class="fas fa-shopping-cart" onclick="showOutOfStockAlert()" title="Out of Stock"></button>
         <?php } ?>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <div class="price"><span>Php: </span><span class="product-price"><?= $fetch_products['price']; ?></span></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2" onchange="updatePrice(this, <?= $fetch_products['price']; ?>)">
         </div>

         <div class="stock-status">
            <?= $fetch_products['stock'] > 0 ? '<span style="color:green;">In Stock</span>' : '<span style="color:red;">Out of Stock</span>'; ?>
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>
   </div>

   <div class="more-btn" >
      <a href="featured_products.php" class="btn">View All</a>
   </div>
</section>

<section class="products" data-aos="fade-up">
   <h1 class="title">Discounted Products</h1>

   <div class="box-container" data-aos="fade-up">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE discount > 0 LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <?php 
         if ($fetch_products['stock'] > 0) { ?>
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
                <span style="text-decoration: line-through; color: red;"> <?= $fetch_products['price']; ?></span>
                <span> → <?= $fetch_products['discount_price']; ?> </span>
            </div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2" onchange="updatePrice(this, <?= $fetch_products['price']; ?>)">
         </div>

         <div class="stock-status">
            <?= $fetch_products['stock'] > 0 ? '<span style="color:green;">In Stock</span>' : '<span style="color:red;">Out of Stock</span>'; ?>
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">No discounted products available!</p>';
         }
      ?>
   </div>

   <div class="more-btn" data-aos="fade-up">
      <a href="discounted products.php" class="btn">View All</a>
   </div>

</section>





   <button class="scroll-to-top" onclick="scrollToTop()">
      <i class="fas fa-arrow-up"></i>
   </button>

   <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<script>
   
   function showOutOfStockAlert() {
    alert("This product is out of stock.");
}

AOS.init({
      duration: 1000,  
      once: true,      
   });
</script>

<?php include 'components/footer.php'; ?>

</body>
</html>

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
   <title>Quick View Section</title>

   <link rel="icon" type="image/png" href="images/sari-sari.png">
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
   <link rel="stylesheet" href="css/style.css?v=1.0">

</head>
<body>


<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <h1 class="title" data-aos="fade-up">Quick View </h1>

   <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$pid]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box" data-aos="fade-up">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
      <div class="name" data-aos="fade-up"><?= $fetch_products['name']; ?></div>
      <div class="flex" data-aos="fade-up">
         <div class="price"><span>Php: </span>
            <?php if ($fetch_products['discount'] > 0): ?>
               <span style="text-decoration: line-through; color: red;"><?= $fetch_products['price']; ?></span>
               <span> → <?= $fetch_products['discount_price']; ?></span>
            <?php else: ?>
               <span class="product-price"><?= $fetch_products['price']; ?></span>
            <?php endif; ?>
         </div>
         <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2" onchange="updatePrice(this, <?= $fetch_products['price']; ?>)">
      </div>
      <div class="stock" data-aos="fade-up">
         <?php
            if ($fetch_products['stock'] > 0) {
                echo '<span style="color:green;">In Stock (' . $fetch_products['stock'] . ' available)</span>';
            } else {
                echo '<span style="color:red;">Out of Stock</span>';
            }
         ?>
      </div>
      <button type="submit" name="add_to_cart" class="cart-btn">Add To Cart</button>
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>

</section>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="js/script.js"></script>
<script>
   function updatePrice(input, price) {
      var quantity = input.value;
      var totalPrice = price * quantity;
      // Update the product price displayed
      var priceElement = input.closest('.box').querySelector('.product-price');
      priceElement.textContent = totalPrice;
   }
   AOS.init({
      duration: 1000,  
      once: true,      
   });
</script>

















<?php include 'components/footer.php'; ?>


<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>




</body>
</html>
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
   <title>Search Section</title>

   <link rel="icon" type="image/png" href="images/sari-sari.png">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

   <link rel="stylesheet" href="css/style.css?v=1.0">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>


<section class="search-form" data-aos="fade-up">
   <form method="post" action="">
      <input type="text" name="search_box" placeholder="Search here..." class="box">
      <button type="submit" name="search_btn" class="fas fa-search"></button>
   </form>
</section>


<section class="products" style="min-height: 100vh; padding-top:0;">

<div class="box-container" data-aos="fade-up">

      <?php
         if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
         $search_box = $_POST['search_box'];
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ? AND (stock > 0 OR discount > 0)");
         $select_products->execute(["%{$search_box}%"]);

         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
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
         <div class="name" data-aos="fade-up"><?= $fetch_products['name']; ?></div>
         <div class="flex" data-aos="fade-up">
            <div class="price"  data-aos="fade-up">
               <span>Php: </span>
               <?php if ($fetch_products['discount'] > 0) { ?>
                  <span style="text-decoration: line-through; color: red;"> <?= $fetch_products['price']; ?></span>
                  <span> → <?= $fetch_products['discount_price']; ?> </span>
               <?php } else { ?>
                  <span class="product-price"><?= $fetch_products['price']; ?></span>
               <?php } ?>
            </div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2" onchange="updatePrice(this, <?= $fetch_products['price']; ?>)">
         </div>
         <div class="stock"  data-aos="fade-up">
            <?= $fetch_products['stock'] > 0 ? '<span style="color:green;">In Stock</span>' : '<span style="color:red;">Out of Stock</span>'; ?>
         </div>
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">no products found!</p>';
         }
      }
      ?>

</div>

</section>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="js/script.js"></script>
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
</script>




</body>
</html>

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
   <title>Category Section</title>
   <link rel="icon" type="images/png" href="images/sari-sari.png">
   <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="title" data-aos="fade-up">Food Category</h1>

   <div class="box-container" data-aos="fade-up">

      <?php
         $category = $_GET['category'];
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
         $select_products->execute([$category]);
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
               $has_discount = ($fetch_products['discount'] > 0);
               $in_stock = ($fetch_products['stock'] > 0);
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <div class="name" data-aos="fade-up"><?= $fetch_products['name']; ?></div>
         <div class="flex" data-aos="fade-up">
            <div class="price" data-aos="fade-up">
               <span>Php: </span>
               <?php if ($has_discount): ?>
                  <span style="text-decoration: line-through; color: red;"><?= $fetch_products['price']; ?></span>
                  <span> → <?= $fetch_products['discount_price']; ?> </span>
               <?php else: ?>
                  <span><?= $fetch_products['price']; ?></span>
               <?php endif; ?>
            </div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2" onchange="updatePrice(this, <?= $fetch_products['price']; ?>)">
         </div>
         <div class="stock" data-aos="fade-up">
            <?php
               if ($in_stock) {
                  echo '<span style="color:green;">In Stock (' . $fetch_products['stock'] . ' available)</span>';
               } else {
                  echo '<span style="color:red;">Out of Stock</span>';
               }
            ?>
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">No products added yet!</p>';
         }
      ?>

   </div>

</section>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
   function updatePrice(input, price) {
      var quantity = input.value;
      var totalPrice = price * quantity;
      var priceElement = input.closest('.box').querySelector('.product-price');
      priceElement.textContent = totalPrice;
   }
   AOS.init({
      duration: 1000,  
      once: true,      
   });
</script>

<?php include 'components/footer.php'; ?>



</body>
</html>

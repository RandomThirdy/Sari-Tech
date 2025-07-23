<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
   echo "<script>alert('Cart item deleted!');</script>";
}

if(isset($_POST['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   echo "<script>alert('Deleted all from cart!');</script>";
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   echo "<script>alert('Cart quantity updated');</script>";
}

$grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart Section</title>

   <link rel="icon" type="images/png" href="images/sari-sari.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">
   <h1 class="title" data-aos="fade-up">Your Cart</h1>

   <div class="box-container" data-aos="fade-up">
      <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT cart.*, products.price, products.discount, products.discount_price FROM `cart` 
                                       INNER JOIN `products` ON cart.pid = products.id 
                                       WHERE cart.user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $product_price = ($fetch_cart['discount'] > 0) ? $fetch_cart['discount_price'] : $fetch_cart['price'];
               $product_has_discount = $fetch_cart['discount'] > 0 ? true : false;
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('delete this item?');"></button>
         <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
         <div class="name"><?= $fetch_cart['name']; ?></div>
         <div class="flex">
            <div class="price">
               <span>Php: </span><span class="price-value"><?= $product_price; ?></span>
               <?php if ($product_has_discount): ?>
                  <span style="color: red;">(Discounted)</span>
               <?php endif; ?>
            </div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" maxlength="2" onchange="updatePrice(this, <?= $product_price; ?>)">
            <button type="submit" class="fas fa-edit" name="update_qty"></button>
         </div>
      </form>
      <?php
               $grand_total += ($product_price * $fetch_cart['quantity']);
            }
         } else {
            echo '<p class="empty">your cart is empty</p>';
         }
      ?>
   </div>

   <div class="cart-total" data-aos="fade-up">
      <p>Cart Total : <span>Php: <?= $grand_total; ?></span></p>
      <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" style ="margin-top:18px; margin-right:25px;">Proceed To Checkout</a>

      </div>

   <div class="more-btn" data-aos="fade-up">
      <form action="" method="post">
      <button type="submit" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" name="delete_all" onclick="return confirm('delete all from cart?');" style="font-size: 1.4rem;">Delete All</button>
      </form>
      <a href="products.php" class="btn">Continue Shopping</a>
   </div>

</section>
<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<script>
   function updatePrice(input, price) {
      var quantity = input.value;
      var updatedPrice = price * quantity;
      var priceSpan = input.closest('form').querySelector('.price-value');
      priceSpan.textContent = updatedPrice;
      updateGrandTotal();
   }

   function updateGrandTotal() {
      var total = 0;
      var priceElements = document.querySelectorAll('.price-value');
      priceElements.forEach(function(priceElement) {
         total += parseFloat(priceElement.textContent);
      });
      document.querySelector('.cart-total span').textContent = 'Php: ' + total;
   }
   AOS.init({
      duration: 1000,  
      once: true,      
   });
</script>

<?php include 'components/footer.php'; ?>

</body>
</html>

<?php

// Check if the form to add an item to the cart has been submitted
if(isset($_POST['add_to_cart'])){

   // If the user is not logged in, redirect them to the login page
   if($user_id == ''){
      header('location:login.php');
   }else{

      // Retrieve and sanitize input data from the form
      $pid = $_POST['pid']; // Product ID
      $pid = filter_var($pid, FILTER_SANITIZE_STRING); // Sanitize the product ID
      $name = $_POST['name']; // Product name
      $name = filter_var($name, FILTER_SANITIZE_STRING); // Sanitize the product name
      $price = $_POST['price']; // Product price
      $price = filter_var($price, FILTER_SANITIZE_STRING); // Sanitize the price
      $image = $_POST['image']; // Product image URL
      $image = filter_var($image, FILTER_SANITIZE_STRING); // Sanitize the image URL
      $qty = $_POST['qty']; // Quantity of the product to be added
      $qty = filter_var($qty, FILTER_SANITIZE_STRING); // Sanitize the quantity

      // Check if the product is already in the cart for this user
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      // If the product is already in the cart, alert the user
      if($check_cart_numbers->rowCount() > 0){
         echo "<script>alert('already added to cart!');</script>";  
      }else{
         // If the product is not in the cart, insert it into the cart table
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         echo "<script>alert('added to cart!');</script>";  // Notify the user that the product was added to the cart
      }

   }

}

?>



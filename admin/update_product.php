<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $discount = $_POST['discount'];
   $discount_price = $price - ($price * ($discount / 100));

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, price = ?, discount = ?, discount_price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $price, $discount, $discount_price, $pid]);

   echo "<script>alert('Product updated successfully!');</script>";

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   if(!empty($image)){
      if($image_size > 2000000){
         echo "<script>alert('Image size is too large!');</script>";
      }else{
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('../uploaded_img/'.$old_image);
         echo "<script>alert('Image updated successfully!');</script>";
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>

   <link rel="icon" type="image/png" href="../images/sari-sari.png">
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>


<section class="update-product">

   <h1 class="heading">Update Product</h1>

   <?php
      // Get the product ID from the URL parameter 'update'
      $update_id = $_GET['update'];
      
      // Check if 'update' parameter is set and is a valid number
      if (!isset($_GET['update']) || !is_numeric($_GET['update'])) {
         // If not, redirect to products page
         header('location:products.php');
         exit;
      }

      // Prepare a query to fetch product details by the ID
      $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $show_products->execute([$update_id]);

      // Check if any product is found
      if($show_products->rowCount() > 0){
         // Loop through the fetched product details
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <!-- Form for updating product details -->
   <form action="" method="POST" enctype="multipart/form-data">
      <!-- Hidden fields to store product ID and old image for reference -->
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">

      <!-- Display current product image -->
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      
      <!-- Field to update the product name -->
      <span>Update Name</span>
      <input type="text" required placeholder="enter product name" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">
      
      <!-- Field to update the product price -->
      <span>Update Price</span>
      <input type="number" min="0" max="9999999999" required placeholder="enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_products['price']; ?>">
      
      <!-- Dropdown to update product category -->
      <span>Update Category</span>
      <select name="category" class="box" required>
         <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
         <option value="Beverages">Beverages</option>
         <option value="Snacks">Snacks</option>
         <option value="Essentials">Essentials</option>
         <option value="Personal Care">Personal Care</option>
      </select>
      
      <!-- Field to update the discount percentage -->
      <span>Update Discount</span>
      <input type="number" name="discount" min="0" max="100" class="box" value="<?= $fetch_products['discount']; ?>" placeholder="Discount %">
      
      <!-- Field to update the product image -->
      <span>Update Image</span>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      
      <!-- Buttons to submit the form or go back -->
      <div class="flex-btn">
         <input type="submit" value="update" class="btn" name="update">
         <a href="products.php" class="option-btn">Go Back</a>
      </div>
   </form>
   <?php
         }
      }else{
         // If no products are found, display a message
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>
</section>



<script src="../js/admin_script.js"></script>

</body>
</html>

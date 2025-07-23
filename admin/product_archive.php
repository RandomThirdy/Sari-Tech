<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
};

if (isset($_GET['restore'])) { // Check if the "restore" button or link was clicked
   $restore_id = $_GET['restore']; // Get the ID of the product to be restored

   // Fetch the product details from the archive table
   $restore_product_details = $conn->prepare("SELECT * FROM `product_archive` WHERE id = ?");
   $restore_product_details->execute([$restore_id]);
   $product_details = $restore_product_details->fetch(PDO::FETCH_ASSOC); // Get the product details as an associative array

   // Check if the product already exists in the main products list
   $check_existing = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $check_existing->execute([$product_details['name']]);

   if ($check_existing->rowCount() > 0) { // If the product is already in the main list, show a message and stop
       echo "<script>alert('Product already exists in the main products list'); window.location.href = 'product_archive.php';</script>";
       exit;
   }

   // Handle cases where discount or discount price might not exist in the archive
   $discount = isset($product_details['discount']) ? $product_details['discount'] : 0;
   $discount_price = isset($product_details['discount_price']) ? $product_details['discount_price'] : 0;

   // Insert the product back into the main products table
   $insert_product = $conn->prepare("INSERT INTO `products` (name, category, price, image, stock, discount, discount_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
   $insert_product->execute([
       $product_details['name'],
       $product_details['category'],
       $product_details['price'],
       $product_details['image'],
       $product_details['stock'],
       $discount, 
       $discount_price 
   ]);

   // Delete the product from the archive table after restoration
   $delete_product_from_archive = $conn->prepare("DELETE FROM `product_archive` WHERE id = ?");
   $delete_product_from_archive->execute([$restore_id]);

   // Let the user know the product was restored and redirect them to the archive page
   echo "<script>alert('Product restored successfully'); window.location.href = 'product_archive.php';</script>";
   exit;
}

if(isset($_GET['delete'])){
   echo "<script>
            if(confirm('Are you sure you want to permanently delete this?')){
               window.location.href = 'product_archive.php?confirm_delete=" . $_GET['delete'] . "';
            } else {
               window.location.href = 'product_archive.php';
            }
         </script>";
   exit;
}

if(isset($_GET['confirm_delete'])){
   $delete_id = $_GET['confirm_delete'];

   $delete_product_permanently = $conn->prepare("DELETE FROM `product_archive` WHERE id = ?");
   $delete_product_permanently->execute([$delete_id]);

   echo "<script>alert('Product permanently deleted'); window.location.href = 'product_archive.php';</script>";
   exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Product Archive</title>
   <link rel="icon" type="image/png" href="../images/sari-sari.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="show-productss" style="padding-top:5;">
<h1 class="heading">Archived Products</h1>
   <div class="box-containers">

   <?php
      $show_archived_products = $conn->prepare("SELECT * FROM `product_archive` ORDER BY date_deleted DESC");
      $show_archived_products->execute();
      if($show_archived_products->rowCount() > 0){
         while($fetch_archived_product = $show_archived_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="boxs">
      <img src="../uploaded_img/<?= $fetch_archived_product['image']; ?>" alt="">
      <div class="flexs">
         <div class="prices"><span>Php: </span><?= $fetch_archived_product['price']; ?><span></span></div>
         <div class="categoryy"><?= $fetch_archived_product['category']; ?></div>
      </div>
      <div class="name"><?= $fetch_archived_product['name']; ?></div>
      <div class="stock">
         <span style="color:red;">Archived on: <?= $fetch_archived_product['date_deleted']; ?></span>
      </div>
      <a href="product_archive.php?restore=<?= $fetch_archived_product['id']; ?>" class="btn">Restore</a>
      <a href="product_archive.php?delete=<?= $fetch_archived_product['id']; ?>" class="btn" style="background-color: red;">Delete Permanently</a>
      <div class="export-button">
    <a href="../ProductArchivePDF.php?export_pdf=1" class="btn btn-primary" style="background-color: #007bff;">Export to PDF</a>
</div>

   </div>
    <button class="scroll-to-top" onclick="scrollToTop()">
      <i class="fas fa-arrow-up"></i>
   </button>
   <?php
         }
      }else{
         echo '<p class="empty">No Archived Products Found!</p>';
      }
   ?>

   </div>
</section>



<script src="../js/admin_script.js"></script>

</body>
</html>

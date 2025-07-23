<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sari-Tech - Experience the Taste of the Philippines</title>

   <link rel="icon" type="image/png" href="../images/sari-sari.png">


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>


<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">

   <div class="box">
      <h3>Welcome!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update_profile.php" class="btn">Update Profile</a>
   </div>

   <div class="box">
   <?php
      $total_sales_day = 0;
      $today = date('Y-m-d');
      
      $select_sales_day = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ? AND DATE(completed_on) = ?");
      $select_sales_day->execute(['completed', $today]);
      
      while($fetch_sales_day = $select_sales_day->fetch(PDO::FETCH_ASSOC)){
         $total_sales_day += $fetch_sales_day['total_price'];
      }
   ?>
   <h3><span>Php: </span><?= number_format($total_sales_day); ?><span></span></h3>
   <p>Total Sales (Today)</p>
   <a href="placed_orders.php" class="btn">See Orders</a>
</div>





<div class="box">
   <?php
      $total_sales_week = 0;
      $week_start = date('Y-m-d', strtotime('monday this week'));
      $week_end = date('Y-m-d', strtotime('sunday this week'));
      $select_sales_week = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ? AND DATE(placed_on) BETWEEN ? AND ?");
      $select_sales_week->execute(['completed', $week_start, $week_end]);
      while($fetch_sales_week = $select_sales_week->fetch(PDO::FETCH_ASSOC)){
         $total_sales_week += $fetch_sales_week['total_price'];
      }
   ?>
   <h3><span>Php: </span><?= $total_sales_week; ?><span></span></h3>
   <p>Total Sales (This Week)</p>
   <a href="placed_orders.php" class="btn">See Orders</a>
</div>

<div class="box">
   <?php
      $total_sales_month = 0;
      $month_start = date('Y-m-01');
      $month_end = date('Y-m-t');
      $select_sales_month = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ? AND DATE(placed_on) BETWEEN ? AND ?");
      $select_sales_month->execute(['completed', $month_start, $month_end]);
      while($fetch_sales_month = $select_sales_month->fetch(PDO::FETCH_ASSOC)){
         $total_sales_month += $fetch_sales_month['total_price'];
      }
   ?>
   <h3><span>Php: </span><?= $total_sales_month; ?><span></span></h3>
   <p>Total Sales (This Month)</p>
   <a href="placed_orders.php" class="btn">See Orders</a>
</div>



   <div class="box">
      <?php
         $total_completes = 0;
         $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_completes->execute(['completed']);
         while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
            $total_completes += $fetch_completes['total_price'];
         }
      ?>
      <h3><span>Php: </span><?= $total_completes; ?><span></span></h3>
      <p>Total Completes</p>
      <a href="placed_orders.php" class="btn">See Orders</a>
   </div>

   <div class="box">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         $numbers_of_orders = $select_orders->rowCount();
      ?>
      <h3><?= $numbers_of_orders; ?></h3>
      <p>Total Orders</p>
      <a href="placed_orders.php" class="btn">See Orders</a>
   </div>

   <div class="box">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $numbers_of_products = $select_products->rowCount();
      ?>
      <h3><?= $numbers_of_products; ?></h3>
      <p>Products Added</p>
      <a href="products.php" class="btn">See Products</a>
   </div>

   <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();
         $numbers_of_users = $select_users->rowCount();
      ?>
      <h3><?= $numbers_of_users; ?></h3>
      <p>Users Accounts</p>
      <a href="users_accounts.php" class="btn">See Users</a>
   </div>

   <div class="box">
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `admin`");
         $select_admins->execute();
         $numbers_of_admins = $select_admins->rowCount();
      ?>
      <h3><?= $numbers_of_admins; ?></h3>
      <p>Admins</p>
      <a href="admin_accounts.php" class="btn">See Admins</a>
   </div>

   <div class="box">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         $numbers_of_messages = $select_messages->rowCount();
      ?>
      <h3><?= $numbers_of_messages; ?></h3>
      <p>New Messages</p>
      <a href="messages.php" class="btn">See Messages</a>
   </div>

   </div>

</section>


<script src="../js/admin_script.js"></script>

</body>
</html>
<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if (isset($_POST['payment_status'])) {
   $payment_status = $_POST['payment_status'];
   $order_id = $_POST['order_id'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ?, completed_on = ? WHERE id = ?");
   $update_status->execute([$payment_status, date('Y-m-d H:i:s'), $order_id]);
   echo "<script>alert('Payment status updated!');</script>";
}


if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}
if(!empty($message)){
   foreach($message as $msg){
      echo '<p class="message">'.$msg.'</p>';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>

   <link rel="icon" type="image/png" href="../images/sari-sari.png">
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>



<section class="placed-orders">

   <h1 class="heading">Placed Orders</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> User Id : <span><?= $fetch_orders['user_id']; ?></span> </p>
      <p> Placed On : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total Products : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total Price : <span>Php: <?= $fetch_orders['total_price']; ?></span> </p>
      <p> Payment Method : <span><?= $fetch_orders['method']; ?></span> </p>
      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="drop-down" required>
            <option value="" selected disabled>Select Payment Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
         </select>
         <div class="flex-btn">
            <input type="submit" value="update" class="btn" name="update_payment">
            <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">Delete</a>
         </div>
      </form>
   </div>

   <button class="scroll-to-top" onclick="scrollToTop()">
      <i class="fas fa-arrow-up"></i>
   </button>
   <?php
      }
   }else{
      echo '<p class="empty">no orders placed yet!</p>';
   }
   ?>

   </div>

</section>


<script src="../js/admin_script.js"></script>

</body>
</html>
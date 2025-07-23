<?php
session_start();
include 'components/connect.php';

if (!isset($_SESSION['payment_details'])) {
   header('location:home.php');
   exit;
}

$payment_details = $_SESSION['payment_details']; // Retrieve payment details from the session

if (isset($_POST['pay_now'])) { // Check if the "Pay Now" button was clicked
   $transaction_id = 'TXN' . uniqid(); // Generate a unique transaction ID
   $payment_status = 'Paid'; // Set the payment status to "Paid"

   // Insert the order details into the orders table
   $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, transaction_id, payment_status) VALUES(?,?,?,?,?,?,?,?,?,?)");
   $insert_order->execute([
      $_SESSION['user_id'], // User ID from the session
      $payment_details['name'], // Name from the payment details
      $payment_details['number'], // Contact number
      $payment_details['email'], // Email address
      $payment_details['method'], // Payment method (e.g., cash, card)
      $payment_details['address'], // Shipping or billing address
      $payment_details['total_products'], // Total number of products
      $payment_details['total_price'], // Total price for the order
      $transaction_id, // Unique transaction ID
      $payment_status // Payment status (Paid)
   ]);

   // Clear the user's cart after a successful payment
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$_SESSION['user_id']]);

   // Remove payment details from the session to clean up
   unset($_SESSION['payment_details']);

   // Redirect the user to the order success page with the transaction ID
   header('location:order_success.php?transaction_id=' . $transaction_id);
   exit; 
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payment Gateway</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <section class="payment">
      <h1>Confirm Your Payment</h1>
      <p>Name: <?= $payment_details['name'] ?></p>
      <p>Phone: <?= $payment_details['number'] ?></p>
      <p>Email: <?= $payment_details['email'] ?></p>
      <p>Total Amount: Php <?= $payment_details['total_price'] ?></p>
      <form action="" method="post">
         <button type="submit" name="pay_now" class="btn">Pay Now</button>
      </form>
   </section>
</body>
</html>

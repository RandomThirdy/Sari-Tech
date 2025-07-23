<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};


if(isset($_POST['submit'])){
   // Collect and sanitize individual address components
   $unit = filter_var($_POST['unit'], FILTER_SANITIZE_STRING);
   $house = filter_var($_POST['house'], FILTER_SANITIZE_STRING);
   $building = filter_var($_POST['building'], FILTER_SANITIZE_STRING);
   $street = filter_var($_POST['street'], FILTER_SANITIZE_STRING);
   $pin_code = filter_var($_POST['pin_code'], FILTER_SANITIZE_NUMBER_INT);

   // Format address
   $address = "House Number: $house, Building: $building, Street Name: $street, Pin Code: $pin_code";
   
   // Update the address in the database
   $update_address = $conn->prepare("UPDATE `users` set address = ? WHERE id = ?");
   $update_address->execute([$address, $user_id]);

   echo "<script>
            alert('Address saved!');
            window.location.href = 'checkout.php';
         </script>";
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Address</title>

   <link rel="icon" type="image/png" href="images/sari-sari.png">
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css?v=1.0">

</head>
<body>
   
<?php include 'components/user_header.php' ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Your Address</h3>
      <input type="text" class="box" placeholder="Unit Number" maxlength="50" name="unit">
      <input type="text" class="box" placeholder="House Number" required maxlength="50" name="house">
      <input type="text" class="box" placeholder="Building Number" maxlength="50" name="building">
      <input type="text" class="box" placeholder="Street Name" required maxlength="50" name="street">
      <input type="number" class="box" placeholder="Pin Code" required max="999999" min="0" maxlength="6" name="pin_code">
      <input type="submit" value="Save Address" name="submit" class="btn">
   </form>

</section>

<?php include 'components/footer.php' ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

</body>
</html>

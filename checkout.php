<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
    exit;
}

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);
    $address = $_POST['address'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];

    $payment_details = isset($_POST['payment_details']) ? filter_var($_POST['payment_details'], FILTER_SANITIZE_STRING) : '';

    $check_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $check_cart->execute([$user_id]);
    
    if ($check_cart->rowCount() > 0) {

        if ($address == '') {
            echo "<script>alert('Please add your address!');</script>";
        } else {
            if ($method === 'sari-tech' && empty($payment_details)) {
                echo "<script>alert('Please enter your Sari-Tech e-wallet payment details!');</script>";
            } else {
                $insert_order = $conn->prepare("INSERT INTO orders(user_id, name, number, email, method, address, total_products, total_price, payment_details, placed_on) VALUES(?,?,?,?,?,?,?,?,?, NOW())");
                $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price, $payment_details]);
    
                $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $delete_cart->execute([$user_id]);
    
                echo "<script>
                    alert('Order placed successfully!');
                    window.location.href = 'receipt.php';
                </script>";
            }
        }
    
    } else {
        echo "<script>alert('Your cart is empty!');</script>";
    }
}

$total_sales = 0;
$select_sales = $conn->prepare("SELECT * FROM orders WHERE payment_status = ?");
$select_sales->execute(['completed']);
while ($fetch_sales = $select_sales->fetch(PDO::FETCH_ASSOC)) {
    $total_sales += $fetch_sales['total_price'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Section</title>

    <link rel="icon" type="image/png" href="images/sari-sari.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="checkout" data-aos="fade-up">

        <h1 class="title" data-aos="fade-up">Order Summary</h1>

        <form action="" method="post">

            <div class="cart-items" data-aos="fade-up">
                <h3>Cart Items</h3>
                <?php
                $grand_total = 0;
                $cart_items = [];
                $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['quantity'] . ') - ';
                        $total_products = implode($cart_items);
                        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                ?>
                        <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">Php: <?= $fetch_cart['price']; ?> (<?= $fetch_cart['quantity']; ?>)</span></p>
                <?php
                    }
                } else {
                    echo '<p class="empty">your cart is empty!</p>';
                }
                ?>
                <p class="grand-total"><span class="name">Grand Total :</span><span class="price">Php: <?= $grand_total; ?></span></p>
                <a href="cart.php" class="btn">View Cart</a>
            </div>

            <input type="hidden" name="total_products" value="<?= $total_products; ?>">
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
            <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
            <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
            <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
            <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

            <div class="user-info" data-aos="fade-up">
                <h3>Your Info</h3>
                <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
                <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
                <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
                <a href="update_profile.php" class="btn">Update Info</a>
                <h3>Delivery Address</h3>
                <p><i class="fas fa-map-marker-alt"></i><span><?php if ($fetch_profile['address'] == '') {echo 'Please Enter your Address';} else {echo $fetch_profile['address'];} ?></span></p>
                <a href="update_address.php" class="btn">Update Address</a>

                <div id="sari-tech-payment" data-aos="fade-up" style="display:none;">
                    <label for="payment-details">Enter Payment Details (e.g., Card/Account Number):</label>
                    <input type="text" name="payment_details" id="payment-details" class="box" placeholder="Enter your Sari-Tech Payment Details">
                </div>

                <select name="method" class="box" required>
                    <option value="" disabled selected>Select Payment Method -</option>
                    <option value="cash on delivery">Cash On Delivery</option>
                    <option value="sari-tech">Sari-Tech Payment</option>
                </select>

                <input type="submit" value="Place Order" class="btn <?php if ($fetch_profile['address'] == '') { echo 'disabled';} ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
            </div>

        </form>

    </section>

    <?php include 'components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script src="js/script.js"></script>
    <script>
        document.querySelector('select[name="method"]').addEventListener('change', function () {
            const paymentDetails = document.getElementById('sari-tech-payment');
            if (this.value === 'sari-tech') {
                paymentDetails.style.display = 'block';
            } else {
                paymentDetails.style.display = 'none';
            }
        });
        AOS.init({
            duration: 1000,
            once: true,
        });
    </script>

</body>

</html>

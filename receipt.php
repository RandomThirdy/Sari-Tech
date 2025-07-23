<?php
include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:home.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$get_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY placed_on DESC");
$get_orders->execute([$user_id]);

if ($get_orders->rowCount() > 0) {
    $orders = $get_orders->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "No orders found!";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipts Section</title>
    <link rel="icon" type="image/png" href="images/sari-sari.png">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .receipt-container {
            max-width: 900px;
            margin: 60px auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 4px solid #f2f2f2;
            padding-bottom: 40px;
            margin-bottom: 40px;
        }

        .receipt-header img {
            width: 120px;
            margin-bottom: 20px;
        }

        .receipt-header h1 {
            margin: 0;
            color: rgba(3, 19, 156, 1);
            font-size: 3rem;
        }

        .receipt-details {
            line-height: 2.5;
            font-size: 1.4rem;
        }

        .receipt-details strong {
            color: #333;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 40px;
        }

        .btn {
            display: inline-block;
            padding: 20px 40px;
            margin: 15px;
            font-size: 18px;
            color: #fff;
            background-color: rgba(3, 19, 156, 1);
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn:hover {
            background-color:rgb(34, 47, 163);
        }

        .btn-secondary {
            background-color: #fed330;
        }

        .btn-secondary:hover {
            background-color:rgb(252, 211, 62);
        }

        .receipt-item {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .receipt-item:last-child {
            margin-bottom: 0;
        }

        .receipt-item:not(:last-child) {
            border-bottom: 2px solid #f2f2f2;
        }
        .scroll-btn {
            position: fixed;
            bottom: 100px;
            right: 100px;
            background-color: #e74c3c;
            color: white;
            border: none;  
            font-size: 2rem;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease-in-out, transform 0.3s ease;
            border-radius: 4px;
            padding: 20px 25px;
        }

        .scroll-btn:hover {
            transform: scale(1.2);
            background-color:rgb(233, 89, 74);
        }

        .scroll-btn.show {
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <img src="images/sari-sari.png" alt="Sari-Tech Logo">
            <h1>Order Receipts</h1>
        </div>

        <?php foreach ($orders as $order): ?> <!-- Loop through each order in the orders array -->
    <div class="receipt-item">
        <div class="receipt-details">
            <!-- Display the customer's name -->
            <p><strong>Name:</strong> <?= htmlspecialchars($order['name']); ?></p> 
            <!-- Display the customer's email -->
            <p><strong>Email:</strong> <?= htmlspecialchars($order['email']); ?></p> 
            <!-- Display the customer's phone number -->
            <p><strong>Phone Number:</strong> <?= htmlspecialchars($order['number']); ?></p> 
            <!-- Display the customer's address -->
            <p><strong>Address:</strong> <?= htmlspecialchars($order['address']); ?></p> 
            <!-- Display the total number of products in the order -->
            <p><strong>Total Products:</strong> <?= htmlspecialchars($order['total_products']); ?></p> 
            <!-- Display the total price of the order -->
            <p><strong>Total Price:</strong> Php <?= htmlspecialchars($order['total_price']); ?></p> 
            <!-- Display the payment method used -->
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['method']); ?></p> 

            <!-- Check if additional payment details exist and display them -->
            <?php if (!empty($order['payment_details'])): ?> 
                <p><strong>Payment Details:</strong> <?= htmlspecialchars($order['payment_details']); ?></p> 
            <?php endif; ?>

            <!-- Display the date the order was placed -->
            <p><strong>Order Date:</strong> <?= htmlspecialchars($order['placed_on']); ?></p> 
        </div>
        <div class="receipt-footer">
            <!-- Provide a link to download the receipt for this order -->
            <a href="download_receipt.php?order_id=<?= $order['id']; ?>" class="btn">Download Receipt</a> 
        </div>
    </div>
    <?php endforeach; ?> <!-- End of the orders loop -->

       
        <button id="scrollToTopBtn" class="scroll-btn">↑</button>

        <div class="receipt-footer">
            <a href="home.php" class="btn btn-secondary">Return to Home</a>
            
        </div>
    </div>
</body>

</html>
<script>
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');

    window.onscroll = function() {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            scrollToTopBtn.classList.add('show');
        } else {
            scrollToTopBtn.classList.remove('show');
        }
    };
    scrollToTopBtn.onclick = function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };
</script>
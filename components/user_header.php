<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { transform: translate(-50%, -100%); }
            to { transform: translate(-50%, 0); }
        }

        .header {
            position: sticky;
            top: 0;
            background: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            z-index: 1000;
        }

        .header .flex {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .header .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            padding: 5px;  /* Added padding around logo */
        }

        .header .logo-img {
            height: 70px;
            width: auto;  /* This ensures the width scales proportionally */
            object-fit: contain;  /* This maintains aspect ratio */
            transition: transform 0.3s ease;
        }

        .header .logo:hover .logo-img {
            transform: scale(1.05);
        }

        .navbar {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .navbar a {
            font-size: 1.8rem;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #1a237e;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar a:hover {
            color: #1a237e;
        }

        .navbar a:hover::after {
            width: 70%;
        }

        .icons {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .icons a, .icons div {
            position: relative;
            font-size: 1.9rem;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f5f5f5;
        }

        .icons a:hover, .icons div:hover {
            background: #1a237e;
            color: white;
            transform: translateY(-2px);
        }

        .icons span {
            position: absolute;
            top: -16px;
            right: -16px;
            background: #1a237e;
            color: white;
            font-size: 1.4rem;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            min-width: 20px;
            text-align: center;
        }

   .header .flex .profile {
   background-color: white;
   box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
   padding: 1.5rem;
   text-align: center;
   position: absolute;
   top: 85%;
   right: 2rem;
   width: 280px;
   border-radius: 8px;
   display: none;
   animation: fadeIn 0.3s linear;
}

.header .flex .profile.active {
   display: block;
}

.header .flex .profile .name {
   font-size: 1.7rem;
   font-weight: bold;
   color: #333;
   margin-bottom: 1rem;
}

.header .flex .profile .flex {
   display: flex;
   gap: 0.5rem;
   justify-content: center;
   margin: 1rem 0;
}

.header .flex .profile .account {
   margin-top: -2rem;
   font-size: 1.4rem;
   color: #666;
}

.header .flex .profile .account a {
   color: #1a237e;
   font-weight: bold;
   font-size: 1.2rem;
   text-decoration: none;
}

.header .flex .profile .account a:hover {
   text-decoration: underline;
}

@keyframes fadeIn {
   0% { opacity: 0; transform: translateY(-10px); }
   100% { opacity: 1; transform: translateY(0); }
}


        .btn, .delete-btn {
            padding: 1rem 5rem;
            border-radius: 6px;
            font-size: 1.7rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-weight: 500;
        }

        .btn {
            background: #1a237e;
            color: white;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        .btn:hover, .delete-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .account {
            margin-top: 1rem;
            font-size: 0.95srem;
            color: #666;
        }

        .account a {
            color: #1a237e;
            font-weight: 600;
            text-decoration: none;
        }

        .account a:hover {
            text-decoration: underline;
        }

        #menu-btn {
            display: none;
        }

        @media (max-width: 991px) {
            .navbar {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid #eee;
                padding: 1rem;
                clip-path: polygon(0 0, 100% 0, 100% 0, 0 0);
                transition: 0.3s ease;
            }

            .navbar.active {
                clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
            }

            .navbar {
                flex-direction: column;
                gap: 1rem;
            }

            .navbar a {
                display: block;
                width: 100%;
                text-align: center;
            }

            #menu-btn {
                display: flex;
            }
        }

        @media (max-width: 450px) {
            .header .flex {
                padding: 1rem;
            }

            .logo-img {
                height: 40px;
            }

            .icons a, .icons div {
                font-size: 1.2rem;
                width: 35px;
                height: 35px;
            }
        }
    </style>
</head>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         
      </div>
      <script>
         setTimeout(function() {
            var message = document.querySelector(".message");
            if(message) {
               message.remove();
            }
         }, 1500); 
      </script>
      ';
   }
}

?>
<div class="top-bar" data-aos="fade-in">
        <div class="contact-info">
            <div class="contact-details">
                <a href="tel:+9385100460" class="contact-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>+9 385 100 460</span>
                </a>
                <a href="mailto:contact@sari-tech.com" class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>contact@sari-tech.com</span>
                </a>
            </div>
            <div class="social-links">
                <a href="https://www.facebook.com/angelo.decatoria.5" class="social-icon facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-icon twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-icon instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </div>
<header class="header">
   <section class="flex">
   <a href="home.php" class="logo">
    <img src="images/sari-sari.png" alt="Sari-Tech Logo" class="logo-img">
</a>

      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="about.php">About</a>
         <a href="products.php">Products</a>
         <a href="orders.php">Orders</a>
         <a href="contact.php">Contact</a>
         <a href="product_comparison.php">Compare</a>
      </nav>

      <div class="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p class="name"><?= $fetch_profile['name']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">profile</a>
            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         </div>
         <p class="account">
            <a href="login.php">Login</a> or
            <a href="register.php">Register</a>
         </p> 
         <?php
            }else{
         ?>
            <p class="name">Please Login First!</p>
            <br>
            <a href="login.php" class="btn">Login</a>
         <?php
          }
         ?>
      </div>
   </section>
</header>

</body>
</html>

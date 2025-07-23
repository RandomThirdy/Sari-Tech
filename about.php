<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us | Sari-Tech</title>

   <link rel="icon" type="images/png" href="images/sari-sari.png">
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
   <link rel="stylesheet" href="css/style.css">

   <style>
   :root {
      --primary: #1a237e;
      --secondary: #303f9f;
      --light: #f5f5f5;
      --dark: #212121;
      --gray: #757575;
   }

   .about {
      padding: 6rem 2rem;
   }

   .about .row {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 4rem;
      align-items: center;
   }

   .about .image {
      position: relative;
      padding: 2rem;
   }

   .about .image::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: var(--primary);
      opacity: 0.1;
      border-radius: 2rem;
      transform: rotate(-3deg);
   }

   .about .image img {
      width: 100%;
      border-radius: 1rem;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
   }

   .about .image:hover img {
      transform: translateY(-10px);
   }

   .about .content {
      padding: 2rem;
   }

   .about .content h3 {
      font-size: 3rem;
      color: var(--primary);
      margin-bottom: 1.5rem;
      position: relative;
      display: inline-block;
   }

   .about .content h3::after {
      content: '';
      position: absolute;
      bottom: -0.5rem;
      left: 0;
      width: 50%;
      height: 3px;
      background: var(--primary);
      border-radius: 2px;
   }

   .about .content p {
      font-size: 1.5rem;
      line-height: 1.8;
      color: var(--gray);
      margin-bottom: 2rem;
   }

   .about .content .btn {
      display: inline-block;
      padding: 1rem 2rem;
      background: var(--primary);
      color: white;
      text-decoration: none;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      font-weight: 500;
      font-size: 1.4rem;
   }

   .about .content .btn:hover {
      background: var(--secondary);
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
   }

   /* Steps-Section */
   .steps {
      padding: 6rem 2rem;
      background: white;
   }

   .steps .title {
      text-align: center;
      font-size: 3rem;
      color: var(--primary);
      margin-bottom: 4rem;
      position: relative;
   }

   .steps .title::after {
      content: '';
      position: absolute;
      bottom: -1rem;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 3px;
      background: var(--primary);
      border-radius: 2px;
   }

   .steps .box-container {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
   }

   .steps .box {
      background: white;
      padding: 3rem 2rem;
      border-radius: 1rem;
      text-align: center;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
   }

   .steps .box::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 0;
      background: var(--primary);
      opacity: 0.05;
      transition: height 0.3s ease;
   }

   .steps .box:hover::before {
      height: 100%;
   }

   .steps .box:hover {
      transform: translateY(-10px);
   }

   .steps .box img {
      height: 120px;
      width: auto;
      margin-bottom: 2rem;
      transition: transform 0.3s ease;
   }

   .steps .box:hover img {
      transform: scale(1.1);
   }

   .steps .box h3 {
      font-size: 1.9rem;
      color: var(--dark);
      margin-bottom: 1rem;
   }

   .steps .box p {
      font-size: 1.4rem;
      line-height: 1.7;
      color: var(--gray);
   }

   @media (max-width: 991px) {
      .about .row {
         grid-template-columns: 1fr;
         text-align: center;
      }
      
      .about .content h3::after {
         left: 50%;
         transform: translateX(-50%);
      }
      
      .steps .box-container {
         grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      }
   }

   @media (max-width: 768px) {
      .about, .steps {
         padding: 3rem 1rem;
      }
      
      .about .content h3 {
         font-size: 2rem;
      }
      
      .steps .title {
         font-size: 2rem;
      }
   }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about" data-aos="fade-up">
   <div class="row">
      <div class="image" data-aos="fade-right" data-aos-delay="200">
         <img src="images/sari-sari.png" alt="Sari-Tech Store">
      </div>
      <div class="content" data-aos="fade-left" data-aos-delay="400">
         <h3>Why Choose Us?</h3>
         <p>Sari-Tech brings everyday essentials to your doorstep—quickly, affordably, and conveniently. Shop with us to enjoy great products, personalized service, and the satisfaction of supporting a local business that cares about its community.</p>
         <a href="products.php" class="btn">Explore Our Products</a>
      </div>
   </div>
</section>

<section class="steps" data-aos="fade-up">
   <h1 class="title">How to Shop with Sari-Tech</h1>
   
   <div class="box-container">
      <div class="box" data-aos="fade-up" data-aos-delay="200">
         <img src="images/order.png" alt="Browse">
         <h3>Browse Our Website</h3>
         <p>Explore a wide range of products tailored to your needs on our user-friendly website.</p>
      </div>

      <div class="box" data-aos="fade-up" data-aos-delay="400">
         <img src="images/paymentss.png" alt="Payment">
         <h3>Add to Cart and Pay</h3>
         <p>Select your desired items, add them to your cart, and proceed with secure payment options.</p>
      </div>

      <div class="box" data-aos="fade-up" data-aos-delay="600">
         <img src="images/grocery-cart.png" alt="Delivery">
         <h3>Receive Your Order</h3>
         <p>Relax as we deliver your order promptly to your doorstep with care and precision.</p>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

<script>
   AOS.init({
      duration: 800,
      offset: 100,
      once: true
   });
</script>

</body>
</html>
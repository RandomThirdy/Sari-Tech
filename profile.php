<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

// Fetch the user profile
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile Section</title>

   <link rel="icon" type="image/png" href="images/sari-sari.png">
      
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
   <link rel="stylesheet" href="css/style.css?v=1.0">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="profile-section" data-aos="fade-up">
   <div class="profile-card">
      <div class="profile-header">
         <img src="images/user-icon.png" alt="Profile Picture" class="profile-image">
         <h2 class="profile-name"><?= $fetch_profile['name']; ?></h2>
      </div>

      <div class="profile-info">
         <div class="info-item" data-aos="fade-right" data-aos-delay="200">
            <i class="fas fa-phone"></i>
            <span><?= $fetch_profile['number']; ?></span>
         </div>

         <div class="info-item" data-aos="fade-right" data-aos-delay="300">
            <i class="fas fa-envelope"></i>
            <span><?= $fetch_profile['email']; ?></span>
         </div>

         <div class="info-item" data-aos="fade-right" data-aos-delay="400">
            <i class="fas fa-map-marker-alt"></i>
            <span>
               <?php 
                  if($fetch_profile['address'] == '') {
                     echo 'Please enter your address';
                  } else {
                     echo nl2br($fetch_profile['address']);
                  }
               ?>
            </span>
         </div>
      </div>

      <div class="profile-actions">
         <a href="update_profile.php" class="action-btn primary-btn" data-aos="fade-up" data-aos-delay="500">
            <i class="fas fa-user-edit"></i> Update Profile
         </a>
         <a href="update_address.php" class="action-btn secondary-btn" data-aos="fade-up" data-aos-delay="600">
            <i class="fas fa-map-marked-alt"></i> Update Address
         </a>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<script>
 AOS.init({
      duration: 1000,  
      once: true,      
   });
</script>



</body>
</html>

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
         }, 00); // Message disappears after 3 seconds
      </script>
      ';
   }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<header class="header">
   <div class="sidebar">
      <a href="dashboard.php" class="logo">
         <img src="../images/sari-sari.png" alt="Sari-Tech Logo" class="logo-img">
      </a>
      <nav class="navbar">
         <a href="products.php">
            <i class='bx bx-basket' ></i>Products          
         <a href="placed_orders.php">
            <i class='bx bx-cart-add' ></i>Orders
         </a>
         <a href="admin_accounts.php">
            <i class='bx bxs-user-detail' ></i>Admins
         </a>
         <a href="users_accounts.php">
            <i class='bx bxs-user-account' ></i>Users
         </a>
         <a href="messages.php">
            <i class='bx bx-chat' ></i>Messages
         </a>
         <a href="product_archive.php">
          <i class='bx bx-archive' ></i>Archived Products
         </a>
         
      </nav>
      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="profile-info">
            <i class="fas fa-user-circle"></i>
            <p><?= $fetch_profile['name']; ?></p>
         </div>
         <a href="update_profile.php" class="btn"><i class="fas fa-edit"></i> Update Profile</a>
         <div class="flex-btn">
            <a href="admin_login.php" class="option-btn"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="register_admin.php" class="option-btn"><i class="fas fa-user-plus"></i> Register</a>
         </div>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
         </a>
      </div>
   </div>
</header>

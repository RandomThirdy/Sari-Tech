<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      echo "<script>alert('Login successful!'); window.location='home.php';</script>";
   }else{
      $error_msg = "Incorrect email or password!";
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login | Sari-Tech</title>

   <link rel="icon" type="image/png" href="images/sari-sari.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
   <link rel="stylesheet" href="css/style.css?v=1.0">

<style>
    :root{
      --yellow:#fed330;
      --red:#e74c3c;
      --white:#fff;
      --black:#2c3e50;
      --light-color:#777;
      --border:.2rem solid var(--black);
      --background-color: rgba(3, 19, 156, 1);
      --box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.2);
   }

   .login-container {
      min-height: 50vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
   }

   .login-form {
      width: 100%;
      max-width: 400px;
      background: var(--white);
      padding: 3rem;
      border-radius: 20px;
      box-shadow: var(--box-shadow);
   }

   .login-header {
      text-align: center;
      margin-bottom: 2.5rem;
   }

   .login-header img {
      width: 80px;
      height: auto;
      margin-bottom: 1rem;
   }

   .login-header h3 {
      font-size: 2.5rem;
      color: var(--background-color);
      font-weight: 600;
      margin-bottom: 0.5rem;
   }

   .login-header p {
      color: var(--black);
      font-size: 1.05rem;
   }

   .form-group {
      margin-bottom: 1.5rem;
      position: relative;
   }

   .form-group i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--light-color);
   }

   .form-control {
      width: 100%;
      padding: 1rem 1rem 1rem 2.8rem;
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      font-size: 1.4rem;
      color: var(--light-color);
      transition: all 0.3s ease;
      background: #f8f9fa;
   }

   .form-control:focus {
      border-color: var(--background-color);
      box-shadow: 0 0 0 3px rgba(3, 19, 156, 0.1);
      background: var(--white);
   }

   .form-control::placeholder {
      color: var(--light-color);
   }

   .password-field {
      position: relative;
   }

   .toggle-password {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--yellow);
      transition: color 0.3s ease;
   }

   .toggle-password:hover {
      color: var(--background-color);
   }

   .login-btn {
      width: 100%;
      padding: 1rem;
      background: var(--background-color);
      color: var(--white);
      border: none;
      border-radius: 10px;
      font-size: 1.4rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
   }

   .login-btn:hover {
      background: var(--black);
      transform: translateY(-3px);
      box-shadow: var(--box-shadow);
   }

   .register-link {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 1.4rem;
      color: var(--black);
   }

   .register-link a {
      color: var(--background-color);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
   }

   .register-link a:hover {
      color: var(--black);
   }

   .error-message {
      background: #fee2e2;
      color: var(--red);
      padding: 0.8rem;
      border-radius: 8px;
      font-size: 1.4rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
   }

   @media (max-width: 768px) {
      .login-form {
         padding: 2rem;
      }

      .login-header h3 {
         font-size: 1.8rem;
      }
   }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="login-container" data-aos="fade-up">
   <form action="" method="post" class="login-form">
      <div class="login-header">
         <img src="images/sari-sari.png" alt="Sari-Tech Logo">
         <h3>Welcome Back!</h3>
         <p>Please login to your account</p>
      </div>

      <?php if(isset($error_msg)): ?>
      <div class="error-message">
         <i class="fas fa-exclamation-circle"></i>
         <?= $error_msg ?>
      </div>
      <?php endif; ?>

      <div class="form-group">
         <i class="fas fa-envelope"></i>
         <input type="email" 
            name="email" 
            required 
            placeholder="Enter your email" 
            class="form-control" 
            maxlength="200" 
            oninput="this.value = this.value.replace(/\s/g, '')">
      </div>

            <div class="form-group password-field">
         <i class="fas fa-lock"></i>
         <input type="password" 
            name="pass" 
            required 
            placeholder="Enter your password" 
            class="form-control" 
            maxlength="200" 
            oninput="this.value = this.value.replace(/\s/g, '')">
      </div>

      <button type="submit" name="submit" class="login-btn">Login</button>

      <div class="register-link">
         Don't have an account? <a href="register.php">Register Now</a>
      </div>
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<script>
AOS.init({
   duration: 800,
   offset: 50,
   once: true
});

// Toggle password visibility
document.querySelector('.toggle-password').addEventListener('click', function() {
   const passwordInput = this.previousElementSibling;
   if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      this.classList.replace('fa-eye', 'fa-eye-slash');
   } else {
      passwordInput.type = 'password';
      this.classList.replace('fa-eye-slash', 'fa-eye');
   }
});
</script>

</body>
</html>
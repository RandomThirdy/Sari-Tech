<?php

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   
   if($select_admin->rowCount() > 0){
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      $_SESSION['admin_id'] = $fetch_admin_id['id'];
      header('location:dashboard.php');
   }else{
      echo '<p class="incorrect username or password!</p>';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>

   <link rel="icon" type="image/png" href="../images/sari-sari.png">
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <style>
   body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, rgba(3, 19, 156, 1), #e74c3c);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    padding: 0;
    color: #fff;
}

h3 {
    color: #fed330;
    font-size: 2.5em;
    margin-bottom: 30px;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

a {
    text-decoration: none;
    color: #fed330;
    font-size: 16px;
    display: inline-block;
    margin-top: 20px;
    font-weight: 600;
}

a:hover {
    color: #e74c3c;
    text-decoration: underline;
}

.form-container {
    width: 100%;
    max-width: 400px;
    padding: 40px;
    background: rgba(0, 0, 0, 0.7); 
    border-radius: 15px;
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
    text-align: center;
}

.form-container input[type="text"], 
.form-container input[type="password"] {
    width: 91.5%;
    padding: 15px;
    margin: 10px 0;
    border: 2px solid #fed330; 
    border-radius: 8px;
    background-color: rgba(255, 255, 255, 0.1); 
    font-size: 16px;
    color: #fff;
    transition: all 0.3s ease;
}

.form-container input[type="text"]:focus, 
.form-container input[type="password"]:focus {
    border-color: #e74c3c;
    background-color: #fff;
    color: #000;
}

.form-container input::placeholder {
    color: rgba(255, 255, 255, 0.7); 
}

.form-container input[type="submit"] {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #e74c3c, #fed330);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
}

.form-container input[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0px 7px 15px rgba(0, 0, 0, 0.3);
    background: #fed330;
    color: #000;
}

.message {
    background-color: #e74c3c; 
    color: white;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
}


   @media (max-width: 768px) {
      .form-container {
         width: 90%;
         padding: 30px;
      }

      h3 {
         font-size: 2em;
      }
   }
</style>


</head>
<body>

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
         }, 1500); // Message disappears after 3 seconds
      </script>
      ';
   }
}
?>

<!-- admin login form section starts  -->

<section class="form-container">

   <form action="" method="POST">
      <h3>Login Now</h3>
      
      <input type="text" name="name" maxlength="20" required placeholder="enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" name="submit" class="btn">
   </form>

</section>

<!-- admin login form section ends -->











</body>
</html>
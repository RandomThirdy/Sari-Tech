<?php

// Database connection details
$db_name = 'mysql:host=localhost;dbname=sari-tech'; // Database name and host information
$user_name = 'root'; // Username for the database connection
$user_password = ''; // Password for the database connection (empty in this case for local development)

// Establishing a connection to the database using PDO (PHP Data Objects)
$conn = new PDO($db_name, $user_name, $user_password); // PDO object is created to interact with the database


?>



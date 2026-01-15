<?php
// Database configuration
$servername = 'localhost'; // Server
$username = 'root';
$password = '';
$db = 'user'; // Database

$conn = mysqli_connect($servername, $username, $password, $db);

// Check connection
if (!$conn) {
  echo "Connection failed ";
}

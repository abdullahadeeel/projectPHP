<?php 

  $hostname = 'localhost';
  $username = 'root';
  $password = '';
  $db = 'you data base name';
  $conn = new mysqli($hostname,$username,$password,$db);

  if($conn->connect_error){
    die("FAILED TO CONNECT WITH DATABASE: " . $conn->connect_error);
  }

?>

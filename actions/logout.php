<?php
	setcookie("id" , "", time() - 3600, "/");
  header('Location: ../index.html');
  exit();
?>

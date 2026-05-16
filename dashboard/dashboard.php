
<?php 
include('../connection.php'); 
$userId = $_COOKIE['id'] ?? null;
if(!$userId){
  header('Location: ../pages/login.html');
  exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete'])){
  $id = $_POST['id'];
  $query = "DELETE FROM dish WHERE id = ? AND userId = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ii", $id, $userId);
  if(!$stmt->execute()){
    die("DELETION FAILED");
  }
  header("Location: dashboard.php?action=READ");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <title>Dashboard | Cuisine</title>
  </head>
  <body>
    <aside>
      <div>
        <img src="../assests/logo.webp" alt="company logo Dashboard" width="80px" height="80px">
        <h2>Dashboard</h2>
      </div>
      <div>
        <a href="dashboard.php?action=READ">My Dishes</a>
        <a href="dashboard.php?action=CREATE">Publish Dish</a>
        <a href="dashboard.php?action=ORDERS">Orders</a>
        <a href="dashboard.php?action=CART">Cart</a>
      </div>
    </aside>
    <section class="my-dishes">

      <?php
      if(isset($_GET['action']) && $_GET['action'] == "CART"){
          echo "<h2>Your Cart</h2>";
          $query = "SELECT cart.*, dish.title, dish.price FROM cart JOIN dish ON cart.dishId = dish.id WHERE cart.userId = ?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("i", $userId);
          $stmt->execute();
          $result = $stmt->get_result();

          if($result->num_rows > 0){
              echo "<ul>";
              while($row = $result->fetch_assoc()){
                  echo "<li>" . htmlspecialchars($row['title']) . " - Qty: " . $row['quantity'] . " - $" . ($row['price'] * $row['quantity']) . "</li>";
              }
              echo "</ul>";
              echo "<form action='../actions/checkout.php' method='POST'>
                        <button type='submit'>Checkout Now</button>
                    </form>";
          } else {
              echo "<p>Your cart is empty.</p>";
          }
      }
      ?>

      <?php 
      if(isset($_GET['action']) && $_GET['action'] == "READ"){
        $query = "SELECT * FROM dish WHERE userId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        echo "<h2>My Dishes</h2>";
        if($result->num_rows > 0){
          while($row = $result->fetch_assoc()){ 
          echo "
              <div class='dish-card'>
                  <div>
                    <img src='{$row['image']}' alt='product dish picture' width='100px' >
                  </div>
                  <div class='text-part'>
                    <h2>" . htmlspecialchars($row['title']) . "</h2>
                    <p>" . htmlspecialchars($row['description']) . "</p>
                    <div>
                      <form action='dashboard.php' method='GET'>
                        <input type='hidden' name='action' value='EDIT'>
                        <input type='hidden' value='{$row['id']}' name='id' >
                        <button type='submit'>Edit</button>
                      </form>
                      <form action='dashboard.php' method='POST' onsubmit=\"return confirm('Are you sure you want to delete this dish?');\">
                        <input type='hidden' value='{$row['id']}' name='id' >
                        <button type='submit' name='delete'>Delete</button>
                      </form>
                    </div>
                  </div>
              </div>
          ";         
          }
        } else {
          echo "<p>No dishes found. <a href='dashboard.php?action=CREATE'>Publish your first dish!</a></p>";
        }
      }
      ?>


      <?php

        if(isset($_GET['action']) && $_GET['action'] == "EDIT"){
          $id = (int)$_GET['id'];
          $query = "SELECT * FROM dish WHERE id = ? AND userId = ?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("ii", $id, $userId);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          if($row){
            echo "
            <h2>Edit Dish</h2>
            <form action='dashboard.php?action=EDIT' class='edit-form' method='post' enctype='multipart/form-data'>
              <input type='hidden' name='id' value='{$row['id']}'>
              <div class='img-input'>
                <label for='imgUpload'>
                  <img id='img-dish' src='{$row['image']}' alt='upload icon' height='180px' width='180px'>
                </label>
                <input type='file' id='imgUpload' name='imgUpload'>
              </div>
              <div>
                <input type='text' name='title' placeholder='Title of Dish' value='" . htmlspecialchars($row['title']) . "' required>
                <input type='text' name='description' placeholder='Description of Dish' value='" . htmlspecialchars($row['description']) . "' required>
                <input type='number' name='price' placeholder='Price of Dish' value='" . htmlspecialchars($row['price']) . "' required>
                <button name='edit' type='submit' >Submit</button>
              </div>
            </form>
            ";
          } else {
            echo "<p>Dish not found or access denied.</p>";
          }   
        }
        
        if(isset($_GET['action']) && $_GET['action'] == "ORDERS"){
            echo "<h2>Orders</h2>";
            $query = "SELECT * FROM orders WHERE userId = ? ORDER BY created_at DESC";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0){
                echo "<table border='1' style='width:100%; border-collapse: collapse; margin-top: 20px;'>
                        <tr>
                            <th>Order ID</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>";
                while($row = $result->fetch_assoc()){
                    echo "<tr>
                            <td>#" . htmlspecialchars($row['id']) . "</td>
                            <td>$" . htmlspecialchars($row['total_price']) . "</td>
                            <td>" . htmlspecialchars(ucfirst($row['status'])) . "</td>
                            <td>" . htmlspecialchars($row['created_at']) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No orders found.</p>";
            }
        }

        if( $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit'])){
          $id = $_POST['id'];
          $title = $_POST['title'];
          $description = $_POST['description'];
          $price = $_POST['price'];

          // Fetch old image to delete if new one is uploaded
          $query = "SELECT image FROM dish WHERE id = ? AND userId = ?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("ii", $id, $userId);
          $stmt->execute();
          $oldRow = $stmt->get_result()->fetch_assoc();

          $targetFile = $oldRow['image'];

          if(isset($_FILES['imgUpload']) && $_FILES['imgUpload']['error'] == 0){
            $targetDir = 'uploads/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $imageName = basename($_FILES['imgUpload']['name']);
            $extension = pathinfo($imageName, PATHINFO_EXTENSION);
            $targetFile = $targetDir . $userId . "_" . uniqid() . "." . $extension;

            if($oldRow['image'] && file_exists($oldRow['image'])){
              unlink($oldRow['image']);
            }

            if(!move_uploaded_file($_FILES['imgUpload']['tmp_name'], $targetFile))
            {
              die("Uploading photo Failed");
            }
          }

          $query = "UPDATE dish SET image = ?, title = ?, description = ?, price = ? WHERE id = ? AND userId = ?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("sssiii", $targetFile, $title, $description, $price, $id, $userId);

          if(!$stmt->execute()){
            die("UPDATE OF RECORD FAILED");
          }
          header("Location: dashboard.php?action=READ");
          exit();

        }
      
      ?>

      <?php 
      
        if(isset($_GET['action']) && $_GET['action'] == "CREATE"){
          echo "
            <h2>Publish Dish</h2>
            <form action='dashboard.php?action=CREATE' class='edit-form' method='post' enctype='multipart/form-data'>

              <div class='img-input'>
                <label for='imgUpload'>
                  <img id='img-dish' src='../assests/upload.png' alt='upload icon' height='180px' width='180px'>
                </label>
                <input type='file' id='imgUpload' name='imgUpload' required>
              </div>
              <div>
                <input type='text' name='title' placeholder='Title of Dish' required>
                <input type='text' name='description' placeholder='Description of Dish' required>
                <input type='number' name='price' placeholder='Price of Dish' required>
                <button name='publish' type='submit' >Submit</button>
              </div>
            </form>
            ";
        }

        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['publish'])){
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
  
            $targetDir = 'uploads/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $imageName = basename($_FILES['imgUpload']['name']);
            $extension = pathinfo($imageName, PATHINFO_EXTENSION);
            $targetFile = $targetDir . $userId . "_" . uniqid() . "." . $extension;
  
  
            if(!move_uploaded_file($_FILES['imgUpload']['tmp_name'], $targetFile))
            {
              die("Uploading photo Failed");
            }
  
  
  
            $query = "INSERT INTO dish (image, title, description, price, userId) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssii", $targetFile, $title, $description, $price, $userId);
  
            if(!$stmt->execute()){
              die("INSERTION OF RECORD FAILED");
            }
            header("Location: dashboard.php?action=READ");
            exit();
  
        
        }
      
      ?>
     
    </section>
  </body>
</html>

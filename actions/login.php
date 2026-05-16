<?php
   if(isset($_POST['login'])){

         include("../connection.php");

    // Get email and password from POST
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the query
    $query = "SELECT id, password FROM users WHERE email = ?";
    $prepare = $conn->prepare($query);
    $prepare->bind_param("s", $email);
    $prepare->execute();
    $result = $prepare->get_result();

    // Check if the email exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Fetch the result row
        $hashed_password = $row['password'];

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Set a secure cookie with the user ID
            setcookie("id", $row['id'], [
                'expires' => time() + 3600, // Cookie expires in 1 hour
                'path' => '/',
                'secure' => false,          // Changed to false for local testing
                'httponly' => true,        // Prevent JavaScript access
                'samesite' => 'Strict'     // Prevent cross-site requests
            ]);

            // Redirect to the dashboard
            header("Location: ../dashboard/dashboard.php?action=READ");
            exit();
        } else {
            // Invalid password
            echo "Email or Password Invalid!";
            exit();
        }
    } else {
        // Email not found
        echo "Email or Password Invalid!";
        exit();
    }      
    }
?>

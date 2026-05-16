<?php
    if(isset($_POST['signup'])){
    include("../connection.php"); // Ensure the connection file is included

    // Get the email and password from the POST request
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user already exists
    $query = "SELECT * FROM users WHERE email = ?";
    $preparedStmt = $conn->prepare($query);
    $preparedStmt->bind_param("s", $email);
    $preparedStmt->execute();
    $result = $preparedStmt->get_result();

    if ($result->num_rows > 0) {
        // User already exists
        echo "User Already Exists";
        exit();
    }

    // Hash the password
    $hashedPass = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insertQuery = "INSERT INTO users(email, password) VALUES(?, ?)";
    $prepareInsertQuery = $conn->prepare($insertQuery);
    $prepareInsertQuery->bind_param("ss", $email, $hashedPass);

    if ($prepareInsertQuery->execute()) {
        // Registration successful
        echo "Registration Successful!";
    } else {
        // Registration failed
        echo "Registration Failed!";
    }

    // Close the prepared statements and the connection
}
?>

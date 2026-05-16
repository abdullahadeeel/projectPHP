<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'cuisine_db'; // Default database name

// 1. Connect to MySQL server
$conn = new mysqli($hostname, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// 3. Select the database
$conn->select_db($dbname);

// 4. Create 'users' table
$sqlUsers = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sqlUsers) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table 'users': " . $conn->error . "<br>";
}

// 5. Create 'dish' table
$sqlDishes = "CREATE TABLE IF NOT EXISTS dish (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    userId INT(11) NOT NULL,
    image VARCHAR(255),
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sqlDishes) === TRUE) {
    echo "Table 'dish' created successfully.<br>";
} else {
    echo "Error creating table 'dish': " . $conn->error . "<br>";
}

echo "<br>Setup complete! Please update your connection.php with the database name: <strong>$dbname</strong>";

$conn->close();
?>

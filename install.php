<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'cuisine_db'; // Ensure this matches the database name you created in phpMyAdmin

// 1. Connect to MySQL server
$conn = new mysqli($hostname, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Select the database (User must create this in phpMyAdmin first)
if (!$conn->select_db($dbname)) {
    die("Error: The database '<strong>$dbname</strong>' does not exist. <br>Please create it manually in phpMyAdmin before running this script.");
}

echo "Connected to database: <strong>$dbname</strong><br>";

// 3. Create 'users' table
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

// 4. Create 'dish' table
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

// 5. Create 'orders' table
$sqlOrders = "CREATE TABLE IF NOT EXISTS orders (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    userId INT(11) NOT NULL,
    total_price INT(11) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sqlOrders) === TRUE) {
    echo "Table 'orders' created successfully.<br>";
} else {
    echo "Error creating table 'orders': " . $conn->error . "<br>";
}

echo "<br>Setup complete! You can now log in and use the dashboard.";

$conn->close();
?>

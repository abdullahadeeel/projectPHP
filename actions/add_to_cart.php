<?php
include('../connection.php');

$userId = $_COOKIE['id'] ?? null;
if (!$userId) {
    echo json_encode(["status" => "error", "message" => "Please login first."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['dishId'])) {
    $dishId = (int)$_POST['dishId'];
    $quantity = 1; // Default to 1

    // 1. Check if item exists in cart
    $checkQuery = "SELECT id, quantity FROM cart WHERE userId = ? AND dishId = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $userId, $dishId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // 2. Increment quantity if exists
        $row = $result->fetch_assoc();
        $updateQuery = "UPDATE cart SET quantity = quantity + 1 WHERE id = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param("i", $row['id']);
        $stmtUpdate->execute();
        echo "Item quantity updated.";
    } else {
        // 3. Add to cart if not
        $insertQuery = "INSERT INTO cart (userId, dishId, quantity) VALUES (?, ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param("iii", $userId, $dishId, $quantity);
        $stmtInsert->execute();
        echo "Item added to cart.";
    }
}
?>

<?php
include('../connection.php');

$userId = $_COOKIE['id'] ?? null;
if (!$userId) {
    header('Location: ../pages/login.html');
    exit();
}

// 1. Get cart items for the user
$query = "SELECT cart.*, dish.price FROM cart 
          JOIN dish ON cart.dishId = dish.id 
          WHERE cart.userId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartItems = $stmt->get_result();

if ($cartItems->num_rows === 0) {
    die("Cart is empty.");
}

// 2. Calculate total price
$totalPrice = 0;
while ($row = $cartItems->fetch_assoc()) {
    $totalPrice += ($row['price'] * $row['quantity']);
}

// 3. Create Order
$conn->begin_transaction();

try {
    $insertOrder = "INSERT INTO orders (userId, total_price, status) VALUES (?, ?, 'pending')";
    $stmt = $conn->prepare($insertOrder);
    $stmt->bind_param("ii", $userId, $totalPrice);
    $stmt->execute();
    $orderId = $stmt->insert_id;

    // 4. Move cart items to order_items
    $cartItems->data_seek(0); // Reset result pointer
    while ($item = $cartItems->fetch_assoc()) {
        $insertItem = "INSERT INTO order_items (orderId, dishId, quantity, price) VALUES (?, ?, ?, ?)";
        $stmtItem = $conn->prepare($insertItem);
        $stmtItem->bind_param("iiii", $orderId, $item['dishId'], $item['quantity'], $item['price']);
        $stmtItem->execute();
    }

    // 5. Clear Cart
    $clearCart = "DELETE FROM cart WHERE userId = ?";
    $stmtClear = $conn->prepare($clearCart);
    $stmtClear->bind_param("i", $userId);
    $stmtClear->execute();

    $conn->commit();
    echo "Order placed successfully! Order ID: #" . $orderId;
    echo "<br><a href='../dashboard/dashboard.php?action=ORDERS'>View Orders</a>";

} catch (Exception $e) {
    $conn->rollback();
    die("Order failed: " . $e->getMessage());
}
?>

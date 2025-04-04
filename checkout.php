<?php
// Database connection details
$servername = "localhost:3308";
$username = "root";
$password = "Ankit@12345";
$dbname = "digimenu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve cart data and instructions
$cartData = isset($_GET['cart']) ? json_decode($_GET['cart'], true) : [];
$specialInstructions = isset($_GET['instructions']) ? $_GET['instructions'] : '';

// Handle form submission (if any)
$userName = isset($_POST['userName']) ? $_POST['userName'] : '';
$tableNumber = isset($_POST['tableNumber']) ? $_POST['tableNumber'] : '';
$orderPlaced = false;

if ($userName && $tableNumber) {
    $orderId = uniqid(); // Generate unique order ID

    // Insert into orders table
    $sql = "INSERT INTO orders (order_id, user_name, table_number, special_instructions) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $orderId, $userName, $tableNumber, $specialInstructions);
    $stmt->execute();

    // Insert into order_items table
    // foreach ($cartData as $item) {
    //     $sql = "INSERT INTO order_items (order_id, quantity) VALUES (?, ?)";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bind_param("ssii", $orderId, $item['quantity']);
    //     $stmt->execute();
    // }
    // foreach ($cartData as $item) {
    //     $sql = "INSERT INTO order_items (order_id, quantity, item_id) VALUES (?, ?, ?)"; // corrected query
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bind_param("sii", $orderId, $item['quantity'], $item['itemId']); // corrected bind_param
    //     $stmt->execute();
    // }

    foreach ($cartData as $item) {
        $sql = "INSERT INTO order_items (order_id, quantity, item_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $orderId, $item['quantity'], $item['itemId']); // Use itemId here
        $stmt->execute();
    }

    $orderPlaced = true;
}

// Function to format price in Indian Rupees
function formatIndianRupee($price) {
    return '₹' . number_format($price, 2);
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="checkout.css">
    <title>Checkout</title>
   
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>

        <?php if (!empty($cartData)): ?>
            <ul class="cart-items">
                <?php
                $total = 0;
                foreach ($cartData as $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                    ?>
                    <li><?= $item['quantity'] ?> x <?= $item['name'] ?> - <?= formatIndianRupee($subtotal) ?></li>
                <?php endforeach; ?>
            </ul>
            <p class="total">Total: <?= formatIndianRupee($total) ?></p>
            <p class="instructions">Special Instructions: <?= htmlspecialchars($specialInstructions) ?></p>

            <?php if (!$orderPlaced): ?>
                <div class="form-container">
                    <h2>Enter Your Details</h2>
                    <form method="post" action="checkout.php?cart=<?= urlencode(json_encode($cartData)) ?>&instructions=<?= urlencode($specialInstructions) ?>">
                        <label for="userName">Name:</label>
                        <input type="text" id="userName" name="userName" required>

                        <label for="tableNumber">Table Number:</label>
                        <input type="text" id="tableNumber" name="tableNumber" required>

                        <button type="submit">Place Order</button>
                        <a href="index.php" class="back-button">Back to Menu</a>
                    </form>
                </div>
            <?php else: ?>
                <div class="order-confirm">
                    <div class="notification" id="orderNotification">
                        <span id="notificationMessage"></span>
                        <span class="close-btn" onclick="document.getElementById('orderNotification').style.display='none';">&times;</span>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.getElementById('notificationMessage').textContent = "<?php echo htmlspecialchars($userName); ?> , You have ordered successfully!";
                            document.getElementById('orderNotification').style.display = 'block';
                        });
                    </script>
                    <a href="index.php" class="back-button">Back to Menu</a>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <p>Your cart is empty.</p>
            <a href="index.php" class="back-button">Back to Menu</a>
        <?php endif; ?>
    </div>
</body>
</html>
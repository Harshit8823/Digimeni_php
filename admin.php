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

// Handle menu item operations
$message = '';
$notification = '';
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'add' || $action == 'update') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];

        if ($action == 'add') {
            $sql = "INSERT INTO menu (name, description, price, category) VALUES (?, ?, ?, ?)";
        } else {
            $id = $_POST['id'];
            $sql = "UPDATE menu SET name = ?, description = ?, price = ?, category = ? WHERE id = ?";
        }

        $stmt = $conn->prepare($sql);
        if ($action == 'add') {
            $stmt->bind_param("ssds", $name, $description, $price, $category);
        } else {
            $stmt->bind_param("ssdsi", $name, $description, $price, $category, $id);
        }

        if ($stmt->execute()) {
            $message = "Menu item " . ($action == 'add' ? "added" : "updated") . " successfully.";
            $notification = ($action == 'add') ? $name . " added successfully." : $name . " updated successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        $sql = "DELETE FROM menu WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $message = "Menu item deleted successfully.";
            $notification = "Item deleted successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}

// Fetch menu items
$sql = "SELECT * FROM menu";
$result = $conn->query($sql);
$menuItems = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menuItems[] = $row;
    }
}

// Mark order as completed
if (isset($_GET['completeOrder'])) {
    $orderId = $_GET['completeOrder'];
    $sql = "UPDATE orders SET completed = TRUE WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
}

// Mark order as declined
if (isset($_GET['declineOrder'])) {
    $orderId = $_GET['declineOrder'];
    $sql = "UPDATE orders SET declined = TRUE WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
}

// Fetch orders with formatted time, excluding declined orders
$sql = "SELECT *, DATE_FORMAT(order_date, '%Y-%m-%d %H:%i:%s') as formatted_order_date FROM orders WHERE completed = FALSE AND declined = FALSE";
$result = $conn->query($sql);
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

// Fetch declined orders with formatted time
$sql = "SELECT *, DATE_FORMAT(order_date, '%Y-%m-%d %H:%i:%s') as formatted_order_date FROM orders WHERE declined = TRUE";
$result = $conn->query($sql);
$declinedOrders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $declinedOrders[] = $row;
    }
}

// Fetch order items for current orders including price
foreach ($orders as &$order) {
    $sql = "SELECT order_items.*, menu.price FROM order_items JOIN menu ON order_items.item_id = menu.id WHERE order_items.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $order['order_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $order['items'] = $result->fetch_all(MYSQLI_ASSOC);
    $order['total_items'] = 0;
    $order['total_price'] = 0;
    foreach($order['items'] as $item){
        $order['total_items'] += $item['quantity'];
        $order['total_price'] += $item['quantity'] * $item['price'];
    }
}

// Fetch order items for declined orders including price
foreach ($declinedOrders as &$order) {
    $sql = "SELECT order_items.*, menu.price FROM order_items JOIN menu ON order_items.item_id = menu.id WHERE order_items.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $order['order_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $order['items'] = $result->fetch_all(MYSQLI_ASSOC);
    $order['total_items'] = 0;
    $order['total_price'] = 0;
    foreach($order['items'] as $item){
        $order['total_items'] += $item['quantity'];
        $order['total_price'] += $item['quantity'] * $item['price'];
    }
}

// Fetch completed orders with formatted time
$sql = "SELECT *, DATE_FORMAT(order_date, '%Y-%m-%d %H:%i:%s') as formatted_order_date FROM orders WHERE completed = TRUE";
$result = $conn->query($sql);
$completedOrders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $completedOrders[] = $row;
    }
}

// Fetch order items for completed orders including price
foreach ($completedOrders as &$order) {
    $sql = "SELECT order_items.*, menu.price FROM order_items JOIN menu ON order_items.item_id = menu.id WHERE order_items.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $order['order_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $order['items'] = $result->fetch_all(MYSQLI_ASSOC);
    $order['total_items'] = 0;
    $order['total_price'] = 0;
    foreach($order['items'] as $item){
        $order['total_items'] += $item['quantity'];
        $order['total_price'] += $item['quantity'] * $item['price'];
    }
}

// Function to format price in Indian Rupees
function formatIndianRupee($price) {
    return '₹' . number_format($price, 2);
}
?>

<!DOCTYPE html>
<html>
    <style>
        .decline-link {
    background-color:rgb(230, 153, 153); /* Light red background */
    color:rgb(239, 16, 16); /* Moderate red text */
    padding: 8px 10px; /* Add some padding for better appearance */
    border: 1px solid #d32f2f; /* Optional: Add a border */
    border-radius: 5px; /* Optional: Rounded corners */
    text-decoration: none; /* Remove underline from link */
}

.decline-link:hover {
    background-color:rgb(245, 105, 105); /* Slightly darker red on hover */
}
    </style>
<head>
    <title>Admin Panel - Orders and Menu</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-dashboard">
    <button onclick="showCurrentOrders()">Current Orders</button>
    <button onclick="showOrderHistory()">Completed Orders</button>
    <button onclick="showMenuManagement()">Menu Management</button>
    <button onclick="showDeclinedOrders()">Rejected Orders</button>
</div>

<div class="admin-container">
    <div id="current-orders" class="order-section">
        <h2>Current Orders</h2>
        <ul class="order-list" id="current-order-list">
            <?php foreach ($orders as $order): ?>
                <li>
                    <strong>Customer:</strong> <?= htmlspecialchars($order['user_name']) ?><br>
                    <strong>Table:</strong> <?= htmlspecialchars($order['table_number']) ?><br>
                    <strong>Order Time:</strong> <?= htmlspecialchars($order['formatted_order_date']) ?><br>
                    <strong>Items:</strong>
                    <ul>
                        <?php foreach ($order['items'] as $item): ?>
                            <li><?= $item['quantity'] ?> x <?= htmlspecialchars($menuItems[array_search($item['item_id'], array_column($menuItems, 'id'))]['name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <strong>Total Items:</strong> <?= $order['total_items'] ?><br>
                    <strong>Total Price:</strong> <?= formatIndianRupee($order['total_price']) ?><br>
                    <strong>Special Instructions:</strong> <?= htmlspecialchars($order['special_instructions'] ?? 'None') ?><br>
                    <a href="admin.php?completeOrder=<?= urlencode($order['order_id']) ?>" class="complete-link">Complete</a>
                    <a href="admin.php?declineOrder=<?= urlencode($order['order_id']) ?>" class="decline-link">Reject</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="order-history" class="order-section hidden">
        <h2 style="color: chartreuse;">Completed Orders</h2>
        <ul class="order-list" id="order-history-list">
            <?php foreach ($completedOrders as $order): ?>
                <li>
                    <strong>Customer:</strong> <?= htmlspecialchars($order['user_name']) ?><br>
                    <strong>Table:</strong> <?= htmlspecialchars($order['table_number']) ?><br>
                    <strong>Order Time:</strong> <?= htmlspecialchars($order['formatted_order_date']) ?><br>
                    <strong>Items:</strong>
                    <ul>
                        <?php foreach ($order['items'] as $item): ?>
                            <li><?= $item['quantity'] ?> x <?= htmlspecialchars($menuItems[array_search($item['item_id'], array_column($menuItems, 'id'))]['name']) ?> - <?= formatIndianRupee($item['price']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <strong>Total Items:</strong> <?= $order['total_items'] ?><br>
                    <strong>Total Price:</strong> <?= formatIndianRupee($order['total_price']) ?><br>
                    <strong>Special Instructions:</strong> <?= htmlspecialchars($order['special_instructions'] ?? 'None') ?><br>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="menu-management" class="order-section hidden">
        <h2>Menu Management</h2>
        <?php if ($message): ?>
            <p><?= $message ?></p>
        <?php endif; ?>

        <form method="post" class="menu-form">
            <input type="hidden" name="action" value="add">
            <input type="text" name="name" placeholder="Name" required><br>
            <textarea name="description" placeholder="Description" required></textarea><br>
            <input type="number" name="price" placeholder="Price" required><br>
            <select name="category" required>
                <option value="welcome_drink">WELCOME DRINK</option>
                <option value="soup">SOUP</option>
                <option value="salad">SALAD</option>
                <option value="veg_starter">VEG STARTER</option>
                <option value="veg_main_course">VEG MAIN COURSE</option>
                <option value="south_indian">SOUTH INDIAN</option>
                <option value="rice_breads">RICE & BREADS</option>
                <option value="sweets">SWEETS</option>
                <option value="desserts">DESSERTS</option>
                <option value="icecream">ICE CREAM & KULFI</option>
            </select><br>
            <button type="submit">Add Item</button>
        </form>

        <table id="menuTable" class="display">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menuItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['description']) ?></td>
                        <td><?= formatIndianRupee($item['price']) ?></td>
                        <td><?= htmlspecialchars($item['category']) ?></td>
                        <td>
                            <button onclick="editMenuItem(<?= $item['id'] ?>, '<?= htmlspecialchars($item['name']) ?>', '<?= htmlspecialchars($item['description']) ?>', <?= $item['price'] ?>, '<?= htmlspecialchars($item['category']) ?>')">Edit</button>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="declined-orders" class="order-section hidden">
        <h2 style="color: #d32f2f;">Rejected Orders</h2>
        <ul class="order-list" id="declined-order-list">
            <?php foreach ($declinedOrders as $order): ?>
                <li>
                    <strong>Customer:</strong> <?= htmlspecialchars($order['user_name']) ?><br>
                    <strong>Table:</strong> <?= htmlspecialchars($order['table_number']) ?><br>
                    <strong>Order Time:</strong> <?= htmlspecialchars($order['formatted_order_date']) ?><br>
                    <strong>Items:</strong>
                    <ul>
                        <?php foreach ($order['items'] as $item): ?>
                            <li><?= $item['quantity'] ?> x <?= htmlspecialchars($menuItems[array_search($item['item_id'], array_column($menuItems, 'id'))]['name']) ?> - <?= formatIndianRupee($item['price']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <strong>Total Items:</strong> <?= $order['total_items'] ?><br>
                    <strong>Total Price:</strong> <?= formatIndianRupee($order['total_price']) ?><br>
                    <strong>Special Instructions:</strong> <?= htmlspecialchars($order['special_instructions'] ?? 'None') ?><br>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="success-notification" id="menuNotification">
        <span id="menuNotificationMessage"></span>
        <span class="close-btn" onclick="document.getElementById('menuNotification').classList.remove('show');">&times;</span>
    </div>
    <div class="notification" id="newOrderNotification">You have a new order!</div>
</div>

<script>
    $(document).ready(function() {
        $('#menuTable').DataTable();
    });

    function showCurrentOrders() {
        document.getElementById('current-orders').classList.remove('hidden');
        document.getElementById('order-history').classList.add('hidden');
        document.getElementById('menu-management').classList.add('hidden');
        document.getElementById('declined-orders').classList.add('hidden');
    }

    function showOrderHistory() {
        document.getElementById('current-orders').classList.add('hidden');
        document.getElementById('order-history').classList.remove('hidden');
        document.getElementById('menu-management').classList.add('hidden');
        document.getElementById('declined-orders').classList.add('hidden');
    }

    function showMenuManagement() {
        document.getElementById('current-orders').classList.add('hidden');
        document.getElementById('order-history').classList.add('hidden');
        document.getElementById('menu-management').classList.remove('hidden');
        document.getElementById('declined-orders').classList.add('hidden');
    }

    function showDeclinedOrders() {
        document.getElementById('current-orders').classList.add('hidden');
        document.getElementById('order-history').classList.add('hidden');
        document.getElementById('menu-management').classList.add('hidden');
        document.getElementById('declined-orders').classList.remove('hidden');
    }

    function editMenuItem(id, name, description, price, category) {
        let form = document.querySelector('.menu-form');
        form.innerHTML = `
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="${id}">
            <input type="text" name="name" value="${name}" required><br>
            <textarea name="description" required>${description}</textarea><br>
            <input type="number" name="price" value="${price}" required><br>
            <select name="category" required>
                <option value="welcome_drink" ${category === 'welcome_drink' ? 'selected' : ''}>WELCOME DRINK</option>
                <option value="soup" ${category === 'soup' ? 'selected' : ''}>SOUP</option>
                <option value="salad" ${category === 'salad' ? 'selected' : ''}>SALAD</option>
                <option value="veg_starter" ${category === 'veg_starter' ? 'selected' : ''}>VEG STARTER</option>
                <option value="veg_main_course" ${category === 'veg_main_course' ? 'selected' : ''}>VEG MAIN COURSE</option>
                <option value="south_indian" ${category === 'south_indian' ? 'selected' : ''}>SOUTH INDIAN</option>
                <option value="rice_breads" ${category === 'rice_breads' ? 'selected' : ''}>RICE & BREADS</option>
                <option value="sweets" ${category === 'sweets' ? 'selected' : ''}>SWEETS</option>
                <option value="desserts" ${category === 'desserts' ? 'selected' : ''}>DESSERTS</option>
                <option value="icecream" ${category === 'icecream' ? 'selected' : ''}>ICE CREAM & KULFI</option>
            </select><br>
            <button type="submit">Update Item</button>
        `;
    }

    <?php if (!empty($orders)): ?>
        document.getElementById('newOrderNotification').classList.add('show');
    <?php endif; ?>

    setTimeout(function() {
        document.getElementById('newOrderNotification').classList.remove('show');
    }, 5000);

    <?php if ($notification): ?>
        document.getElementById('menuNotificationMessage').textContent = "<?= $notification ?>";
        document.getElementById('menuNotification').classList.add('show');

        setTimeout(function() {
            document.getElementById('menuNotification').classList.remove('show');
        }, 5000);
    <?php endif; ?>
</script>

</body>
</html>
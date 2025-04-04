<?php
// Database connection details
$servername = "localhost:3308";
$username = "root";
$password = "Ankit@12345";
$dbname = "digimenu";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch menu items from the database, including the image path
$sql = "SELECT * FROM menu"; // Assuming your menu table has an image_path column
$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$menuItems = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menuItems[] = $row;
    }
}

$conn->close();

// Function to format price in Indian Rupees
function formatIndianRupee($price)
{
    return '₹' . number_format($price, 2);
}
?>

<!DOCTYPE html>
<html>

<head>


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    <!-- <link rel="stylesheet" href="style.css"> -->
    <link rel="stylesheet" href="chatgpt.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- Tailwind Css -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> 


    <title>Digital Menu</title>
</head>

<body>
    <div class="menu-container">
        <div class="menu-categories ">
            <button class="" onclick="showCategory('welcome_drink')">WELCOME DRINK</button>
            <button class="" onclick="showCategory('soup')">SOUP</button>
            <button class="" onclick="showCategory('salad')">SALAD</button>
            <button class="" onclick="showCategory('veg_starter')">VEG STARTER</button>
            <button class="" onclick="showCategory('veg_main_course')">VEG MAIN COURSE</button>
            <button class="" onclick="showCategory('south_indian')">SOUTH INDIAN</button>
            <button class="" onclick="showCategory('rice_breads')">RICE & BREADS</button>
            <button class="" onclick="showCategory('sweets')">SWEETS</button>
            <button class="" onclick="showCategory('desserts')">DESSERTS</button>
            <button class="" onclick="showCategory('icecream')">ICE CREAM & KULFI</button>
        </div>

        <div class="menu-items">
            <?php if (!empty($menuItems)): ?>
                <?php foreach (array_unique(array_column($menuItems, 'category')) as $category): ?>
                    <div id="<?= $category ?>" class="menu-item <?php if ($category === 'welcome_drink')
                          echo 'active';
                      else
                          echo 'hidden'; ?>">
                        <h2><?= ucfirst($category) ?></h2>
                        <?php foreach (array_filter($menuItems, function ($item) use ($category) {
                            return $item['category'] === $category;
                        }) as $item): ?>
                            <div class="item">
                                <h3><?= $item['name'] ?></h3>
                                <p><?= $item['description'] ?>             <?= formatIndianRupee($item['price']) ?></p>
                                <div class="quantity-control">
                                <button class="btn mb-3 "
                                        onclick="changeQuantity('<?= str_replace(' ', '', $item['name']) ?>', -1, '<?= $category ?>')">
                                        <i class="fa-regular fa-square-minus"></i>
                                    </button>
                                    
                                    <input class="mb-0 text-center fs-5"
                                     type="number" id="qty-<?= str_replace(' ', '', $item['name']) ?>-<?= $category ?>"value="1" min="1">

                                     <button class="btn mb-3"
                                        onclick="changeQuantity('<?= str_replace(' ', '', $item['name']) ?>', 1, '<?= $category ?>')">
                                        <i class="fa-solid fa-square-plus"></i>
                                    </button>
                                   
                                </div>

                                <button class="btn mb-3"
                                    onclick="addToCart('<?= $item['name'] ?>', <?= $item['price'] ?>, <?= $item['id'] ?>, '<?= $category ?>')">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No menu items found.</p>
            <?php endif; ?>
        </div>

        <div class="cart">
            <h2>
                <i class="fa-solid fa-cart-plus"></i>
                Cart
            </h2>
            <ul class="cart-items" id="cart-items"></ul>
            <p class="cart-total">Total:<span id="cart-total-value"></span></p>
            <div class="special-instructions">
                <label>Special Instructions:</label>
                <textarea id="special-instructions"></textarea>
            </div>
            <button onclick="checkout()">Checkout</button>
            <button onclick="removeSelected()">Remove Selected</button>
        </div>
    </div>
    <script>
        let cart = [];

        function showCategory(categoryId) {
            let items = document.getElementsByClassName('menu-item');
            for (let item of items) {
                item.classList.remove('active');
                item.classList.add('hidden');
            }
            document.getElementById(categoryId).classList.remove('hidden');
            document.getElementById(categoryId).classList.add('active');
        }

        // function addToCart(itemName, price, category) {
        //     let quantity = parseInt(document.getElementById(`qty-${itemName.replace(' ', '')}-${category}`).value);
        //     cart.push({ name: itemName, price: price, quantity: quantity });
        //     updateCart();
        // }

        // function addToCart(itemName, price, category) {
        //     let quantity = parseInt(document.getElementById(`qty-${itemName.replace(' ', '')}-${category}`).value);
        //     cart.push({ name: itemName.replace(' ',''), price: price, quantity: quantity }); // Remove spaces here
        //     updateCart();
        // }

        function addToCart(itemName, price, itemId, category) {
            let quantity = parseInt(document.getElementById(`qty-${itemName.replace(' ', '')}-${category}`).value);
            cart.push({
                name: itemName.replace(' ', ''),
                price: price,
                quantity: quantity,
                itemId: itemId // Add itemId here
            });
            updateCart();
        }

        function changeQuantity(itemName, change, category) {
            let quantityInput = document.getElementById(`qty-${itemName.replace(' ', '')}-${category}`);
            let quantity = parseInt(quantityInput.value);
            quantity += change;
            if (quantity < 1) { quantity = 1; }
            quantityInput.value = quantity;
        }


        function updateCart() {
            let cartItems = document.getElementById('cart-items');
            let cartTotalValue = document.getElementById('cart-total-value');
            let total = 0;

            cartItems.innerHTML = '';
            cart.forEach((item, index) => {
                let li = document.createElement('li');
                let checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.id = `remove-${index}`;
                li.appendChild(checkbox);

                let itemDetails = `${item.quantity} x ${item.name} - <?= '₹' ?>${(item.price * item.quantity).toFixed(2)}`;
                li.appendChild(document.createTextNode(itemDetails));

                cartItems.appendChild(li);
                total += item.price * item.quantity;
            });

            cartTotalValue.textContent = `<?= '₹' ?>${total.toFixed(2)}`; // Update only the total value
            // cartTotalValue.textContent = total.toFixed(2); // Update only the total value
        }

        function removeSelected() {
            let newCart = [];
            let cartItems = document.getElementById('cart-items').children;

            for (let i = 0; i < cart.length; i++) {
                if (!document.getElementById(`remove-${i}`).checked) {
                    newCart.push(cart[i]);
                }
            }

            cart = newCart;
            updateCart();
        }

        function checkout() {
            let cartString = encodeURIComponent(JSON.stringify(cart));
            let instructions = encodeURIComponent(document.getElementById('special-instructions').value);
            window.location.href = `checkout.php?cart=${cartString}&instructions=${instructions}`;
        }

        showCategory('welcome_drink');
        // ... (rest of your JavaScript code remains the same) ...
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
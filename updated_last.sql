CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT
);



CREATE TABLE orders (
    order_id VARCHAR(255) PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL,
    table_number VARCHAR(255) NOT NULL,
    special_instructions TEXT,
    total_price DECIMAL(10, 2),
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    completed BOOLEAN DEFAULT FALSE,
    declined BOOLEAN DEFAULT FALSE
);



CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(255) NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (item_id) REFERENCES menu(id)
);
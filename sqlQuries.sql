-- Create the 'menu' table
use digimenu;

CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT
);

Select * from menu;

CREATE TABLE orders (
    order_id VARCHAR(255) PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL,
    table_number VARCHAR(255) NOT NULL,
    special_instructions TEXT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    completed BOOLEAN DEFAULT FALSE
);

CREATE TABLE order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(255) NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);
select * from order_items;

select * from orders;

describe orders;

use digimenu;

ALTER TABLE orders ADD COLUMN total_price DECIMAL(10, 2);

CREATE INDEX idx_order_time ON orders (order_time);

ALTER TABLE orders
ADD declined BOOLEAN DEFAULT FALSE;



-- Changes Applied
-- 1. Add item_id column to order_items
select * from order_items;

ALTER TABLE order_items
ADD COLUMN item_id INT;

-- 2. Populate item_id column with correct menu ids
UPDATE order_items oi
JOIN menu m ON oi.item_name = m.name
SET oi.item_id = m.id;

-- 3. Add foreign key constraint to menu table
ALTER TABLE order_items
ADD FOREIGN KEY (item_id) REFERENCES menu(id);

-- 4. Remove item_name column
ALTER TABLE order_items
DROP COLUMN item_name;

-- 5. Remove price column
ALTER TABLE order_items
DROP COLUMN price;


-- Remove the incorrect primary key
describe order_items;
ALTER TABLE order_items
DROP PRIMARY KEY;

-- Add a new auto-incrementing primary key column
ALTER TABLE order_items
ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY FIRST;
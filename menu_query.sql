CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT
);

select * from menu;

INSERT INTO menu (name, category, price, description) VALUES
('MINT LEMON FIZZ', 'welcome_drink', 50.00, 'A refreshing mint and lemon drink.'),
('HAWAIIAN BLUE', 'welcome_drink', 60.00, 'A tropical blue drink.'),
('JAL JEERA', 'welcome_drink', 40.00, 'A spiced cumin and mint drink.'),
('FRUIT PUNCH', 'welcome_drink', 55.00, 'A mix of various fruit juices.'),
('BUTTER MILK', 'welcome_drink', 35.00, 'A traditional Indian drink.'),

('CREAM OF TOMATO SOUP', 'soup', 70.00, 'A creamy tomato soup.'),
('LEMON CORIANDER', 'soup', 75.00, 'A tangy lemon and coriander soup.'),
('HOT & SOUR SOUP', 'soup', 80.00, 'A spicy and tangy soup.'),
('VEG CLEAR SOUP', 'soup', 65.00, 'A light and clear vegetable soup.'),
('MINESTRONE SOUP', 'soup', 85.00, 'A hearty Italian vegetable soup.'),
('SWEET CORN VEG SOUP', 'soup', 78.00, 'A sweet and creamy corn soup.'),
('DAL OR TOMATO SHORBHA', 'soup', 72.00, 'A lentil or tomato based soup.'),

('KOSUMBARI SALAD', 'salad', 90.00, 'A South Indian salad with lentils and vegetables.'),
('FRESH GREEN SALAD', 'salad', 85.00, 'A simple salad with fresh greens.'),
('ALOO CHANNA CHAAT', 'salad', 100.00, 'A tangy potato and chickpea salad.'),
('KIMCHI SALAD', 'salad', 110.00, 'A spicy Korean fermented cabbage salad.'),
('CORN SALAD', 'salad', 95.00, 'A sweet and savory corn salad.'),
('RUSSIAN SALAD', 'salad', 105.00, 'A creamy salad with vegetables and mayonnaise.'),
('TOSSED SALAD', 'salad', 88.00, 'A mixed green salad with dressing.'),

('CORN AND ALOO TIKKI', 'veg_starter', 120.00, 'Crispy corn and potato patties.'),
('HARABHHAR KEBAB', 'veg_starter', 130.00, 'Spinach and vegetable kebabs.'),
('BABY CORN TEMPURA', 'veg_starter', 125.00, 'Batter-fried baby corn.'),
('ACHARI PANEER TIKKI', 'veg_starter', 140.00, 'Pickled paneer patties.'),
('CRISPY VEG', 'veg_starter', 115.00, 'Assorted crispy vegetables.'),
('ALOO GOBI TANDOORI', 'veg_starter', 135.00, 'Tandoori roasted potato and cauliflower.'),
('TANDOORI ARBI', 'veg_starter', 128.00, 'Tandoori roasted colocasia.'),
('COCKTAIL SAMOSA', 'veg_starter', 118.00, 'Miniature samosas.'),
('GOBI/PANEER/BABY CORN MANCHURIA', 'veg_starter', 132.00, 'Manchurian with cauliflower, paneer, or baby corn.'),
('VEG CUTLET', 'veg_starter', 122.00, 'Vegetable patties.'),

('HOT AND SOUR VEG', 'veg_main_course', 150.00, 'Spicy and tangy vegetable curry.'),
('METHI MALAI MUTTER', 'veg_main_course', 160.00, 'Fenugreek, cream, and pea curry.'),
('PANEER MAKHANI', 'veg_main_course', 170.00, 'Paneer in a rich tomato and cream gravy.'),
('BHINDI DO PYAZA', 'veg_main_course', 155.00, 'Okra cooked with double the onions.'),
('PANEER JALFREZI', 'veg_main_course', 165.00, 'Paneer and vegetables in a spicy tomato sauce.'),
('KURKURE BHINDI', 'veg_main_course', 158.00, 'Crispy fried okra.'),
('PANEER KALI MIRCH', 'veg_main_course', 175.00, 'Paneer cooked in a black pepper gravy.'),
('VEG JAIPURI', 'veg_main_course', 162.00, 'Mixed vegetable curry in a Rajasthani style.'),
('VEG KHOORMA', 'veg_main_course', 168.00, 'Mild and creamy vegetable curry.'),
('ACHARI VEGETABLE', 'veg_main_course', 165.00, 'Vegetable curry with pickled spices.'),
('SUBJI MILONI', 'veg_main_course', 155.00, 'Mixed vegetable curry with spinach.'),
('MAKHAI KUMBH PALAK', 'veg_main_course', 170.00, 'Corn, mushroom, and spinach curry.'),
('ALOO GOBI', 'veg_main_course', 152.00, 'Potato and cauliflower curry.'),
('PANEER DO PYAZA', 'veg_main_course', 168.00, 'Paneer cooked with double the onions.'),
('DUM ALOO BANARASI', 'veg_main_course', 160.00, 'Potato curry in a rich Banarasi style.');

select * from menu;

INSERT INTO menu (name, category, price, description) VALUES
('Mini Dosa', 'south_indian', 80.00, 'Small, crispy South Indian crepes.'),
('Mini Uttappa', 'south_indian', 85.00, 'Small, thick South Indian pancakes.'),
('Sambhaar', 'south_indian', 50.00, 'A lentil-based vegetable stew.'),
('Idli', 'south_indian', 60.00, 'Steamed rice cakes.'),
('Fried Idli with Sauce', 'south_indian', 75.00, 'Fried rice cakes served with a sauce.'),
('Medu Vada', 'south_indian', 70.00, 'Lentil doughnuts.');

select * from menu;

INSERT INTO menu (name, category, price, description) VALUES
('Plain Boiled Rice', 'rice_breads', 1.95, 'Simple cooked white rice.'),
('Traditional Basmati Rice', 'rice_breads', 2.95, 'Long-grain aromatic rice.'),
('Saffron Peas Pilaf', 'rice_breads', 4.95, 'Rice cooked with saffron and peas.'),
('Shah\'s Biryani-E-Gosht', 'rice_breads', 13.95, 'Aromatic rice dish with meat.'),
('Banarsi Biryani Satrang (vegetarian)', 'rice_breads', 11.95, 'Vegetarian biryani in Banarasi style.'),
('Pilao Rice', 'rice_breads', 8.95, 'Flavored rice dish.'),
('Punjabi Roti (whole wheat)', 'rice_breads', 1.95, 'Whole wheat flatbread.'),
('Puja Paratha Lachedar', 'rice_breads', 2.95, 'Layered Indian flatbread.'),
('Naan Khyber Pass (afghan bread)', 'rice_breads', 1.95, 'Afghan style leavened bread.'),
('Allu Paratha (spicy potato stuffed bread)', 'rice_breads', 3.50, 'Flatbread stuffed with spiced potatoes.'),
('Kulcha Kashmiri', 'rice_breads', 3.50, 'Leavened bread with Kashmiri flavors.'),
('Peshawari Naan', 'rice_breads', 4.95, 'Naan stuffed with nuts and dried fruits.'),
('Keema Kulcha', 'rice_breads', 5.95, 'Leavened bread stuffed with minced meat.'),
('Maharaja\'s Mistress Kulcha', 'rice_breads', 5.95, 'Special kulcha with unique ingredients.');

select * from menu;

INSERT INTO menu (name, category, price, description) VALUES
('Gulaab Jamun', 'sweets', 6.00, 'Deep-fried milk balls in sugar syrup.'),
('Shrikhand', 'sweets', 7.00, 'Sweetened strained yogurt dessert.'),
('Aamrakhund', 'sweets', 8.00, 'Mango-flavored shrikhand.'),
('Payasam', 'sweets', 6.50, 'South Indian rice pudding.'),
('Aamras', 'sweets', 7.50, 'Mango pulp dessert.'),
('Kala Jamun', 'sweets', 6.50, 'Dark version of gulaab jamun.'),
('Rajbhog', 'sweets', 8.50, 'Large cheese-filled sweet balls.'),
('Chamcham', 'sweets', 7.00, 'Cylinder-shaped sweet with coconut.'),
('Malai Sandwich', 'sweets', 9.00, 'Creamy sandwich dessert.'),
('Rabdi', 'sweets', 8.00, 'Thickened milk dessert.'),
('Sitafal Basundi', 'sweets', 9.50, 'Custard apple flavored thickened milk.');

select * from menu;

INSERT INTO menu (name, category, price, description) VALUES
('Shahi Tukda', 'desserts', 8.00, 'Bread pudding with saffron and nuts.'),
('Mango Kheer', 'desserts', 7.50, 'Rice pudding with mango flavor.'),
('Custard Pudding', 'desserts', 6.50, 'Creamy custard dessert.'),
('Kheer', 'desserts', 7.00, 'Traditional rice pudding.'),
('Mango Kulfi', 'desserts', 8.50, 'Indian ice cream with mango flavor.');

select * from menu;

INSERT INTO menu (name, category, price, description) VALUES
('Strawberry Ice Cream', 'icecream', 5.00, 'Strawberry flavored ice cream.'),
('Vanilla Ice Cream', 'icecream', 5.00, 'Vanilla flavored ice cream.'),
('Chocolate Ice Cream', 'icecream', 5.00, 'Chocolate flavored ice cream.'),
('Mango Ice Cream', 'icecream', 5.00, 'Mango flavored ice cream.'),
('Mango Kulfi', 'icecream', 6.00, 'Mango flavored Indian ice cream.'),
('Almond Kulfi', 'icecream', 6.00, 'Almond flavored Indian ice cream.'),
('Pistachio Kulfi', 'icecream', 6.00, 'Pistachio flavored Indian ice cream.'),
('Malai Kulfi', 'icecream', 6.00, 'Creamy Indian ice cream.'),
('Original Kulfi', 'icecream', 6.00, 'Traditional Indian ice cream.');

select * from menu;
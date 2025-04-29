# E-Commerce-Website
# Project Overview
This e-commerce platform is designed for Customers and Admins to handle shopping and management functionalities. Below are concise, sectioned explanations for each part of the project
## 1. User Section
This section explains how users interact with the website.

•	Users can create an account on the register.php page by providing their name, email, and password. This allows them to access personalized features like the shopping cart.

•	Login: Users log in on the login.php page using their email and password.

•	Logout: The logout.php page ends the session, ensuring user data is secure.

•	All products are displayed on the homepage (index.php). Users can see product names, prices, descriptions, and images.

•	On the index.php page, users click "Add to Cart" to save items. The system records these items in the cart for the logged-in user.

•	The cart.php page lets users view, update, or remove items from their cart. It also shows the total cost of selected products.

## 2. Admin Section
This section explains how admins manage the platform.

•	Login: Admins log in via admin/login.php using their credentials.

•	Logout: The admin/logout.php page ends the admin session securely.

•	The admin/dashboard.php page provides a control panel with options to add, edit, and delete products.

•	The admin/add_product.php page allows admins to add new products by filling out a form with product details and uploading an image.

•	The admin/manage_products.php page shows all products in a table with options to:

  Edit product details.
  
  Delete products from the store.

## 3. Database Section

This section explains how the database is structured.

•	The users table stores information about all users (customers and admins):
Usernames, emails, hashed passwords.
A role field to differentiate between customers (user) and admins (admin).

•	The products table contains product details:
Names, prices, descriptions, and image filenames.

•	The cart table keeps track of items added to users' carts:
Links users to products and stores the quantity of each item.

## 4. Flow of the Website
This section explains the flow of user actions.

•	When a user registers the system validates the input, hashes the password, and saves the user data in the users table.

•	When a user logs in the system verifies the email and password, then starts a session to allow personalized access.

•	When a product is added to the cart the system saves the product details in the cart table, linked to the logged-in user's ID.

•	When an admin adds a product the system uploads the product image, saves the product details in the products table, and displays it on the homepage.

•	When an admin deletes a product the system removes the product entry from the products table, making it unavailable on the website. 

## 5. Security Measures

This section explains how the website ensures security.

•	Passwords are hashed using password_hash() before being stored in the database.

•	Sessions track logged-in users ($_SESSION['user_id'] for customers, $_SESSION['admin_id'] for admins) to restrict access to sensitive pages.

•	Admin login checks the role field in the users table to ensure only admins can access the control panel.                                                                                                                                                

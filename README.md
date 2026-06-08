# Gau Shop

A simple e-commerce platform for beginners, built with:

- Plain PHP
- MySQL
- HTML/CSS
- PHP sessions for cart

## How to Run

1. Copy this project to your server folder, for example:
   - XAMPP: `htdocs/gauEcommerce`
   - WAMP: `www/gauEcommerce`

2. Create a MySQL database named:

```sql
gau_ecommerce
```

3. Import file:

```text
database/schema.sql
```

4. Open file:

```text
config/database.php
```

Update username/password if your MySQL requires it.

5. Open browser:

```text
http://localhost/gauEcommerce
```

## Admin

Admin is simple for learning:

```text
http://localhost/gauEcommerce/admin/login.php
```

You can add, edit, and delete products.

Default admin login:

```text
Username: admin
Password: admin123
```

You can change username/password at:

```text
config/admin.php
```

## Project Structure

```text
admin/              Product management pages
assets/css/         CSS files
config/             Database connection
database/           MySQL schema
includes/           Header and footer
index.php           Home page with products
product.php         Product details
cart.php            Shopping cart
checkout.php        Checkout form
order_success.php   Message after order
```

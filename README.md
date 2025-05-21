# আজরা কিডর Landing Page

A PHP landing page application for the আজরা কিডর product with SQLite database integration.

## Features

- Responsive landing page design
- Product showcase with pricing options
- Customer testimonials/reviews section
- Order form with validation
- SQLite database for storing orders
- Clean URL routing

## Requirements

- PHP 7.4 or higher
- SQLite3 extension for PHP
- Apache web server with mod_rewrite enabled

## Installation

1. Clone this repository to your web server directory:
   ```
   git clone https://github.com/yourusername/Landing_php_laravel.git
   ```

2. Make sure the `database` directory is writable by the web server:
   ```
   chmod 755 database
   ```

3. Configure your web server to point to the `public` directory as the document root.

4. If you're using Apache, make sure the `.htaccess` file in the public directory is properly configured and mod_rewrite is enabled.

## Directory Structure

```
Landing_php_laravel/
├── app/
│   ├── bootstrap.php
│   ├── controllers/
│   │   ├── HomeController.php
│   │   └── OrderController.php
│   └── routes.php
├── config/
├── database/
│   └── database.sqlite (created automatically)
├── public/
│   ├── css/
│   │   └── style.css
│   ├── images/
│   ├── js/
│   │   └── script.js
│   ├── .htaccess
│   └── index.php
├── resources/
│   └── views/
│       ├── home.php
│       ├── order.php
│       └── thank-you.php
└── README.md
```

## Usage

1. Access the landing page by visiting your domain in a web browser.
2. Customers can view product information and testimonials.
3. Customers can select a package and fill out the order form.
4. Order data is stored in the SQLite database.
5. After successful order submission, customers are redirected to a thank you page.

## Customization

- Modify the views in the `resources/views` directory to change the page content and layout.
- Update the CSS in `public/css/style.css` to customize the appearance.
- Add or modify JavaScript functionality in `public/js/script.js`.
- Extend the controllers in the `app/controllers` directory to add new features.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

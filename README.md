# ZEND.APK 

A minimalist, high-performance e-commerce web application built with PHP and Tailwind CSS. ZEND.APK is a webserver with a clean, modular architecture designed for simplicity and extensibility.

**Version:** 2.1.1.1 | **Author:** HASTINGS-EGO | **Release:** 2026/07/18

---

## 🌟 Features

### Core Features
- **Product Catalog** - Browse and view clothing products with images, descriptions, and pricing
- **Shopping Cart** - Add products to cart and manage items
- **Checkout System** - Complete purchase flow with form validation
- **User Accounts** - User authentication and account management
- **Product Search** - Search and filter products by category and name
- **Responsive Design** - Mobile-first design using Tailwind CSS

### Pages & Routes
- **Home** (`/`) - Landing page with featured products
- **Shop** (`/shop/`) - Browse all products
- **Product Details** (`/product/{id}/`) - Individual product page with full description
- **Cart** (`/cart/`) - Shopping cart management
- **Checkout** (`/checkout/`) - Payment and shipping information
- **User Accounts**
  - Login (`/login/`)
  - Signup (`/signup/`)
  - Account Dashboard (`/account/`)
  - Order History (`/account/orders/`)
  - Settings (`/account/settings/`)
- **Collections** (`/collection/`) - Product categories and collections
- **Informational Pages**
  - About (`/about/`)
  - FAQ (`/faq/`)
  - Contact (`/contact/`)
  - Privacy Policy (`/privacy-policy/`)
  - Terms of Use (`/terms-of-use/`)
  - Search Results (`/search/`)

---

## 🛠️ Technology Stack

### Backend
- **PHP 8.1.2** - Server-side logic and routing
- **cURL** - HTTP requests for external APIs
- **Sessions** - User session management

### Frontend
- **HTML5** - Semantic markup
- **Tailwind CSS** - Utility-first CSS framework
- **JavaScript (Vanilla)** - Interactive features
- **Google Fonts** - Typography (Inter, JetBrains Mono)

### Architecture
- **Custom Router** - URL-based routing system
- **Template Engine** - `.kit` file format for templating
- **Compiler System** - HTML compilation with dynamic content
- **API Integration** - External API connectivity via cURL

---

## 📁 Project Structure

```
/home/hastings/zend.app/
├── index.php                 # Application entry point
├── routes.php               # Main routing logic and page dispatcher
├── config.php               # Configuration (API keys, site settings)
├── scripts.php              # Utility functions (routing, page navigation)
├── compiler.php             # HTML compilation and template processing
├── forms.php                # Form handling and submission
├── api.kit                  # API integration layer
├── engine.kit               # Application engine and HTML parsing
├── template.kit             # Template system
├── structure.kit            # Application structure framework
├── server.sh                # PHP development server launch script
├── publish.sh               # Deployment/publishing script
│
├── assets/                  # Static assets
│   ├── script.js           # Frontend JavaScript
│   └── styles.css          # Additional CSS styles
│
├── data/                    # Application data
│   ├── head.php            # HTML head template
│   ├── body.php            # HTML body wrapper
│   ├── navbar.card         # Navigation component
│   ├── theme.structure.kit # Theme structure
│   ├── theme.template.kit  # Theme templates
│   ├── log/                # Application logs
│   │   └── error-file.log  # Error logs
│   └── pages/              # Page components
│       ├── home.page       # Home page
│       ├── shop.page       # Shop/catalog page
│       ├── product.page    # Product detail page
│       ├── cart.page       # Shopping cart
│       ├── checkout.page   # Checkout process
│       ├── login.page      # Login page
│       ├── signup.page     # Registration page
│       ├── account.page    # User dashboard
│       ├── about.page      # About page
│       ├── contact.page    # Contact page
│       ├── faq.page        # FAQ page
│       ├── 404.page        # 404 error page
│       ├── privacy-policy.page
│       ├── terms-of-use.page
│       └── ...             # Additional pages
│
├── autofill.json           # Autofill configuration
├── sample.txt              # Sample data
└── .git/                   # Version control

```

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.1+ with CLI support
- Bash shell
- curl (for HTTP requests)

### Installation

1. **Clone or navigate to the project:**
   ```bash
   cd /home/hastings/zend.app
   ```

2. **Verify PHP is installed:**
   ```bash
   php --version
   ```

3. **Start the development server:**
   ```bash
   bash server.sh
   ```
   
   Or manually with:
   ```bash
   php -S localhost:8089
   ```

4. **Access the application:**
   Open your browser and navigate to:
   ```
   http://localhost:8089
   ```

### Configuration

Edit `config.php` to customize:
- **API Endpoint:** `__SYSTEM_API__` - Backend API URL
- **API Key:** `__SYSTEM_API_KEYS__` - Authentication token
- **Store Index:** `__STORE_INDEX__` - Store identifier
- **Site Title:** `__SITE_TITLE__` - Site name (currently "REDUES")
- **Currency:** `__SYSTEM_CURRENCY__` - Currency symbol (currently "R")
- **External Resources:** JavaScript and theme CDN URLs

---

## 🔄 How It Works

### Routing System
The application uses a **segment-based router** that parses the URL path:

```php
// URL: /shop/product-name/2/
routes(1) // Returns "shop"
routes(2) // Returns "product-name"
routes(3) // Returns "2"
```

**Route Mapping:**
- Empty URL (`/`) → Home page
- `/product/{id}/` → Product detail page
- `/shop/` → Product catalog
- `/account/` → User dashboard with sub-routes
  - `/account/orders/` → Order history
  - `/account/settings/` → Account settings
- Query string override: `/?page=shop` → Forces shop page

### Page Loading Flow

1. **Request arrives** at `index.php`
2. **Router (routes.php)** determines the page based on URL segments
3. **Page resolver** maps segments to page components in `/data/pages/`
4. **Compiler** processes `.kit` template files
5. **API calls** fetch product and user data
6. **HTML** is rendered with Tailwind CSS styling
7. **JavaScript** adds interactivity

### Template System

The app uses `.kit` file format for templating:

```php
// Template definition
$product_template = "
{ 
    id: (#item_id), 
    title: '(#item_name)', 
    price: (#item_price),
    category: '(#item_category)',
    gallery: ['(#item_image)']
}
";

// Template variables that get replaced
$product_input = ['(#item_id)', '(#item_name)', '(#item_description)'];
```

### API Integration

The app integrates with a Varsity Market API:
- **Endpoint:** `http://beta-embedded.varsitymarket.co.za/store-access/`
- **Authentication:** API key in `config.php`
- **Features:** Product fetching, category management, order processing

Fallback data is provided when API is unavailable:
- Default products (Classic Denim Jacket, Leather Tote, Sweater, etc.)
- Sample categories (Women, Men, Accessories, Shoes, Watches)

---

## 📋 Key Functions

### `routes($section)`
Extracts URL segments for routing.

**Example:**
```php
// URL: /shop/women/category/
$page = routes(1);      // "shop"
$filter = routes(2);    // "women"
$sort = routes(3);      // "category"
```

### `page($page, $data = false)`
Generates navigation URLs dynamically.

```php
page('shop');           // Returns: /?page=shop
page('product', 123);   // Returns: /?page=product&data=123
```

### `sendFormData($endpoint, $formData)`
Sends form submissions to external API via cURL.

**Features:**
- POST request with URL-encoded data
- JSON response parsing
- Error handling for cURL and JSON errors
- 30-second timeout

### `analytics()`
Loads the Varsity Market analytics tracking script.

---

## 🎨 Design System

### Colors (Archive Palette)
- **Paper:** `#F5F5F5` (Light background)
- **Ink:** `#1A1A1A` (Dark text)
- **Line:** `#E0E0E0` (Borders)

### Typography
- **Display Font:** Inter (weights: 200, 900)
- **Code Font:** JetBrains Mono (weights: 300, 700)

### Components
- **Product Cards** - Hover animations with image scale and info reveal
- **Navigation** - Minimalist top menu with cart icon
- **Mobile Menu** - Responsive menu with smooth transitions
- **Product Grid** - Responsive grid layout (1 col mobile, 2+ desktop)

---

## 📝 Development Notes

### Adding New Pages

1. Create a new file in `/data/pages/{pagename}.page`
2. Add route in `routes.php`:
   ```php
   elseif ($segment1 === 'pagename') {
       $page = 'pagename';
   }
   ```
3. Include the page template in the rendering flow

### Customizing Products

Edit product templates in `/data/theme.template.kit`:
```php
public function return_shop($state = "products") {
    // Modify $product_template to change product display
}
```

### Error Logging

Errors are logged to `/data/log/error-file.log`. Check this file for debugging.

### API Fallback

If the external API is unavailable, the application automatically uses fallback product data defined in `api.kit`. This ensures the application remains functional even without API connectivity.

---

## 🔧 Deployment

### Using the Deployment Script

```bash
bash publish.sh
```

This script:
1. Verifies PHP installation
2. Checks the application directory
3. Confirms the port is available
4. Starts the PHP server
5. Logs output to `php_server.log`

### Manual Deployment

```bash
php -S 0.0.0.0:8089
```

---

## 🐛 Troubleshooting

### Server won't start
- Check if PHP is installed: `php --version`
- Verify port 8089 is not in use: `lsof -i :8089`
- Check error log: `cat data/log/error-file.log`

### API connection fails
- Verify `config.php` API settings
- Check network connectivity to `beta-embedded.varsitymarket.co.za`
- App falls back to local product data automatically

### Pages not loading
- Check URL routing in `routes.php`
- Verify page files exist in `/data/pages/`
- Check PHP error log for parsing errors

### Styling issues
- Ensure Tailwind CSS CDN is accessible
- Check browser console for JavaScript errors
- Verify styles.css is linked correctly

---

## 📞 Support & Contribution

**Current Version:** 2.1.1.1  
**Last Updated:** 2026/07/18  
**Developed by:** LEVIDOC AGENCY  
**Proprietary:** VARSITYMARKET_TECHNOLOGIES

---

## 📄 License

This project is proprietary software developed for VARSITYMARKET_TECHNOLOGIES. All rights reserved.

---

## 🔗 External Resources

- **API Documentation:** Varsity Market Store API
- **Styling:** [Tailwind CSS](https://tailwindcss.com/)
- **Fonts:** [Google Fonts](https://fonts.google.com/)
- **PHP Docs:** [php.net](https://www.php.net/docs.php)

---

**Ready to launch your VOIDE store!** 🚀

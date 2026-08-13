# ZEND.APK - Web Hosting & Management System

A powerful, minimalist web hosting control panel and management system built with PHP and Tailwind CSS. ZEND.APK provides an intuitive GUI for managing HTML code, templates, themes, and enables seamless deployment of web projects.

**Version:** 2.1.1.1 | **Author:** HASTINGS-EGO | **Release:** 2026/07/18 | **Owner:** Hastings-Ego

---

## 🌟 Features

### Core Management Features
- **HTML Code Editor** - Visual and raw code editing interface for managing website content
- **Template Management** - Create, edit, and organize reusable templates (`.kit` format)
- **Theme System** - Browse, preview, apply, and customize multiple themes
- **File Browser** - Intuitive file management for assets, data, and configurations
- **Live Preview** - Real-time preview of changes before deployment
- **Responsive Design** - Mobile-first admin interface using Tailwind CSS

### Deployment & Hosting Features
- **One-Click Deployment** - Deploy websites with a single command
- **Server Management** - Control PHP development server lifecycle
- **Domain & URL Routing** - Segment-based routing system for flexible URL handling
- **Multi-Site Support** - Host multiple websites from single installation
- **Auto-Fallback System** - Graceful degradation with fallback templates and content
- **Error Logging** - Comprehensive error tracking and debugging tools

### Administration Pages
- **Dashboard** (`/`) - Home/landing page with hosting overview
- **Projects/Sites** (`/shop/`) - Browse and manage hosted websites
- **Project Settings** (`/product/`) - Configure individual project settings
- **Template Editor** (`/cart/`) - Create and manage templates
- **Theme Manager** (`/checkout/`) - Browse and apply themes
- **User Management**
  - Login (`/login/`)
  - Registration (`/signup/`)
  - Account Dashboard (`/account/`)
  - Project History (`/account/orders/`)
  - Settings (`/account/settings/`)
- **File Explorer** (`/collection/`) - Browse project files and assets
- **Documentation & Support**
  - Getting Started (`/about/`)
  - Help Center (`/faq/`)
  - Contact Support (`/contact/`)
  - API Reference (`/privacy-policy/`)
  - Terms of Service (`/terms-of-use/`)
  - Search Docs (`/search/`)

---

## 🛠️ Technology Stack

### Backend
- **PHP 8.1.2** - Server-side hosting control logic and routing
- **cURL** - HTTP requests for inter-server communication
- **Sessions** - Secure user authentication and account management
- **File System I/O** - Direct access to hosting files and configurations

### Frontend
- **HTML5** - Semantic markup for admin interfaces
- **Tailwind CSS** - Utility-first CSS framework for responsive design
- **JavaScript (Vanilla)** - Interactive admin controls and live previews
- **Google Fonts** - Professional typography (Inter, JetBrains Mono)

### Hosting & Architecture
- **PHP Development Server** - Lightweight hosting environment
- **Custom Router** - Flexible URL-based routing for multi-site hosting
- **Template Engine** - `.kit` file format for reusable templates
- **Compiler System** - Dynamic HTML compilation and asset processing
- **Theme System** - Modular theme architecture with fallback support

---

## 📁 Project Structure

```
/home/hastings/zend.app/
├── index.php                 # Hosting control panel entry point
├── routes.php               # Main routing engine for multi-site hosting
├── config.php               # Hosting configuration (API keys, settings)
├── scripts.php              # Utility functions (routing, navigation, helpers)
├── compiler.php             # Template compiler and HTML processor
├── forms.php                # Form handling for deployment and configuration
├── api.kit                  # Server-to-server API integration
├── engine.kit               # Core hosting engine
├── template.kit             # Template management system
├── structure.kit            # Hosting structure framework
├── server.sh                # PHP development server launcher
├── publish.sh               # One-click deployment script
│
├── assets/                  # Admin panel assets
│   ├── script.js           # Admin interface JavaScript
│   └── styles.css          # Additional styling
│
├── data/                    # Hosting data & configurations
│   ├── head.php            # HTML head template
│   ├── body.php            # HTML body wrapper
│   ├── navbar.card         # Admin navigation
│   ├── theme.structure.kit # Theme structure definitions
│   ├── theme.template.kit  # Theme template system
│   ├── log/                # Hosting logs
│   │   └── error-file.log  # System error logs
│   └── pages/              # Admin interface pages
│       ├── home.page       # Hosting dashboard
│       ├── shop.page       # Projects/sites manager
│       ├── product.page    # Project configuration
│       ├── cart.page       # Template editor
│       ├── checkout.page   # Deployment interface
│       ├── login.page      # Authentication
│       ├── signup.page     # Account registration
│       ├── account.page    # User account panel
│       ├── about.page      # Documentation
│       ├── contact.page    # Support contact
│       ├── faq.page        # Help center
│       ├── 404.page        # Error page
│       ├── privacy-policy.page
│       ├── terms-of-use.page
│       └── ...             # Additional admin pages
│
├── autofill.json           # Auto-configuration data
├── sample.txt              # Sample templates
└── .git/                   # Version control

```

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.1+ with CLI support
- Bash shell
- curl (for inter-server communication)

### Installation

1. **Clone or navigate to the hosting control panel:**
   ```bash
   cd /home/hastings/zend.app
   ```

2. **Verify PHP is installed:**
   ```bash
   php --version
   ```

3. **Start the hosting control panel:**
   ```bash
   bash server.sh
   ```
   
   Or manually with:
   ```bash
   php -S localhost:8089
   ```

4. **Access the control panel:**
   Open your browser and navigate to:
   ```
   http://localhost:8089
   ```

### Configuration

Edit `config.php` to customize hosting settings:
- **Hosting API Endpoint:** `__SYSTEM_API__` - Backend hosting API
- **Server Key:** `__SYSTEM_API_KEYS__` - Server authentication token
- **Store Index:** `__STORE_INDEX__` - Hosting account identifier
- **Panel Title:** `__SITE_TITLE__` - Control panel name
- **Currency:** `__SYSTEM_CURRENCY__` - For hosting billing
- **External Resources:** CDN URLs for admin interface components

---

## 🔄 How It Works

### Routing System
The hosting control panel uses a **segment-based router** to organize admin interfaces:

```php
// URL: /projects/website-name/settings/
routes(1) // Returns "projects"
routes(2) // Returns "website-name"
routes(3) // Returns "settings"
```

**Route Mapping:**
- Empty URL (`/`) → Hosting dashboard
- `/projects/` → Manage hosted websites
- `/projects/{name}/` → Configure specific project
- `/themes/` → Browse and apply themes
- `/templates/` → Create and manage templates
- `/account/` → User account with sub-routes
  - `/account/deployments/` → Deployment history
  - `/account/settings/` → Account configuration
- Query string override: `/?page=projects` → Forces projects page

### Admin Panel Flow

1. **User connects** to control panel at `localhost:8089`
2. **Router (routes.php)** determines the admin page
3. **Page resolver** loads admin interface from `/data/pages/`
4. **Compiler** processes template files (`.kit` format)
5. **Content retrieves** from hosted project files
6. **Admin interface** renders with Tailwind CSS
7. **JavaScript interactions** enable live editing and deployment

### Template & Theme System

Templates use `.kit` file format for flexible content management:

```php
// Template definition for reusable components
$template = "
{ 
    id: (#item_id), 
    title: '(#item_name)', 
    content: '(#item_content)',
    theme: '(#theme_name)',
    files: ['(#item_files)']
}
";

// Template variables for replacement
$variables = ['(#item_id)', '(#item_name)', '(#item_content)'];
```

### Deployment Process

1. **User configures** website in admin panel
2. **System compiles** all templates and themes
3. **Assets collected** and prepared for deployment
4. **One-click publish** deploys to hosting server
5. **Error handling** logs issues and rolls back if needed
6. **Live website** immediately available

### Multi-Site Hosting

ZEND.APK supports managing multiple websites:
- Each site has its own configuration
- Shared theme library across all sites
- Independent error logs per site
- Unified admin interface for all projects

---

## 📋 Key Functions

### `routes($section)`
Extracts URL segments for admin panel routing.

**Example:**
```php
// URL: /projects/mysite/settings/
$section = routes(1);     // "projects"
$project = routes(2);     // "mysite"
$action = routes(3);      // "settings"
```

### `page($page, $data = false)`
Generates navigation URLs within the admin panel.

```php
page('projects');         // Returns: /?page=projects
page('project', 'mysite'); // Returns: /?page=project&data=mysite
```

### `sendFormData($endpoint, $formData)`
Sends hosting configurations and deployment commands to backend server via cURL.

**Features:**
- POST request with URL-encoded configuration data
- JSON response parsing for deployment status
- Error handling for connection and parsing issues
- 30-second timeout for deployment operations

### `analytics()`
Tracks hosting control panel usage and deployments.

**Example:**
```php
analytics(); // Logs admin panel session
```

---

## 🎨 Control Panel Design

### Color Scheme
- **Paper (Background):** `#F5F5F5` - Clean admin interface background
- **Ink (Text):** `#1A1A1A` - High contrast for readability
- **Line (Borders):** `#E0E0E0` - Subtle visual separation

### Typography
- **Display Font:** Inter (weights: 200, 900) - For headings and UI
- **Code Font:** JetBrains Mono (weights: 300, 700) - For code editors and snippets

### Admin Interface Components
- **Project Cards** - Hover effects with project details and quick actions
- **Admin Navigation** - Clean menu with current section highlighting
- **Mobile Menu** - Responsive navigation for tablet and mobile admin access
- **Project Grid** - Responsive layout for managing multiple websites
- **Template Editor** - Code editor with syntax highlighting
- **Theme Previewer** - Live preview of theme changes

---

## 📝 Development Notes

### Adding New Admin Pages

1. Create a new file in `/data/pages/{pagename}.page`
2. Add route in `routes.php`:
   ```php
   elseif ($segment1 === 'pagename') {
       $page = 'pagename';
   }
   ```
3. Include the page in the rendering flow

### Creating Custom Themes

1. Create theme structure in `/data/theme.structure.kit`
2. Define templates in `/data/theme.template.kit`
3. Use `.kit` format for reusable components:
   ```php
   public function return_site_theme($state = "themes") {
       $theme_template = '
           <div class="theme-preview">
               <h3>(#theme_name)</h3>
               <p>(#theme_description)</p>
           </div>
       ';
       return $state === 'themes' ? $theme_template : ['(#theme_id)', '(#theme_name)'];
   }
   ```

### Managing Projects

Edit project configurations in `config.php` and extend with dynamic project management in `/data/pages/projects.page`.

### Error Logging & Debugging

All errors are logged to `/data/log/error-file.log`. Monitor this file for:
- Deployment failures
- Template compilation errors
- Authentication issues
- File system access problems

### Fallback & Recovery

The system uses fallback templates when primary templates fail, ensuring continuous hosting availability even during errors.

---

## 🔧 Deployment

### Using One-Click Deployment

From the admin control panel:
1. Select project to deploy
2. Review configuration and theme
3. Click "Deploy" button
4. Monitor deployment progress
5. Access live website upon completion

### Using Deployment Script

```bash
bash publish.sh
```

This script:
1. Verifies PHP installation
2. Checks hosting directory integrity
3. Confirms required ports are available
4. Starts the hosting control panel
5. Logs operations to `php_server.log`

### Manual Hosting Panel Start

```bash
php -S 0.0.0.0:8089
```

Access the hosting control panel from any machine on your network.

---

## 🐛 Troubleshooting

### Control Panel won't start
- Check if PHP is installed: `php --version`
- Verify port 8089 is not in use: `lsof -i :8089`
- Check system error log: `cat data/log/error-file.log`

### Deployment fails
- Verify all required template files exist
- Check project configuration in `config.php`
- Review deployment log in `/data/log/error-file.log`
- Ensure file permissions allow write access

### Themes not applying
- Verify theme files exist in `/data/`
- Check theme syntax in `.kit` files
- Use fallback theme if primary theme fails
- Review theme compiler errors in log

### Admin pages not loading
- Check URL routing in `routes.php`
- Verify page files exist in `/data/pages/`
- Clear browser cache and reload
- Check PHP error log for parsing errors

### File editor not saving
- Ensure `/data/pages/` directory is writable
- Check disk space availability
- Verify file permissions (755 or 777)
- Review file system error logs

---

## 📞 Support & Information

**Current Version:** 2.1.1.1  
**Last Updated:** 2026/07/18  
**Owner:** Hastings-Ego  
**Developed by:** HASTINGS-EGO

---

## 📄 License

This project is proprietary web hosting software owned by **Hastings-Ego**. All rights reserved. Unauthorized copying or distribution is prohibited.

---

## 🔗 Resources

- **Hosting Documentation:** Control panel guides and API reference
- **Admin Panel:** [http://localhost:8089](http://localhost:8089)
- **Styling Framework:** [Tailwind CSS](https://tailwindcss.com/)
- **Fonts:** [Google Fonts](https://fonts.google.com/)
- **Server Documentation:** [PHP Official Docs](https://www.php.net/docs.php)

---

**ZEND.APK - Web Hosting Made Simple** 🚀  
*Manage, Deploy, and Host with Ease*

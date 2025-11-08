# Sunda Framework (SFRW) v3.0

A lightweight PHP MVC framework designed for rapid web application development with built-in security features, error handling, and modern UI components.

## 🚀 Features

- **MVC Architecture**: Model-View-Controller pattern for organized code structure
- **Built-in Security**: SQL injection protection, XSS prevention, and secure session management
- **Error Handling**: Comprehensive error logging and custom error pages
- **Dark Mode**: Modern UI with dark mode toggle functionality
- **Responsive Design**: Bootstrap-based responsive interface
- **Database Support**: MySQL and MySQLi database connections
- **Session Management**: Automatic session timeout and management
- **Email Integration**: SMTP email support with configuration
- **Logging System**: Detailed error and activity logging
- **URL Rewriting**: Clean URLs with Apache mod_rewrite

## 📁 Project Structure

```
sfrw_3/
├── .htaccess                 # Apache configuration with error pages
├── index.php                 # Application entry point
├── env.php                   # Environment configuration
├── app/                      # Application directory
│   ├── Models/              # Data models
│   │   ├── Forgotlink.php   # Password reset model
│   │   └── Users.php        # User model with schema definition
│   ├── app.php              # Application bootstrap
│   └── storage/             # Application storage
├── bootstrap/               # Framework bootstrap
│   ├── app.php              # Application initialization
│   └── theme/               # UI themes and assets
│       ├── css/             # Stylesheets
│       ├── fontawesome/     # FontAwesome icons
│       ├── js/              # JavaScript files
│       └── globe-network.png # Theme assets
├── library/                 # Core framework libraries
│   ├── Autoload.php         # Class autoloader
│   ├── Config.php           # Database configuration
│   ├── Controller.php       # Base controller class
│   ├── Core.php             # Core framework functionality
│   ├── Library.php          # Utility functions library
│   ├── Settings.php         # Framework settings
│   ├── captcha/             # CAPTCHA functionality
│   ├── classes/             # Third-party classes
│   │   ├── class.phpmailer.php # PHPMailer integration
│   │   ├── class.smtp.php   # SMTP configuration
│   │   └── excel_reader2.php # Excel file reader
│   ├── config.txt           # Database connection config
│   └── error/               # Error handling
│       ├── 404handler.php   # 404 error handler
│       ├── Handler.php      # General error handler
│       ├── classnotfound.php # Class not found handler
│       ├── errorhandler.php # Error handler utility
│       └── viewnotfound.php # View not found handler
├── logs/                    # Application logs
│   └── error.log            # Error log file
├── mvc/                     # MVC components
│   ├── controller/          # Controllers
│   │   ├── Controller.php   # Base controller
│   │   └── login.controller.php # Login controller
│   ├── model/               # Models
│   │   └── login.model.php  # Login model
│   └── view/                # Views
│       ├── footer.view.php  # Footer template
│       ├── header.view.php  # Header template
│       ├── index.view.php   # Homepage view
│       ├── login.view.php   # Login page with dark mode
│       ├── logs.view.php    # Logs view
│       └── pagenotfound.view.php # 404 page
├── skin/                    # Application themes
│   └── default/             # Default theme
├── vendor/                  # Third-party packages
│   ├── logcarbon/           # Logging utility
│   │   └── logcarbon.php    # LogCarbon implementation
│   └── sfrw/                # SFRW framework core
│       └── framework/       # Framework core files
└── web/                     # Web routing
    └── route.php            # URL routing configuration
```

## 🔧 Configuration

### Environment Settings (`env.php`)

```php
define('ENVIRONMENT', 'local');        // local, maintenance, production
define('DEBUG', 'true');               // Enable/disable debug mode
define('BASEURL', 'http://localhost/sfrw_3/');
define('DBNAME', '');                 // Database name
```

### Database Configuration (`library/config.txt`)

Format: `host, username, password, database, driver`

```
localhost, root, , framework, MySqli,
```

### Email Configuration

```php
define('MAILACTIVATE', 'false');      // Enable email functionality
define('MAILHOST', 'smtp.yourdomain.com');
define('MAILUSER', 'yourmail@yourdomain.com');
define('MAILPASS', '');
define('MAILPORT', '587');
```

## 🛡️ Security Features

### SQL Injection Protection
- Built-in `anti_injection()` function for input sanitization
- Parameterized queries support
- HTML entity encoding with `htmlspecialchars()`

### XSS Prevention
- Input validation and sanitization
- Output encoding for all user inputs
- CSRF protection mechanisms

### Session Security
- Automatic session timeout (configurable)
- Session regeneration
- Secure session storage

### Error Handling
- Custom error pages (403, 404, 500)
- Error logging to `logs/error.log`
- Environment-based error display

## 🎨 UI Components

### Dark Mode Support
The framework includes a built-in dark mode toggle:
- Automatic theme switching
- Persistent user preference
- Smooth transitions

### Responsive Design
- Bootstrap 5 integration
- Mobile-first approach
- Cross-browser compatibility

### Form Components
- Pre-styled login forms
- Social login buttons
- Remember me functionality
- Password recovery links

## 🔗 Routing System

The routing is handled in `web/route.php`:

```php
// Homepage route
if(routeget('/', ROUTE)){
  return Indexcontroller::index();
}

// Login route
if(routeget('login', ROUTE)){
  require_once view('login');
}

// Logout route
if(routeget('signout', ROUTE)){
  require_once view('signout');
}

// Logs route
if(routeget('logs-', ROUTE)){
  require_once vendors('logcarbon/logcarbon');
  require_once view('logs', [
    $data['title'] = "Logs",
    $data['breadcrumb'] = "Logs",
    $data['icon'] = "fa fa-logs",
  ]);
}

// 404 fallback
{
  require_once view('pagenotfound');
}
```

## 📊 Models

### User Model (`app/Models/Users.php`)

```php
class Users extends Model {
    static public function schemafillable() {
        $fill = [
            'id',
            'fullname', 
            'email',
            'username',
            'passsword',
            'active',
            'role',
        ];
        return implode(", ", $fill); 
    }
    
    static public function schematable($table = "master_users") {
        return $table; 
    }
}
```

## 📝 Utility Functions

### Date & Time Functions
- `datelongind()`: Indonesian long date format
- `date_indo()`: Indonesian date format
- `validateDate()`: Date validation

### Security Functions
- `anti_injection()`: SQL injection prevention
- `anti_number_format()`: Number format cleaning

### Helper Functions
- `get_client_ip()`: Client IP detection
- `get_client_browser()`: Browser detection
- `thousandsCurrencyFormat()`: Currency formatting

## 🚀 Getting Started

1. **Clone/Download** the framework files
2. **Configure** database settings in `library/config.txt`
3. **Set** environment variables in `env.php`
4. **Create** your database tables
5. **Access** the application via web browser

## 📋 Requirements

- PHP 7.4 or higher
- MySQL/MariaDB database
- Apache web server with mod_rewrite
- PHP extensions: mysqli, session, json

## 🔧 Development

### Adding New Routes
1. Edit `web/route.php`
2. Add your route condition
3. Create corresponding controller and view

### Creating Controllers
1. Create new file in `mvc/controller/`
2. Extend the base `Controller` class
3. Implement your methods

### Creating Models
1. Create new file in `app/Models/`
2. Extend the base `Model` class
3. Define your schema and methods

## 🐛 Error Handling

All errors are logged to `logs/error.log` with detailed information:
- Timestamp
- Client IP address
- Browser information
- Error details
- Request URI

## 📄 License

This framework is open-source and available for personal and commercial use.

## 🤝 Contributing

Feel free to contribute to the Sunda Framework by:
- Reporting bugs
- Suggesting features
- Submitting pull requests
- Improving documentation

## 📞 Support

For support and questions, please refer to the documentation or create an issue in the repository.

---

**Sunda Framework (SFRW) v3.0** - Built with ❤️ for modern PHP development
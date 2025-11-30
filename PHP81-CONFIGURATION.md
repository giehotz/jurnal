# PHP 8.1 Configuration for project-jurnalGuru

This document provides instructions on how to configure your environment to use PHP 8.1 for the project-jurnalGuru application.

## Server Configuration Options

### 1. Apache with .htaccess

The project includes an `.htaccess` file in the `public/` directory with directives to specify PHP 8.1:

```apache
# Method 1: AddHandler directive (common on many shared hosts)
AddHandler application/x-httpd-php81 .php

# Method 2: SetEnv directive (some hosts)
SetEnv PHP_VER 8.1

# Method 3: SetEnvIf directive (alternative)
SetEnvIf PHPRC PHP-8.1

# Method 4: Action directive (some configurations)
Action application/x-httpd-php81 /cgi-sys/php81.cgi
```

These directives attempt to set PHP 8.1 using multiple methods that work on different hosting providers.

### 2. PHP Configuration Files

The project includes configuration files to ensure proper PHP 8.1 settings:

- `php.ini` - Main PHP configuration file in the project root
- `public/.user.ini` - Directory-specific PHP configuration

Both files contain the necessary settings and extensions required for CodeIgniter 4:

```
extension=intl
extension=mbstring
extension=mysqli
extension=pdo_mysql
extension=curl
extension=openssl
extension=json
```

## Local Development Environment

### Using PHP Built-in Server

To run the application with PHP 8.1 using the built-in server:

```bash
cd d:\project-jurnalGuru
php -S localhost:8080 -t public
```

Ensure you're using PHP 8.1 by checking the version:

```bash
php -v
```

### Using XAMPP/WAMP

1. Install XAMPP/WAMP with PHP 8.1
2. Place the project in the web server directory
3. Configure the virtual host to point to the `public/` directory
4. Ensure the required PHP extensions are enabled in php.ini

### Using Docker (Optional)

If you're using Docker, you can specify the PHP version in your Dockerfile:

```dockerfile
FROM php:8.1-apache

# Install required extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql intl mbstring

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html/
```

## Required PHP Extensions

CodeIgniter 4 requires the following PHP extensions to be enabled:

- `intl` - Internationalization extension
- `mbstring` - Multibyte string functions
- `mysqli` - MySQL database connectivity
- `pdo_mysql` - PDO MySQL database connectivity
- `curl` - cURL library
- `openssl` - OpenSSL support
- `json` - JSON support

## Environment Variables

Ensure the following environment variables are set in your `.env` file:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
database.default.hostname = localhost
database.default.database = jurnalguru
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

## Troubleshooting

### PHP Version Not Changing

If the application is still using a different PHP version:

1. Check your web server configuration
2. Verify the PHP handler in your hosting control panel
3. Contact your hosting provider for assistance with PHP version selection
4. Ensure no conflicting directives exist in higher-level configuration files

### Extension Not Loading

If required extensions are not loading:

1. Verify extensions are installed and enabled in php.ini
2. Check the PHP error logs for specific error messages
3. Restart your web server after making configuration changes

### Permission Issues

Ensure the `writable/` directory and its subdirectories are writable:

```bash
chmod -R 775 writable/
```

## Verification

To verify that PHP 8.1 is being used, create a temporary PHP file in the `public/` directory:

```php
<?php
phpinfo();
?>
```

Access this file through your web browser and check:
1. PHP Version should show 8.1.x
2. Required extensions should be loaded
3. Configuration settings should match those in php.ini

Remember to remove this file after verification for security reasons.

## Additional Resources

- [CodeIgniter 4 Requirements](https://codeigniter.com/user_guide/intro/requirements.html)
- [PHP 8.1 Documentation](https://www.php.net/manual/en/migration81.php)
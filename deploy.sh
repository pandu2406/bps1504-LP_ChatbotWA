#!/bin/bash

# ============================================
# Laravel Deployment Script for Shared Hosting
# bps-batanghari.com
# ============================================

echo "======================================"
echo "Laravel Deployment Script"
echo "BPS Batang Hari Landing Page"
echo "======================================"
echo ""

# Configuration
LARAVEL_DIR="$HOME/landingpage"
PUBLIC_DIR="$HOME/public_html"
BACKUP_DIR="$HOME/backups"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Functions
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

# Step 1: Check if Laravel directory exists
echo "Step 1: Checking Laravel directory..."
if [ ! -d "$LARAVEL_DIR" ]; then
    print_error "Laravel directory not found at $LARAVEL_DIR"
    echo "Please upload your Laravel project first!"
    exit 1
fi
print_success "Laravel directory found"
echo ""

# Step 2: Create backup directory
echo "Step 2: Creating backup directory..."
mkdir -p "$BACKUP_DIR"
print_success "Backup directory ready"
echo ""

# Step 3: Backup current public_html/index.html
echo "Step 3: Backing up current index.html..."
if [ -f "$PUBLIC_DIR/index.html" ]; then
    TIMESTAMP=$(date +%Y%m%d_%H%M%S)
    cp "$PUBLIC_DIR/index.html" "$BACKUP_DIR/index.html.$TIMESTAMP"
    print_success "Backup created: $BACKUP_DIR/index.html.$TIMESTAMP"
else
    print_warning "No index.html found to backup"
fi
echo ""

# Step 4: Install Composer dependencies
echo "Step 4: Installing Composer dependencies..."
cd "$LARAVEL_DIR"
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader --no-dev
    print_success "Composer dependencies installed"
else
    print_warning "Composer not found. Please install dependencies manually."
fi
echo ""

# Step 5: Setup .env file
echo "Step 5: Setting up .env file..."
if [ ! -f "$LARAVEL_DIR/.env" ]; then
    if [ -f "$LARAVEL_DIR/.env.example" ]; then
        cp "$LARAVEL_DIR/.env.example" "$LARAVEL_DIR/.env"
        print_success ".env file created from .env.example"
        print_warning "Please edit .env file with your database credentials!"
    else
        print_error ".env.example not found"
    fi
else
    print_success ".env file already exists"
fi
echo ""

# Step 6: Generate application key
echo "Step 6: Generating application key..."
php artisan key:generate --force
print_success "Application key generated"
echo ""

# Step 7: Set permissions
echo "Step 7: Setting permissions..."
chmod -R 755 "$LARAVEL_DIR"
chmod -R 775 "$LARAVEL_DIR/storage"
chmod -R 775 "$LARAVEL_DIR/bootstrap/cache"
print_success "Permissions set"
echo ""

# Step 8: Copy public folder contents to public_html
echo "Step 8: Deploying public files..."
echo "Copying Laravel public files to $PUBLIC_DIR..."

# Backup and remove old index.html
if [ -f "$PUBLIC_DIR/index.html" ]; then
    rm "$PUBLIC_DIR/index.html"
    print_success "Old index.html removed"
fi

# Copy public folder contents
cp -r "$LARAVEL_DIR/public/"* "$PUBLIC_DIR/"
print_success "Public files copied"
echo ""

# Step 9: Update index.php
echo "Step 9: Updating index.php..."
cat > "$PUBLIC_DIR/index.php" << 'EOF'
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../landingpage/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../landingpage/vendor/autoload.php';

$app = require_once __DIR__.'/../landingpage/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
EOF
print_success "index.php updated"
echo ""

# Step 10: Create/Update .htaccess
echo "Step 10: Creating .htaccess..."
cat > "$PUBLIC_DIR/.htaccess" << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Disable directory browsing
Options -Indexes

# Protect .env file
<Files .env>
    Order allow,deny
    Deny from all
</Files>
EOF
print_success ".htaccess created"
echo ""

# Step 11: Run migrations (optional, ask user)
echo "Step 11: Database migrations..."
read -p "Do you want to run migrations? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    cd "$LARAVEL_DIR"
    php artisan migrate --force
    print_success "Migrations completed"
    
    read -p "Do you want to seed the database? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        php artisan db:seed --class=AdminUserSeeder --force
        print_success "Database seeded"
    fi
else
    print_warning "Migrations skipped"
fi
echo ""

# Step 12: Optimize Laravel
echo "Step 12: Optimizing Laravel..."
cd "$LARAVEL_DIR"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
print_success "Laravel optimized"
echo ""

# Final summary
echo "======================================"
echo "Deployment Summary"
echo "======================================"
echo ""
print_success "Laravel deployed successfully!"
echo ""
echo "Next steps:"
echo "1. Edit .env file with your database credentials"
echo "2. Visit https://bps-batanghari.com/ to test"
echo "3. Login to admin: https://bps-batanghari.com/admin/login"
echo "   Email: superadmin@bps.com"
echo "   Password: password"
echo ""
echo "Backup location: $BACKUP_DIR"
echo ""
print_warning "Don't forget to:"
echo "- Update database credentials in .env"
echo "- Change default admin password"
echo "- Set APP_DEBUG=false in .env"
echo ""
echo "======================================"

# 🚀 Quick Deploy - Landing Page ke bps-batanghari.com

## 📋 Struktur Target

```
public_html/                           # https://bps-batanghari.com/
├── .htaccess                         # Redirect root → landingpage
├── simabar/                          # Aplikasi lain (tetap ada)
├── spaneng/                          # Aplikasi lain (tetap ada)
└── landingpage/                      # Laravel Landing Page (NEW)
    ├── .htaccess                     # Protect Laravel files
    ├── public/                       # Web accessible
    ├── storage/                      # Writable
    └── .env                          # Production config
```

## 📦 Persiapan di Local

```bash
# 1. Compress project (exclude node_modules & .git)
cd e:/Ngoding
zip -r landingpage.zip landingpage/ -x "landingpage/node_modules/*" "landingpage/.git/*"

# 2. File siap upload: landingpage.zip (~50-100 MB)
```

## 📤 Upload ke Server

### Opsi A: Via cPanel File Manager (Recommended)
1. Login cPanel → File Manager
2. Navigate ke `public_html/`
3. Upload `landingpage.zip`
4. Klik kanan → Extract
5. Delete `landingpage.zip` setelah extract

### Opsi B: Via FTP (FileZilla, WinSCP)
1. Connect FTP ke `bps-batanghari.com`
2. Navigate ke `/public_html/`
3. Upload folder `landingpage/` (akan memakan waktu lama)

### Opsi C: Via Git (Jika ada SSH)
```bash
ssh username@bps-batanghari.com
cd ~/public_html
git clone https://github.com/pandu2406/bps1504-LP_ChatbotWA.git landingpage
```

## ⚙️ Setup di Server (via Terminal cPanel)

### 1. Install Composer Dependencies
```bash
cd ~/public_html/landingpage
composer install --optimize-autoloader --no-dev
```

**Jika composer tidak tersedia:**
- Upload folder `vendor` dari local yang sudah di-generate

### 2. Setup Environment
```bash
cd ~/public_html/landingpage
cp .env.example .env
nano .env  # atau edit via File Manager
```

**Edit `.env` - PENTING:**
```env
APP_NAME="BPS Batang Hari"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bps-batanghari.com/landingpage

# Database - GANTI DENGAN CREDENTIALS ANDA
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database_anda      ← GANTI
DB_USERNAME=username_database        ← GANTI
DB_PASSWORD=password_database        ← GANTI

# WhatsApp
WHATSAPP_NUMBER=6282129660986
WHATSAPP_MESSAGE="Halo Admin BPS Batang Hari..."
```

### 3. Generate Key & Run Migrations
```bash
cd ~/public_html/landingpage

# Generate application key
php artisan key:generate

# Run migrations (pastikan database sudah dibuat di cPanel)
php artisan migrate --force

# Seed admin users
php artisan db:seed --class=AdminUserSeeder --force
```

### 4. Set Permissions
```bash
cd ~/public_html/landingpage

# Set writable permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Jika perlu, set ownership
chgrp -R www-data storage bootstrap/cache
```

### 5. Optimize Laravel
```bash
cd ~/public_html/landingpage

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 🔀 Setup Redirect Root ke Landing Page

### Opsi 1: Via .htaccess (Recommended)

**Buat/Edit file:** `~/public_html/.htaccess`

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect root (/) ke landing page
    RewriteCond %{REQUEST_URI} ^/$
    RewriteRule ^(.*)$ /landingpage/public/ [L]
    
    # Optional: Force HTTPS
    # RewriteCond %{HTTPS} off
    # RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>

# Disable directory browsing
Options -Indexes
```

**Gunakan file helper:**
```bash
# Copy dari project
cp ~/public_html/landingpage/public_html.htaccess ~/public_html/.htaccess
```

### Opsi 2: Via index.php

**Buat file:** `~/public_html/index.php`

```php
<?php
// Redirect to landing page
header('Location: /landingpage/public/');
exit;
?>
```

## 🔒 Protect Laravel Files

**Buat file:** `~/public_html/landingpage/.htaccess`

```apache
# Redirect all requests to public folder
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

# Protect sensitive files
<FilesMatch "(\.env|composer\.json|artisan)$">
    Order allow,deny
    Deny from all
</FilesMatch>

Options -Indexes
```

**Gunakan file helper:**
```bash
cp ~/public_html/landingpage/landingpage.htaccess ~/public_html/landingpage/.htaccess
```

## ✅ Test Deployment

### 1. Test Landing Page
```
https://bps-batanghari.com/
→ Should redirect to landing page

https://bps-batanghari.com/landingpage/public/
→ Landing page Laravel
```

### 2. Test Admin Login
```
https://bps-batanghari.com/landingpage/public/admin/login

Email: superadmin@bps.com
Password: password
```

### 3. Test Aplikasi Lain (Pastikan Tetap Jalan)
```
https://bps-batanghari.com/simabar/      ✓
https://bps-batanghari.com/spaneng/      ✓
https://bps-batanghari.com/temfora/      ✓
```

## 🔑 Default Login Credentials

**Super Admin:**
- Email: `superadmin@bps.com`
- Password: `password`

**Regular Admin:**
- Email: `admin@bps.com`
- Password: `password`

⚠️ **WAJIB ganti password setelah login pertama!**

## 🔧 Troubleshooting

### Error 500 Internal Server Error
```bash
# Set permissions
chmod -R 775 ~/public_html/landingpage/storage
chmod -R 775 ~/public_html/landingpage/bootstrap/cache

# Clear cache
cd ~/public_html/landingpage
php artisan config:clear
php artisan cache:clear

# Check log
tail -f ~/public_html/landingpage/storage/logs/laravel.log
```

### Assets tidak muncul (CSS/JS/Images)
```bash
# Edit .env
nano ~/public_html/landingpage/.env

# Pastikan:
APP_URL=https://bps-batanghari.com/landingpage

# Clear cache
php artisan config:clear
```

### Database Connection Error
```bash
# Cek credentials di .env
# Pastikan database sudah dibuat di cPanel → MySQL Databases
# Test koneksi:
cd ~/public_html/landingpage
php artisan tinker
>>> DB::connection()->getPdo();
```

### Redirect Loop
```bash
# Cek .htaccess tidak konflik
# Pastikan hanya ada 1 redirect rule di public_html/.htaccess
```

## 📁 Struktur Akhir

```
public_html/
├── .htaccess                         ← Redirect root
├── index.php                         ← (Optional) Redirect handler
├── simabar/                          ← Aplikasi lain (tidak terpengaruh)
├── spaneng/                          ← Aplikasi lain (tidak terpengaruh)
├── temfora/                          ← Aplikasi lain (tidak terpengaruh)
└── landingpage/                      ← Laravel Landing Page
    ├── .htaccess                     ← Protect Laravel files
    ├── .env                          ← Production config
    ├── app/
    ├── bootstrap/
    │   └── cache/                    ← Writable (775)
    ├── config/
    ├── database/
    ├── public/                       ← Web accessible
    │   ├── .htaccess
    │   ├── index.php
    │   └── assets/
    ├── resources/
    │   └── views/
    │       └── welcome.blade.php     ← Landing page content
    ├── routes/
    ├── storage/                      ← Writable (775)
    │   └── logs/
    ├── vendor/
    └── artisan
```

## 📋 Deployment Checklist

```
[ ] Upload landingpage.zip ke public_html/
[ ] Extract landingpage.zip
[ ] composer install (atau upload vendor)
[ ] cp .env.example .env
[ ] Edit .env (APP_URL, database credentials)
[ ] php artisan key:generate
[ ] Buat database di cPanel → MySQL Databases
[ ] php artisan migrate --force
[ ] php artisan db:seed --class=AdminUserSeeder
[ ] chmod -R 775 storage bootstrap/cache
[ ] Copy public_html.htaccess → public_html/.htaccess
[ ] Copy landingpage.htaccess → landingpage/.htaccess
[ ] php artisan config:cache
[ ] php artisan route:cache
[ ] php artisan view:cache
[ ] Test: https://bps-batanghari.com/
[ ] Test admin: /landingpage/public/admin/login
[ ] Test aplikasi lain (simabar, spaneng, dll)
[ ] Ganti password default admin
```

---

## 📚 Dokumentasi Lengkap

- **Quick Guide:** File ini
- **Detailed Guide:** Lihat artifact `DEPLOYMENT_MANUAL.md`
- **Helper Files:**
  - `public_html.htaccess` → Copy ke `public_html/.htaccess`
  - `landingpage.htaccess` → Copy ke `landingpage/.htaccess`

## 🆘 Need Help?

**Check Logs:**
```bash
# Laravel log
tail -f ~/public_html/landingpage/storage/logs/laravel.log

# Apache error log (via cPanel → Errors)
```

**Common Issues:**
- Permission denied → `chmod 775 storage`
- 404 on routes → Check `.htaccess`
- Assets 404 → Check `APP_URL` in `.env`
- Database error → Check credentials in `.env`

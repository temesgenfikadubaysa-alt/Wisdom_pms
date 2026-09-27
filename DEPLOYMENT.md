# Wisdom SACCOs - Deployment Guide

## Server Information
- **Domain**: wisdom2s.ethiofinancialnote.com
- **Server**: ethiofur (Shared IP: 91.204.209.37)
- **cPanel URL**: https://sapphire.premium.hostns.io:2083
- **Document Root**: /home/ethiofur/wisdom2s.ethiofinancialnote.com/
- **PHP Version**: 8.1

---

## Step 1: Create MySQL Database in cPanel

1. Login to cPanel
2. Click **MySQL Databases**
3. Under "Create New Database":
   - Type: `ethiofur_pms`
   - Click **Create Database**
4. Scroll to "MySQL Users" → "Add New User":
   - Username: `ethiofur_Jesus` (capital J)
   - Password: `Jesus@123Jesus@123`
   - Click **Create User**
5. Scroll to "Add User To Database":
   - User: `ethiofur_Jesus`
   - Database: `ethiofur_pms`
   - Click **Add**
   - Check **ALL PRIVILEGES**
   - Click **Make Changes**

---

## Step 2: Import Database

1. Click **phpMyAdmin** in cPanel
2. Click `ethiofur_pms` in left sidebar
3. Click **Import** tab (top menu)
4. Click **Choose File**
5. Select: `C:\xampp\htdocs\wisdom\database\sql\production.sql`
6. Click **Go**
7. Verify tables exist: branches, users, daily_reports, daily_transactions, approval_logs, cash_entries, notifications

---

## Step 3: Upload Files to Server

### Option A: cPanel File Manager (slow for many files)

1. Click **File Manager** in cPanel
2. Navigate to: `wisdom2s.ethiofinancialnote.com/`
3. Upload ALL files from `C:\xampp\htdocs\wisdom\`:
   - `app/` folder
   - `bootstrap.php`
   - `composer.json`
   - `composer.lock` (if exists)
   - `database/` folder
   - `storage/` folder
   - `vendor/` folder
   - `.env`
   - `.env.example`

### Option B: SSH (faster)

```bash
# From your computer, run in PowerShell:
scp -r "C:\xampp\htdocs\wisdom\app" ethiofur@wisdom2s.ethiofinancialnote.com:~/wisdom2s.ethiofinancialnote.com/
scp -r "C:\xampp\htdocs\wisdom\database" ethiofur@wisdom2s.ethiofinancialnote.com:~/wisdom2s.ethiofinancialnote.com/
scp -r "C:\xampp\htdocs\wisdom\storage" ethiofur@wisdom2s.ethiofinancialnote.com:~/wisdom2s.ethiofinancialnote.com/
scp -r "C:\xampp\htdocs\wisdom\vendor" ethiofur@wisdom2s.ethiofinancialnote.com:~/wisdom2s.ethiofinancialnote.com/
scp "C:\xampp\htdocs\wisdom\bootstrap.php" ethiofur@wisdom2s.ethiofinancialnote.com:~/wisdom2s.ethiofinancialnote.com/
scp "C:\xampp\htdocs\wisdom\composer.json" ethiofur@wisdom2s.ethiofinancialnote.com:~/wisdom2s.ethiofinancialnote.com/
scp "C:\xampp\htdocs\wisdom\.env" ethiofur@wisdom2s.ethiofinancialnote.com:~/wisdom2s.ethiofinancialnote.com/
```

---

## Step 4: Move Files from public/ to Root

Since the document root is `wisdom2s.ethiofinancialnote.com/` directly:

1. Open **File Manager** → `wisdom2s.ethiofinancialnote.com/`
2. Open `public/` folder
3. Select **ALL files** inside `public/`:
   - `index.php`
   - `.htaccess`
   - `assets/` folder
4. Click **Move** → Move to: `/home/ethiofur/wisdom2s.ethiofinancialnote.com/`
5. Go back to root folder
6. Delete the now-empty `public/` folder

After this step, your root folder should contain:
```
wisdom2s.ethiofinancialnote.com/
  app/
  assets/
  database/
  storage/
  vendor/
  bootstrap.php
  composer.json
  index.php          (MUST be the front controller from public/index.php)
  .htaccess          (MUST have the rewrite rules, NOT the redirect)
  .env
```

**CRITICAL**: The `index.php` in root must be the front controller (`public/index.php`), NOT the old redirect file. The `.htaccess` must have the rewrite rules, NOT the redirect to `/wisdom/public/`.

---

## Step 5: Create Correct .htaccess

1. In File Manager root folder
2. Delete ALL existing `.htaccess` files (there may be multiple)
3. Right-click → **New File** → Name: `.htaccess`
4. Right-click new `.htaccess` → **Edit**
5. Paste this exact content:

```
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

6. Click **Save**

---

## Step 6: Create Storage Directory

1. In File Manager root folder
2. Open `storage/` folder
3. Make sure `rate_limit/` subfolder exists
4. If not, create it: New Folder → `rate_limit`
5. Right-click `storage/` folder → **Change Permissions** → Set to **755**
6. Right-click `rate_limit/` folder → **Change Permissions** → Set to **755**

---

## Step 7: Verify .env File

1. Right-click `.env` → **Edit**
2. Make sure it contains exactly:

```
DB_HOST=localhost
DB_NAME=ethiofur_pms
DB_USER=ethiofur_Jesus
DB_PASS=Jesus@123Jesus@123
APP_URL=https://wisdom2s.ethiofinancialnote.com
APP_ENV=production
APP_DEBUG=false
APP_KEY=
SESSION_LIFETIME=120
RATE_LIMIT_MAX=5
RATE_LIMIT_WINDOW=900
```

3. Click **Save**

**IMPORTANT**: `APP_URL` must NOT have `/wisdom/public` at the end. Since `public/` folder is flattened to root, the URL is just the domain.

---

## Step 8: Run Performance Index Migration (CRITICAL)

After uploading all files, you MUST run this SQL on the production database to add missing performance indexes:

1. Go to **phpMyAdmin** in cPanel
2. Select the database `ethiofur_pms`
3. Click the **SQL** tab
4. Copy and paste the contents of `database/sql/add_performance_indexes.sql`
5. Click **Go** to execute

This adds 12 critical indexes that will dramatically improve query performance.

---

## Step 9: Test the Website

Open browser and go to:
```
https://wisdom2s.ethiofinancialnote.com/login
```

### Default Login
- **Username**: admin
- **Password**: password

**Change password immediately after first login!**

---

## Troubleshooting

### 404 Not Found
- Make sure files are in the ROOT folder, not inside a subfolder
- Make sure `.htaccess` has the correct content (Step 5)
- Make sure `public/` folder was deleted

### Database Connection Failed
- Check `.env` has correct credentials (Step 7)
- Make sure username is `ethiofur_Jesus` (capital J)
- Make sure user was added to database with ALL PRIVILEGES (Step 1)

### Login Doesn't Work
- Make sure `APP_URL=https://wisdom2s.ethiofinancialnote.com` in `.env`
- Clear browser cache (Ctrl+Shift+Delete)
- Try incognito/private window

### Blank Page / 500 Error
- Check PHP version is 8.1+ in cPanel → Select PHP Version
- Check error log: cPanel → Error Log

### Styles/CSS Not Loading
- Check `.env` has `APP_URL=https://wisdom2s.ethiofinancialnote.com` (NOT `/wisdom/public`)
- Make sure `assets/` folder exists in root directory
- Clear browser cache (Ctrl+Shift+Delete)

### Login Page Not Working
- Make sure `index.php` in root is the front controller (not the old redirect)
- Make sure `.htaccess` has rewrite rules (not the redirect to `/wisdom/public/`)
- Make sure `public/` folder was deleted

---

## Files Structure on Server

```
/home/ethiofur/wisdom2s.ethiofinancialnote.com/
  .env                          <- Database config
  .htaccess                     <- URL rewriting
  index.php                     <- Entry point
  bootstrap.php                 <- App loader
  composer.json                 <- Dependencies
  app/
    Config/
    Controllers/
    Core/
    Exceptions/
    Helpers/
    Middleware/
    Repositories/
    Services/
    Validators/
  assets/
    css/
    images/
    js/
  database/
    sql/
  storage/
    rate_limit/                 <- Rate limiter cache
  vendor/                       <- PHP dependencies
```

---

## Post-Deployment Checklist

- [ ] Database created and user added with ALL PRIVILEGES
- [ ] Database tables imported successfully
- [ ] **Ran `add_performance_indexes.sql` on production database** (Step 8)
- [ ] All files uploaded to root directory
- [ ] `public/` folder deleted
- [ ] `.htaccess` has correct rewrite rules
- [ ] `.env` has correct APP_URL (no trailing slash)
- [ ] `storage/rate_limit/` folder exists with 755 permissions
- [ ] Can access login page without 404
- [ ] Can login with admin/password
- [ ] Changed admin password
- [ ] Deleted `setup.php` if it exists

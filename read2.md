# Wisdom SACCOs Daily Task Report System - Progress

## Current Date
August 26, 2026

---

## Objective
Reorganized a flat PHP PDO + MySQL XAMPP project from a single-directory structure into a clean, professional, PSR-4 autoloaded folder structure without changing business logic or database queries.

---

## Project Structure
```
wisdom/
├── _old_backup/              # All original files (safe backup)
├── app/
│   ├── Components/
│   ├── Config/constants.php  # $CATEGORIES, $WORKFLOW_STATUSES, $ROLE_LABELS, etc.
│   ├── Controllers/          # 13 controllers (all pass php -l)
│   │   ├── BaseController.php
│   │   ├── AuthController.php        # Login/logout (standalone HTML)
│   │   ├── DashboardController.php   # Role-based dashboard
│   │   ├── BranchController.php
│   │   ├── CashierController.php     # Cashier with deposit/withdrawal breakdown
│   │   ├── EntryController.php
│   │   ├── NotificationController.php
│   │   ├── ProfileController.php
│   │   ├── ReportActionController.php
│   │   ├── ReportListController.php
│   │   ├── ReportPrintController.php # Standalone (no sidebar layout)
│   │   ├── ReportViewController.php
│   │   └── UserController.php
│   ├── Core/
│   │   ├── Database.php      # PDO singleton (simplified, no auto-migration)
│   │   └── Layout.php        # Shared sidebar/topbar layout
│   ├── DTOs/
│   ├── Exceptions/
│   ├── Helpers/helpers.php   # All global helper functions
│   ├── Interfaces/
│   ├── Middleware/
│   ├── Policies/
│   ├── Repositories/
│   ├── Services/
│   └── Validators/
├── config/
├── database/
│   ├── migrations/
│   ├── seeds/
│   └── sql/database.sql      # Full schema + seed data
├── public/
│   ├── .htaccess
│   ├── index.php             # Root redirect to public/
│   ├── login.php             # Entry point → AuthController::login()
│   ├── logout.php
│   ├── cashier.php           # Entry point → CashierController
│   ├── entry.php
│   ├── branches.php
│   ├── reports.php
│   ├── view.php
│   ├── print.php
│   ├── action.php
│   ├── users.php
│   ├── profile.php
│   ├── notifications.php
│   ├── assets/
│   │   ├── css/style.css
│   │   └── images/PP2-01.jpg
│   └── uploads/
├── resources/
├── routes/
├── storage/
├── tests/
├── .env                     # DB credentials (space in DB name)
├── .env.example
├── .gitignore
├── bootstrap.php            # Session, .env, autoloader, DB init, global $pdo
├── composer.json            # PSR-4: "App\\ → app/"
└── index.php                # Root redirect
```

---

## Key Details

### Database
- DB name: `'wisdom_saccos database'` (with a space — corrected in `.env`)
- Auto-migration removed from `Database.php` due to foreign key errors
- Use `database/sql/database.sql` to set up tables

### Configuration
- Apache DocumentRoot: `C:\xampp\htdocs\wisdom\public`
- APP_URL in `.env`: `http://localhost/wisdom/public`
- Composer PSR-4: `"App\\" → "app/"`
- `$pdo` global and all helper functions remain globally accessible

### Logo
- Image: `public/assets/images/PP2-01.jpg`
- Used in: Sidebar (Layout.php), Login page (AuthController.php), Print page (ReportPrintController.php)
- Previously used text "VS" — now replaced with actual image

---

## Cashier Feature (Latest Work)
### What It Does
The cashier page now shows separate totals for:
1. **Total Deposits (Credit)** — money staff collected from customers (inflow categories)
2. **Total Withdrawals (Debit)** — money staff paid to customers (outflow categories)
3. **Net Cash to Receive** — Deposits - Withdrawals = what staff hands to cashier

### Staff Breakdown Table
| Staff Name | Status | Total Amount | Deposits (Credit) | Withdrawals (Debit) | Net Cash |
|---|---|---|---|---|---|

### Category Keys
- **Deposits (INFLOW_KEYS):** customer_base, new_account, new_member, new_share, saving_deposit, regular_saving, loan_repayment, penalty, transfer_bank, cash_collection
- **Withdrawals (OUTFLOW_KEYS):** withdrawal

### Flow
1. Staff goes out → collects deposits, pays withdrawals
2. Cashier sees each staff's deposits vs withdrawals in a table
3. Net cash = Deposits - Withdrawals = what staff hands to cashier
4. Cashier enters this in "Net Cash Received from Staff" field
5. Confirms cash collection → closes branch

---

## Access URLs
- Login: `http://localhost/wisdom/public/login.php`
- Dashboard: `http://localhost/wisdom/public/index.php` (redirects to login if not authenticated)
- Cashier: `http://localhost/wisdom/public/cashier.php`

---

## Fixes Applied
1. Removed auto-migration from `Database.php` (FK errors)
2. Fixed DB name in `.env` (added space: `wisdom_saccos database`)
3. Added root `index.php` redirect to `public/`
4. Added `.htaccess` for Apache (cleared — was causing Forbidden errors)
5. Fixed `$_SERVER['REQUEST_METHOD']` warning in AuthController
6. Replaced text "VS" brand icons with actual logo image

---

## What Was Done Today
- Reorganized flat PHP project into PSR-4 folder structure
- Created all controllers, core files, helpers
- Updated CashierController with deposit/withdrawal breakdown
- Added logo image to sidebar, login page, and print page

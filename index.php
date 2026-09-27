<?php
/**
 * Main Application Entry Point
 * All requests are routed through here.
 */
require_once __DIR__ . '/bootstrap.php';

use App\Core\Router;
use App\Controllers\{
    AuthController,
    DashboardController,
    EntryController,
    CashierController,
    UserController,
    BranchController,
    ProfileController,
    NotificationController,
    ReportListController,
    ReportViewController,
    ReportPrintController,
    ReportActionController,
    CategoryController,
    AuditorController,
    ReportsController
};

// Public routes (no auth required)
Router::get('/login', [AuthController::class, 'login']);
Router::post('/login', [AuthController::class, 'login']);

// Protected routes (auth required via middleware in controllers)
Router::get('/', [DashboardController::class, 'index']);
Router::get('/dashboard', [DashboardController::class, 'index']);
Router::get('/logout', [AuthController::class, 'logout']);

// Entry routes
Router::get('/entry', [EntryController::class, 'index']);
Router::post('/entry', [EntryController::class, 'index']);

// Cashier routes
Router::get('/cashier', [CashierController::class, 'index']);
Router::post('/cashier', [CashierController::class, 'index']);

// User management routes (admin only)
Router::get('/users', [UserController::class, 'index']);
Router::post('/users', [UserController::class, 'index']);

// Branch management routes (admin only)
Router::get('/branches', [BranchController::class, 'index']);
Router::post('/branches', [BranchController::class, 'index']);

// Category management routes (admin only)
Router::get('/categories', [CategoryController::class, 'index']);
Router::post('/categories', [CategoryController::class, 'index']);

// Auditor review routes
Router::get('/auditor', [AuditorController::class, 'index']);
Router::post('/auditor', [AuditorController::class, 'index']);

// Report routes
Router::get('/reports', [ReportListController::class, 'index']);
Router::post('/reports', [ReportListController::class, 'index']);

Router::get('/view', [ReportViewController::class, 'index']);
Router::post('/view', [ReportViewController::class, 'index']);

Router::get('/print', [ReportPrintController::class, 'index']);

Router::post('/action', [ReportActionController::class, 'process']);

// Professional reports
Router::get('/pro-reports', [ReportsController::class, 'index']);

// Profile routes
Router::get('/profile', [ProfileController::class, 'index']);
Router::post('/profile', [ProfileController::class, 'index']);

// Notification routes
Router::get('/notifications', [NotificationController::class, 'index']);
Router::post('/notifications', [NotificationController::class, 'index']);

// Dispatch the request
Router::dispatch();

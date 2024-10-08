<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\InvoiceController;

// Print Invoice Route
Route::post('/print-invoice', [InvoiceController::class, 'printInvoice']);

// Public routes
Route::get('/', [POSController::class, 'index'])->name('home'); // Home route
Route::get('/login', [POSController::class, 'index'])->name('login'); // Login view route
Route::post('/login', [POSController::class, 'login']); // Login POST route
Route::post('/logout', [POSController::class, 'logout'])->name('logout'); // Logout route

Route::get('/user', [POSController::class, 'user'])->name('user');
Route::get('/user_account', [POSController::class, 'userInformation'])->name('user_account');
Route::post('/upload-avatar', [AvatarController::class, 'upload'])->name('upload.avatar');

// Protected routes (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [POSController::class, 'dashboard'])->name('dashboard');

    Route::get('/cashierdashobard', [POSController::class, 'cashierDashboard'])->name('cashierdashboard');
    Route::get('/salereturn', [POSController::class, 'salereturn'])->name('salereturn');
    Route::get('/saletransaction', [POSController::class, 'saletransaction'])->name('saletransaction');

    // Supplier information
    Route::get('/supplier', [POSController::class, 'supplier'])->name('supplier');
    Route::get('/order', [POSController::class, 'orderSupplies'])->name('order_supplies');
    Route::get('/delivery', [POSController::class, 'delivery'])->name('delivery');

    // Item management
    Route::get('/item_management', [POSController::class, 'itemManagement'])->name('item_management');

    // Inventory management
    Route::get('/inventory_management', [POSController::class, 'inventoryManagement'])->name('inventory_management');
    Route::get('/inventory_adjustment', [POSController::class, 'inventoryAdjustment'])->name('inventory_adjustment');

    // Activity log
    Route::get('/activity_log', [POSController::class, 'activityLog'])->name('activity_log');

    // Reports
    Route::get('/inventory_report', [POSController::class, 'showInventoryReport'])->name('inventory_report');
    Route::get('/reorder_list_report', [POSController::class, 'reorderListReport'])->name('reorder_list_report');
    Route::get('/order_list_report', [POSController::class, 'showOrderList'])->name('order_list_report');
    Route::get('/fast_moving_item_report', [POSController::class, 'fastMovingItemReport'])->name('fast_moving_item_report');
    Route::get('/slow_moving_item_report', [POSController::class, 'slowMovingItemReport'])->name('slow_moving_item_report');
    Route::get('/sales_report', [POSController::class, 'salesReport'])->name('sales_report');
    Route::get('/stock_movement_report', [POSController::class, 'stockMovementReport'])->name('stock_movement_report');
    Route::get('/expiration_report', [POSController::class, 'expirationReport'])->name('expiration_report');
    Route::get('/sales_return_report', [POSController::class, 'showSalesReturnReport'])->name('sales_return_report');
    Route::get('/transaction_history_report', [POSController::class, 'transactionsHistoryReport'])->name('transaction_history_report');
});

// Print receipt route
Route::get('/print-reciept/{items}/{subtotal}/{total}/{discount}/{amountTendered}/{change}', [InvoiceController::class, 'printInvoice'])->name('print-reciept');

// Print Sales Report Route (Corrected)
Route::get('/print-sales-report', [InvoiceController::class, 'generateSalesReport'])->name('print-sales-report');

// Print Inventory Report Route (Corrected)
Route::get('/print-inventory-report', [InvoiceController::class, 'generateInventoryReport'])->name('print-inventory-report');

// Print Reorder List Report route
Route::get('/print-reorder-list-report', [InvoiceController::class, 'generateReorderListReport'])->name('print-reorder-list-report');


Route::get('/inventory_report', [POSController::class, 'inventoryReport'])->name('inventory_report');
Route::get('/vatable-items', [POSController::class, 'showVatableItems'])->name('vatable.items');

Route::get('/reorder-list-report', [POSController::class, 'reorderListReport'])->name('reorder.list.report');

Route::get('/print-order-list-report', [InvoiceController::class, 'generateOrderListReport'])->name('print-order-list-report');

Route::get('/print-transaction-report', [InvoiceController::class, 'generateTransactionHistoryReport'])->name('print-transaction-report');

Route::get('/print-sales-return-report', [InvoiceController::class, 'generateSalesReturnReport'])->name('print-sales-return-report');

Route::get('/print-stock_movement_report', [InvoiceController::class, 'generateStockMovementReport'])->name('print-stock_movement_report');

Route::get('/most-selling-items', [POSController::class, 'mostSellingItems'])->name('most.selling.items');


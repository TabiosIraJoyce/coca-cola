<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\BusinessLineController;
use App\Http\Controllers\Admin\SalesTemplateController;
use App\Http\Controllers\Admin\SalesInputController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ConsolidatedReportController;
use App\Http\Controllers\Admin\PeriodReportController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RouteTargetController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PeriodTargetController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USERS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))
        ->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin.only'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('divisions', DivisionController::class);
        Route::resource('business-lines', BusinessLineController::class);
        Route::resource('sales-templates', SalesTemplateController::class)->except('show');
        Route::resource('users', UserController::class)->except('show');
    });

/*
|--------------------------------------------------------------------------
| ADMIN + USER SHARED
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,user'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /* DASHBOARD */
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/cash-shortage', [DashboardController::class, 'getCashShortage'])->name('dashboard.cash-shortage');
        Route::get('/dashboard/sales-progression', [DashboardController::class, 'getSalesProgression'])->name('dashboard.sales-progression');
        Route::get('/dashboard/treasury', [DashboardController::class, 'treasury'])->name('dashboard.treasury');
        Route::get('/dashboard/treasury-progression', [DashboardController::class, 'getTreasuryProgression'])->name('dashboard.treasury-progression');
        Route::post('/dashboard/update-sales', [DashboardController::class, 'updateSales'])->name('dashboard.update-sales');

        /* CONSOLIDATED DASHBOARD (COKE) */
        Route::get('/consolidated-dashboard',[ConsolidatedReportController::class, 'dashboard'])->name('consolidated.index');

        /* CONSOLIDATED DASHBOARD EXPORTS (COKE) */
        Route::get('/reports/export/pdf',
            [ConsolidatedReportController::class, 'exportDashboardPDF']
        )->name('reports.export.pdf');

        Route::get('/reports/export/csv',
            [ConsolidatedReportController::class, 'exportDashboardCSV']
        )->name('reports.export.csv');

        Route::get('/consolidated-dashboard/print',
            [ConsolidatedReportController::class, 'printPreview']
        )->name('consolidated.print');

        /* SALES INPUTS */
        Route::resource('sales-inputs', SalesInputController::class);

        Route::post('/validated-remittance/update',
            [SalesInputController::class, 'updateValidatedRemittance']
        )->name('validated-remittance.update');

        Route::delete('/validated-remittance/delete-receipt/{id}',
            [SalesInputController::class, 'deleteReceipt']
        )->name('validated-remittance.delete-receipt');

        /* SALES REPORTS */
        Route::get('/reports', [SalesReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-csv', [SalesReportController::class, 'exportCsv'])->name('reports.export.csv');
        Route::get('/reports/print', [SalesReportController::class, 'print'])->name('reports.print');

        Route::get('/reports/treasury/export-csv',
            [SalesReportController::class, 'exportTreasuryCsv']
        )->name('reports.treasury.export.csv');

        Route::get('/reports/treasury/print',
            [SalesReportController::class, 'printTreasury']
        )->name('reports.treasury.print');

        /* ===============================
           CONSOLIDATED (4 REPORT TYPES)
        =============================== */
        Route::get('/reports/consolidated',
            [ReportController::class, 'consolidated']
        )->name('reports.consolidated');

        Route::get('/reports/consolidated/export/pdf',
            [ReportController::class, 'exportPdf']
        )->name('reports.consolidated.export.pdf');

        Route::get('/reports/consolidated/export/csv',
            [ReportController::class, 'exportCsv']
        )->name('reports.consolidated.export.csv');

        /*
        |--------------------------------------------------------------------------
        | PERIOD PERFORMANCE REPORTS
        |--------------------------------------------------------------------------
        */
        Route::get('/reports/periods', [ConsolidatedReportController::class, 'periodSummary'])->name('reports.periods.index');
        Route::get('/reports/periods/create', [PeriodReportController::class, 'create'])->name('reports.periods.create');
        Route::post('/reports/periods',[PeriodReportController::class, 'store'])->name('reports.periods.store');
        Route::put('/reports/periods/{report}',[App\Http\Controllers\Admin\PeriodReportController::class, 'update'])
            ->whereNumber('report')
            ->name('reports.periods.update');
        Route::get('/reports/periods/{report}/edit',[PeriodReportController::class, 'edit'])
            ->whereNumber('report')
            ->name('reports.periods.edit');
        Route::post('/reports/periods/{report}/submit', [PeriodReportController::class, 'submit'])
            ->whereNumber('report')
            ->name('reports.periods.submit');
        Route::post('/reports/periods/{report}/approve', [PeriodReportController::class, 'approve'])
            ->whereNumber('report')
            ->name('reports.periods.approve');
        Route::delete('/reports/periods/{report}', [ConsolidatedReportController::class, 'destroyPeriodReport'])
            ->whereNumber('report')
            ->name('reports.periods.destroy');
        Route::get('/reports/periods/{report}', [PeriodReportController::class, 'show'])
            ->whereNumber('report')
            ->name('reports.periods.show');
        Route::get('/reports/periods/export/range',[ConsolidatedReportController::class, 'exportRange'])->name('reports.periods.export.range');
        Route::get('/reports/periods/print-preview',[ConsolidatedReportController::class, 'periodReportsPrintPreview'])->name('reports.periods.print-preview');
        Route::get('/reports/periods/dates',[ConsolidatedReportController::class, 'getPeriodDates'])->name('reports.periods.dates');
        Route::get('/reports/consolidated/print',[\App\Http\Controllers\Admin\ConsolidatedReportController::class, 'printPreview'])->name('reports.consolidated.print');
        Route::post('/consolidated/save-targets',[ConsolidatedReportController::class, 'saveDivisionTargets'])->name('consolidated.saveTargets');

        Route::post('/period-targets', [PeriodTargetController::class, 'store'])->name('period-targets.store');
        Route::get('/period-targets/create',[PeriodTargetController::class, 'create'])->name('period-targets.create');
        Route::get('/period-targets/list', [PeriodTargetController::class, 'index'])->name('period-targets.index');
        Route::delete('/period-targets/{target}', [PeriodTargetController::class, 'destroy'])->name('period-targets.destroy');
        Route::get('/period-targets', [PeriodTargetController::class, 'index'])->name('period-targets.index');
        Route::get('/period-targets/show',[PeriodTargetController::class, 'show'])->name('period-targets.show');

        Route::get('/consolidated/global-search', [ConsolidatedReportController::class, 'globalSearch'])->name('consolidated.global.search');
        Route::get('/consolidated/autocomplete',[ConsolidatedReportController::class, 'autocomplete'])->name('consolidated.autocomplete');

        // products and pricing
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // routes target
        Route::post('/route-targets/store', [RouteTargetController::class, 'store'])->name('route-targets.store');

        Route::delete('/products/bulk-delete',[ProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');

    });

/*
|--------------------------------------------------------------------------
| MANUAL MULTI-STEP REPORT ADD (ADMIN ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin/reports')
    ->name('admin.reports.')
    ->group(function () {

        Route::get('/add/select-division', [ReportController::class, 'selectDivision'])->name('add.select-division');
        Route::post('/add/report-type', [ReportController::class, 'chooseReportType'])->name('choose-report-type');
        Route::post('/add/process-type', [ReportController::class, 'addReportType'])->name('add.report-type');
        Route::get('/add/{division}/{report_type}', [ReportController::class, 'addReport'])->name('add.form');
        Route::post('/store/{report_type}', [ReportController::class, 'storeReport'])->name('store');
    });

Route::prefix('admin')
    ->middleware(['auth'])
    ->name('admin.')
    ->group(function () {

        /* =========================
           BANK MANAGEMENT
        ========================= */
        Route::get('/banks', [BankController::class, 'index'])->name('banks.index');
        Route::get('/banks/create', [BankController::class, 'create'])->name('banks.create');
        Route::post('/banks', [BankController::class, 'store'])->name('banks.store');
        Route::get('/banks/{bank}/edit', [BankController::class, 'edit'])->name('banks.edit');
        Route::put('/banks/{bank}', [BankController::class, 'update'])->name('banks.update');
        Route::delete('/banks/{bank}', [BankController::class, 'destroy'])->name('banks.destroy');
        Route::patch('/banks/{bank}/toggle-status', [BankController::class, 'toggleStatus'])->name('banks.toggle-status');
        Route::get('/banks/search', [BankController::class, 'search'])->name('banks.search');

    });

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // BASIC CRUD
    Route::get('/customers', [CustomerController::class, 'index'])
        ->name('customers.index');

    Route::get('/customers/create', [CustomerController::class, 'create'])
        ->name('customers.create');

    // IMPORT (EXCEL/CSV)
    Route::get('/customers/import', [CustomerController::class, 'importForm'])
        ->name('customers.import.form');
    Route::post('/customers/import', [CustomerController::class, 'importStore'])
        ->name('customers.import.store');
    Route::get('/customers/import/template', [CustomerController::class, 'downloadTemplate'])
        ->name('customers.import.template');

    Route::post('/customers', [CustomerController::class, 'store'])
        ->name('customers.store');

    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])
        ->name('customers.edit');

    Route::put('/customers/{customer}', [CustomerController::class, 'update'])
        ->name('customers.update');

    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
        ->name('customers.destroy');

    // Bulk delete
    Route::delete('/customers/bulk-delete', [CustomerController::class, 'bulkDestroy'])
        ->name('customers.bulk-destroy');
});

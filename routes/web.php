<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\KbArticleController;
use App\Http\Controllers\KbCategoryController;
use App\Http\Controllers\Maintenance\InventoryPartController;
use App\Http\Controllers\Maintenance\MaintenanceScheduleController;
use App\Http\Controllers\Maintenance\MaintenanceTaskController;
use App\Http\Controllers\RepairRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest routes (only for non-authenticated users)
Route::middleware("guest")->group(function () {
    Route::get("login", [LoginController::class, "showLoginForm"])->name(
        "login",
    );
    Route::post("login", [LoginController::class, "login"]);
});

// Public Repair Request Routes (no login required)
Route::prefix("repair-requests")
    ->name("repair-requests.")
    ->group(function () {
        Route::get("/create", [RepairRequestController::class, "create"])->name(
            "create",
        );
        Route::post("/store", [RepairRequestController::class, "store"])->name(
            "store",
        );
        Route::get("/success/{requestNumber}", [
            RepairRequestController::class,
            "success",
        ])->name("success");
    });

// Public Home Page (welcome view)
Route::get("/", function () {
    return view("welcome");
})->name("welcome");

// Authenticated routes
Route::middleware(["auth", "password.expired"])->group(function () {
    // Logout
    Route::post("logout", [LoginController::class, "logout"])->name("logout");

    // Password change (accessible even if must_change_password)
    Route::get("change-password", [
        PasswordChangeController::class,
        "show",
    ])->name("password.change");
    Route::put("change-password", [
        PasswordChangeController::class,
        "update",
    ])->name("password.update");

    // Dashboard (must be logged in)
    Route::get("/dashboard", [DashboardController::class, "index"])->name(
        "dashboard",
    );

    // Ticket Category Management (MUST be before tickets resource to avoid conflict)
    Route::prefix("tickets/categories")
        ->name("tickets.categories.")
        ->group(function () {
            Route::get("/", [TicketCategoryController::class, "index"])->name(
                "index",
            );
            Route::get("/create", [
                TicketCategoryController::class,
                "create",
            ])->name("create");
            Route::post("/", [TicketCategoryController::class, "store"])->name(
                "store",
            );
            Route::get("/{category}/edit", [
                TicketCategoryController::class,
                "edit",
            ])->name("edit");
            Route::put("/{category}", [
                TicketCategoryController::class,
                "update",
            ])->name("update");
            Route::delete("/{category}", [
                TicketCategoryController::class,
                "destroy",
            ])->name("destroy");
        });

    // Ticket Routes
    Route::resource("tickets", TicketController::class)->except(["destroy"]);

    // Ticket delete route (soft delete)
    Route::delete("tickets/{ticket}", [TicketController::class, "destroy"])
        ->name("tickets.destroy")
        ->can("delete", "ticket");

    // Additional ticket actions
    Route::post("tickets/{ticket}/assign", [TicketController::class, "assign"])
        ->name("tickets.assign")
        ->can("assign", "ticket");

    Route::post("tickets/{ticket}/status", [
        TicketController::class,
        "changeStatus",
    ])
        ->name("tickets.status.change")
        ->can("changeStatus", "ticket");

    Route::post("tickets/{ticket}/link-kb", [
        TicketController::class,
        "linkKbArticle",
    ])
        ->name("tickets.link-kb")
        ->can("update", "ticket");

    Route::post("tickets/{ticket}/comments", [
        TicketController::class,
        "addComment",
    ])
        ->name("tickets.comments.add")
        ->can("comment", "ticket");

    // SLA pause/resume
    Route::post("tickets/{ticket}/sla/pause", [
        TicketController::class,
        "toggleSlaPause",
    ])
        ->name("tickets.sla.pause")
        ->can("update", "ticket");

    // Knowledge Base Routes
    Route::resource("kb", KbArticleController::class);
    Route::post("kb/{article}/vote", [KbArticleController::class, "vote"])
        ->name("kb.vote")
        ->can("vote", "article");

    // KB Image Upload
    Route::post("kb/upload-image", [
        KbArticleController::class,
        "uploadImage",
    ])->name("kb.upload-image");

    // KB Category Management (IT staff/manager only)
    Route::middleware("can:viewAny,App\Models\KbCategory")
        ->prefix("kb-categories")
        ->name("kb.categories.")
        ->group(function () {
            Route::get("/", [KbCategoryController::class, "index"])->name(
                "index",
            );
            Route::get("/create", [
                KbCategoryController::class,
                "create",
            ])->name("create");
            Route::post("/", [KbCategoryController::class, "store"])->name(
                "store",
            );
            Route::get("/{category}/edit", [
                KbCategoryController::class,
                "edit",
            ])->name("edit");
            Route::put("/{category}", [
                KbCategoryController::class,
                "update",
            ])->name("update");
            Route::delete("/{category}", [
                KbCategoryController::class,
                "destroy",
            ])->name("destroy");
        });

    // Asset Management (IT staff/manager only)
    Route::resource("assets", AssetController::class);

    // Additional asset actions
    Route::post("assets/{asset}/assign", [AssetController::class, "assign"])
        ->name("assets.assign")
        ->can("update", "asset");

    Route::post("assets/{asset}/status", [
        AssetController::class,
        "changeStatus",
    ])
        ->name("assets.status.change")
        ->can("update", "asset");

    Route::post("assets/{asset}/maintenance", [
        AssetController::class,
        "logMaintenance",
    ])
        ->name("assets.maintenance.log")
        ->can("update", "asset");

    // Department Management (IT Manager & Super Admin only)
    Route::resource("departments", DepartmentController::class);

    // User Management (IT Manager & Super Admin only)
    Route::resource("users", UserController::class);

    // User Management (IT Manager & Super Admin only)
    Route::resource('users', UserController::class);

    // Maintenance Management Module
    Route::prefix("maintenance")
        ->name("maintenance.")
        ->group(function () {
            // Schedules
            Route::resource(
                "schedules",
                MaintenanceScheduleController::class,
            )->except(["show"]);
            Route::post("schedules/generate-tasks", [
                MaintenanceScheduleController::class,
                "generateTasks",
            ])->name("schedules.generate-tasks");

            // Tasks
            Route::resource("tasks", MaintenanceTaskController::class);
            Route::get("tasks/{task}/execute", [
                MaintenanceTaskController::class,
                "execute",
            ])->name("tasks.execute");
            Route::post("tasks/{task}/execute", [
                MaintenanceTaskController::class,
                "saveExecution",
            ])->name("tasks.save-execution");
            Route::post("tasks/{task}/photos", [
                MaintenanceTaskController::class,
                "uploadPhoto",
            ])->name("tasks.upload-photo");
            Route::get("tasks/{task}/approval", [
                MaintenanceTaskController::class,
                "requestApproval",
            ])->name("tasks.request-approval");
            Route::post("tasks/{task}/approve", [
                MaintenanceTaskController::class,
                "approve",
            ])->name("tasks.approve");
            Route::post("tasks/{task}/reject", [
                MaintenanceTaskController::class,
                "reject",
            ])->name("tasks.reject");

            // Inventory Parts
            Route::resource("inventory", InventoryPartController::class);
            Route::post("inventory/{inventory}/adjust", [
                InventoryPartController::class,
                "adjustStock",
            ])->name("inventory.adjust");
            Route::get("inventory/{inventory}/stock-in", [
                InventoryPartController::class,
                "stockIn",
            ])->name("inventory.stock-in");
            Route::post("inventory/{inventory}/stock-in", [
                InventoryPartController::class,
                "processStockIn",
            ])->name("inventory.process-stock-in");
        });

    // Reporting Routes (IT Staff, IT Manager, Super Admin only)
    Route::prefix("reports")
        ->name("reports.")
        ->group(function () {
            Route::get("/", [ReportController::class, "index"])->name("index");
            Route::post("/tickets", [
                ReportController::class,
                "generateTicketReport",
            ])->name("tickets");
            Route::post("/assets", [
                ReportController::class,
                "generateAssetReport",
            ])->name("assets");
            Route::post("/kb", [
                ReportController::class,
                "generateKbReport",
            ])->name("kb");
        });

    // Repair Request Verification (IT Manager & Super Admin only)
    Route::prefix("admin/repair-requests")
        ->name("repair-requests.admin.")
        ->group(function () {
            Route::get("/", [RepairRequestController::class, "index"])->name(
                "index",
            );
            Route::get("/{repairRequest}", [
                RepairRequestController::class,
                "show",
            ])->name("show");
            Route::post("/{repairRequest}/approve", [
                RepairRequestController::class,
                "approve",
            ])->name("approve");
            Route::post("/{repairRequest}/reject", [
                RepairRequestController::class,
                "reject",
            ])->name("reject");
            Route::post("/{repairRequest}/convert", [
                RepairRequestController::class,
                "convertToTicket",
            ])->name("convert");
        });
});

// Fallback untuk authenticated users yang belum ganti password
Route::middleware("auth")->group(function () {
    Route::get("change-password", [
        PasswordChangeController::class,
        "show",
    ])->name("password.change");
    Route::put("change-password", [
        PasswordChangeController::class,
        "update",
    ])->name("password.update");
});

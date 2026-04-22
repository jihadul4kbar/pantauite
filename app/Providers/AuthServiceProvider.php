<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Department;
use App\Models\InventoryPart;
use App\Models\KbArticle;
use App\Models\KbCategory;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceTask;
use App\Models\RepairRequest;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\Vendor;
use App\Policies\AssetPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\InventoryPartPolicy;
use App\Policies\KbArticlePolicy;
use App\Policies\KbCategoryPolicy;
use App\Policies\MaintenanceSchedulePolicy;
use App\Policies\MaintenanceTaskPolicy;
use App\Policies\RepairRequestPolicy;
use App\Policies\TicketCategoryPolicy;
use App\Policies\TicketPolicy;
use App\Policies\VendorPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Ticket::class => TicketPolicy::class,
        Asset::class => AssetPolicy::class,
        KbArticle::class => KbArticlePolicy::class,
        KbCategory::class => KbCategoryPolicy::class,
        Department::class => DepartmentPolicy::class,
        TicketCategory::class => TicketCategoryPolicy::class,
        MaintenanceSchedule::class => MaintenanceSchedulePolicy::class,
        MaintenanceTask::class => MaintenanceTaskPolicy::class,
        InventoryPart::class => InventoryPartPolicy::class,
        RepairRequest::class => RepairRequestPolicy::class,
        Vendor::class => VendorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates untuk simple permission checks
        $this->registerGates();
    }

    /**
     * Register gates untuk system-level permissions
     */
    protected function registerGates(): void
    {
        // All permissions that can be used with @can directive
        $permissions = [
            // User Management
            'manage-users',
            'manage-roles',
            
            // Department Management
            'manage-departments',
            
            // Ticket Management
            'manage-tickets',
            'view-all-tickets',
            'view-own-tickets',
            'create-tickets',
            'update-own-tickets',
            'comment-tickets',
            'assign-tickets',
            
            // Asset Management
            'manage-assets',
            'view-assets',
            
            // Knowledge Base
            'manage-kb',
            'view-kb',
            
            // SLA Management
            'manage-sla',
            
            // Categories
            'manage-categories',
            
            // Vendor Management
            'manage-vendors',
            
            // Reports
            'manage-reports',
            'view-reports',
            'export-reports',
            
            // Dashboard & Analytics
            'view-dashboard',
            'view-audit-logs',
            
            // System
            'manage-system',
        ];

        // Register each permission as a Gate
        foreach ($permissions as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }
    }
}

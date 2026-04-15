<?php

namespace App\Services;

use App\Enums\AssetStatus;
use App\Models\Asset;
use App\Models\AssetLifecycleLog;
use App\Models\User;
use App\Repositories\AssetRepository;
use Illuminate\Support\Facades\DB;

class AssetService
{
    public function __construct(
        private AssetRepository $assets
    ) {}

    /**
     * Get paginated assets with filters
     */
    public function getPaginated(array $filters = [], int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->assets->getPaginated($filters, $perPage);
    }

    /**
     * Create new asset
     */
    public function createAsset(array $data, User $user): Asset
    {
        return DB::transaction(function () use ($data, $user) {
            // Generate asset code
            $assetCode = $this->assets->generateAssetCode($data['asset_type']);

            // Calculate initial depreciated value if applicable
            if (isset($data['price']) && isset($data['useful_life_years']) && $data['useful_life_years'] > 0) {
                $data['depreciated_value'] = $data['price'];
                $data['depreciation_start_date'] = $data['purchase_date'] ?? now();
            }

            // Create asset
            $asset = $this->assets->create([
                'asset_code' => $assetCode,
                'asset_type' => $data['asset_type'],
                'name' => $data['name'],
                'brand' => $data['brand'] ?? null,
                'model' => $data['model'] ?? null,
                'serial_number' => $data['serial_number'] ?? null,
                'part_number' => $data['part_number'] ?? null,
                'specs' => $data['specs'] ?? null,
                'status' => $data['status'] ?? 'inventory',
                'condition' => $data['condition'] ?? 'new',
                'location' => $data['location'] ?? null,
                'vendor_id' => $data['vendor_id'] ?? null,
                'vendor_name' => $data['vendor_name'] ?? null,
                'purchase_order_number' => $data['purchase_order_number'] ?? null,
                'purchase_date' => $data['purchase_date'] ?? null,
                'price' => $data['price'] ?? null,
                'currency' => $data['currency'] ?? 'IDR',
                'warranty_start' => $data['warranty_start'] ?? null,
                'warranty_end' => $data['warranty_end'] ?? null,
                'warranty_provider' => $data['warranty_provider'] ?? null,
                'warranty_notes' => $data['warranty_notes'] ?? null,
                'depreciation_method' => $data['depreciation_method'] ?? 'straight_line',
                'useful_life_years' => $data['useful_life_years'] ?? null,
                'depreciated_value' => $data['depreciated_value'] ?? null,
                'depreciation_start_date' => $data['depreciation_start_date'] ?? null,
                'notes' => $data['notes'] ?? null,
                'images' => $data['images'] ?? null,
                // Assignment fields
                'assigned_to_user_id' => $data['assigned_to_user_id'] ?? null,
                'assigned_to_department_id' => $data['assigned_to_department_id'] ?? null,
                'assigned_at' => isset($data['assigned_to_user_id']) || isset($data['assigned_to_department_id']) ? now() : null,
                'assigned_notes' => $data['assigned_notes'] ?? null,
            ]);

            // Log creation
            $this->logLifecycle($asset, 'procurement', $asset->status, 'Asset created', $user);

            return $asset;
        });
    }

    /**
     * Update asset
     */
    public function updateAsset(Asset $asset, array $data, User $user): Asset
    {
        $oldStatus = $asset->status;

        // Handle assignment timestamps
        if (isset($data['assigned_to_user_id']) || isset($data['assigned_to_department_id'])) {
            if (!$asset->assigned_to_user_id && !$asset->assigned_to_department_id) {
                // First time assignment
                $data['assigned_at'] = now();
            } elseif (!$data['assigned_to_user_id'] && !$data['assigned_to_department_id']) {
                // Unassigning
                $data['assigned_at'] = null;
            }
        }

        $this->assets->update($asset, $data);

        // Log status change if status changed
        $newStatus = $data['status'] ?? $oldStatus;
        if ($oldStatus !== $newStatus) {
            $this->logLifecycle($asset, $oldStatus, $newStatus, 'Status updated', $user);
        }

        return $asset->fresh();
    }

    /**
     * Assign asset to user
     */
    public function assignToUser(Asset $asset, ?int $userId, User $user): Asset
    {
        $updateData = [
            'assigned_to_user_id' => $userId,
            'assigned_at' => $userId ? now() : null,
        ];

        if ($userId) {
            $updateData['status'] = 'deployed';
            $assignedUser = User::find($userId);
            $this->logLifecycle(
                $asset,
                $asset->status,
                'deployed',
                "Assigned to user: {$assignedUser?->name}",
                $user
            );
        } else {
            $updateData['status'] = 'inventory';
            $this->logLifecycle($asset, $asset->status, 'inventory', 'Unassigned from user', $user);
        }

        $this->assets->update($asset, $updateData);

        return $asset->fresh();
    }

    /**
     * Assign asset to department
     */
    public function assignToDepartment(Asset $asset, ?int $departmentId, User $user): Asset
    {
        $updateData = [
            'assigned_to_department_id' => $departmentId,
            'assigned_at' => $departmentId ? now() : null,
        ];

        $this->assets->update($asset, $updateData);

        return $asset->fresh();
    }

    /**
     * Change asset status
     */
    public function changeStatus(Asset $asset, string $newStatus, User $user, ?string $reason = null): Asset
    {
        $oldStatus = $asset->status;

        $this->assets->update($asset, [
            'status' => $newStatus,
        ]);

        $this->logLifecycle($asset, $oldStatus, $newStatus, $reason ?? 'Status changed', $user);

        return $asset->fresh();
    }

    /**
     * Log maintenance
     */
    public function logMaintenance(Asset $asset, array $data, User $user): \App\Models\MaintenanceLog
    {
        return $asset->maintenanceLogs()->create([
            'description' => $data['description'],
            'cost' => $data['cost'] ?? 0,
            'performed_by' => $data['performed_by'] ?? null,
            'date' => $data['date'] ?? now(),
            'vendor_id' => $data['vendor_id'] ?? null,
        ]);
    }

    /**
     * Delete asset
     */
    public function deleteAsset(Asset $asset, User $user): bool
    {
        $this->logLifecycle($asset, $asset->status, 'deleted', 'Asset deleted', $user);

        return $asset->delete();
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return [
            'total' => Asset::count(),
            'deployed' => Asset::deployed()->count(),
            'available' => Asset::available()->count(),
            'maintenance' => Asset::byStatus('maintenance')->count(),
            'warranty_expiring' => Asset::warrantyExpiring(30)->count(),
            'by_status' => $this->assets->getCountByStatus(),
            'by_type' => $this->assets->getCountByType(),
        ];
    }

    /**
     * Log lifecycle change
     */
    protected function logLifecycle(Asset $asset, ?string $fromStatus, ?string $toStatus, string $reason, User $user): void
    {
        AssetLifecycleLog::create([
            'asset_id' => $asset->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'reason' => $reason,
            'changed_by' => $user->id,
        ]);
    }
}

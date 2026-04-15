<?php

namespace App\Repositories;

use App\Models\Asset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AssetRepository
{
    /**
     * Get paginated assets with filters
     */
    public function getPaginated(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Asset::with(['vendor', 'assignedUser', 'assignedDepartment']);

        // Apply filters
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['type']) && $filters['type']) {
            $query->where('asset_type', $filters['type']);
        }

        if (isset($filters['vendor_id']) && $filters['vendor_id']) {
            $query->where('vendor_id', $filters['vendor_id']);
        }

        if (isset($filters['assigned_to_user_id']) && $filters['assigned_to_user_id']) {
            $query->where('assigned_to_user_id', $filters['assigned_to_user_id']);
        }

        if (isset($filters['assigned_to_department_id']) && $filters['assigned_to_department_id']) {
            $query->where('assigned_to_department_id', $filters['assigned_to_department_id']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('asset_code', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find asset by ID
     */
    public function findById(int $id): ?Asset
    {
        return Asset::with([
            'vendor',
            'assignedUser',
            'assignedDepartment',
            'lifecycleLogs.user',
            'maintenanceLogs',
            'documents.uploadedBy',
        ])->find($id);
    }

    /**
     * Find asset by asset code
     */
    public function findByCode(string $code): ?Asset
    {
        return Asset::where('asset_code', $code)->first();
    }

    /**
     * Create new asset
     */
    public function create(array $data): Asset
    {
        return Asset::create($data);
    }

    /**
     * Update asset
     */
    public function update(Asset $asset, array $data): bool
    {
        return $asset->update($data);
    }

    /**
     * Delete asset (soft delete)
     */
    public function delete(Asset $asset): bool
    {
        return $asset->delete();
    }

    /**
     * Get assets by status
     */
    public function getByStatus(string $status, int $limit = 10): Collection
    {
        return Asset::with(['assignedUser', 'vendor'])
            ->where('status', $status)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get assets expiring warranty
     */
    public function getWarrantyExpiring(int $days = 30): Collection
    {
        return Asset::with(['vendor', 'assignedUser'])
            ->warrantyExpiring($days)
            ->orderBy('warranty_end')
            ->get();
    }

    /**
     * Get asset count by status
     */
    public function getCountByStatus(): array
    {
        return Asset::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get asset count by type
     */
    public function getCountByType(): array
    {
        return Asset::selectRaw('asset_type, COUNT(*) as count')
            ->groupBy('asset_type')
            ->pluck('count', 'asset_type')
            ->toArray();
    }

    /**
     * Generate next asset code
     */
    public function generateAssetCode(string $type): string
    {
        $typeCode = match($type) {
            'hardware' => 'HW',
            'software' => 'SW',
            'network' => 'NW',
            default => 'OT',
        };

        $lastAsset = Asset::where('asset_type', $type)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastAsset ? (int) substr($lastAsset->asset_code, -4) + 1 : 1;

        return 'AST-' . $typeCode . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}

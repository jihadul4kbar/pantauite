<?php

use App\Models\Asset;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    seedRoles();

    // Create a super admin user
    $this->admin = User::factory()->create([
        'role_id' => 1, // super_admin
    ]);

    // Create IT Manager
    $this->manager = User::factory()->create([
        'role_id' => 2, // it_manager
    ]);

    // Create IT Staff
    $this->staff = User::factory()->create([
        'role_id' => 3, // it_staff
    ]);

    // Create End User
    $this->user = User::factory()->create([
        'role_id' => 4, // end_user
    ]);

    $this->vendor = Vendor::factory()->create();
    $this->department = Department::factory()->create();
});

/**
 * IMAGE UPLOAD VALIDATION TESTS
 */
test('can create asset with minimum 1 image', function () {
    Storage::fake('public');

    $assetData = [
        'asset_type' => 'hardware',
        'name' => 'Test Laptop',
        'brand' => 'Dell',
        'model' => 'Latitude 5420',
        'status' => 'inventory',
        'condition' => 'new',
        'price' => 15000000,
        'currency' => 'IDR',
        'images' => [
            UploadedFile::fake()->image('asset1.jpg', 1920, 1080),
        ],
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('assets.store'), $assetData);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('assets', [
        'name' => 'Test Laptop',
        'asset_type' => 'hardware',
    ]);
});

test('can create asset with maximum 5 images', function () {
    Storage::fake('public');

    $assetData = [
        'asset_type' => 'hardware',
        'name' => 'Test Server',
        'brand' => 'HP',
        'model' => 'ProLiant',
        'status' => 'inventory',
        'condition' => 'new',
        'price' => 50000000,
        'currency' => 'IDR',
        'images' => [
            UploadedFile::fake()->image('asset1.jpg', 1920, 1080),
            UploadedFile::fake()->image('asset2.jpg', 1920, 1080),
            UploadedFile::fake()->image('asset3.jpg', 1920, 1080),
            UploadedFile::fake()->image('asset4.jpg', 1920, 1080),
            UploadedFile::fake()->image('asset5.jpg', 1920, 1080),
        ],
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('assets.store'), $assetData);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $asset = Asset::where('name', 'Test Server')->first();
    $this->assertNotNull($asset);
    $this->assertCount(5, $asset->images);
});

test('asset creation fails without images', function () {
    Storage::fake('public');

    $assetData = [
        'asset_type' => 'hardware',
        'name' => 'Test Laptop',
        'brand' => 'Dell',
        'status' => 'inventory',
        'condition' => 'new',
        'price' => 15000000,
        'currency' => 'IDR',
        'images' => [],
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('assets.store'), $assetData);

    $response->assertSessionHasErrors('images');
});

test('asset creation fails with more than 5 images', function () {
    Storage::fake('public');

    $assetData = [
        'asset_type' => 'hardware',
        'name' => 'Test Laptop',
        'brand' => 'Dell',
        'status' => 'inventory',
        'condition' => 'new',
        'price' => 15000000,
        'currency' => 'IDR',
        'images' => [
            UploadedFile::fake()->image('asset1.jpg', 1920, 1080),
            UploadedFile::fake()->image('asset2.jpg', 1920, 1080),
            UploadedFile::fake()->image('asset3.jpg', 1920, 1080),
            UploadedFile::fake()->image('asset4.jpg', 1920, 1080),
            UploadedFile::fake()->image('asset5.jpg', 1920, 1080),
            UploadedFile::fake()->image('asset6.jpg', 1920, 1080),
        ],
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('assets.store'), $assetData);

    $response->assertSessionHasErrors('images');
});

test('asset creation fails with image larger than 5MB', function () {
    Storage::fake('public');

    // Create a file that's too large (5121 KB = ~5MB+)
    $largeFile = UploadedFile::fake()->image('large.jpg', 4000, 3000)->size(6144);

    $assetData = [
        'asset_type' => 'hardware',
        'name' => 'Test Laptop',
        'brand' => 'Dell',
        'status' => 'inventory',
        'condition' => 'new',
        'price' => 15000000,
        'currency' => 'IDR',
        'images' => [$largeFile],
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('assets.store'), $assetData);

    $response->assertSessionHasErrors('images.0');
});

test('asset creation fails with non-image file', function () {
    Storage::fake('public');

    $assetData = [
        'asset_type' => 'hardware',
        'name' => 'Test Laptop',
        'brand' => 'Dell',
        'status' => 'inventory',
        'condition' => 'new',
        'price' => 15000000,
        'currency' => 'IDR',
        'images' => [
            UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ],
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('assets.store'), $assetData);

    $response->assertSessionHasErrors('images.0');
});

test('images are converted to webp format', function () {
    Storage::fake('public');

    $assetData = [
        'asset_type' => 'hardware',
        'name' => 'Test Laptop',
        'brand' => 'Dell',
        'status' => 'inventory',
        'condition' => 'new',
        'price' => 15000000,
        'currency' => 'IDR',
        'images' => [
            UploadedFile::fake()->image('asset1.jpg', 1920, 1080),
        ],
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('assets.store'), $assetData);

    $response->assertRedirect();

    $asset = Asset::where('name', 'Test Laptop')->first();
    $this->assertNotNull($asset);
    
    // Check that the stored image has .webp extension
    foreach ($asset->images as $imagePath) {
        $this->assertStringEndsWith('.webp', $imagePath);
        Storage::disk('public')->assertExists($imagePath);
    }
});

test('images are resized if larger than 1920x1080', function () {
    Storage::fake('public');

    // Create a very large image
    $assetData = [
        'asset_type' => 'hardware',
        'name' => 'Test Laptop',
        'brand' => 'Dell',
        'status' => 'inventory',
        'condition' => 'new',
        'price' => 15000000,
        'currency' => 'IDR',
        'images' => [
            UploadedFile::fake()->image('large.jpg', 4000, 3000),
        ],
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('assets.store'), $assetData);

    $response->assertRedirect();

    $asset = Asset::where('name', 'Test Laptop')->first();
    $this->assertNotNull($asset);
    $this->assertCount(1, $asset->images);
});

/**
 * INDEX TESTS
 */
test('admin can view all assets', function () {
    Asset::factory()->count(3)->create();

    $response = $this->actingAs($this->admin)
        ->get(route('assets.index'));

    $response->assertOk()
        ->assertViewIs('assets.index');
});

test('manager can view all assets', function () {
    Asset::factory()->count(3)->create();

    $response = $this->actingAs($this->manager)
        ->get(route('assets.index'));

    $response->assertOk();
});

test('staff can view all assets', function () {
    Asset::factory()->count(3)->create();

    $response = $this->actingAs($this->staff)
        ->get(route('assets.index'));

    $response->assertOk();
});

/**
 * CREATE TESTS
 */
test('authenticated user can view create asset form', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('assets.create'));

    $response->assertOk()
        ->assertViewIs('assets.create');
});

test('end user cannot create asset', function () {
    $response = $this->actingAs($this->user)
        ->get(route('assets.create'));

    $response->assertForbidden();
});

/**
 * SHOW TESTS
 */
test('user can view asset', function () {
    $asset = Asset::factory()->create();

    $response = $this->actingAs($this->admin)
        ->get(route('assets.show', $asset));

    $response->assertOk()
        ->assertViewIs('assets.show');
});

/**
 * UPDATE TESTS
 */
test('can update asset without new images', function () {
    $asset = Asset::factory()->create([
        'name' => 'Original Name',
        'images' => ['assets/images/existing.webp'],
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('assets.update', $asset), [
            'name' => 'Updated Name',
        ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('assets', [
        'id' => $asset->id,
        'name' => 'Updated Name',
    ]);
});

test('can add images to existing asset', function () {
    Storage::fake('public');

    $asset = Asset::factory()->create([
        'images' => ['assets/images/existing1.webp', 'assets/images/existing2.webp'],
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('assets.update', $asset), [
            'name' => $asset->name,
            'images' => [
                UploadedFile::fake()->image('new1.jpg', 1920, 1080),
                UploadedFile::fake()->image('new2.jpg', 1920, 1080),
            ],
        ]);

    $response->assertRedirect();

    $asset->refresh();
    $this->assertCount(4, $asset->images);
});

test('cannot add more than 5 images to existing asset', function () {
    Storage::fake('public');

    $asset = Asset::factory()->create([
        'images' => [
            'assets/images/img1.webp',
            'assets/images/img2.webp',
            'assets/images/img3.webp',
            'assets/images/img4.webp',
            'assets/images/img5.webp',
        ],
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('assets.update', $asset), [
            'name' => $asset->name,
            'images' => [
                UploadedFile::fake()->image('new1.jpg', 1920, 1080),
            ],
        ]);

    $response->assertSessionHasErrors('images');
});

/**
 * DELETE TESTS
 */
test('admin can delete asset', function () {
    $asset = Asset::factory()->create();

    $response = $this->actingAs($this->admin)
        ->delete(route('assets.destroy', $asset));

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertSoftDeleted('assets', ['id' => $asset->id]);
});

test('end user cannot delete asset', function () {
    $asset = Asset::factory()->create();

    $response = $this->actingAs($this->user)
        ->delete(route('assets.destroy', $asset));

    $response->assertForbidden();
});

/**
 * ASSIGN TESTS
 */
test('can assign asset to user', function () {
    $asset = Asset::factory()->create([
        'assigned_to_user_id' => null,
    ]);

    $response = $this->actingAs($this->admin)
        ->post(route('assets.assign', $asset), [
            'assigned_to_user_id' => $this->staff->id,
        ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('assets', [
        'id' => $asset->id,
        'assigned_to_user_id' => $this->staff->id,
    ]);
});

test('can assign asset to department', function () {
    $asset = Asset::factory()->create([
        'assigned_to_department_id' => null,
    ]);

    $response = $this->actingAs($this->admin)
        ->post(route('assets.assign', $asset), [
            'assigned_to_department_id' => $this->department->id,
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('assets', [
        'id' => $asset->id,
        'assigned_to_department_id' => $this->department->id,
    ]);
});

/**
 * STATUS CHANGE TESTS
 */
test('can change asset status', function () {
    $asset = Asset::factory()->create([
        'status' => 'inventory',
    ]);

    $response = $this->actingAs($this->admin)
        ->post(route('assets.status.change', $asset), [
            'status' => 'deployed',
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('assets', [
        'id' => $asset->id,
        'status' => 'deployed',
    ]);
});

/**
 * UNAUTHORIZED ACCESS TESTS
 */
test('guest cannot access assets', function () {
    $response = $this->get(route('assets.index'));

    $response->assertRedirect(route('login'));
});

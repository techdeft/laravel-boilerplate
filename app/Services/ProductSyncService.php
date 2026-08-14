<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductSyncService
{
    protected string $baseUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.clearwox.base_url'), '/');
        $this->apiToken = config('services.clearwox.api_token');
    }

    /**
     * Synchronize products from the external API with pagination.
     */
    public function sync(): void
    {
        try {
            $page = 1;
            $pageSize = 20; // Default from documentation
            $totalSynced = 0;

            do {
                $response = Http::withToken($this->apiToken)
                    ->get("{$this->baseUrl}/api/items/retail", [
                        'page' => $page,
                        'size' => $pageSize,
                    ]);

                if ($response->failed()) {
                    Log::error('Clearwox API sync failed', [
                        'page' => $page,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    break;
                }

                $data = $response->json();
                $items = $data['items'] ?? [];
                $totalItems = $data['total'] ?? 0;

                foreach ($items as $item) {
                    $this->upsertProduct($item);
                    $totalSynced++;
                }

                Log::info("Clearwox sync progress: Page $page of " . ceil($totalItems / $pageSize));

                $page++;

                // Continue if we haven't reached the total amount of items
            } while ($totalSynced < $totalItems && !empty($items));

            Log::info('Clearwox API sync completed successfully', [
                'total_synced' => $totalSynced
            ]);

        } catch (\Exception $e) {
            Log::error('Clearwox API sync exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Create or update a single product.
     */
    protected function upsertProduct(array $item): void
    {
        // Handle Category
        $categoryId = null;
        if (!empty($item['category'])) {
            $category = Category::firstOrCreate(
                ['name' => $item['category']],
                ['slug' => Str::slug($item['category'])]
            );
            $categoryId = $category->id;
        }

        // Handle Brand
        $brandId = null;
        if (!empty($item['brand'])) {
            $brand = Brand::firstOrCreate(
                ['name' => $item['brand']],
                ['slug' => Str::slug($item['brand'])]
            );
            $brandId = $brand->id;
        }

        Product::updateOrCreate(
            ['external_id' => $item['productId']],
            [
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => $item['description'] ?? null,
                'price' => $item['price'] ?? 0,
                'stock' => $item['stock'] ?? 0,
                //'image_path' => $item['imageUrl'] ?? null,
                'image_path' => 'media/product.png', //i set default image
                'category' => $item['category'] ?? null, // Fallback string
                'brand' => $item['brand'] ?? null,       // Fallback string
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'is_synced' => true,
                'last_synced_at' => now(),
            ]
        );
    }
}

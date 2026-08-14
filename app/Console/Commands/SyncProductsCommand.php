<?php

namespace App\Console\Commands;

use App\Services\ProductSyncService;
use Illuminate\Console\Command;

class SyncProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize products from the Clearwox API';

    /**
     * Execute the console command.
     */
    public function handle(ProductSyncService $syncService)
    {
        $this->info('Starting product synchronization...');

        $syncService->sync();

        $this->info('Product synchronization completed.');
    }
}

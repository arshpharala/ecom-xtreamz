<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Jasani\JasaniClient;

class FetchJasaniData extends Command
{
    protected JasaniClient $client;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jasani:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Jasani products, prices and stock and store as JSON files';



    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Jasani API sync...');
        $this->client = new JasaniClient();

        try {

            $products = $this->client->fetchProducts();
            $this->info("Products synced: {$products['total_records']}");

            $prices = $this->client->fetchPrices();
            $this->info("Prices synced: {$prices['total_records']}");

            $stock = $this->client->fetchStock();
            $this->info("Stock synced: {$stock['total_records']}");
        } catch (\Throwable $e) {
            $this->error('Sync failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $this->info('Jasani sync completed successfully.');
        return Command::SUCCESS;
    }
}

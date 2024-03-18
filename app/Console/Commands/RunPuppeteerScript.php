<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RunPuppeteerScript extends Command
{
    protected $signature = 'puppeteer:run {origin} {destination} {departure_date} {return_date}';
    protected $description = 'Run Puppeteer scripts to fetch flight, train, and bus tickets';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $origin = $this->argument('origin');
        $destination = $this->argument('destination');
        $departure_date = $this->argument('departure_date');
        $return_date = $this->argument('return_date');

        $services = ['bus', 'train', 'flight'];

        $combinedResults = [];

        foreach ($services as $service) {
            $response = Http::timeout(60)->post('http://localhost:3000/scrape', [
                'service' => $service,
                'origin' => $origin,
                'destination' => $destination,
                'departureDate' => $departure_date,
                'returnDate' => $return_date
            ]);

            if ($response->successful()) {
                $this->info(ucfirst($service) . ' scraping successful');
                $results = json_decode($response->body(), true);
                $combinedResults[$service] = $results;
            } else {
                $this->error(ucfirst($service) . ' scraping failed');
                $this->error($response->body());
            }
        }

        $this->info('Combined Results:');
        $this->info(json_encode($combinedResults, JSON_PRETTY_PRINT));
    }
}

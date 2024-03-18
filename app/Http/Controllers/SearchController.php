<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $departure_date = $request->input('departure_date');
        $return_date = $request->input('return_date');

        // Define the services you want to scrape
        $services = ['bus', 'train', 'flight'];
        $combinedResults = [];

        foreach ($services as $service) {
            $response = Http::post('http://localhost:3000/scrape', [
                'service' => $service,
                'origin' => $origin,
                'destination' => $destination,
                'departureDate' => $departure_date,
                'returnDate' => $return_date,
            ]);

            // Log the response for debugging
            \Log::info('Puppeteer response for ' . $service, ['response' => $response->json()]);

            if ($response->successful()) {
                $results = $response->json();
                $combinedResults = array_merge($combinedResults, $results);
            }
        }

        return view('results', [
            'results' => $combinedResults,
            'origin' => $origin,
            'destination' => $destination,
            'departureDate' => $departure_date,
            'returnDate' => $return_date,
        ]);
    }
}

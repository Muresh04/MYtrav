<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYtrav Results</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-image: url('{{ asset('images/bg3.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .header {
            background-color: #f8f9fa;
            padding: 3px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header img {
            height: 50px;
        }
        .header a {
            text-decoration: none;
            color: #000;
            margin: 0 10px;
            font-weight: bold;
        }
        .filter-bar {
            display: flex;
            justify-content: center;
            padding: 3px;
            border-radius: 2px;
            margin-bottom: 5px;
            background-color: rgba(0, 0, 0, 0);
        }
        .filter-bar select {
            padding: 5px;
            margin: 0 10px;
            background-color: rgba(255, 255, 255, 0.6);
        }
        .results-container {
            display: flex;
            justify-content: space-around;
        }
        .image-container {
            width: 20%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .image-container img {
            width: 80%;
            margin: 10px 0;
        }
        .results {
            width: 70%;
        }
        .result-card {
            background-color: rgba(255, 255, 255, 0.7);;
            border: 1px solid #ddd;
            margin: 10px 0;
            padding: 10px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .result-info {
            display: flex;
            flex-direction: column;
            width: 60%;
        }
        .go-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 4px;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        .top-banner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body>
<header class="header">
    <a href="{{ route('welcome') }}">
        <img src="{{ asset('images/logo.jpg') }}" alt="MYtrav Logo">
    </a>
    <div>
        @auth
            <a href="{{ route('profile') }}">Profile</a>
        @else
            <a href="{{ route('register') }}">Sign up/Sign in</a>
        @endauth
        <a href="{{ route('ktm.live_schedule') }}">Live KTM</a>
        <a href="{{ route('about') }}">About</a>
    </div>
</header>

<div class="container">
    <div class="top-banner">
        <div>
            <strong>Depart Trip:</strong> {{ $origin }} -> {{ $destination }} ({{ $departureDate }})
        </div>
        <div>
            <strong>Return Trip:</strong> {{ $destination }} -> {{ $origin }} ({{ $returnDate }})
        </div>
    </div>
</div>

<div class="filter-bar">
    <label for="filter">Filter by:</label>
    <select id="filter" onchange="filterResults()">
        <option value="cheapest">Cheapest</option>
        <option value="shortest">Shortest Duration</option>
    </select>
</div>


<div class="results-container">
    <div class="image-container">
        <img src="{{ asset('images/bus2.jpg') }}" alt="Bus">
        <img src="{{ asset('images/plane2.jpeg') }}" alt="Airplane">
        <img src="{{ asset('images/train2.jpg') }}" alt="Train">
    </div>
    <div id="results" class="results">
        @foreach ($results as $result)
            <div class="result-card">
                <div class="result-info">
                    <div>Service: {{ $result['busOperator'] ?? $result['operator'] ?? $result['airline'] }}</div>
                    <div>Departure: {{ $result['departure'] ?? $result['departureTime'] }}</div>
                    <div>Arrival: {{ $result['arrival'] ?? $result['arrivalTime'] }}</div>
                    <div>Duration: {{ $result['duration'] }}</div>
                    <div>Price: {{ $result['fare'] ?? $result['price'] }}</div>
                </div>
                <a class="go-button" href="https://www.google.com/search?q={{ $result['busOperator'] ?? $result['operator'] ?? $result['airline'] }}" target="_blank">Go</a>
            </div>
        @endforeach
    </div>
</div>

<footer class="footer">
    &copy; 2024 The Project by Muresh. All Rights Reserved.
</footer>

<script>

    document.addEventListener('DOMContentLoaded', () => {
        const results = @json($results);

        function sortResults(criteria) {
            let sortedResults = [];
            if (criteria === 'shortest') {
                sortedResults = results.slice().sort((a, b) => {
                    const durationA = parseDuration(a.duration);
                    const durationB = parseDuration(b.duration);
                    return durationA - durationB;
                });
            } else if (criteria === 'cheapest') {
                sortedResults = results.slice().sort((a, b) => {
                    const priceA = parseFloat(a.fare ?? a.price.replace('MYR', '').replace(',', '').trim());
                    const priceB = parseFloat(b.fare ?? b.price.replace('MYR', '').replace(',', '').trim());
                    return priceA - priceB;
                });
            }
            renderResults(sortedResults);
        }

        function parseDuration(duration) {
            if (!duration) return Infinity;
            const match = duration.match(/(\d+)[^\d]+(\d+)?/);
            if (match) {
                const hours = parseInt(match[1], 10);
                const minutes = match[2] ? parseInt(match[2], 10) : 0;
                return hours * 60 + minutes;
            }
            return Infinity;
        }

        function renderResults(sortedResults) {
            const resultsContainer = document.getElementById('results');
            resultsContainer.innerHTML = '';
            sortedResults.forEach(result => {
                const resultCard = document.createElement('div');
                resultCard.classList.add('result-card');
                resultCard.innerHTML = `
                <div class="result-info">
                    <div>Service: ${result.busOperator ?? result.operator ?? result.airline}</div>
                    <div>Departure: ${result.departure ?? result.departureTime}</div>
                    <div>Arrival: ${result.arrival ?? result.arrivalTime}</div>
                    <div>Duration: ${result.duration}</div>
                    <div>Price: ${result.fare ?? result.price}</div>
                </div>
                <a class="go-button" href="https://www.google.com/search?q=${result.busOperator ?? result.operator ?? result.airline}" target="_blank">Go</a>
            `;
                resultsContainer.appendChild(resultCard);
            });
        }

        document.getElementById('filter').addEventListener('change', (event) => {
            sortResults(event.target.value);
        });

        // Initial render
        renderResults(results);
    });


</script>
</body>
</html>

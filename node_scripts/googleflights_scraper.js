const puppeteer = require('puppeteer');

const [,, origin, destination, departureDate, returnDate] = process.argv;

const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

const typeSlowly = async (page, text) => {
    for (const char of text) {
        await page.keyboard.type(char);
        await delay(150); // Increase delay to 150ms
    }
};

(async () => {
    const browser = await puppeteer.launch({ headless: false });
    const page = await browser.newPage();

    try {
        console.log('Navigating to Google Flights...');
        // Go to Google Flights search page
        await page.goto('https://www.google.com/travel/flights', { waitUntil: 'networkidle2' });
        console.log('Page loaded');

        // Clear the "Where from?" field
        const originField = await page.$('input[aria-label="Where from?"]');
        if (originField) {
            await originField.click({ clickCount: 3 }); // Triple click to select all text
            await page.keyboard.press('Backspace'); // Clear the field
        } else {
            throw new Error('Origin input field not found');
        }

        // Fill in the origin field
        await typeSlowly(page, origin);
        await delay(500); // Wait for dropdown to appear
        await page.keyboard.press('ArrowDown');
        await page.keyboard.press('Enter');
        console.log('Filled origin field');

        // Wait for a short period to ensure the dropdown selection is processed
        await delay(1000);

        // Use Tab key to move to the destination field
        await page.keyboard.press('Tab');
        await delay(1000); // Add delay to ensure focus moves to the destination field

        // Fill in the destination field
        await typeSlowly(page, destination);
        await delay(500); // Wait for dropdown to appear
        await page.keyboard.press('ArrowDown');
        await page.keyboard.press('Enter');
        console.log('Filled destination field');

        // Wait for a short period to ensure the dropdown selection is processed
        await delay(500);

        // Fill in the departure date
        const departureField = await page.$('input[aria-label="Departure"]');
        if (departureField) {
            await departureField.click();
        } else {
            throw new Error('Departure date input field not found');
        }

        await typeSlowly(page, departureDate);
        await delay(500);
        await page.keyboard.press('Enter');

        await delay(500);

        // Fill in the return date
        await page.keyboard.press('Tab');
        await delay(500); // Add delay to ensure focus moves to the destination field

        await typeSlowly(page, returnDate);
        await delay(500);
        await page.keyboard.press('Enter');

        await delay(500);
        await page.keyboard.press('Escape');
        // Click the "Explore" button
        await page.evaluate(() => {
            document.evaluate('/html/body/c-wiz[2]/div/div[2]/c-wiz/div[1]/c-wiz/div[2]/div[1]/div[1]/div[2]/div/button/div[1]', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue.click();
        });
        console.log('Search button clicked');

        await delay(10000); // Wait for 10 seconds to let the results load

        // Scrape the flight results
        const results = await page.evaluate(() => {
            const flights = [];
            const flightRows = document.evaluate('//*[@id="yDmH0d"]/c-wiz[2]/div/div[2]/c-wiz/div[1]/c-wiz/div[2]/div[2]/div[3]', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;

            if (flightRows) {
                const rows = flightRows.querySelectorAll('li');

                rows.forEach(row => {
                    const flight = {};

                    // Airline Name
                    const airlineName = document.evaluate('.//span[@class="h1fkLb"]', row, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
                    flight.airline = airlineName ? airlineName.innerText : null;

                    // Departure Time
                    const departureTime = row.querySelector('div[aria-label*="Departure time:"]');
                    flight.departureTime = departureTime ? departureTime.innerText : null;

                    // Arrival Time
                    const arrivalTime = row.querySelector('div[aria-label*="Arrival time:"]');
                    flight.arrivalTime = arrivalTime ? arrivalTime.innerText : null;

                    // Calculate Duration
                    if (flight.departureTime && flight.arrivalTime) {
                        const parseTime = time => {
                            const [timeStr, period] = time.split(' ');
                            let [hours, minutes] = timeStr.split(':').map(Number);
                            if (period === 'PM' && hours !== 12) hours += 12;
                            if (period === 'AM' && hours === 12) hours = 0;
                            return { hours, minutes };
                        };

                        const departure = parseTime(flight.departureTime);
                        const arrival = parseTime(flight.arrivalTime);

                        let durationHours = arrival.hours - departure.hours;
                        let durationMinutes = arrival.minutes - departure.minutes;

                        if (durationMinutes < 0) {
                            durationMinutes += 60;
                            durationHours -= 1;
                        }

                        flight.duration = `${durationHours} hr ${durationMinutes} min`;
                    } else {
                        flight.duration = null;
                    }

                    // Price
                    const price1 = row.querySelector('div.YMlIz.FpEdX span[aria-label*="Malaysian ringgits"]');
                    const price2 = row.querySelector('div.YMlIz.FpEdX.jLMuyc span[aria-label*="Malaysian ringgits"]');
                    flight.price = price1 ? price1.innerText : (price2 ? price2.innerText : null);

                    flights.push(flight);
                });
            }

            return flights;
        });

        console.log('Results:', JSON.stringify(results, null, 2));


    } catch (error) {
        console.error('Error:', error);
    } finally {
        await browser.close();
    }
})();

const puppeteer = require('puppeteer');

const [,, origin, destination, departureDate, returnDate] = process.argv;

const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

(async () => {
    const browser = await puppeteer.launch({ headless: false });
    const page = await browser.newPage();

    try {
        console.log('Navigating to Easybook Bus page...');
        await page.goto('https://www.easybook.com/en-my/bus', { waitUntil: 'networkidle2' });
        console.log('Page loaded');

        // Fill in the origin field
        await page.focus('#txtSearchOrigin_Bus');
        await page.keyboard.type(origin);
        await delay(500);
        await page.keyboard.press('ArrowDown');
        await page.keyboard.press('Enter');
        console.log('Filled origin field');

        await delay(500);

        // Fill in the destination field
        await page.focus('#txtSearchDestination_Bus');
        await page.keyboard.type(destination);
        await delay(500);
        await page.keyboard.press('ArrowDown');
        await page.keyboard.press('Enter');
        console.log('Filled destination field');

        await delay(500);

        // Fill in the departure date
        await page.evaluate(() => {
            document.querySelector('#dpDepartureDate_Bus').value = '';
        });
        await page.focus('#dpDepartureDate_Bus');
        await page.keyboard.type(departureDate);
        await delay(500);

        // Fill in the return date
        await page.evaluate(() => {
            document.querySelector('#dpReturnDate_Bus').value = '';
        });
        await page.focus('#dpReturnDate_Bus');
        await page.keyboard.type(returnDate);
        await delay(500);
        console.log('Dates selected');

        // Click the search button
        await page.click('#Bus-search-panel-box-div > div:nth-child(8) > div > div > button');
        console.log('Search button clicked');

        // Wait for results to load
        await page.waitForSelector('#_tblDailySummary', { timeout: 100000 });
        console.log('Results section loaded');

        await delay(5000);

        // Scrape the bus results
        const results = await page.evaluate(() => {
            const rows = document.querySelectorAll('#_tblDailySummary tbody tr');
            const data = [];

            rows.forEach(row => {
                const busOperatorElement = row.querySelector('td[data-title="Bus Operator"] a');
                const firstBusElement = row.querySelector('td[data-title="First Bus"]');
                const durationElement = row.querySelector('td[data-title="Duration"]');
                const fareElement = row.querySelector('td[data-title="Fare"]');

                const busOperator = busOperatorElement ? busOperatorElement.innerText : null;
                const departure = firstBusElement ? firstBusElement.innerText : null;
                const duration = durationElement ? durationElement.innerText : null;
                const fare = fareElement ? fareElement.innerText.replace('RM', '').trim() : null;

                let arrival = null;
                if (departure && duration) {
                    const [hours, minutes] = departure.split(':').map(Number);
                    const departureTime = new Date();
                    departureTime.setHours(hours, minutes, 0, 0);

                    const durationParts = duration.split('j');
                    const durationHours = durationParts.length > 1 ? parseInt(durationParts[0].trim(), 10) : 0;
                    const durationMinutes = durationParts.length > 1 ? parseInt(durationParts[1].replace('m', '').trim(), 10) : parseInt(durationParts[0].replace('m', '').trim(), 10);

                    const arrivalTime = new Date(departureTime.getTime() + durationHours * 60 * 60 * 1000 + durationMinutes * 60 * 1000);
                    const arrivalHours = arrivalTime.getHours().toString().padStart(2, '0');
                    const arrivalMinutes = arrivalTime.getMinutes().toString().padStart(2, '0');
                    arrival = `${arrivalHours}:${arrivalMinutes}`;
                }


                data.push({
                    busOperator,
                    departure,
                    arrival,
                    duration,
                    fare: fare ? parseFloat(fare) : null,
                });
            });

            return data;
        });

        console.log('Results:', JSON.stringify(results, null, 2));

    } catch (error) {
        console.error('Error:', error);
    } finally {
        await browser.close();
    }
})();

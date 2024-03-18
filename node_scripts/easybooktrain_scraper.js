const puppeteer = require('puppeteer');

const [,, origin, destination, departureDate, returnDate] = process.argv;

const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

const typeSlowly = async (page, selector, text) => {
    await page.focus(selector);
    for (const char of text) {
        await page.keyboard.type(char);
        await delay(150);
    }
};

(async () => {
    const browser = await puppeteer.launch({ headless: false });
    const page = await browser.newPage();

    try {
        console.log('Navigating to Easybook Train page...');
        await page.goto('https://www.easybook.com/en-my/train/', { waitUntil: 'networkidle2' });
        console.log('Page loaded');

        // Fill in the origin field
        await typeSlowly(page, '#txtSearchOrigin_Train', origin);
        await delay(500);
        await page.keyboard.press('ArrowDown');
        await page.keyboard.press('Enter');
        console.log('Filled origin field');

        // Wait for a short period to ensure the dropdown selection is processed
        await delay(500);

        // Fill in the destination field
        await typeSlowly(page, '#txtSearchDestination_Train', destination);
        await delay(500);
        await page.keyboard.press('ArrowDown');
        await page.keyboard.press('Enter');
        console.log('Filled destination field');

        // Wait for a short period to ensure the dropdown selection is processed
        await delay(500);

        // Fill in the departure date
        await page.evaluate(() => {
            document.querySelector('#dpDepartureDate_Train').value = '';
        });
        await typeSlowly(page, '#dpDepartureDate_Train', departureDate);
        await delay(500);

        // Fill in the return date
        await page.evaluate(() => {
            document.querySelector('#dpReturnDate_Train').value = '';
        });
        await typeSlowly(page, '#dpReturnDate_Train', returnDate);
        await delay(500);
        console.log('Dates selected');

        // Click the search button
        await page.click('#Train-search-panel-box-div > div:nth-child(8) > div > div > button');
        console.log('Search button clicked');

        // Wait for results to load
        await page.waitForSelector('.search-result-div', { timeout: 100000 });
        console.log('Results section loaded');

        // Scrape the train results
        const results = await page.evaluate(() => {
            const rows = document.evaluate('/html/body/div[13]/div[2]/div/div/div[1]/div/div/div[2]/div[3]/div[1]', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
            const data = [];

            if (rows) {
                const resultCards = rows.querySelectorAll('div.row');

                resultCards.forEach(row => {
                    const train = {};

                    // Train Operator (Default "KTM")
                    train.operator = "KTM";

                    // Departure Time
                    const departureTimeElement = row.querySelector('div.depart-time');
                    train.departureTime = departureTimeElement ? departureTimeElement.innerText.trim() : null;

                    // Arrival Time
                    const arrivalTimeElement = row.querySelector('div.arrival-time');
                    train.arrivalTime = arrivalTimeElement ? arrivalTimeElement.innerText.replace('Arrival Time: ', '').trim() : null;

                    // Duration (Calculate from departure and arrival times)
                    if (train.departureTime && train.arrivalTime) {
                        const parseTime = (timeStr) => {
                            const [time, modifier] = timeStr.split(' ');
                            let [hours, minutes] = time.split(':').map(Number);
                            if (modifier === 'PM' && hours !== 12) {
                                hours += 12;
                            } else if (modifier === 'AM' && hours === 12) {
                                hours = 0;
                            }
                            return { hours, minutes };
                        };

                        const depTime = parseTime(train.departureTime);
                        const arrTime = parseTime(train.arrivalTime);

                        let durationHours = arrTime.hours - depTime.hours;
                        let durationMinutes = arrTime.minutes - depTime.minutes;

                        if (durationMinutes < 0) {
                            durationMinutes += 60;
                            durationHours -= 1;
                        }

                        if (durationHours < 0) {
                            durationHours += 24;
                        }

                        train.duration = `${durationHours}h ${durationMinutes}m`;
                    } else {
                        train.duration = null;
                    }

                    // Price
                    const priceElement = row.querySelector('div.ticket-price span.price');
                    train.price = priceElement ? priceElement.innerText.replace('RM', '').trim() : null;

                    // Only push if departure time is found (assuming it's essential for a valid result)
                    if (train.departureTime) {
                        data.push(train);
                    }
                });
            }

            return data;
        });

        console.log('Results:', JSON.stringify(results, null, 2));


    } catch (error) {
        console.error('Error:', error);
    } finally {
        await browser.close();
    }
})();

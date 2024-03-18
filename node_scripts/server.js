const express = require('express');
const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');
const os = require('os');
const bodyParser = require('body-parser');

const app = express();
const port = 3000;

app.use(bodyParser.json());

const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

const typeSlowly = async (page, text) => {
    for (const char of text) {
        await page.keyboard.type(char);
        await delay(150); // Increase delay to 150ms
    }
};

app.post('/scrape', async (req, res) => {
    const { service, origin, destination, departureDate, returnDate } = req.body;

    console.log('Request received:', service, origin, destination, departureDate, returnDate);

    const tmpDir = path.join(os.tmpdir(), 'puppeteer_temp');
    console.log('Temporary directory:', tmpDir);

    if (!fs.existsSync(tmpDir)) {
        console.log('Temporary directory does not exist, creating it...');
        fs.mkdirSync(tmpDir, { recursive: true });
    } else {
        console.log('Temporary directory already exists.');
    }

    let browser;

    try {
        browser = await puppeteer.launch({
            headless: false,
            userDataDir: tmpDir
        });
        const page = await browser.newPage();

        if (service === 'bus') {
            await page.goto('https://www.easybook.com/en-my/bus', { waitUntil: 'networkidle2' });
            console.log('Navigating to Easybook Bus page...');
            await page.type('#txtSearchOrigin_Bus', origin);
            await delay(1000);
            await page.keyboard.press('ArrowDown');
            await page.keyboard.press('Enter');

            await delay(1000);

            await page.type('#txtSearchDestination_Bus', destination);
            await delay(1000);
            await page.keyboard.press('ArrowDown');
            await page.keyboard.press('Enter');

            await delay(1000);

            await page.evaluate(() => {
                document.querySelector('#dpDepartureDate_Bus').value = '';
                document.querySelector('#dpReturnDate_Bus').value = '';
            });

            await page.type('#dpDepartureDate_Bus', departureDate);
            await page.type('#dpReturnDate_Bus', returnDate);
            console.log('Dates selected');

            await delay(5000);

            await page.click('#Bus-search-panel-box-div > div:nth-child(8) > div > div > button');
            console.log('Search button clicked');

            await delay(5000);

            await page.waitForSelector('#_tblDailySummary', { timeout: 120000 });
            console.log('Results table loaded');

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

            console.log('Results:', JSON.stringify(results));
            res.json(results);

        } else if (service === 'train') {
            await page.goto('https://www.easybook.com/en-my/train/', { waitUntil: 'networkidle2' });
            console.log('Navigating to Easybook Train page...');
            await page.type('#txtSearchOrigin_Train', origin);
            await delay(500);
            await page.keyboard.press('ArrowDown');
            await page.keyboard.press('Enter');
            console.log('Filled origin field');

            await delay(500);

            await page.type('#txtSearchDestination_Train', destination);
            await delay(500);
            await page.keyboard.press('ArrowDown');
            await page.keyboard.press('Enter');
            console.log('Filled destination field');

            await delay(500);

            await page.evaluate(() => {
                document.querySelector('#dpDepartureDate_Train').value = '';
            });
            await page.type('#dpDepartureDate_Train', departureDate);
            await delay(500);

            await page.evaluate(() => {
                document.querySelector('#dpReturnDate_Train').value = '';
            });
            await page.type('#dpReturnDate_Train', returnDate);
            await delay(500);
            console.log('Dates selected');

            await page.click('#Train-search-panel-box-div > div:nth-child(8) > div > div > button');
            console.log('Search button clicked');

            await delay(5000);

            await page.waitForSelector('.search-result-div', { timeout: 100000 });
            console.log('Results section loaded');

            const results = await page.evaluate(() => {
                const rows = document.evaluate('/html/body/div[13]/div[2]/div/div/div[1]/div/div/div[2]/div[3]/div[1]', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
                const data = [];

                if (rows) {
                    const resultCards = rows.querySelectorAll('div.row');

                    resultCards.forEach(row => {
                        const train = {};

                        train.operator = "KTM";

                        const departureTimeElement = row.querySelector('div.depart-time');
                        train.departureTime = departureTimeElement ? departureTimeElement.innerText.trim() : null;

                        const arrivalTimeElement = row.querySelector('div.arrival-time');
                        train.arrivalTime = arrivalTimeElement ? arrivalTimeElement.innerText.replace('Arrival Time: ', '').trim() : null;

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

                        const priceElement = row.querySelector('div.ticket-price span.price');
                        train.price = priceElement ? priceElement.innerText.replace('RM', '').trim() : null;

                        if (train.departureTime) {
                            data.push(train);
                        }
                    });
                }

                return data;
            });

            console.log('Results:', JSON.stringify(results));
            res.json(results);

        } else if (service === 'flight') {
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

            await delay(5000); // Wait for 10 seconds to let the results load

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
                        flight.airline = airlineName ? airlineName.innerText.replace(/\u202f/g, ' ').replace(/\u00a0/g, ' ') : null;

                        // Departure Time
                        const departureTime = row.querySelector('div[aria-label*="Departure time:"]');
                        flight.departureTime = departureTime ? departureTime.innerText.replace(/\u202f/g, ' ').replace(/\u00a0/g, ' ') : null;

                        // Arrival Time
                        const arrivalTime = row.querySelector('div[aria-label*="Arrival time:"]');
                        flight.arrivalTime = arrivalTime ? arrivalTime.innerText.replace(/\u202f/g, ' ').replace(/\u00a0/g, ' ') : null;

                        // Calculate Duration
                        if (flight.departureTime && flight.arrivalTime) {
                            const parseTime = time => {
                                const [timeStr, period] = time.split(' ');
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
                        flight.price = price1 ? price1.innerText.replace(/\u202f/g, ' ').replace(/\u00a0/g, ' ') : (price2 ? price2.innerText.replace(/\u202f/g, ' ').replace(/\u00a0/g, ' ') : null);

                        flights.push(flight);
                    });
                }

                return flights;
            });

            console.log('Results:', JSON.stringify(results));
            res.json(results);

        } else {
            res.status(400).send('Invalid service type');
        }

    } catch (error) {
        console.error('Error:', error);
        res.status(500).send('Error occurred while scraping');
    } finally {
        if (browser) {
            await browser.close();
        }
    }
});

app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});


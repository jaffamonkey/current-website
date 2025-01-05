---
title: Lighthouse with Playwright
intro: |
  Lighthouse is an open-source, automated tool to help you improve the quality of web pages. Playwright is an open-source automation framework that is used for testing web applications
date: 2025-01-05
tags:
  - Accessibility
  - Screenreader
  - Performance
  - Keyboard
  - Design
  - GithubActions
---

> Lighthouse is an open-source, automated tool to help you improve the quality of web pages. Playwright is an open-source automation framework that is used for testing web applications

## Install

```bash
npm install --save-dev playwright playwright-lighthouse chai fs
# playwright can install supported browsers.
npx playwright install
```

## The script

`lighthouse-playwright.spec.js`
```javascript
import { playAudit } from 'playwright-lighthouse';
const test = require("@playwright/test");
import { chromium } from 'playwright';
import fs from 'fs';
import chai from 'chai';
const expect = chai.expect;

test.describe("Lighthouse report", () => {
  test("run lighthouse", async () => {
    let reportDirectory, reportFilename, reportFileTypes, browser, page;

    before(async () => {
      reportDirectory = `${process.cwd()}/lighthouse`;
      reportFilename = 'reports-test';
      reportFileTypes = ['html', 'json'];
      reportFileTypes.forEach((type) => {
        var fileToDelete = `${reportDirectory}/${reportFilename}.${type}`;
        if (fs.existsSync(fileToDelete)) {
          fs.unlinkSync(fileToDelete);
        }
      });

      // Playwright by default does not share any context (eg auth state) between pages, and this code is to address that.
      const browser = await chromium.launchPersistentContext(userDataDir, {
        args: ['--remote-debugging-port=9222']
      });
      const page = await browser.newPage();
      await page.goto("https://practicetestautomation.com/practice-test-login/");
      await page.getByLabel('Username').fill('student');
      await page.getByLabel('Password').fill('Password123');
      await page.getByText('Submit').click();
      await page.waitForURL('https://practicetestautomation.com/logged-in-successfully/');
      await page.goto("https://practicetestautomation.com/courses/");
      await page.waitForSelector('#selenium-webdriver-with-java-for-beginners')

      await playAudit({
        page: page,
        thresholds: {
          performance: 60,
          accessibility: 100,
          "best-practices": 80,
          seo: 80
        },
        port: 9222
      });
    });

    after(async () => {
      await browser.close();
    });

    it('writes json and html reports', async () => {
      const reportDirectory = `${process.cwd()}/lighthouse`;
      const reportFilename = 'reports-test';

      await playAudit({
        reports: {
          formats: {
            json: true,
            html: true,
            csv: false,
          },
          name: reportFilename,
          directory: reportDirectory,
        },
        page: page,
        thresholds: {
          performance: 30,
        },
        port: 9222,
      });

      reportFileTypes.forEach(() => {
        reportFileTypes.forEach((type) => {
          expect(
            fs.existsSync(`${reportDirectory}/${reportFilename}.${type}`),
            `${type} Report file does not exist.`
          ).to.be.true;
        });
      });
    });
  });
});

export { playAudit };
```

## Run script

```bash
npx playwright test lighthouse-playwright.spec.js
```

## Example GitHub Actions setup

```yaml
 analyze:
  # Runs on successful playwright
  needs: [build, test]
  name: lighthouse-playwright-test
  # Running on ubuntu-latest, nothing special
  runs-on: ubuntu-latest
  steps:
   # As usual, we simply checkout the project
   - name: Checkout
    uses: actions/checkout@v4
   # Install the latest version of node
   - name: Set up Node.js
    uses: actions/setup-node@v4
    with:
     node-version: "20"
   # Install Playwright browsers
   - name: Install Playwright and Lighthouse
    run: npm install --save-dev playwright playwright-lighthouse chai fs
   # Install Playwright browsers
   - name: Install Playwright Browsers
    run: npx playwright install --with-deps
   # Run Lighthouse Playwright tests
   - name: Run test
    run: npx playwright test lighthouse-playwright.spec.js
```
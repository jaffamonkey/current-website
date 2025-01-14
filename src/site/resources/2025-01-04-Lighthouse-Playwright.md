---
title: Lighthouse & Playwright
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
  - Web
  - Lighthouse
  - Playwright
---

> Lighthouse is an open-source, automated tool to help you improve the quality of web pages. Playwright is an open-source automation framework that is used for testing web applications

## Install

```bash
yarn add -D playwright-lighthouse playwright lighthouse typescript
# playwright can install supported browsers.
npx playwright install
```

## The script

`tests/lighthouse-playwright.spec.ts`
```javascript
import { playAudit } from "playwright-lighthouse";
import { test, chromium } from "@playwright/test";

test.describe("audit", () => {
  test("run lighthouse", async () => {
     // Playwright by default does not share any context (eg auth state) between pages.
    const browser = await chromium.launchPersistentContext(userDataDir, {
        args: ['--remote-debugging-port=9222'],
        headless: true
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
        performance: 50,
        accessibility: 100,
        "best-practices": 100,
        seo: 100
      },
      port: 9222,
      reports: {
        formats: {
          json: true, //defaults to false
          html: true, //defaults to false
          csv: true, //defaults to false
        },
        name: `logged-in-lighthouse-report.html`, //defaults to `lighthouse-${new Date().getTime()}`
        directory: `./reports`, //defaults to `${process.cwd()}/lighthouse`
      },
    });
    await browser.close();
  })
})
```

## Typescript configuration

`tsconfig.json`
```json
{
  "compilerOptions": {
    "experimentalDecorators": true,
    "target": "esnext",
    "module": "commonjs",
    "moduleResolution": "node",
    "declaration": true
  },
  "include": ["tests/*.ts"],
  "exclude": ["node_modules"]
}
```

## Run script

```bash
npx playwright test lighthouse-playwright.spec.js
```

## Output report

A html report is generated: `reports/logged-in-lighthouse-report.html`

<picture>
    <img src="/assets/img/lighthouse-report.png" alt="Lighthouse report" width="800" decoding="async" />
</picture>

## Example GitHub Actions setup

```yaml
name: Lighthouse Test

on:
  push:
    branches: [master]
  pull_request:

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '18'
          cache: 'npm'
      - run: npm install --save-dev playwright playwright-lighthouse typescript
      - run: npx playwright install --with-deps chromium
      - run: npx playwright test lighthouse-playwright.spec.ts
      - name: Output reports
        uses: actions/upload-artifact@v4
        if: always()
        continue-on-error: true
        with:
          name: artifacts
          path: |
            ./reports/*
```
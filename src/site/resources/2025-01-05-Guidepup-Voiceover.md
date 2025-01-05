---
title: Guidepup (Voiceover)
intro: |
    Guidepup is a screen reader driver for test automation for NVDA and Voiceover.
date: 2025-01-05
featured: true
tags:
    - Accessibility
    - Screenreader
---

> [Guidepup](https://www.guidepup.dev/) is a screen reader driver for test automation for NVDA and Voiceover.

## Environment setup

Setup your environment for screen reader automation with @guidepup/setup:
```bash
npx @guidepup/setup
```

## Install Guidepup

For this example, I am using playwright to drive tests. 
```bash
yarn add @guidepup/playwright @playwright/test fs @types/node ts-node typescript
# install Chromium browser
npx playwright install chromium
```

## Test script

Example of test that logs in and check list of url stored in a json file, and reports the screenreader output for each page into a json file.

`voiceover-playwright-test.ts`
```typescript
import { macOSRecord } from "@guidepup/guidepup";
import { voTest as test } from "./voiceover-test";
import * as fs from 'fs';
const directory = process.cwd();

test.describe("Chromium Playwright voiceOver", () => {
  test("Voiceover Logged In", async ({
    page,
    voiceOver
  }) => {

    const jsonString = fs.readFileSync(directory + '/urls.json', 'utf-8');
    let urls = JSON.parse(jsonString);
    for (let i = 0; i < urls.length; i++) {
      (await voiceOver.spokenPhraseLog()).fill('');
      let getLine = urls[i];
      let url = getLine["url"];
      let pagename = getLine["pagename"];

      const stopRecording = macOSRecord(directory + '/recordings/' + pagename + '.mov');

      if (i == 0) {
        await page.goto(url, {
          waitUntil: "domcontentloaded",
        });
        await page.goto("https://practicetestautomation.com/practice-test-login/");
        await page.getByLabel('Username').fill('student');
        await page.getByLabel('Password').fill('Password123');
        await page.getByText('Submit').click();
        await page.waitForURL('https://practicetestautomation.com/logged-in-successfully/');
      }
      else {
        await page.goto(url, {
          waitUntil: "domcontentloaded",
        });
      }
      const spokenPhraseLog = await voiceOver.spokenPhraseLog();
      while ((await voiceOver.lastSpokenPhrase()).indexOf('All rights reserved') == -1) {
        await voiceOver.next();
      }
      const spokenPhraseLogClean = spokenPhraseLog.filter((str) => str !== '');
      fs.writeFile('./results/' + pagename + '.json', JSON.stringify(spokenPhraseLogClean, undefined, 2).toString(), (err: any) => {
        if (err) throw err;
      })
      stopRecording?.();
    }
  });
});

async function delay(ms: number) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
```

## Url file

`urls.json`
```json
[
    {
        "pagename": "Login",
        "url": "https://practicetestautomation.com/practice-test-login/"
    },
    {
        "pagename": "Logged-in",
        "url": "https://practicetestautomation.com/logged-in-successfully/"
    }
        {
        "pagename": "Courses",
        "url": "https://practicetestautomation.com/courses/"
    },
    {
        "pagename": "Blog",
        "url": "https://practicetestautomation.com/blog/"
    }
        {
        "pagename": "Practice",
        "url": "https://practicetestautomation.com/practice/"
    },
    {
        "pagename": "Contact",
        "url": "https://practicetestautomation.com/contact/"
    }
]
```

## Voiceover setup file

`voiceover-test.ts`
```typescript
import { macOSActivate, voiceOver } from "@guidepup/guidepup";
import { test } from "@playwright/test";
import type { VoiceOver } from "@guidepup/guidepup";

const applicationNameMap = {
  chromium: "Chromium",
  chrome: "Google Chrome",
  "chrome-beta": "Google Chrome Beta",
  msedge: "Microsoft Edge",
  "msedge-beta": "Microsoft Edge Beta",
  "msedge-dev": "Microsoft Edge Dev",
  firefox: "Nightly",
  webkit: "Playwright",
};

/**
 * These tests extend the default Playwright environment that launches the
 * browser with a running instance of the VoiceOver screen reader for MacOS.
 *
 * A fresh started VoiceOver instance `vo` is provided to each test.
 */
const voTest = test.extend<{ voiceOver: VoiceOver }>({
  voiceOver: async ({ browserName }, use) => {
    try {
      await voiceOver.start();
      await macOSActivate(applicationNameMap[browserName]);
      await use(voiceOver);
    } finally {
      try {
        await voiceOver.stop();
      } catch {
        // swallow stop failure
      }
    }
  },
});

export { voTest };
```

## Chrome configuration

`chromium.config.ts`
```typescript
import { devices, PlaywrightTestConfig } from "@playwright/test";

const config: PlaywrightTestConfig = {
  reportSlowTests: null,
  workers: 1,
  timeout: 100 * 100 * 1000,
  retries: 0,
  projects: [
    {
      name: "chromium",
      use: { ...devices["Desktop Chrome"], headless: false, video: "off", viewport: { width: 1200, height: 800 } }
    },
  ],
};

export default config;
```

## Run test

```bash
./node_modules/.bin/playwright test --config chromium.config.ts voiceover-playwright-test.ts
```

## Github Actions Example

```yaml
name: Playwright VoiceOver

on:
  push:
    branches: [main]
  pull_request:
    branches: [main]

jobs:
  playwright-voiceover:
    runs-on: ${{ matrix.os }}
    strategy:
      matrix:
        os: [macos-14]
        browser: [chromium]
    steps:
      - uses: actions/checkout@v3
      - uses: actions/setup-node@v3
        with:
          node-version: 20
      - name: Guidepup Setup
        uses: guidepup/setup-action@0.17.2
        with:
          record: true
      - run: yarn add @guidepup/playwright @playwright/test fs @types/node ts-node typescript
      - run: npx playwright install chromium
      - run: ./node_modules/.bin/playwright test --config chromium.config.ts voiceover-playwright-test.ts
      - uses: actions/upload-artifact@v3
        if: always()
        continue-on-error: true
        with:
          name: artifacts
          path: |
            **/results/**/*
            **/recordings/**/*
```

## Json output snippet

<picture>
    <img src="/assets/img/guidepup.png" alt="Guidepup screenreader output json report" width="800" decoding="async" />
</picture>
---
title: Guidepup (NVDA)
intro: |
    Guidepup is a screen reader driver for test automation for NVDA and nvda.
date: 2025-01-05
tags:
    - Accessibility
    - Screenreader
---

> [Guidepup](https://www.guidepup.dev/) is a screen reader driver for test automation for NVDA and nvda.

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

`nvda-playwright-test.ts`
```typescript
import { windowsRecord } from "@guidepup/record";
import { voTest as test } from "./nvda-test";

import * as fs from 'fs';
const directory = process.cwd();

test.describe("Chromium Playwright nvda", () => {
  test("nvda Logged In", async ({
    page,
    nvda
  }) => {

    const jsonString = fs.readFileSync(directory + '/urls.json', 'utf-8');
    let urls = JSON.parse(jsonString);
    for (let i = 0; i < urls.length; i++) {
      (await nvda.spokenPhraseLog()).fill('');
      let getLine = urls[i];
      let url = getLine["url"];
      let pagename = getLine["pagename"];

      const stopRecording = windowsRecord(directory + '/recordings/' + pagename + '.mp4');

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
      const spokenPhraseLog = await nvda.spokenPhraseLog();
      while ((await nvda.lastSpokenPhrase()).indexOf('All rights reserved') == -1) {
        await nvda.next();
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
        "pagename": "Login",
        "url": "https://practicetestautomation.com/courses/"
    },
    {
        "pagename": "Logged-in",
        "url": "https://practicetestautomation.com/blog/"
    }
        {
        "pagename": "Login",
        "url": "https://practicetestautomation.com/practice/"
    },
    {
        "pagename": "Logged-in",
        "url": "https://practicetestautomation.com/contact/"
    }
]
```

## NVDA setup file

`nvda-test.ts`
```typescript
import { nvda, WindowsKeyCodes, WindowsModifiers } from "../../lib";
import { test } from "@playwright/test";

export const applicationNameMap = {
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
 * browser with a running instance of the NVDA screen reader for Windows.
 *
 * A fresh started NVDA instance `nvda` is provided to each test.
 */
const nvdaTest = test.extend<{ nvda: typeof nvda }>({
  nvda: async ({ browserName, page }, use) => {
    try {
      const applicationName = applicationNameMap[browserName];

      if (!applicationName) {
        throw new Error(`Browser ${browserName} is not installed.`);
      }

      await nvda.start();
      await page.goto("about:blank", { waitUntil: "load" });

      let applicationSwitchRetryCount = 0;

      while (applicationSwitchRetryCount < 10) {
        applicationSwitchRetryCount++;

        await nvda.perform({
          keyCode: [WindowsKeyCodes.Tab],
          modifiers: [WindowsModifiers.Alt],
        });

        const lastSpokenPhrase = await nvda.lastSpokenPhrase();

        if (lastSpokenPhrase.includes(applicationName)) {
          break;
        }
      }

      if (browserName === "chromium") {
        let mainPageFocusRetryCount = 0;

        // Get to the main page - sometimes focus can land on the address bar
        while (
          !(await nvda.lastSpokenPhrase()).includes("document") &&
          mainPageFocusRetryCount < 10
        ) {
          mainPageFocusRetryCount++;

          await nvda.press("F6");
        }
      } else if (browserName === "firefox") {
        // Force focus to somewhere in the web content
        await page.locator("body").first().focus();
      }

      // Make sure not in focus mode
      await nvda.perform(nvda.keyboardCommands.exitFocusMode);

      // Clear the log so clean for the actual test!
      await nvda.clearSpokenPhraseLog();

      await use(nvda);
    } finally {
      try {
        await nvda.stop();
      } catch {
        // swallow stop failure
      }
    }
  },
});

export { nvdaTest };
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
./node_modules/.bin/playwright test --config chromium.config.ts nvda-playwright-test.ts
```

## Github Actions Example

```yaml
name: Playwright nvda

on:
  push:
    branches: [main]
  pull_request:
    branches: [main]

jobs:
  playwright-nvda:
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
      - run: ./node_modules/.bin/playwright test --config chromium.config.ts nvda-playwright-test.ts
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
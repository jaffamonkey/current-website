---
title: Virtual Screen Reader
intro: |
    Virtual Screen Reader is a screen reader simulator for unit tests.
date: 2025-01-03
tags:
    - Accessibility
    - Screenreader
---

> This package automates a [Virtual Screen Reader](https://www.guidepup.dev/docs/virtual), for unit test workflows.

## Install

```
npm install --save-dev @guidepup/virtual-screen-reader
npm install --save-dev jest
npm install --save-dev jest-environment-jsdom
```

## Test script

The script below scrape the html from a url, and process it to output what a screen reader would read (in tab order).

`test.ts`
```
import { virtual } from "@guidepup/virtual-screen-reader";
const directory = process.cwd();
const { gotScraping } = require("got-scraping");

describe("Screen Reader Tests", () => {
    test("should traverse the page announcing the expected roles and content", async () => {
            let url = "http://www.jaffamonkey.com"
            let pagename = "Homepage"

            const response = await gotScraping.get(url);
            const html = response.body;
            document.body.innerHTML = html

            // Start your Virtual Screen Reader instance
            await virtual.start({ container: document.body });

            const spokenPhraseLog = await virtual.spokenPhraseLog();
            // Navigate your environment with the Virtual Screen Reader similar to how your users would
            while ((await virtual.lastSpokenPhrase()) !== "end of document") {
                await virtual.next();
            }
            const spokenPhraseLogClean = spokenPhraseLog.filter(name => !name.includes('document'));
            fs.writeFile('./results/' + pagename + '.json', JSON.stringify(spokenPhraseLogClean, undefined, 2).toString(), (err: any) => {
                if (err) throw err;
            })
            await virtual.stop();
    });
});
```

## Jest config

`jest.config.js`
```
// eslint-disable-next-line no-undef
module.exports = {
  preset: "ts-jest",
  resolver: "ts-jest-resolver",
  testEnvironment: "jsdom",
  testTimeout: 120000,
  roots: ["tests"],
  collectCoverageFrom: ["src/**/*.ts", "src/**/*.tsx"],
  coveragePathIgnorePatterns: ["/node_modules/", "/tests/"],
  coverageThreshold: {
    global: {
      branches: 100,
      functions: 100,
      lines: 100,
      statements: 100,
    },
  },
  setupFilesAfterEnv: ["<rootDir>/tests/jest.setup.ts"],
  transform: {
    "^.+\\.tsx?$": ["ts-jest", { tsconfig: "./tsconfig.test.json" }],
  },
};
```

## Run the script

```
jest ./tests/test.ts
```
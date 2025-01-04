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

In this example, the script below scrapes the html from a url, and reports what a screen reader would read (in tab order). 

`test.ts`
```
import { virtual } from "@guidepup/virtual-screen-reader";
const { gotScraping } = require("got-scraping");

describe("Screen Reader Tests", () => {
    test("should traverse the page announcing the expected roles and content", async () => {

            let url = "http://www.jaffamonkey.com"

            // Retrieves HTML from the url provided

            const response = await gotScraping.get(url);
            const html = response.body;
            document.body.innerHTML = html

            // Start your Virtual Screen Reader instance

            await virtual.start({ container: document.body });
            const spokenPhraseLog = await virtual.spokenPhraseLog();
            
            // Navigate your environment with the Virtual Screen Reader similar to how your users would.

            while ((await virtual.lastSpokenPhrase()) !== "end of document") {
                await virtual.next();
            }
            
            // Screen reader output displayed ij console, but you can also easily write this to a file.
            
            console.log(spokenPhraseLog);

            await virtual.stop();
    });
});
```

## Jest config file

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
  setupFilesAfterEnv: ["jest.setup.ts"],
  transform: {
    "^.+\\.tsx?$": ["ts-jest", { tsconfig: "./tsconfig.test.json" }],
  },
};
```

## Jest setup file

`jest.setup.js`
```
jest.setTimeout(10000);
```

## Run the script

```
jest test.ts
```
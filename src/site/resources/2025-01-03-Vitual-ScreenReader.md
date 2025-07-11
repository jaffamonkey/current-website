---
title: Virtual screen reader
intro: |
    Virtual Screen Reader mirrors screen reader functionality, tp simulate and assert on what users can do when using screen readers.
date: 2025-01-03
tags:
    - Accessibility
    - Screenreader
    - Web
    - Guidepup
    - Jest
---

> Virtual Screen Reader is a screen reader simulator for unit tests.

This package automates a [Virtual Screen Reader](https://www.guidepup.dev/docs/virtual), for unit test workflows.

## Install

```
npm install --save-dev @guidepup/virtual-screen-reader jest jest-environment-jsdom got-scraping typescript
```

## Test script 1

In this example, the script below scrapes the html from a url, and reports what a screen reader would read (in tab order). 

`tests/test.ts`
```typescript
import { virtual } from "@guidepup/virtual-screen-reader";
const { gotScraping } = require("got-scraping");

describe("Screen Reader Tests", () => {
    test("should traverse the page announcing the expected roles and content", async () => {

      let url = "http://www.jaffamonkey.sbs"

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

## Test script 2

In this example, the a HTML chunk is added to the script. 

`tests/test.ts`
```typescript
import { virtual } from "@guidepup/virtual-screen-reader";

async function setupFocusChangePage() {
    document.body.innerHTML = `[Enter full of partial HTML here]`;
}

describe("click", () => {
    it("should update the screen reader position when a node not currently active for the screen reader is focussed", async () => {
      setupFocusChangePage();
      await virtual.start({ container: document.body });
      const spokenPhraseLog = await virtual.spokenPhraseLog();
      while ((await virtual.lastSpokenPhrase()) !== "end of document") {
        await virtual.next();
      }

      console.log(spokenPhraseLog);

      // Stop your virtual screen reader instance
      await virtual.stop();
    }, 180000);
})
```

## Jest config file

`jest.config.ts`
```javascript
import { Config } from '@jest/types';

const config: Config.InitialOptions = {
  preset: "ts-jest",
  resolver: "ts-jest-resolver",
  testEnvironment: "jsdom",
  testTimeout: 120000,
  roots: ["tests"],
  collectCoverageFrom: ["**/*.ts"],
  coveragePathIgnorePatterns: ["/node_modules/"],
  coverageThreshold: {
    global: {
      branches: 100,
      functions: 100,
      lines: 100,
      statements: 100,
    },
  },
  transform: {
    "^.+\\.tsx?$": ["ts-jest", { tsconfig: "./tsconfig.json" }],
  },
};

export default config;
```

## Typescript config file

`tsconfig.json`
```json
{
  "compilerOptions": {
    "experimentalDecorators": true,
    "target": "esnext",
    "module": "commonjs",
    "moduleResolution": "node",
    "declaration": true,
  },
  "include": ["**/*.ts"],
  "exclude": ["node_modules"]
}
```

## Jest setup file

`jest.setup.ts`
```typescript
jest.setTimeout(10000);
```

## Run the script

```bash
jest tests/test.ts
```

## Example output

```
  ....json
  "navigation, primary",
  "list",
  "listitem, level 1, position 1, set size 4",
  "link, About",
  "end of listitem, level 1, position 1, set size 4",
  "listitem, level 1, position 2, set size 4",
  "link, Testing",
  "end of listitem, level 1, position 2, set size 4",
  "listitem, level 1, position 3, set size 4",
  "link, Accessibility",
  "end of listitem, level 1, position 3, set size 4",
  "listitem, level 1, position 4, set size 4",
  "link, Contact, current page",
  "end of listitem, level 1, position 4, set size 4",
  "end of list",
  "end of navigation, primary" 
  ....
```
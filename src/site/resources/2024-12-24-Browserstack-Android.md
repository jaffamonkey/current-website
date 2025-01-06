---
title: Browserstack & Android
intro: |
    Browserstack is a cloud web and mobile testing platform that provides developers with the ability to test their websites and mobile applications across on-demand browsers, operating systems and real mobile devices. 
date: 2024-12-24
tags:
    - BrowserStack
    - Android
    - Mobile
---

> Browserstack is a cloud web and mobile testing platform that provides developers with the ability to test their websites and mobile applications across on-demand browsers, operating systems and real mobile devices. 

## Install

```bash
npm install --save-dev chai chai-as-promised colors wd
```

## Example test

This test is initiated from your machine, and runs and reports on BrowserStack cloud.

`android-browserstack.js`
```javascript
let wd = require('wd');
let assert = require('assert');
let asserters = wd.asserters;

desiredCaps = {
  // Set your BrowserStack access credentials
  'browserstack.user' : '[BroswerStack user id]',
  'browserstack.key' : '[BroswerStack key]',

    // Set URL of the application under test
  'app' : 'bs://[App ID]]',

  // Specify device and os_version for testing
  'device' : 'Google Pixel 3',
  'os_version' : '9.0',

  // Set other BrowserStack capabilities
  'project' : 'First NodeJS project',
  'build' : 'Node Android',
  'name': 'first_test'
};

// Initialize the remote Webdriver using BrowserStack remote URL and desired capabilities defined above
driver = wd.promiseRemote("http://hub-cloud.browserstack.com/wd/hub");

// Test case for the BrowserStack sample Android app. 
driver.init(desiredCaps)
  .then(function () {
    return driver.waitForElementByAccessibilityId(
      'Search Wikipedia', asserters.isDisplayed 
      && asserters.isEnabled, 30000);
  })
  .then(function (searchElement) {
    return searchElement.click();
  })
  .then(function () {
    return driver.waitForElementById(
      'org.wikipedia.alpha:id/search_src_text', asserters.isDisplayed 
      && asserters.isEnabled, 30000);
  })
  .then(function (searchInput) {
    return searchInput.sendKeys("BrowserStack");
  })
  .then(function () {
    return driver.elementsByClassName('android.widget.TextView');    
  })
  .then(function (search_results) {
    assert(search_results.length > 0);
  })
  .fin(function() { 
    // Invoke driver.quit() after the test is done to indicate that the test is completed.
    return driver.quit(); 
  })
  .done();

```

## Run test 

```
node android-browserstack.js
```

_You can check reports on your BrowserStack account. You can additional code to retrieve the reports and test artefacts from BrowserStack cloud_
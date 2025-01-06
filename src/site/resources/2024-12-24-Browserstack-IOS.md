---
title: Browserstack & iOS
intro: |
    Browserstack is a cloud web and mobile testing platform that provides developers with the ability to test their websites and mobile applications across on-demand browsers, operating systems and real mobile devices. 
date: 2024-12-24
tags:
    - BrowserStack
    - IOS
    - DeviceTesting
    - Mobile
---

> Browserstack is a cloud web and mobile testing platform that provides developers with the ability to test their websites and mobile applications across on-demand browsers, operating systems and real mobile devices. 

## Install

```bash
npm install --save-dev assert wd
```

## Example iOS app test

This test is initiated from your machine, and runs test then report results, on the BrowserStack cloud.

`ios-browserstack.js`
```javascript
let wd = require('wd');
let assert = require('assert');
let asserters = wd.asserters;
let Q = wd.Q;

desiredCaps = {
  // Set your BrowserStack access credentials
  'browserstack.user' : '[BroswerStack user id]',
  'browserstack.key' : '[BroswerStack key]',

    // Set URL of the application under test
  'app' : 'bs://[App ID]]',

  // Specify device and os_version for testing
  'device' : 'iPhone 11 Pro',
  'os_version' : '13',

  // Set other BrowserStack capabilities
  'project' : 'First NodeJS project',
  'build' : 'Node iOS',
  'name': 'first_test'
};

// Initialize the remote Webdriver using BrowserStack remote URL and desired capabilities defined above
driver = wd.promiseRemote("http://hub-cloud.browserstack.com/wd/hub");

// Test case for the BrowserStack sample iOS app. 
driver.init(desiredCaps)
  .then(function () {
    return driver.waitForElementById('Text Button', asserters.isDisplayed 
    && asserters.isEnabled, 30000);
  })
  .then(function (textButton) {
    return textButton.click();
  })
  .then(function () {
    return driver.waitForElementById('Text Input', asserters.isDisplayed 
    && asserters.isEnabled, 30000);
  })
  .then(function (textInput) {
    return textInput.sendKeys("hello@browserstack.com"+"\n");
  })
  .then(function () {
    return driver.waitForElementById('Text Output', asserters.isDisplayed 
    && asserters.isEnabled, 30000);
  })
  .then(function (textOutput) {
    return textOutput.text().then(function(value) {
      if (value === "hello@browserstack.com")
        assert(true);
      else
        assert(false);
    });
  })
  .fin(function() { 
    // Invoke driver.quit() after the test is done to indicate that the test is completed.
    return driver.quit(); 
  })
  .done();
```

## Run test 

```
node ios-browserstack.js
```

_You can check reports on your BrowserStack account. You can additional code to retrieve the reports and test artefacts from BrowserStack cloud_

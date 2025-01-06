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
```
var assert = require('assert');
const { Builder, By, until } = require("selenium-webdriver");

var buildDriver = function() {
  return new Builder()
    .usingServer('http://127.0.0.1:4723/wd/hub')
    .build();
};

async function iosBrowserStackTest () {
  let driver =  buildDriver();
  try {
    await driver.wait(
      until.elementLocated(
        By.xpath(
          '/XCUIElementTypeApplication/XCUIElementTypeWindow/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeButton[1]'
        )
      ), 30000
    ).click();

    var textInput = await driver.wait(
      until.elementLocated(
        By.xpath(
          '/XCUIElementTypeApplication/XCUIElementTypeWindow[1]/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeTextField'
        ), 30000
      )
    );
    await textInput.sendKeys('hello@browserstack.com\n');
    await driver.sleep(5000);

    var textOutput = await driver.findElement(
      By.xpath(
        '/XCUIElementTypeApplication/XCUIElementTypeWindow[1]/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeOther/XCUIElementTypeStaticText'
      )
    ).getText();

    assert(textOutput === 'hello@browserstack.com');
    await driver.executeScript(
      'browserstack_executor: {"action": "setSessionStatus", "arguments": {"status":"passed","reason": "Input and output text matches"}}'
    );
  } catch (e) {
    await driver.executeScript(
      'browserstack_executor: {"action": "setSessionStatus", "arguments": {"status":"failed","reason": "Some elements failed to load"}}'
    );
  } finally {
    if (driver) {
      await driver.quit();
    }
  }
}

iosBrowserStackTest();
```

## BrowserStack configuration file

First, upload ipa up to your BrowserStack account, and get the app ID.

`browserstack.yml`
```yaml
# =============================
# Set BrowserStack Credentials
# =============================
# Add your BrowserStack userName and acccessKey here or set BROWSERSTACK_USERNAME and
# BROWSERSTACK_ACCESS_KEY as env variables
userName: BROWSERSTACK_USERNAME
accessKey: BROWSERSTACK_ACCESS_KEY

# ======================
# BrowserStack Reporting
# ======================
# The following capabilities are used to set up reporting on BrowserStack:
# Set 'projectName' to the name of your project. Example, Marketing Website
projectName: BrowserStack Samples
# Set `name` to set the session name
name: BStack iOS test
# Set `buildName` as the name of the job / testsuite being run
buildName: browserstack build
# `buildIdentifier` is a unique id to differentiate every execution that gets appended to
# buildName. Choose your buildIdentifier format from the available expressions:
# ${BUILD_NUMBER} (Default): Generates an incremental counter with every execution
# ${DATE_TIME}: Generates a Timestamp with every execution. Eg. 05-Nov-19:30
# Read more about buildIdentifiers here -> https://www.browserstack.com/docs/automate/selenium/organize-tests
buildIdentifier: '#${BUILD_NUMBER}' # Supports strings along with either/both ${expression}

source: node:appium-sample-sdk:v1.0

# Set `app` to define the app that is to be used for testing. 
# It can either take the id of any uploaded app or the path of the app directly.
app: bs://[app ID]]
# app: ./LocalSample.ipa #For running local tests

# =======================================
# Platforms (Browsers / Devices to test)
# =======================================
# Platforms object contains all the browser / device combinations you want to test on.
# Entire list available here -> (https://www.browserstack.com/list-of-browsers-and-platforms/automate)

platforms:
  - deviceName: iPhone 14 Pro
    osVersion: 16
    platformName: ios
  - deviceName: iPhone 13 Pro
    osVersion: 15
    platformName: ios
  - deviceName: iPhone XS
    osVersion: 14
    platformName: ios

# ==========================================
# BrowserStack Local
# (For localhost, staging/private websites)
# ==========================================
# Set browserStackLocal to true if your website under test is not accessible publicly over the internet
# Learn more about how BrowserStack Local works here -> https://www.browserstack.com/docs/automate/selenium/local-testing-introduction
browserstackLocal: true # <boolean> (Default false)
#browserStackLocalOptions:
#Options to be passed to BrowserStack local in-case of advanced configurations
#  localIdentifier: # <string> (Default: null) Needed if you need to run multiple instances of local.
#  forceLocal: true  # <boolean> (Default: false) Set to true if you need to resolve all your traffic via BrowserStack Local tunnel.
# Entire list of arguments available here -> https://www.browserstack.com/docs/automate/selenium/manage-incoming-connections

# ===================
# Debugging features
# ===================
debug: false # <boolean> # Set to true if you need screenshots for every selenium command ran
networkLogs: false # <boolean> Set to true to enable HAR logs capturing
consoleLogs: errors # <string> Remote browser's console debug levels to be printed (Default: errors)
# Available options are `disable`, `errors`, `warnings`, `info`, `verbose` (Default: errors)
```

## Run test 

```
node ios-browserstack.js
```

_You can check reports on your BrowserStack account. You can additional code to retrieve the reports and test artefacts from BrowserStack cloud_
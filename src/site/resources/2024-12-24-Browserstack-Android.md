---
title: BrowserStack & Android
intro: |
    BrowserStack is a cloud web and mobile testing platform that provides developers with the ability to test their websites and mobile applications across on-demand browsers, operating systems and real mobile devices. 
date: 2024-12-24
tags:
    - BrowserStack
    - Android
    - Mobile
    - Appium
    - DeviceTesting
---

> BrowserStack is a cloud web and mobile testing platform that provides developers with the ability to test their websites and mobile applications across on-demand browsers, operating systems and real mobile devices. 

## Install

```bash
npm install --save selenium-webdriver
npm install --save-dev browserstack-node-sdk
```

## Example test

This test is initiated from your machine, and runs test then report results, on the BrowserStack cloud.

`android-browserstack.js`
```javascript
var assert = require('assert');
const { Builder, By, until } = require('selenium-webdriver');

var buildDriver = function() {
  return new Builder()
    .usingServer('http://127.0.0.1:4723/wd/hub')
    .build();
};

async function androidBrowserStackTest () {
  let driver =  buildDriver();
  try {
    await driver.wait(
      until.elementLocated(
        By.xpath(
          '/hierarchy/android.widget.FrameLayout/android.widget.LinearLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.LinearLayout/android.view.ViewGroup/android.support.v4.view.ViewPager/android.view.ViewGroup/android.widget.FrameLayout/android.support.v7.widget.RecyclerView/android.widget.FrameLayout[1]/android.widget.LinearLayout/android.widget.TextView'
        )
      ), 30000
    ).click();

    var insertTextSelector = await driver.wait(
      until.elementLocated(
        By.xpath(
          '/hierarchy/android.widget.FrameLayout/android.widget.LinearLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.LinearLayout[1]/android.widget.FrameLayout[1]/android.view.ViewGroup/android.widget.LinearLayout/android.support.v7.widget.LinearLayoutCompat/android.widget.LinearLayout/android.widget.LinearLayout/android.widget.LinearLayout/android.widget.AutoCompleteTextView'
        ), 30000
      )
    );
    await insertTextSelector.sendKeys('BrowserStack');
    await driver.sleep(5000);

    var allProductsName = await driver.findElements(
      By.xpath(
        '/hierarchy/android.widget.FrameLayout/android.widget.LinearLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.FrameLayout/android.widget.LinearLayout[1]/android.widget.FrameLayout[2]/android.widget.FrameLayout/android.widget.LinearLayout/android.widget.ListView/android.widget.LinearLayout'
      )
    );

    assert(allProductsName.length > 0);
    await driver.executeScript(
      'browserstack_executor: {"action": "setSessionStatus", "arguments": {"status":"passed","reason": "Search in Wikipedia done correctly"}}'
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

androidBrowserStackTest();
```

## BrowserStack configuration file

First, upload apk up to your BrowserStack account, and get the app ID.

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
name: BStack android test
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
# app: ./LocalSample.apk #For running local tests

# =======================================
# Platforms (Browsers / Devices to test)
# =======================================
# Platforms object contains all the browser / device combinations you want to test on.
# Entire list available here -> (https://www.browserstack.com/list-of-browsers-and-platforms/automate)

platforms:
  - deviceName: Samsung Galaxy S22 Ultra
    osVersion: 12.0
    platformName: android
  - deviceName: OnePlus 9
    osVersion: 11.0
    platformName: android
  - deviceName: Google Pixel 6 Pro
    osVersion: 12.0
    platformName: android

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
node android-browserstack.js
```

_You can check reports on your BrowserStack account. You can additional code to retrieve the reports and test artefacts from BrowserStack cloud_
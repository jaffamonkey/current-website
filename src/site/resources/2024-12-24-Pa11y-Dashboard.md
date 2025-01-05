---
title: Pa11y Dashboard
intro: |
    Pa11y Dashboard which is a code monitoring tool, to check web pages for WCAG compliance.
date: 2025-01-01
tags:
    - Accessibility
    - Screenreader
    - Keyboard
    - Design
---

[Pa11y Dashboard](https://github.com/pa11y/pa11y-dashboard) which is a code monitoring tool, to check web pages for WCAG compliance. It keeps run history, and displays statistics.

## Install

```bash
git clone https://github.com/pa11y/pa11y-dashboard.git
cd pa11y-dashboard
npm install
```

## Install Mongo (Mac)

```bash
brew tap mongodb/brew
# Install a supported Community version of MongoDB:
brew install mongodb-community@4.4
# Start the MongoDB server:
brew services start mongodb/brew/mongodb-community@4.4
# Check that the service has started properly:
$ brew services list
```
## Run dashboard

Choose a free port of your choice.

```bash
PORT=8080 node index.js
```
<picture>
    <img src="/assets/img/pa11y1.png" alt="Pa11y dashboard" width="800" loading="lazy" decoding="async" />
</picture>

## Add url form

1. Add the name of the URL
2. Add the URL that you want to test
3. Select any of the standards listed (section 508, WCAG2A, WCAG2AA, WCAG2AAA)
4. If your server is taking too long to reply to a data request made, we can set `Timeout`.
5. In some case, the page waits for the content to be loaded first, for we can set `Wait` time.
6. `Actions` section is for additional interactions that we can make Pa11y perform before the tests are run (example is in the next `Authentication` section)
7. If your website is behind a firewall or proxy, we need to pass credentials to log in to the website and further test it.
8. If we want to hide any elements from the pa11y test, we can add them to `Hide elements`.
9. If you want to add cookies, we can add them to `Add headers`.
10. If you want to ignore a certain set of rules, you can add them to `Ignore rules`.
11. Click on Add URL button

## Authentication

If pages need user to be logged in, Pa11y supports basic scripting *, an example of which is below. This script would go in the `Actions` section of the "Add URL" form.

```javascript
wait for #login_id to be visible
set field #login_id to user1
set field #password to Password1
click element #submit
wait for url to be https://somesite.com/logged-in
wait for #userphoto to be visible
screen capture test.png
```
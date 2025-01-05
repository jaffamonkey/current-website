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

```bash
PORT=8080 node index.js
```
<picture>
    <img src="/assets/img/pa11y1.png" alt="Pa11y dashboard" width="800" loading="lazy" decoding="async" />
</picture>

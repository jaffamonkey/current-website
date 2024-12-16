---
title: Colophon
intro: |
    This website was build using Eleventy, Gulp, SCSS and the TemperTemper theme.
hideIntro: true
layout: default
permalink: colophon.html
cta: true
---

This website was put together using the TemperTemper (an [Eleventy](https://www.11ty.dev/) theme). 

The build itself is taken care of by npm scripts and it's all hosted on Netlify.

## Styling

The styling is written in [SCSS](https://sass-lang.com) with [Post CSS](https://postcss.org) being used to add vendor prefixes for deeper browser support.


## Scripts

There is only a tiny amount of JavaScript on this website; used to enhance some keyboard behaviour on links that look like buttons] for better accessibility.

## Accessibility

I use a variety of tools to keep the site accessible, including:

* [Lighthouse scores](https://elegant-biscotti-25e1e9.netlify.app/jaffamonkey-website) from Speedlify's SaaS service.
* [Pa11y Dashboard](https://github.com/pa11y/pa11y-dashboard) which is a monitoring too.,

### Pa11y dashboard
<picture>
    <img src="/assets/img/pa11y1.png" alt="Pa11y dashboard" width="800" loading="lazy" decoding="async" />
</picture>

### Pa11y page report
<picture>
    <img src="/assets/img/pa11y2.png" alt="Pa11y dashboard page report view" width="800" loading="lazy" decoding="async" />
</picture>

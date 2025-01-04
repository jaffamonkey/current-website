---
title: Site checks
intro: |
    This website was build using Eleventy, Gulp, SCSS and the TemperTemper theme.
hideIntro: true
layout: default
permalink: site-checks.html
noCta: true
---

## Lighthouse

I use the Chrome dev tool [Lighthouse](https://developer.chrome.com/docs/lighthouse/overview), for performance checks.

<picture>
    <img src="/assets/img/lighthouse.png" alt="Lighthouse report" width="800" loading="lazy" decoding="async" />
</picture>

## Pa11y Dashboard

[Pa11y Dashboard](https://github.com/pa11y/pa11y-dashboard) which is a code monitoring tool, to check web pages for WCAG compliance.

<picture>
    <img src="/assets/img/pa11y1.png" alt="Pa11y dashboard" width="800" loading="lazy" decoding="async" />
</picture>
<!-- <picture>
    <img src="/assets/img/pa11y2.png" alt="Pa11y dashboard page report view" width="800" loading="lazy" decoding="async" />
</picture> -->

## Pa11y CI

[Pa11y CI](https://github.com/pa11y/pa11y-ci), a deOps friendly version of the pa11y tool.
_Note: Both `Pa11y Dashboard` and `Pa11y CI` support scripting to get pass login barriers._

<picture>
    <img src="/assets/img/pa11y-ci.png" alt="Pa11y CI report" width="800" loading="lazy" decoding="async" />
</picture>

## aXe Scan

I like to use different tools, which sometimes highlight issues other tool may not find. Between the [a11y](https://www.a11yproject.com/) and [aXe](https://www.deque.com/axe/) tools, you can get a good coverage on the automation side of accessibility. Here, it's picked up on work I need to do to improve the screen reader output, by adding a caption to the video.

<picture>
    <img src="/assets/img/axe-scan.png" alt="Axe scan CSV report" width="800" loading="lazy" decoding="async" />
</picture>


## Guidepup

[Guidepup](https://www.guidepup.dev/) tools to test screen reader, and return granular screen reader output.

<picture>
    <img src="/assets/img/guidepup.png" alt="Guidepup screen reader report" width="800" loading="lazy" decoding="async" />
</picture>

## Web Rotor

I use the Web Rotor feature that is available with all screen readers, which represents a page using categorised lists. It provides an easier way to navigate a web page with VoiceOver using lists of headings, links, forms and other items on a web page.

{% set youtubeVideoTitle = "Web Rotor" %}
{% set youtubeVideoID = "UeSAFQnI53o" %}
{% include "youtube-embed.html" %}


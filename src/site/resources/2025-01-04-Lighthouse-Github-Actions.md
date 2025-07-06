---
title: Lighthouse & Github
intro: |
    Lighthouse is an open-source, automated tool to help you improve the quality of web pages.
date: 2025-01-05
tags:
    - Accessibility
    - Screenreader
    - Performance
    - Keyboard
    - Design
    - GithubActions
    - Web
    - Lighthouse
---

> Lighthouse is an open-source, automated tool to help you improve the quality of web pages.

You can run [Lighthouse](https://developer.chrome.com/docs/lighthouse/overview/) on any web page, public or requiring authentication. It has audits for performance, accessibility, progressive web apps, SEO, and more. You can run Lighthouse in Chrome DevTools, from the command line, or as a Node module.

## The Github Actions yaml file

Here, we are going to use [Github Actions](https://github.com/features/actions) to run the Lighthouse tool against a list of urls. Individual HTML and json reports are generated for each url. Add the yaml file, to the `.github/workflows` directory in your Github project.

```yaml
name: Lighthouse Report From Urls List
on: push
jobs:
  multiple-urls:
    runs-on: ubuntu-latest
    environment: test
    steps:
      - uses: actions/checkout@v4
      - name: Run Lighthouse on multiple URLs and interpolate env variables.
        uses: treosh/lighthouse-ci-action@v11
        env:
          URL_DOMAIN: 'jaffamonkey.sbs'
        with:
          urls: |
            https://jaffamonkey.sbs
            https://jaffamonkey.sbs/contact
            https://jaffamonkey.sbs/skills/accessibility
            https://jaffamonkey.sbs/skills/testing
            https://jaffamonkey.sbs/site-checks
          uploadArtifacts: true # save results as an action artifacts
          temporaryPublicStorage: true # upload lighthouse report to the temporary storage

```
## Example report output

<picture>
    <img src="/assets/img/lighthouse-githubactions.png" alt="Lighthouse report" width="800" decoding="async" />
</picture>


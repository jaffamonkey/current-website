---
title: Artillery load test
intro: |
    Artillery is a scalable, flexible and easy-to-use platform that contains everything you need for production-grade load testing.
date: 2025-01-05
tags:
    - Performance
    - Load
    - Web
    - API
    - Artillery
---

[Artillery](https://www.artillery.io/) is a scalable, flexible and easy-to-use platform that contains everything you need for production-grade load testing.

## Installation

```
npm install -g artillery@latest
```
## Example

This example shows how you can modify how Artillery selects a scenario for a virtual user during load testing. In Artillery, each VU will be assigned to one of the defined scenarios. By default, each scenario has a weight of 1, meaning each scenario has the same probability of getting assigned to a VU. By specifying a weight in a scenario, you'll increase the chances of Artillery assigning the scenario for a VU. The probability of a scenario getting chosen depends on the total weight for all scenarios.

`artillery-test.yml`
```yaml
config:
  target: "https://jaffamonkey.com"
  phases:
    - duration: 10min
      arrivalRate: 25

scenarios:
  # Approximately 60% of all VUs will access this scenario.
  - name: "access_common_route"
    weight: 6
    flow:
      - get:
          url: "/contact"

  # Approximately 30% of all VUs will access this scenario.
  - name: "access_average_route"
    weight: 3
    flow:
      - get:
          url: "/skills/accessibility"

  # Approximately 10% of all VUs will access this scenario.
  - name: "access_rare_route"
    weight: 1
    flow:
      - get:
          url: "/resources/espresso-accessibility"
```

## Run example

```bash
artillery run --output test-run-report.json artillery-test.yml
artillery report test-run-report.json
```

## Sample report

A HTML report `test-run-report.hmtl` is generated, with the second command.

<picture>
    <img src="/assets/img/artillery.png" alt="Artillery HTML report" width="800" decoding="async" />
</picture>


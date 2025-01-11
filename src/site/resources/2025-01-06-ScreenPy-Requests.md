---
title: Screenplay & Pytest
intro: |
    ScreenPy provides tools for writing automated test suites which follow the Screenplay Pattern. It encourages user-focused tests which are easy to read, understand, and maintain.
date: 2025-01-02
tags:
    - API
    - Screenplay
    - Pytest
---

[ScreenPy](https://screenpy-docs.readthedocs.io/en/latest) provides tools for writing automated test suites which follow the Screenplay Pattern. It encourages user-focused tests which are easy to read, understand, and maintain.

In this example, we are going to use ScreenPy with PyTest (which is a lean test framework) to test REST API endpoints.

## Installation

#### Install file

`requirements.txt`
```bash
screenpy[requests,allure]>=4.2.0
pytest
```

#### Install packages

```bash
python -m venv env
source env/bin/activate
pip install -r requirements.txt
```

## The files

#### Urls file

We are going to use the API test url https://httpsbin.org.

`urls.py`
```python
"""
URLs to be tested via API requests.
"""

BASE_URL = "https://httpbin.org"

BASIC_AUTH_URL = f"{BASE_URL}/basic-auth"
BEARER_AUTH_URL = f"{BASE_URL}/bearer"

SET_COOKIES_URL = f"{BASE_URL}/cookies/set"

BASE64_URL = f"{BASE_URL}/base64"
```

#### Auth Test

`features/test_auth.py`
```python
"""
API test example that tests various auths.
"""
from screenpy import Actor, given, then, when
from screenpy.actions import See
from screenpy.resolutions import IsEqualTo
from screenpy_requests.actions import AddHeader, SendGETRequest
from screenpy_requests.questions import StatusCodeOfTheLastResponse

from ..urls import BASIC_AUTH_URL, BEARER_AUTH_URL


def test_basic_auth(Perry: Actor) -> None:
    """Basic authentication is accepted by the basic auth endpoint."""
    test_username = "USER"
    test_password = "PASS"

    when(Perry).attempts_to(
        SendGETRequest.to(f"{BASIC_AUTH_URL}/{test_username}/{test_password}").with_(
            auth=(test_username, test_password)
        )
    )

    then(Perry).should(See.the(StatusCodeOfTheLastResponse(), IsEqualTo(200)))


def test_bearer_auth(Perry: Actor) -> None:
    """Bearer token authentication is accepted by the bearer auth endpoint."""
    given(Perry).was_able_to(AddHeader(Authorization="Bearer 1234"))

    when(Perry).attempts_to(SendGETRequest.to(BEARER_AUTH_URL))

    then(Perry).should(See.the(StatusCodeOfTheLastResponse(), IsEqualTo(200)))
```

#### Cookies Test

`test_cookies.py`
```python
"""
API test example that tests cookies.
"""

from screenpy import Actor, then, when
from screenpy.actions import SeeAllOf
from screenpy.resolutions import ContainTheEntry, IsEqualTo
from screenpy_requests.actions import SendGETRequest
from screenpy_requests.questions import Cookies, StatusCodeOfTheLastResponse

from ..urls import SET_COOKIES_URL


def test_set_cookies(Perry: Actor) -> None:
    """Cookies set by the set cookies endpoint appear on the session."""
    test_cookie = {"type": "macaroon"}

    when(Perry).attempts_to(
        SendGETRequest.to(SET_COOKIES_URL).with_(params=test_cookie)
    )

    then(Perry).should(
        SeeAllOf.the(
            (StatusCodeOfTheLastResponse(), IsEqualTo(200)),
            (Cookies(), ContainTheEntry(**test_cookie)),
        )
    )
```

#### Test methods

`test_methods.py`
```python
"""
API test examples that use all the HTTP methods.
"""

import pytest

from screenpy import Actor, then, when
from screenpy.actions import See
from screenpy.resolutions import IsEqualTo, ReadsExactly
from screenpy_requests.actions import SendAPIRequest, SendGETRequest
from screenpy_requests.questions import (
    BodyOfTheLastResponse,
    StatusCodeOfTheLastResponse,
)

from ..urls import BASE64_URL, BASE_URL


@pytest.mark.parametrize("action", ["DELETE", "GET", "PATCH", "POST", "PUT"])
def test_actions(action: str, Perry: Actor) -> None:
    """HTTP-action endpoints all respond with 200s."""
    when(Perry).attempts_to(SendAPIRequest(action, f"{BASE_URL}/{action.lower()}"))

    then(Perry).should(See.the(StatusCodeOfTheLastResponse(), IsEqualTo(200)))


def test_base64_decoder(Perry: Actor) -> None:
    """Base64 decoder correctly decodes string"""
    test_string = "QSBsb25nIHRpbWUgYWdvIGluIGEgZ2FsYXh5IGZhciwgZmFyIGF3YXk="

    when(Perry).attempts_to(SendGETRequest.to(f"{BASE64_URL}/{test_string}"))

    then(Perry).should(
        See.the(StatusCodeOfTheLastResponse(), IsEqualTo(200)),
        See.the(
            BodyOfTheLastResponse(),
            ReadsExactly("A long time ago in a galaxy far, far away"),
        ),
    )
```

## Run tests

To run the tests, call the following in the project root folder:

```bash
python -m pytest features/
```

To run the tests with Allure reporting:

```bash
python -m pytest features/ --alluredir allure_report/
allure serve allure_report
```


---
title: Screenplay pattern 
intro: |
    The Screenplay Pattern uses the system metaphor of a stage performance, helping you model each test scenario like a little screenplay describing how the actors should go about performing their activities while interacting with the system under test.
date: 2025-01-01
tags:
    - Screenplay
    - Web
---

> The screenplay pattern uses the system metaphor of a stage performance, helping you model each test scenario like a little screenplay describing how the actors should go about performing their activities while interacting with the system under test.

The Screenplay Pattern is a user-centric approach to writing workflow-level automated acceptance tests. This helps automation testers to write test cases in terms of Business language.

<picture>
    <img src="/assets/img/screenplay.png" alt="Screenplay pattern" width="572" loading="lazy" decoding="async" />
</picture>

- **Actors** initiate Interactions.
- **Abilities** enable Actors to initiate Interactions.
- **Interactions** exercise the behaviors under test.
    - **Tasks** execute procedures on the features under test.
    - **Questions** return state about the features under test.

## Example using Serenity

This example uses [Serenity](https://serenity-bdd.github.io/) with Selenium Webdriver.

#### Create these files in directory structures indicated

`src/test/java/com/ui/pageobject/LoginPage.java`
```java
package com.ui.login.pageobject;

import net.serenitybdd.screenplay.targets.Target;
import net.thucydides.core.pages.PageObject;

public class LoginPage extends PageObject {

    public static final Target USERNAME = Target.the("Username")
            .locatedBy("#username");
    public static final Target PASSWORD = Target.the("Password")
            .locatedBy("#password");
    public static final Target LOGIN_BTN = Target.the("Login Button")
            .locatedBy("//*[@id=\"login\"]/button");
}
```

`src/test/java/com/ui/pageobject/Dashboard.java`
```java
package com.ui.login.pageobject;

import net.serenitybdd.screenplay.targets.Target;
import net.thucydides.core.pages.PageObject;

public class DashboardPage extends PageObject {

    public static final Target LOGOUT = Target.the("Logout")
            .locatedBy("//*[@id=\"content\"]/div/a");
    public static final Target WELCOMETEXT = Target.the("Welcome subheader")
            .locatedBy(".flash");
}
```

`src/test/java/com/ui/tasks/AccessWebPage.java`
```java
package com.ui.login.tasks;

import net.serenitybdd.core.pages.PageObject;
import net.serenitybdd.screenplay.Actor;
import net.serenitybdd.screenplay.Task;
import net.serenitybdd.screenplay.actions.Open;
import net.thucydides.core.annotations.Step;

import static net.serenitybdd.screenplay.Tasks.instrumented;

public class AccessWebPage implements Task {
    public static AccessWebPage loginPage() {
        return instrumented(AccessWebPage.class);
    }

    PageObject loginPage;

    @Step("{0} access Login page")
    public <T extends Actor> void performAs(T t) {
        t.attemptsTo(Open.browserOn().the(loginPage));
    }
}
```

`src/test/java/com/ui/tasks/LoginTo.java`
```java
package com.ui.login.tasks;

import net.serenitybdd.core.steps.Instrumented;
import net.serenitybdd.screenplay.Actor;
import net.serenitybdd.screenplay.Task;
import net.serenitybdd.screenplay.actions.Click;
import net.serenitybdd.screenplay.actions.Enter;
import net.thucydides.core.annotations.Step;
import org.openqa.selenium.Keys;
import com.ui.login.pageobject.LoginPage;

public class LoginTo implements Task {

    @Step("{0} enter username and password '#username' '#password")
    public <T extends Actor> void performAs(T actor) {
        actor.attemptsTo(Enter.theValue(username)
                .into(LoginPage.USERNAME)
                .thenHit(Keys.TAB));
        actor.attemptsTo(Enter.theValue(password)
                .into(LoginPage.PASSWORD)
                .thenHit(Keys.TAB));
        actor.attemptsTo(Click.on(LoginPage.LOGIN_BTN));
    }

    private String username;
    private String password;

    public LoginTo(String username, String password) {
        this.username = username;
        this.password = password;
    }

    public static Task withCredentials(String username, String password) {
        return Instrumented
                .instanceOf(LoginTo.class)
                .withProperties(username, password);
    }
}
```

`src/test/java/com/ui/questions/Dashboard.java`
```java
package com.ui.login.questions;

import com.ui.login.pageobject.DashboardPage;


import net.serenitybdd.screenplay.Actor;
import net.serenitybdd.screenplay.Question;
import net.serenitybdd.screenplay.questions.Text;

public class Dashboard implements Question<String> {

    public static Question<String> displayed() {
        return new Dashboard();
    }

    public String answeredBy(Actor actor) {
        return Text.of(DashboardPage.WELCOMETEXT).answeredBy(actor);
    }
}
```

`src/test/java/com/ui/tests/ScreenPlayTest.java`
```java
package com.ui.login.tests;

import net.serenitybdd.junit.runners.SerenityRunner;
import net.serenitybdd.screenplay.Actor;
import static net.serenitybdd.screenplay.GivenWhenThen.*;
import net.serenitybdd.screenplay.abilities.BrowseTheWeb;
import net.serenitybdd.screenplay.actions.Open;
import net.serenitybdd.screenplay.ensure.Ensure;
import net.thucydides.core.annotations.Managed;

import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.openqa.selenium.WebDriver;

import com.ui.login.pageobject.DashboardPage;
import com.ui.login.tasks.LoginTo;

@RunWith(SerenityRunner.class)
public class ScreenPlayTest {

    private Actor demoUser = Actor.named("Demo User");

    @Managed
    private WebDriver hisBrowser;

    @Before
    public void demoUserCanBrowseTheWeb() {
        demoUser.can(BrowseTheWeb.with(hisBrowser));
    }

    @Test
    public void browseTheWebAsDemoUser() {
        demoUser.attemptsTo(Open.url("https://the-internet.herokuapp.com/login"));
        givenThat(demoUser).attemptsTo(LoginTo.withCredentials("tomsmith", "SuperSecretPassword!"));
        then(demoUser)
                .attemptsTo(Ensure.that(DashboardPage.WELCOMETEXT).text().contains("You logged into a secure area"));
    }
}
```


`src/test/resources/serenity.conf`
```bash
# WebDriver configuration
webdriver {
  driver = chrome
  autodownload = true

  capabilities{

      "goog:chromeOptions" {
           args = [
                    "start-maximized", "test-type", "no-sandbox", "ignore-certificate-errors",
                    "disable-popup-blocking", "disable-default-apps", "disable-extensions-file-access-check"
                    "incognito", "disable-infobars", "disable-gpu", "remote-allow-origins=*"
                   ]
      }
  }
}
headless.mode = true
```

`build.gradle`
```json
defaultTasks 'clean', 'test', 'aggregate'

repositories {
    mavenCentral()
    mavenLocal()
}

buildscript {
    repositories {
        maven {
            url "https://plugins.gradle.org/m2/"
        }
    }
    dependencies {
        classpath "net.serenity-bdd:serenity-gradle-plugin:4.2.11"
    }
}

apply plugin: 'java'
apply plugin: 'eclipse'
apply plugin: 'idea'
apply plugin: "net.serenity-bdd.serenity-gradle-plugin"

sourceCompatibility = 21
targetCompatibility = 21

ext {
    serenity_version = '4.2.11'
    junit_platform_launcher_version="1.11.4"
    junit_platform_suite_version="1.11.4"
    junit_jupiter_engine_version="5.11.4"
    junit_vintage_engine_version="5.11.4"
    logback_classic_version="1.2.10"
    assertj_core_version="3.23.1"
}

dependencies {
    implementation "net.serenity-bdd:serenity-core:${serenity_version}"
    implementation "net.serenity-bdd:serenity-junit:${serenity_version}"
    implementation "net.serenity-bdd:serenity-junit5:${serenity_version}"
    implementation "net.serenity-bdd:serenity-screenplay:${serenity_version}"
    implementation "net.serenity-bdd:serenity-screenplay-webdriver:${serenity_version}"
    implementation "net.serenity-bdd:serenity-ensure:${serenity_version}"
    implementation "ch.qos.logback:logback-classic:${logback_classic_version}"
    implementation "org.assertj:assertj-core:${assertj_core_version}"
    implementation "net.thucydides:thucydides-core:0.9.275"
    testImplementation "org.junit.platform:junit-platform-launcher:${junit_platform_launcher_version}"
    testImplementation "org.junit.platform:junit-platform-suite:${junit_platform_suite_version}"
    testImplementation "org.junit.jupiter:junit-jupiter-engine:${junit_jupiter_engine_version}"
    testImplementation "org.junit.vintage:junit-vintage-engine:${junit_vintage_engine_version}"
}

test {
    useJUnitPlatform()
    systemProperties System.getProperties()
    maxParallelForks = 1
}

gradle.startParameter.continueOnFailure = true

test.finalizedBy(aggregate)
```

#### Run test

```bash
gradlew test
```
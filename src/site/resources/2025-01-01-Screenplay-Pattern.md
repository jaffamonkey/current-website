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

`pageobjects/LoginPage.java`
```java
import net.serenitybdd.screenplay.targets.Target;
import net.thucydides.core.pages.PageObject;

public class LoginPage extends PageObject {

public static final Target USERNAME = Target.the("Username")
.locatedBy("#username");
public static final Target PASSWORD = Target.the("Password")
.locatedBy("#password");
public static final Target LOGIN_BTN = Target.the("Login Button")
.locatedBy(".submit");
}
```

`pageobjects/Dashboard.java`
```java
import net.serenitybdd.screenplay.targets.Target;
import net.thucydides.core.pages.PageObject;

public class DashboardPage extends PageObject {

public static final Target LOGOUT = Target.the("Logout")
.locatedBy(".icon-signout");
}
```

`tasks/AccessWebPage.java`
```java
import com.ui.screenplay.pageobject.LoginPage;
import net.serenitybdd.screenplay.Actor;
import net.serenitybdd.screenplay.Task;
import net.serenitybdd.screenplay.actions.Open;
import net.thucydides.core.annotations.Step;

import static net.serenitybdd.screenplay.Tasks.instrumented;

public class AccessWebPage implements Task {
public static AccessWebPage loginPage() {
return instrumented(AccessWebPage.class);
}

LoginPage loginPage;

@Step("{0} access Login page")
public <T extends Actor> void performAs(T t) {
t.attemptsTo(Open.browserOn().the(loginPage));
}
}
```

`tasks/LoginTo.java`
```java
import com.ui.screenplay.pageobject.LoginPage;
import net.serenitybdd.core.steps.Instrumented;
import net.serenitybdd.screenplay.Actor;
import net.serenitybdd.screenplay.Task;
import net.serenitybdd.screenplay.actions.Click;
import net.serenitybdd.screenplay.actions.Enter;
import net.thucydides.core.annotations.Step;
import org.openqa.selenium.Keys;

public class LoginTo implements Task {

@Step("{0} enter username and password '#username' '#password")
public <T extends Actor> void performAs(T actor)
{
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

`questions/Dashboard.java`
```java
import com.ui.screenplay.pageobject.DashboardPage;
import net.serenitybdd.screenplay.Actor;
import net.serenitybdd.screenplay.Question;
import net.serenitybdd.screenplay.questions.Text;

public class Dashboard implements Question<String> {

public static Question<String> displayed() {
return new Dashboard();
}

public String answeredBy(Actor actor) {
return Text.of(DashboardPage.LOGOUT).answeredBy(actor);
}
}
```

`tests/ScreenPlayTest.java`
```java
import com.ui.screenplay.questions.Dashboard;
import com.ui.screenplay.tasks.LoginTo;
import net.serenitybdd.junit.runners.SerenityRunner;
import net.serenitybdd.screenplay.Actor;
import static net.serenitybdd.screenplay.GivenWhenThen.*;
import net.serenitybdd.screenplay.abilities.BrowseTheWeb;
import net.serenitybdd.screenplay.actions.Open;
import net.thucydides.core.annotations.Managed;
import org.hamcrest.CoreMatchers;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.openqa.selenium.WebDriver;

@RunWith(SerenityRunner.class)
public class ScreenPlayTest {

private Actor demoUser = Actor.named("Demo User");

@Managed
private WebDriver hisBrowser;

@Before
public void demoUserCanBrowseTheWeb(){
demoUser.can(BrowseTheWeb.with(hisBrowser));
}

@Test
public void browseTheWebAsDemoUser(){
demoUser.attemptsTo(Open.url("https://the-internet.herokuapp.com/login"));
givenThat(demoUser).attemptsTo(LoginTo.withCredentials("tomsmith", "SuperSecretPassword!"));
then(demoUser).should(seeThat(Dashboard.displayed(), CoreMatchers.equalTo("demouser1")));
}

}
```

`build.gradle`
```json
plugins {
    id "net.serenity-bdd.serenity-gradle-plugin" version "4.1.12"
    id 'java'
    id 'eclipse'
    id 'idea'
}

defaultTasks 'clean','test','aggregate'

repositories {
    mavenCentral()
}

sourceCompatibility = 16
targetCompatibility = 16

ext {
    slf4jVersion = '1.7.30'
    serenityCoreVersion = '4.1.12'
    junitVersion = '5.10.2'
    assertJVersion = '3.24.2'
    logbackVersion = '1.2.10'
}

dependencies {
    testImplementation "net.serenity-bdd:serenity-core:${serenityCoreVersion}",
                "net.serenity-bdd:serenity-junit5:${serenityCoreVersion}",
                "net.serenity-bdd:serenity-screenplay:${serenityCoreVersion}",
                "net.serenity-bdd:serenity-ensure:${serenityCoreVersion}",
                "net.serenity-bdd:serenity-screenplay-webdriver:${serenityCoreVersion}",
                "org.junit.jupiter:junit-jupiter-api:${junitVersion}",
                "org.assertj:assertj-core:${assertJVersion}",
                "ch.qos.logback:logback-classic:${logbackVersion}"
    testRuntimeOnly "org.junit.jupiter:junit-jupiter-engine:${junitVersion}"
}

test {
    useJUnitPlatform()
    testLogging.showStandardStreams = true
    systemProperties System.getProperties()
}

gradle.startParameter.continueOnFailure = true

test.finalizedBy(aggregate)
```

#### Run test

```bash
gradlew test
```
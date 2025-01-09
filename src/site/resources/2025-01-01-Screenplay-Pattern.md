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

<picture>
    <img src="/assets/img/screenplay.png" alt="Screenplay pattern" width="572" loading="lazy" decoding="async" />
</picture>

- **Actors** initiate Interactions.
- **Abilities** enable Actors to initiate Interactions.
- **Interactions** are procedures that exercise the behaviors under test.
    - **Tasks** execute procedures on the features under test.
    - **Questions** return state about the features under test.

## Example using Serenity

This example uses [Serenity](https://serenity-bdd.github.io/) with Selenium Webdriver.

#### Create these files in directory structures indicated

`pageopbjects/LoginPage.java`
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

`pageopbjects/Dashboard.java`
```java
import net.serenitybdd.screenplay.targets.Target;
import net.thucydides.core.pages.PageObject;

public class DashboardPage extends PageObject {

public static final Target SIGNOUT = Target.the("Logout")
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
actor.attemptsTo(Enter.theValue(username).into(LoginPage.USERNAME).thenHit(Keys.TAB));
actor.attemptsTo(Enter.theValue(password).into(LoginPage.PASSWORD).thenHit(Keys.TAB));
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
return Text.of(DashboardPage.SIGNOUT).answeredBy(actor);
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
        classpath "net.serenity-bdd:serenity-gradle-plugin:4.1.14"
    }
}


apply plugin: 'java'
apply plugin: 'eclipse'
apply plugin: 'idea'
apply plugin: "net.serenity-bdd.serenity-gradle-plugin"

sourceCompatibility = 17
targetCompatibility = 17

ext {
    SERENITY_VERSION = '4.1.14'
    JUNIT_PLATFORM_VERSION = '1.10.2'
    CUCUMBER_JUNIT_PLATFORM_VERSION = '7.14.0'
    JUNIT_JUPITER_VERSION = '5.10.2'
    JUNIT_VINTAGE_VERSION = '5.10.2'
    LOGBACK_CLASSIC_VERSION = '1.2.10'
    ASSERTJ_CORE_VERSION = '3.25.3'
}

dependencies {
    implementation "net.serenity-bdd:serenity-core:${SERENITY_VERSION}"
    implementation "net.serenity-bdd:serenity-junit:${SERENITY_VERSION}"
    implementation "net.serenity-bdd:serenity-junit5:${SERENITY_VERSION}"
    implementation "net.serenity-bdd:serenity-screenplay:${SERENITY_VERSION}"
    implementation "net.serenity-bdd:serenity-screenplay-webdriver:${SERENITY_VERSION}"
    implementation "net.serenity-bdd:serenity-ensure:${SERENITY_VERSION}"
    implementation "net.serenity-bdd:serenity-cucumber:${SERENITY_VERSION}"
    implementation "ch.qos.logback:logback-classic:${LOGBACK_CLASSIC_VERSION}"
    implementation "org.assertj:assertj-core:${ASSERTJ_CORE_VERSION}"
    testImplementation "org.junit.platform:junit-platform-launcher:${JUNIT_PLATFORM_VERSION}"
    testImplementation "io.cucumber:cucumber-junit-platform-engine:${CUCUMBER_JUNIT_PLATFORM_VERSION}"
    testImplementation "org.junit.platform:junit-platform-suite:${JUNIT_PLATFORM_VERSION}"
    testImplementation "org.junit.jupiter:junit-jupiter-engine:${JUNIT_JUPITER_VERSION}"
    testImplementation "org.junit.vintage:junit-vintage-engine:${JUNIT_VINTAGE_VERSION}"
    testImplementation "net.serenity-bdd:serenity-saucelabs:${SERENITY_VERSION}"
}

test {
    useJUnitPlatform()
    systemProperties System.getProperties()
    maxParallelForks = 2
}

serenity {
    // Specify the root package of any JUnit acceptance tests
    testRoot = "net.serenitybdd.demos.todos"

    // Specify the root directory of any Cucumber feature files
    requirementsDir = "src/test/resources/features"
}

gradle.startParameter.continueOnFailure = true

test.finalizedBy(aggregate)
```

#### Run test

```bash
mvn test
```
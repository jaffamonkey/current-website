---
title: Service Virtualization
intro: |
    Service virtualization aims to simulate an entire system, and helps to maintain a continuity to regular test runs.
date: 2025-01-04
tags:
    - API
    - ServiceVirtualization
---

Service virtualization aims to simulate an entire system, and helps to maintain a continuity to regular test runs. For this example, we will be using [Wiremock](https://wiremock.org/) (standalone version).

## Mappings

Mapping files are what Wiremock use to decide what decision to make, when a request is sent to an endpoint. If the json in request matches a mapping, then the response for that mapping is returned.

#### Mapping 1

`mappings/deposit-not-sent.json`
```json
{
    "name": "Check deposit request",
    "priority": 2,
    "request": {
       "url": "/api/v1/check",
       "method": "POST",
       "bodyPatterns": [{
         "matchesJsonPath" : "$[?(@.field2 =~ /.*/)]"
       }]
    },
    "response": {
       "status": 404,
       "bodyFileName": "deposit-not-sent-response.json",
       "headers": {
          "Content-Type": "application/json"
       }
    }
  }
  ```

#### Mapping 2

`mappings/deposit-sent.json`
```json
{
  "name": "Check deposit request",
  "priority": 1,
  "request": {
     "url": "/api/v1/check",
     "method": "POST",
     "bodyPatterns": [
        {
           "equalToJson": {
              "iban": "UK34335353453435"
           }
        }
     ]
  },
  "response": {
     "status": 201,
     "bodyFileName": "deposit-sent-response.json",
     "headers": {
        "Content-Type": "application/json"
     }
  }
}
  ```

#### Mapping 3

`mappings/successful-verification.json`
```json
{
	"name": "Verify deposit request",
	"request": {
	   "method": "POST",
	   "urlPathPattern": "/api/v1/verify",
	   "bodyPatterns": [{
			"equalToJson": {
				"id": "icpwzdnuxu53wyg5gp6ek8nhi",
				"deposits": {
					"first": 0.06,
					"second": 0.10
				}
			}
		}]
	},
	"response": {
	   "status": 201,
	   "bodyFileName": "successful-verification-response.json",
	   "headers": {
		  "Content-Type": "application/json"
	   }
	}
 }
```

## Responses

These files contain the data to return, from a mapping response being initiated. 

#### Mapping 1 response file

`__files/deposit-not-sent-response.json`
```json
{
    "message": "Account not found!"
}
```

#### Mapping 2 response file

`__files/deposit-sent-response.json`
```json
{
    "id": "icpwzdnuxu53wyg5gp6ek8nhi",
    "deposits": {
       "first": 0.06,
       "second": 0.10
    }
 }
```

#### Mapping 3 response file

`__files/successful-verification-response.json`
```json
{
    "message": "Account verification completed!"
}
```

## Install Wiremock

For ease, we will use the Docker image.

#### Dockerfile

`Dockerfile`
```bash
FROM eclipse-temurin:11.0.24_8-jre

LABEL maintainer="Rodolphe CHAIGNEAU <rodolphe.chaigneau@gmail.com>"

ARG WIREMOCK_VERSION=3.10.0
ENV WIREMOCK_VERSION=$WIREMOCK_VERSION
ENV GOSU_VERSION=1.17

WORKDIR /home/wiremock

# grab gosu for easy step-down from root
RUN set -eux; \
  # save list of currently installed packages for later so we can clean up
	savedAptMark="$(apt-mark showmanual)"; \
	apt-get update; \
	apt-get install -y --no-install-recommends ca-certificates wget; \
	if ! command -v gpg; then \
		apt-get install -y --no-install-recommends gnupg2 dirmngr; \
	elif gpg --version | grep -q '^gpg (GnuPG) 1\.'; then \
  # "This package provides support for HKPS keyservers." (GnuPG 1.x only)
		apt-get install -y --no-install-recommends gnupg-curl; \
	fi; \
	rm -rf /var/lib/apt/lists/*; \
	\
	dpkgArch="$(dpkg --print-architecture | awk -F- '{ print $NF }')"; \
	wget -O /usr/local/bin/gosu "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$dpkgArch"; \
	wget -O /usr/local/bin/gosu.asc "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$dpkgArch.asc"; \
	\
  # verify the signature
	export GNUPGHOME="$(mktemp -d)"; \
	gpg --batch --keyserver hkps://keys.openpgp.org --recv-keys B42F6819007F00F88E364FD4036A9C25BF357DD4; \
	gpg --batch --verify /usr/local/bin/gosu.asc /usr/local/bin/gosu; \
	command -v gpgconf && gpgconf --kill all || :; \
	rm -rf "$GNUPGHOME" /usr/local/bin/gosu.asc; \
	\
  # clean up fetch dependencies
	apt-mark auto '.*' > /dev/null; \
	[ -z "$savedAptMark" ] || apt-mark manual $savedAptMark; \
	apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
	\
	chmod +x /usr/local/bin/gosu; \
  # verify that the binary works
	gosu --version; \
	gosu nobody true

# grab wiremock standalone jar
RUN mkdir -p /var/wiremock/lib/ \
  && curl https://repo1.maven.org/maven2/org/wiremock/wiremock-standalone/$WIREMOCK_VERSION/wiremock-standalone-$WIREMOCK_VERSION.jar \
    -o /var/wiremock/lib/wiremock-standalone.jar

# Init WireMock files structure
RUN mkdir -p /var/wiremock/extensions

# Copy our mappings and responses to the Wiremock directories
ADD mappings /home/wiremock/mappings
ADD __files /home/wiremock/__files

COPY docker-entrypoint.sh /

EXPOSE 8080 8443

HEALTHCHECK --start-period=5s --start-interval=100ms CMD curl -f http://localhost:8080/__admin/health || exit 1

ENTRYPOINT ["/docker-entrypoint.sh"]
```

`docker-entrypoint.sh`
```bash
#!/bin/bash

set -e

# Set `java` command if needed
if [ "$1" = "" -o "${1:0:1}" = "-" ]; then
  set -- java $JAVA_OPTS -cp /var/wiremock/lib/*:/var/wiremock/extensions/* wiremock.Run "$@"
fi

# allow the container to be started with `-e uid=`
if [ "$uid" != "" ]; then
  # Change the ownership of /home/wiremock to $uid
  chown -R $uid:$uid /home/wiremock
  set -- gosu $uid:$uid "$@"
fi

exec "$@" $WIREMOCK_OPTIONS
```

## Testing

#### Start the Wiremock service

```
docker run -it --rm -p 8080:8080 wiremock/wiremock
```

#### Check the account

```bash
curl -d '{"iban":"UK34335353453435"}' -H "Content-Type: application/json" -X POST http://localhost:8080/api/v1/check
```

#### Expected result

```json
{
    "id":"icpwzdnuxu53wyg5gp6ek8nhi",
    "deposits":{
       "first":0.06,
       "second":0.10
    }
 }
```

#### Verify the deposit

```bash
curl -d '{"id":"icpwzdnuxu53wyg5gp6ek8nhi","deposits":{"first": 0.06,"second": 0.10}}' -H "Content-Type: application/json" -X POST http://localhost:8080/api/v1/verify
```

#### Expected result

```json
{
    "message": "Account verification completed!"
}
```
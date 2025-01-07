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

## Setup

Firstly, create a project folder and add `mappings` and `__files` folders.  Then create the following files.

## Mapping 1

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

## Mapping 2

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

## Mapping 3

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


## Mapping 1 response file

`__files/deposit-not-sent-response.json`
```json
{
    "message": "Account not found!"
}
```

## Mapping 2 response file

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

## Mapping 3 response file

`__files/successful-verification-response.json`
```json
{
    "message": "Account verification completed!"
}
```

## Run Wiremock

```bash
cd [enter directory where your mappings and __files folders are]
wget https://repo1.maven.org/maven2/org/wiremock/wiremock-standalone/3.10.0/wiremock-standalone-3.10.0.jar
java -jar wiremock-standalone-3.10.0.jar --port 8080
```

## Testing

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
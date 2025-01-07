---
title: Superagent
intro: |
    Superagent is a small progressive client-side HTTP request library, and Node.js module with the same API, supporting many high-level HTTP client features.
date: 2025-01-06
tags:
    - API
---

[Superagent](https://www.npmjs.com/package/superagent) is a small progressive client-side HTTP request library, and Node.js module with the same API, supporting many high-level HTTP client features.

## Installation

```bash
npm install --save-dev superagent assert express
```

## The test

`api-test.js`
```javascript
const superagent = require('superagent');
// Generates a title of 10 characters, with random letters, each time test is run
let randomTitle = Math.random().toString(36).substring(10);
const assert = require('assert');

superagent
  .post('http://localhost:3001/api/v1/todos')
  .send({ "title": randomTitle }) // sends data in JSON format
  .end((err, res) => {
    assert.ifError(err);
    assert.equal(res.status, 201);
    assert.equal(randomTitle, res.body.todo.title);
  });

superagent
  .get('http://localhost:3001/api/v1/todos')
  .end((err, res) => {
    assert.ifError(err);
    assert.equal(res.status, 200);
  });

// promise with then/catch
superagent.get('http://localhost:3001/api/v1/todos').then(console.log).catch(console.error);

// promise with async/await
(async () => {
  try {
    const res = await superagent.post('http://localhost:3001/api/v1/todos').send({ "title": randomTitle });
    assert.equal(res.status, 201);
    assert.equal(randomTitle, res.body.todo.title);
  } catch (err) {
    console.error(err);
  }
})();
```
## The API server

This is code for a very basic API server, using NodeJS and [Express](https://expressjs.com)

`api.js`
```javascript
// Fast minimalist web framework for Node.
var express = require("express");

// A dummy db file, which define Express db structure
var db = require("./db");

// Parses incoming request data
var bodyParser = require("body-parser");

// Set up the express app, the basis of our API server
const app = express();

// Ensures the data is in right format to send
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));

// get all todos
app.get('/api/v1/todos', (req, res) => {
  res.status(200).send({
    success: 'true',
    message: 'todos retrieved successfully',
    todos: db
  })
});

app.post('/api/v1/todos', (req, res) => {
  if (!req.body.title) {
    return res.status(400).send({
      success: 'false',
      message: 'title is required'
    });
  }
  const todo = {
    id: db.length + 1,
    title: req.body.title,
  }
  db.push(todo);
  return res.status(201).send({
    success: 'true',
    message: 'todo added successfully',
    todo
  })
});

// Define what port the api server runs on, in this case the full url would be http://localhost:3001
const PORT = 3001;


app.listen(PORT, function () {
  console.log(`server running on port ${PORT}`)
});
```

## API database

`db.js`
```javascript
var todos = [
  {
    id: 1,
    title: "example",
  }
];

module.exports = todos;
```

## Run API server

```bash
node api.js
```

## Run Superagent test

```bash
node api-test.js
```


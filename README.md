# user-form-robot
## Darrell Raymond Ulm

https://drulm.github.io/user-form-robot/

## A small toy framework for creating, reading, updating and deleting users.
### Uses Twig template engine
### Uses Composer
### Simple theme with Bootstrap

### Main Code is in User directory
### Front controller is index.php
### Driector params/Confuration has the config setting for database and more
### Tests directory has PHPUnit tests
### setup directory has MySQL schema

## How to use
- Works with two types of URI parameters as defined in https://tools.ietf.org/html/rfc6570 either as:
-- index.php?command=command_type&param1=value1&param2=value2&...
-- /command/param1/value1/param2/value2/...
- Parameters can be in any order, except command must be first in path /command/
- Does not have code for command line paramaters, could be added
- database can be loaded either
-- populated at: setup/my_app_database_populated.sql
-- unpopulated at: setup/mysql_database_schema.sql
- Commands below without /type/json or &type=json query parameters are shown in the browser as HTML
- Commands below with /type/json or &type=json query parameters will return json
-- Create command returns the ID
-- Delete command returns number of rows deleted, either 1 or 0
-- Other commands return what the database returns as a return-code
- Performs some error checking, could use more, database bind calls sanitize where used.
- Try to catch exceptions where they occur.
- Limits are based on the current database schema
- Passwords are hashed and can be checked using password_verfy and testing does this.
- Emails are unique and cannot be duplicated.

## Command Examples:
* CREATE:  http://192.168.59.76/create/e/Albert@math.dev/fn/Albert/ln/Brudzewski/p/Maths
* CREATE: http://192.168.59.76/index.php?command=create&e=Albert@math.dev&fn=Albert&ln=Brudzewski&p=Maths
* CREATE: http://192.168.59.76/index.php?command=create&e=email6.dev&fn=first1&ln=last1&p=oassword1&type=json
* CREATE: http://192.168.59.76/create/e/Albert@math.dev/fn/Albert/ln/Brudzewski/p/Maths/type/json
* READ: http://192.168.59.76/read/id/1
* READ: http://192.168.59.76/index.php?command=read&id=1
* READ json output: http://192.168.59.76/read/id/1/type/json
* READ json output: http://192.168.59.76/index.php?command=read&id=1&type=json
* UPDATE: http://192.168.59.76/update/e/Albert@math.dev/fn/Albert/ln/Brudzewski/p/Maths/id/1
* UPDATE: http://192.168.59.76/index.php?command=update&e=ignacy@prtl.dev&fn=Ignacy&ln=T.&p=PrtlGames&id=1
* UPDATE: http://192.168.59.76/index.php?command=update&e=ignacy8@prtl.dev&fn=Ignacy&ln=T.&p=PrtlGames&id=1&type=json
* UPDATE: http://192.168.59.76/update/e/ignacy@prtl.dev/fn/Ignacy/ln/T./p/PrtlGames/id/1/type/json
* DELETE: http://192.168.59.76/delete/id/1
* DELETE: http://192.168.59.76/index.php?command=delete&id=1
* DELETE: http://192.168.59.76/index.php?command=delete&id=1&type=json
* DELETE: http://192.168.59.76/delete/id/1/type/json
* INDEX (list all): http://192.168.59.76/index
* INDEX (list all) json output: http://192.168.59.76/index/type/json
* INDEX (list all): http://192.168.59.76/index.php?command=index
* INDEX (list all): http://192.168.59.76/index.php?command=index&type=json
* NON VALID ROUTE EXAMPLE: http://192.168.59.76/AnythingElse
* NON VALID ROUTE EXAMPLE: http://192.168.59.76/index.php?command=AnythingElse
* DEFAULT PAGE http://192.168.59.76 HOST: example: http://192.168.59.76
* DEFAULT PAGE / HOST: example: http://192.168.59.76/index.php

# Commands
Note: with HTML some parameters are not possible, like strings which use the '/' character.

Example json output for successful create:
```
{ "result": { "create": "297" } }
```

## Create
Creates a new user, as long as the email is not already represented in the current database. All four fields are required, email, first_name, last_name, password.

### Path parameter example with HTML output showing created ID if create worked
* CREATE:  http://192.168.59.76/create/e/Albert@math.dev/fn/Albert/ln/Brudzewski/p/Maths

### Query parameter example with HTML output showing created ID if create worked
* CREATE: http://192.168.59.76/index.php?command=create&e=Albert@math.dev&fn=Albert&ln=Brudzewski&p=Maths

### Path example with json output, returning in JSON success bool and what the new unique ID primary key
* CREATE: http://192.168.59.76/index.php?command=create&e=email6.dev&fn=first1&ln=last1&p=oassword1&type=json

### Query parameter example with json output, returning in JSON success bool and what the new unique ID primary key
* CREATE: http://192.168.59.76/create/e/Albert@math.dev/fn/Albert/ln/Brudzewski/p/Maths/type/json


## Read
Reads a user given parameter id and the integer primary key, such as id=(int). Retuns data to text or json.

Example json output:
```
{ "id_users": "7", "email": "email7.dev", "first_name": "Stefan", "last_name": "Banach", "passwd": "$2y$10$l2r5eMcipiNHoMNeKnSCz.uf0bpmvv80R50Z1gl4LqpA8BdwXFRVa" }
```

### Path parameter example with HTML output
* READ: http://192.168.59.76/read/id/1

### Query parameter example with HTML output
* READ: http://192.168.59.76/index.php?command=read&id=1

### Path example with json output, returning in JSON success bool and all data fields for one user
* READ json output: http://192.168.59.76/read/id/1/type/json

### Query parameter example with json output, returning in JSON success bool and all data fields for one user
* READ json output: http://192.168.59.76/index.php?command=read&id=1&type=json


## Update
Updates a user when provided with
- The id=(int) value
- At least one or more parameters out of: email, first_name, last_name, password.
- Cannot change to an existing email from another record.
- Cannot change the id

Example json output for successful update:
```
{ "result": { "update": 1 } }
```


### Path parameter example with HTML output showing if the command worked
* UPDATE: http://192.168.59.76/update/e/Albert@math.dev/fn/Albert/ln/Brudzewski/p/Maths/id/1

### Query parameter example with HTML output
* UPDATE: http://192.168.59.76/index.php?command=update&e=ignacy@prtl.dev&fn=Ignacy&ln=T.&p=PrtlGames&id=1

### Path example with json output, returning in JSON success bool
* UPDATE: http://192.168.59.76/index.php?command=update&e=ignacy8@prtl.dev&fn=Ignacy&ln=T.&p=PrtlGames&id=1&type=json

### Query parameter example with json output, returning in JSON success bool
* UPDATE: http://192.168.59.76/update/e/ignacy@prtl.dev/fn/Ignacy/ln/T./p/PrtlGames/id/1/type/json


## Delete
Removes a user from the database based on unique id passed in id=(int).

Example output for successful delete:
```
{ "result": { "delete": 1 } }
```

### Path parameter example with HTML output showing if the command worked
* DELETE: http://192.168.59.76/delete/id/1

### Query parameter example with HTML output
* DELETE: http://192.168.59.76/index.php?command=delete&id=1

### Path example with json output, returning in json success bool
* DELETE: http://192.168.59.76/index.php?command=delete&id=1&type=json

### Query parameter example with json output, returning in json success bool
* DELETE: http://192.168.59.76/delete/id/1/type/json


## Index
Reads and returns all user rows in database. Output can either be in simple HTML or Json.

Example HTML Output:
```
id_users	email	first_name	last_name	passwd
7	email7.dev	Stefan	Banach	$2y$10$l2r5eMcipiNHoMNeKnSCz.uf0bpmvv80R50Z1gl4LqpA8BdwXFRVa
8	e@test.dev	first1	last1	$2y$10$Cf4oXUWVOLWNoY5YIqRQD.NRMWG9Y2eNMKZW0OzycKL.Twj8Ds.nm
96	ignacy7@prtl.dev	Ignacy	T.	$2y$10$AV1SidAzV99tKyPYxgGxsudHS4gCHDP2SYecIxnEqiTdVAMeTecaK
98	ignacy8@prtl.dev	Ignacy	T.	$2y$10$8fzD6qfUPq7zCUdPiFs0y.byUfJKxDqmWwLX.xQ1CafUMW8QKsg1i
237	ignacy@prtl.dev	Ignacy	T.	$2y$10$eiqKVGP.dGWhg4rg4Ffc3eZv403jrd64.yvfiKEMleSFprZEpiAxe
239	Albert@math.dev	Albert	Brudzewski	$2y$10$lSHogNjgma66yLEbK4KU5eXjVsXrKfMgXpIYyL.QytGJ6D6pLFvq.
244	Albert3@math.dev	Albert	Brudzewski	$2y$10$qeZqAszz6.aLLqr0vDzAdutMUm0hqAiaOSduZUpQemqHMtOOan6YS
246	Albert5@math.dev	Albert	Brudzewski	$2y$10$0orPEMl234rXFs3dJ.Y4yOR.TEiMdPsBmDYhS5nZLGacVmzoNhXqe
248	Albert6@math.dev	Albert	Brudzewski	$2y$10$XrgJ18RIerWfu.ND8fP3TehtiFJ4y.1kMERAlIktKn/xNz9jlLNWm
251	email9.dev	first1	last1	$2y$10$N5GDBE13FSpmv0z8WNn4Iubod0KIflKd5qzlntpaINu.LesVcON3a
```

Example json Output:
```
[ { "id_users": "7", "email": "Albert888@math.dev", "first_name": "Albert", "last_name": "Brudzewski", "passwd": "$2y$10$E60R9G2MOFi6nQo0Px9LfuJPJPJeLfQvkTl3\/JZr2bGRAOQehBXgu" }, { "id_users": "98", "email": "ignacy8@prtl.dev", "first_name": "Ignacy", "last_name": "T.", "passwd": "$2y$10$8fzD6qfUPq7zCUdPiFs0y.byUfJKxDqmWwLX.xQ1CafUMW8QKsg1i" }, { "id_users": "237", "email": "ignacy@prtl.dev", "first_name": "Ignacy", "last_name": "T.", "passwd": "$2y$10$eiqKVGP.dGWhg4rg4Ffc3eZv403jrd64.yvfiKEMleSFprZEpiAxe" }, { "id_users": "239", "email": "Albert@math.dev", "first_name": "Albert", "last_name": "Brudzewski", "passwd": "$2y$10$lSHogNjgma66yLEbK4KU5eXjVsXrKfMgXpIYyL.QytGJ6D6pLFvq." }, { "id_users": "244", "email": "Albert3@math.dev", "first_name": "Albert", "last_name": "Brudzewski", "passwd": "$2y$10$qeZqAszz6.aLLqr0vDzAdutMUm0hqAiaOSduZUpQemqHMtOOan6YS" }, { "id_users": "246", "email": "Albert5@math.dev", "first_name": "Albert", "last_name": "Brudzewski", "passwd": "$2y$10$0orPEMl234rXFs3dJ.Y4yOR.TEiMdPsBmDYhS5nZLGacVmzoNhXqe" }, { "id_users": "248", "email": "Albert6@math.dev", "first_name": "Albert", "last_name": "Brudzewski", "passwd": "$2y$10$XrgJ18RIerWfu.ND8fP3TehtiFJ4y.1kMERAlIktKn\/xNz9jlLNWm" }, { "id_users": "251", "email": "email9.dev", "first_name": "first1", "last_name": "last1", "passwd": "$2y$10$N5GDBE13FSpmv0z8WNn4Iubod0KIflKd5qzlntpaINu.LesVcON3a" }, { "id_users": "297", "email": "Albert99@math.dev", "first_name": "Albert", "last_name": "Brudzewski", "passwd": "$2y$10$vWN.k09mYwW4PpG0oi8lyOHtSIvLvNdB6XZKQbP7ldxVnGE1w\/TnO" } ]
```

### Path parameter example with HTML output
* INDEX (list all): http://192.168.59.76/index

### Query parameter example with HTML output
* INDEX (list all): http://192.168.59.76/index.php?command=index

### Path example with json output
* INDEX (list all) json output: http://192.168.59.76/index/type/json

### Query parameter example with json output
* INDEX (list all): http://192.168.59.76/index.php?command=index&type=json


## Default
The default page with instructions for use.

### Just host
* DEFAULT PAGE http://192.168.59.76 HOST: example: http://192.168.59.76

### or at index,php
* DEFAULT PAGE / HOST: example: http://192.168.59.76/index.php


# PHPUnit Tests

## To Run PHPUnit tests
- phpunit --configuration configuration.xml --coverage-html ./report User

## Database Schema
```
CREATE TABLE `users` (
  `id_users` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(254) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `passwd` varchar(128) NOT NULL,
  PRIMARY KEY (`id_users`),
  UNIQUE KEY `idusers_UNIQUE` (`id_users`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
```

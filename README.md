# user-form-robot

## A small framework for creating, reading, updating and deleting users.

## Darrell Raymond Ulm

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
* UPDATE: http://192.168.59.76/update/e/ignacy@prtl.dev/fn/Ignacy/ln/T./p/PrtlGames/id/1/type/json;
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

## Create
Creates a new user, as long as the email is not already represented in the current database. All four fields are required, email, first_name, last_name, password.

### Path parameter example with HTML output
* CREATE:  http://192.168.59.76/create/e/Albert@math.dev/fn/Albert/ln/Brudzewski/p/Maths

### Query parameter example with HTML output
* CREATE: http://192.168.59.76/index.php?command=create&e=Albert@math.dev&fn=Albert&ln=Brudzewski&p=Maths

### Path example with json output, returning in JSON success bool and what the new unique ID primary key
* CREATE: http://192.168.59.76/index.php?command=create&e=email6.dev&fn=first1&ln=last1&p=oassword1&type=json

### Query parameter example with HTML output, returning in JSON success bool and what the new unique ID primary key
* CREATE: http://192.168.59.76/create/e/Albert@math.dev/fn/Albert/ln/Brudzewski/p/Maths/type/json


## Read

### Path parameter example with HTML output
* READ: http://192.168.59.76/read/id/1

### Query parameter example with HTML output
* READ: http://192.168.59.76/index.php?command=read&id=1

### Path example with json output, returning in JSON success bool and all data fields for one user
* READ json output: http://192.168.59.76/read/id/1/type/json

### Query parameter example with HTML output, returning in JSON success bool and all data fields for one user
* READ json output: http://192.168.59.76/index.php?command=read&id=1&type=json

## Update

## Delete

## Index

## Default

## To Run PHPUnit tests
- cd /vagrant/Tests
- phpunit --configuration configuration.xml --coverage-html ./report User
### Tests can be seen at: 
- http://192.168.59.76/Tests/report/index.html

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

## From original notes:
### Uses a Vagrant Image:  

| Type           | Value                  |
|----------------|------------------------|
| MySQL Username | my_app                 |
| Mysql Password | secret                 |
| Mysql Database | my_app                 |
| SSH Username   | vagrant                |
| SSH Password   | vagrant                |


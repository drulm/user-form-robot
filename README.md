# user-form-robot

## A small framework for creating, reading, updating and deleting users.

## Darrell Raymond Ulm

Uses a Vagrant Image:  

| Type           | Value                  |
|----------------|------------------------|
| MySQL Username | my_app                 |
| Mysql Password | secret                 |
| Mysql Database | my_app                 |
| SSH Username   | vagrant                |
| SSH Password   | vagrant                |

- Works with two types of URI parameters as defined in https://tools.ietf.org/html/rfc6570
- Does not have command line paramaters

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


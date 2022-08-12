# Architectural decisions

I wasn't sure if this phrase "there is no plan to place this in a database in the near future."
meant that I couldn't store on a database or if I needed to populate the database.

So, I created both ways: There is an InMemoryServerService, that reads the content
from the xls and keep it in memory, and the DatabaseServerService, who uses
Doctrine to store and retrieve data from database.

**Please note that the database is mandatory to run. (See Section How to Run).**

There are some tests under `tests/` directory, and you can run using:
`php bin/phpunit`

# How to run

The easiest way is running the project using docker-composer:

`docker-compose up --build -d
`
With this command, the database and the server will be acessible on https://localhost (it's using a
self-signed certificate, so you must accept it on your browser).

There are two routes:
- /servers
- /servers-inmemory

For both routes you can pass the following filters:

- ram: Number only and can have multiple values (for example ?ram=16&ram=32)
- location: String (?location=AmsterdamAMS-01)
- hardDiskType: String (?hardDiskType=SSD)
- hardDiskCapacity: String, in GB (to be simpler, 1tb = 1000gb) (?hardDiskCapacity=2000)

# How to test it

While running the docker-compose (to have the database up and running), you can,
outside the container, run the following commands:

`
php bin/console --env=test doctrine:database:create

php bin/console --env=test doctrine:schema:create

php bin/phpunit 
`
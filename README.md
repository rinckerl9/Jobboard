# Jobboard!

## Background

The goal of this project is to create a simple website that display job advertisements. 

It has a database where advertisements are stored as well as the users and the companies that could possibly post an ad. 

A webpage that display job advertisements and an administration page that allow the administrator to manage the database.    

An API.  

### Install

*(Depends on linux distro, I'm using Debian 10)*

*Docker must be installed as well.*

``sudo apt-get install php symfony composer``

A .env.bd containing database name and password must be created as well as a .env.local containing dabatase url, at the root of the directory.

At the root of the directory, run ``docker-compose up``

Now that the database is running, run ``composer install``

End by ``php bin/console doctrine:schema:update --force``to create the entries in the database.

Let's start the server with ``symfony server:start``

Now you can connect to the local ip given by symfony.

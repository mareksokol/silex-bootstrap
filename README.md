Silex-bootstrap
=================
[![Build Status](https://travis-ci.org/templar1/silex-bootstrap.svg?branch=master)](https://travis-ci.org/templar1/silex-bootstrap)

Silex-bootstrap is an already setup [Silex][silex] project dedicated for lightweight, RESTful APIs.


Installation
------------

To start a new project, run:

	composer create-project templar1/silex-bootstrap <target-dir>


Directory structure
-------------------

The directory structure is the following

	bin/
	src/
	  Controller/
	  Entity/
	    Migrations/
	    Repository/
	  Service/
	    Provider/
	storage/
	    logs/
	    proxies/
	tests/
	web/

**bin/** place for all executables, eg. command line tool

**src/** is the root of application, contains application files: controllers, entities, migrations, repositories, services, providers and bootstrap file

**storage/** all static files, including application logs

**test/** contains unit tests

**web/** contains everything which is exposed - HTTP server should be configured to point this place as root


Usage
-----

### Configuration
All configuration should be places in `.env` file. To define new configuration parameters need to add definition in `App\Service\Provider\ConfigServiceProvider::$config`.

### Controllers
Controllers should inherit from `App\Controller\AbstractController` - this class implements several helper methods to simplify output from API.
To register new controller need to add declaration in `App\Bootstrap::loadControllers()`.

### Routes
Routes are defined in `App\Bootstrap::routes()`.

### Services
Services are registered via service providers in `App\Bootstrap::loadServices()`. Custom service providers should be placed in `App\Service\Provider` namespace.

### Database
All database mechanism is based on [Doctrine2][doctrin2] framework.
There is dedicated namespace `App\Entity` for all doctrine classes (entities, repositories: `App\Entity\Repository` and migrations: `App\Entity\Migrations`).


[silex]: http://silex.sensiolabs.org/
[doctrin2]: http://docs.doctrine-project.org/en/latest/

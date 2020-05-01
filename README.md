![alt text](https://travis-ci.org/0m3gaC0d3/jwt-secured-api-skeleton.svg?branch=master "Build status")

# Simple JWT secured API skeleton
This is a simple jwt based API skeleton to kick start your API development.
It is based on the PHP micro framework [Slim 4](http://www.slimframework.com/)
 and some well known [Symfony 5](https://symfony.com/) components.

The skeleton comes also bundled with [DI (dependency injection)](https://symfony.com/doc/current/components/dependency_injection.html)
 and [Doctrine DBAL](https://www.doctrine-project.org/projects/doctrine-dbal/en/2.10/index.html).

## Requirements
* PHP 7.4+
* composer
* openssl
* PHP extension ext-json

## How to install
* run `composer create-project omegacode/jwt-secured-api-skeleton`.
* move `.env.dist` to `.env` and adjust the values to your needs.
* Generate a public and a private key and move them to `keys/` (You can also adjust the path in the .env file).

### Generate private key
```shell script
openssl genrsa -out private.pem 2048
```

### Generate public key
```shell script
openssl rsa -in private.pem -outform PEM -pubout -out public.pem
```

## How to configure allowed clients / add client ids.
Simple add your client ids to your `.env`:
````dotenv
CLIENT_IDS="sample-uid-1,sample-uid-2"
````
The client need this id to authenticate itself to your api.

## But I dont want to use Graphql
Simple do the following:
* remove the GraphQL part of `conf/services.yaml`.
* remove `omegacode/jwt-secured-api-graphql` dependency of `composer.json`.
* remove the directory `src/GraphQL`.

## Clear the cache
The framework comes bundled with a console. To clear the cache run the following
`bin/console cache:clear`

If you are running the project using the contained docker-compose file, 
simply run th shell script `bin/docker-clear-cache.sh`
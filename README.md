## Database Model

The model is like a 'blueprint' that presents the representation of the entities that make up the database with their properties, relationships, restrictions, etc.

![](DB_MODEL.png)

# Settings

In the root project folder install composer packages:

```bash
$ composer install
```

The files that must be changed for the system to be configured on the server are:

-   Rename the file [.env.example](https://github.com/MlocksDev/news-api/blob/master/.env.example) to **.env**

```bash
APP_NAME=Simple_Laravel_API
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=UTC

LOG_CHANNEL=stack
LOG_SLACK_WEBHOOK_URL=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_news
DB_USERNAME=api_news
DB_PASSWORD=password

CACHE_DRIVER=file
QUEUE_CONNECTION=sync

JWT_SECRET=
```

All the configuration opotions above should be different in your environment (see [Laravel Docs](https://lumen.laravel.com/docs/8.x) to know more about).

To generate a random **APP_KEY** just run the command on terminal:

```bash
$ php -r "echo md5(uniqid()).\"\n\";"
```

and copy the output.

To generate a secret key **JWT_SECRET**:

```bash
$ php artisan jwt:secret
```

# Docker

**[Docker](https://docs.docker.com/install/) and [Docker Compose](https://docs.docker.com/compose/install/) must be installed.**

First create a directory called **data** in the root project folder:

```bash
$ mkdir -p docker/mysql/data
```

Again, in the root project folder, build the image and fire up the container:

```bash
$ docker-compose up -d --build
```

Or simple run the [docker-start.sh](https://github.com/MlocksDev/news-api/blob/master/docker-start.sh) script:

```bash
$ ./docker-start.sh
```

Obs.: If the script is not executable, run:

```bash
$ chmod +x docker-start.sh
```

To stop the container before moving on:

```bash
$ docker-compose stop
```

See the [references section](#references) to know more or take a look at [MySQL Docker Documentation](https://docs.docker.com/samples/library/mysql/).

# Creating the Database

Run the migration command after docker start:

```bash
$ php artisan migrate
```

Or run (i.e, import) the following DDL instructions in the file [db_schema.sql](https://github.com/MlocksDev/news-api/blob/master/db_schema.sql) directed into the database:

```sql
/* DROP AND RECREATE DATABASE */
DROP DATABASE IF EXISTS api_news;

CREATE DATABASE api_news;

USE api_news;

/* CREATE TABLE  `users` */
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

/* CREATE TABLE 'authors'*/
CREATE TABLE IF NOT EXISTS `authors` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(60) NOT NULL,
  `gender` ENUM('F', 'M') NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `authors_users_id` (`users_id` ASC),
  CONSTRAINT `authors_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB;

/* CREATE TABLE  `news` */
CREATE TABLE IF NOT EXISTS `news` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `author_id` INT NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `subtitle` VARCHAR(155) NOT NULL,
  `description` TEXT NOT NULL,
  `published_at` DATETIME NULL,
  `slug` VARCHAR(255) NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `news_authors_id` (`author_id` ASC),
  CONSTRAINT `news_authors` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

/* CREATE TABLE  `comment` */
CREATE TABLE IF NOT EXISTS `comment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `news_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `comment_news_id` (`news_id` ASC),
  CONSTRAINT `comment_news` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

/* CREATE TABLE  `image_news` */
CREATE TABLE IF NOT EXISTS `image_news` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `news_id` INT NOT NULL,
  `image` TEXT NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `image_news_news` (`news_id` ASC),
  CONSTRAINT `image_news_news` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

/* CREATE TABLE  `migrations` */
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;
```

# Runing the App

In the root project folder start up the project:

```bash
$ php -S localhost:8000 -t public
```

And then access [http://localhost:8000/](http://localhost:8000/) on your browser.

To create some entries simple run the [curl-test.sh](https://github.com/MlocksDev/news-api/blob/master/curl-test.sh) script:

```bash
$ ./curl-test.sh
```

# Enpoints

### Create User (POST):

-   **/api/register**

Example:

```bash
curl -d '{"email":"marthalocks@gmail.com", "password": "password", "password_confirmation": "password"}' \
-H "Content-Type: application/json" \
-X POST http://localhost:8000/api/register
```

The output:

```json
{
    "user": {
        "email": "marthalocks@gmail.com",
        "updated_at": "2022-03-20 10:27:21",
        "created_at": "2022-03-20 10:27:21",
        "id": 2
    },
    "message": "CREATED"
}
```

### User Login (POST):

-   **/api/login**

Example:

```bash
curl -d '{"email":"marthalocks@gmail.com", "password": "password"}' \
-H "Content-Type: application/json" \
-X POST http://localhost:8000/api/login
```

The output:

```json
{
    "message": "Authorized",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600
}
```

### User Logout (POST):

-   **/api/logout**
-   **Needs token.**

Example:

```bash
curl -H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X POST http://localhost:8000/api/logout
```

The output:

```json
{
    "message": "Successfully logged out"
}
```

### Refresh Token (POST):

-   **/api/refresh**
-   **Needs token.**

Example:

```bash
curl -H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X POST http://localhost:8000/api/refresh
```

The output:

```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600
}
```

### Retrieve Logged User (GET):

-   **/api/profile**
-   **Needs token.**

Example:

```bash
 curl -H 'Accept: application/json' \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X GET http://localhost:8000/api/profile
```

The output:

```json
{
    "user": {
        "id": 20,
        "email": "marthalocks@gmail.com",
        "created_at": "2022-03-06T14:44:59.000000Z",
        "updated_at": "2022-03-06T14:44:59.000000Z"
    }
}
```

### Create an Author (POST):

-   **/api/authors**
-   **Needs token.**

Example:

```bash
 curl -d '{"users_id":1,"name":"Martha", "lastname":"Ribeiro Locks","gender":"F","active":1}' \
-H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X POST http://localhost:8000/api/authors
```

The output:

```json
{
    "author": {
        "users_id": 1,
        "name": "Martha",
        "lastname": "Ribeiro Locks",
        "gender": "F",
        "active": 1,
        "updated_at": "2022-03-06T12:27:00.000000Z",
        "created_at": "2022-03-06T12:27:00.000000Z",
        "id": 800
    },
    "message": "CREATED"
}
```

### Update an Author by Id (PUT):

-   **/api/authors/{id}**
-   **id**: number representing the primary key
-   **Needs token.**

Example:

```bash
 curl -d '{"users_id":1,"id":1, "name":"Locks", "lastname":"Ribeiro Martha","gender":"F","active":0}' \
-H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X PUT http://localhost:8000/api/authors/1
```

The output:

```json
{
    "author": {
        "id": 1,
        "users_id": 1,
        "name": "Name Update",
        "lastname": "LastName Update",
        "gender": "F",
        "active": "1",
        "created_at": "2022-03-06T00:06:39.000000Z",
        "updated_at": "2022-03-06T00:06:48.000000Z"
    },
    "message": "UPDATED"
}
```

### List all Authors (GET):

-   **/api/authors**
-   **Needs token.**

Example:

```bash
curl -H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X GET http://localhost:8000/api/authors/
```

The output:

```json
{
    "authors": [
        {
            "id": 1,
            "users_id": 1,
            "name": "Locks",
            "lastname": "Ribeiro Martha",
            "gender": "F",
            "active": 0,
            "created_at": "2022-03-22T01:10:20.000000Z",
            "updated_at": "2022-03-22T20:01:22.000000Z",
            "deleted_at": null,
            "user": {
                "id": 1,
                "email": "marthalocks74@gmail.com",
                "created_at": "2022-03-20T19:42:00.000000Z",
                "updated_at": "2022-03-20T19:42:00.000000Z",
                "deleted_at": null
            },
            "news": [
                {
                    "id": 1,
                    "author_id": 1,
                    "title": "noticia",
                    "subtitle": "teste",
                    "description": "teste noticia",
                    "slug": "new",
                    "active": 1,
                    "created_at": "2022-03-22T01:10:20.000000Z",
                    "updated_at": "2022-03-22T01:10:20.000000Z",
                    "deleted_at": null
                },
                {
                    "id": 2,
                    "author_id": 1,
                    "title": "dede",
                    "subtitle": "dede",
                    "description": "dede",
                    "slug": "ded",
                    "active": 0,
                    "created_at": "2022-03-22T01:10:20.000000Z",
                    "updated_at": "2022-03-22T01:10:20.000000Z",
                    "deleted_at": null
                }
            ]
        },
        {
            "id": 2,
            "users_id": 22,
            "name": "Name New",
            "lastname": "LastName New",
            "gender": "M",
            "active": 1,
            "created_at": "2022-03-22T01:10:46.000000Z",
            "updated_at": "2022-03-22T01:10:46.000000Z",
            "deleted_at": null,
            "user": {
                "id": 22,
                "email": "marthalocks'73'@gmail.com",
                "created_at": "2022-03-22T01:10:46.000000Z",
                "updated_at": "2022-03-22T01:10:46.000000Z",
                "deleted_at": null
            },
            "news": []
        }
    ]
}
```

### Retrieve Author by Id (GET):

-   **/api/authors/{id}**
-   **id**: number representing the primary key
-   **Needs token.**

Example:

```bash
curl -H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X GET http://localhost:8000/api/authors/1
```

The output:

```json
{
    "author": {
        "id": 2,
        "users_id": 1,
        "name": "Ribeiro",
        "lastname": "Locks",
        "gender": "F",
        "active": 1,
        "created_at": "2022-03-06T00:06:37.000000Z",
        "updated_at": "2022-03-06T00:06:37.000000Z"
    }
}
```

### Delete an Author (DELETE):

-   **/api/authors/{id}**
-   **id**: number representing the primary key
-   **Needs token.**

Example:

```bash
curl \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X DELETE http://localhost:8000/api/authors/1
```

The output:

```json
{
    "message": "DELETED"
}
```

### Create a News (POST):

-   **/api/news**
-   **Needs token.**

Example:

```bash
curl -d '{"author_id":1, "title":"noticia", "subtitle": "nova noticia", "description": "teste", "slug": "teste", "active": "1"}'\
-H "Content-Type: application/json"\
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."\
-X POST http://localhost:8000/api/news

```

The output:

```json
{
    "news": {
        "author_id": 1,
        "title": "noticia",
        "subtitle": "nova noticia",
        "description": "teste",
        "slug": "teste",
        "active": "1",
        "plubished_at": "2022-03-06T12:27:00.000000Z",
        "updated_at": "2022-03-06T12:27:00.000000Z",
        "created_at": "2022-03-06T12:27:00.000000Z",
        "id": 800
    },
    "message": "CREATED"
}
```

### Update n News by Id (PUT):

-   **/api/news/{id}**
-   **id**: number representing the primary key
-   **Needs token.**

Example:

```bash
 curl -d '{"author_id":1,"id":1, "title":"teste", "subtitle":"new","description":"update","slug":"atualizar","active":0}' \
-H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X PUT http://localhost:8000/api/news/1
```

The output:

```json
{
    "author": {
        "id": 1,
        "author_id": 1,
        "title": "teste",
        "subtitle": "new",
        "description": "update",
        "slug": "atualizar",
        "active": "0",
        "created_at": "2022-03-06T00:06:39.000000Z",
        "updated_at": "2022-03-06T00:06:48.000000Z"
    },
    "message": "UPDATED"
}
```

### List all News (GET):

-   **/api/news**
-   **Needs token.**

Example:

```bash
curl -H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X GET http://localhost:8000/api/news/
```

The output:

```json
{
    "news": [
        {
            "id": 1,
            "author_id": 1,
            "title": "Noticia",
            "subtitle": "Nova Noticia",
            "description": "update",
            "slug": "noticia",
            "active": 0,
            "published_at": "2022-03-22T01:10:20.000000Z",
            "created_at": "2022-03-22T01:10:20.000000Z",
            "updated_at": "2022-03-22T20:01:22.000000Z",
            "deleted_at": null,
            "author": {
                "id": 1,
                "email": "marthalocks74@gmail.com",
                "created_at": "2022-03-20T19:42:00.000000Z",
                "updated_at": "2022-03-20T19:42:00.000000Z",
                "deleted_at": null
            }
        },
        {
            "id": 2,
            "author_id": 1,
            "title": "Noticia",
            "subtitle": "Nova Noticia",
            "description": "update",
            "slug": "noticia",
            "active": 0,
            "published_at": "2022-03-22T01:10:20.000000Z",
            "created_at": "2022-03-22T01:10:20.000000Z",
            "updated_at": "2022-03-22T20:01:22.000000Z",
            "deleted_at": null
        }
    ]
}
```

### Retrieve News by Id (GET):

-   **/api/news/{id}**
-   **id**: number representing the primary key
-   **Needs token.**

Example:

```bash
curl -H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X GET http://localhost:8000/api/news/3
```

The output:

```json
{
    "news": {
        "id": 2,
        "author_id": 1,
        "title": "nova noticia",
        "subtitle": "new",
        "description": "teste",
        "published_at": "2022-03-06T00:06:37.000000Z",
        "slug": "teste noticia",
        "active": 1,
        "created_at": "2022-03-06T00:06:37.000000Z",
        "updated_at": "2022-03-06T00:06:37.000000Z"
    }
}
```

### Delete a News (DELETE):

-   **/api/news/{id}**
-   **id**: number representing the primary key
-   **Needs token.**

Example:

```bash
curl \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X DELETE http://localhost:8000/api/news/3
```

The output:

```json
{
    "message": "DELETED"
}
```

### Create a Comment (POST):

-   **/api/comment**
-   **Needs token.**

Example:

```bash
curl -d '{"news_id":3, "title":"comentario","description": "teste","status": "1"}'\
-H "Content-Type: application/json"\
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."\
-X POST http://localhost:8000/api/comment

```

The output:

```json
{
    "comment": {
        "news_id": 3,
        "title": "comentário",
        "description": "teste",
        "status": "1",
        "updated_at": "2022-03-06T12:27:00.000000Z",
        "created_at": "2022-03-06T12:27:00.000000Z",
        "id": 800
    },
    "message": "CREATED"
}
```

### Update a Comment by Id (PUT):

-   **/api/comment/{id}**
-   **id**: number representing the primary key
-   **Needs token.**

Example:

```bash
 curl -d '{"news_id":3,"id":4, "title":"teste","description":"update","status":0}' \
-H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X PUT http://localhost:8000/api/comment/1
```

The output:

```json
{
    "author": {
        "id": 4,
        "news_id": 3,
        "title": "teste",
        "description": "update",
        "status": "0",
        "created_at": "2022-03-06T00:06:39.000000Z",
        "updated_at": "2022-03-06T00:06:48.000000Z"
    },
    "message": "UPDATED"
}
```

### List all Comments (GET):

-   **/api/comment**
-   **Needs token.**

Example:

```bash
curl -H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X GET http://localhost:8000/api/comment/
```

The output:

```json
{
    "comment": [
        {
            "id": 4,
            "news_id": 3,
            "title": "Noticia",
            "description": "update",
            "status": 0,
            "created_at": "2022-03-22T01:10:20.000000Z",
            "updated_at": "2022-03-22T20:01:22.000000Z",
            "deleted_at": null,
            "new": {
                "id": 1,
                "title": "marthalocks",
                "subtitle": "martinha",
                "description": "update",
                "slug": "noticia",
                "active": 0,
                "published_at": "2022-03-22T01:10:20.000000Z",
                "created_at": "2022-03-20T19:42:00.000000Z",
                "updated_at": "2022-03-20T19:42:00.000000Z",
                "deleted_at": null
            }
        },
        {
            "id": 2,
            "news_id": 3,
            "title": "Noticia",
            "subtitle": "Nova Noticia",
            "description": "update",
            "slug": "noticia",
            "active": 0,
            "published_at": "2022-03-22T01:10:20.000000Z",
            "created_at": "2022-03-22T01:10:20.000000Z",
            "updated_at": "2022-03-22T20:01:22.000000Z",
            "deleted_at": null
        }
    ]
}
```

### Retrieve Comment by Id (GET):

-   **/api/comment/{id}**
-   **id**: number representing the primary key
-   **Needs token.**

Example:

```bash
curl -H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X GET http://localhost:8000/api/comment/4
```

The output:

```json
{
    "comment": {
        "id": 4,
        "news_id": 3,
        "title": "nova noticia",
        "description": "teste",
        "status": 1,
        "created_at": "2022-03-06T00:06:37.000000Z",
        "updated_at": "2022-03-06T00:06:37.000000Z"
    }
}
```

### Delete a Comment (DELETE):

-   **/api/comment/{id}**
-   **id**: number representing the primary key
-   **Needs token.**

Example:

```bash
curl \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
-X DELETE http://localhost:8000/api/comment/4
```

The output:

```json
{
    "message": "DELETED"
}
```

# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

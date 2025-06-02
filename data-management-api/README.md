<h1 align="center">Full Stack Laravel and Vue.js Dashboard Implementation - API</h1>

This is a [Laravel 10](https://laravel.com/docs/11.x) project made for [Curotec](https://talent.curotec.com/candidate)

## 🚀 Technologies Used</h2>

This project is built with the following technologies:

<ul>
  <li>
    <strong><a href="https://laravel.com/" target="_blank">Laravel 10</a></strong> – A modern PHP framework for building robust web applications
  </li>
  <li>
    <strong><a href="https://www.postgresql.org/" target="_blank">PostgreSQL 15</a></strong> – A powerful, open-source relational database system
  </li>
  <li>
    <strong><a href="https://redis.io/" target="_blank">Redis</a></strong> – In-memory key-value store used for caching and performance optimization
  </li>
  <li>
    <strong><a href="https://www.nginx.com/" target="_blank">Nginx</a></strong> – A high-performance web server and reverse proxy
  </li>
  <li>
    <strong><a href="https://pestphp.com/" target="_blank">Pest</a></strong> – An elegant and expressive testing framework for PHP
  </li>
   <li>
    <strong><a href="https://www.docker.com/" target="_blank">Docker</a></strong> – A platform for developing, shipping, and running applications
  </li>
</ul>

## Getting Started

First copy the file <b>.env.example</b> and rename it to <b>.env</b>

run the container using docker commands:

```bash
# build or rebuild services
docker compose build

# create and start containers
docker compose up -d

# stop and remove containers, networks if needs
docker compose down
```

After that you can access bash from our application called <b>laravel</b>

```bash
docker compose exec laravel bash
```

Finally inside the container we can run our commands to start the application.

```bash
# run these commands inside a container
composer install

php artisan key:generate
```

Now you can access the project via the domain: `http://localhost:80/api`
and pgadmin `http://localhost:5050`

## Run initial data

We can run migrations and seeders

```bash
# run these commands inside a container
php artisan migrate

# or
php artisan migrate --seed

# or reset all migration and seeders
php artisan migrate:refresh --seed
```

If everything went well so far, you can try to access the application via the domain: `http://localhost:80/api`

## Run tests using pest

```bash
# run these commands inside a container
php artisan test
```

# E school SAAS

It is a Saas version of the main E school Product

[//]: # "## Screenshots"
[//]: # "![App Screenshot](https://via.placeholder.com/468x300?text=App+Screenshot+Here)"

### Setup Instructions

Clone the project

```bash
  
```

Go to the project directory

```bash
  cd eschool-saas
```

Install dependencies

```bash
  composer install
```

Copy .env File

```bash
  cp .env.example .env
```

Configure ENV Variables

`DB_HOST`

`DB_PORT`

`DB_DATABASE`

`DB_USERNAME`

`DB_PASSWORD`

<!-- note before running migrations mysqldump must be installed and on path -->

Before running migrations make sure you have `mysqldump` installed and on path

To check if `mysqldump` is installed and on path

```bash
  mysqldump --version
```

Run Migrations

```bash
  php artisan migrate
```

Start the server

```bash
  php artisan serve
```

Default Credentials for Super Admin

```bash
  defaultadmin@oyuacademy.co.ke
  password
```

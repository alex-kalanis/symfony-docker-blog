# Symfony Docker Blog

Docker box for running dummy Blog over Symfony

## Prerequisites

### Required

It is assumed you have Docker installed and it can run commands as root.

---

## What's Included

* Ubuntu Server v20.04 LTS (Focal Fossa) 64bit
* Nginx
* PHP 7.4
* PHP 8.1
* MariaDB
* Redis
* Adminer
* Symfony core

---

## Installation

The first time you clone the repo.

```bash
git clone https://github.com/alex-kalanis/symfony-docker-blog.git
cd symfony-docker-lemp
```

Now install system with simple commands. This also bring the box up.
That may take several minutes. If it doesn't explicitly
fail/quit, then it is still working.

```bash
./install.sh
```

Once the Docker finishes and is ready, you can verify PHP is working at
[http://localhost:41000/](http://localhost:41000/) for 7.4,
[http://localhost:41001/](http://localhost:41001/) for 8.1 and
[http://localhost:41009/](http://localhost:41009/) for Adminer.

## Default settings

MySQL
* root pass: 951357456852
* user: kalasymfony
* pass: kalasymfony654

Postgres
* user: kalasymfony
* pass: kalasymfony654

Blog:
* user: FirstOne
* pass: systemshare

The aplication itself contains Symfony for basics, kw_mapper, kw_table and
kw_form for data manipulation and also Clipr for running tasks, so you can
run things inside php box and with docker syntax also from external environment.
Also many things can be set by running with different environment variables,
so you can change default users and passwords used to connections.

## Caveats

If you got problems with database migrations, you will need to check inside
the docker if there already are all necessary files to run configurations
*(system_user.sql and first_db_run.sh)* or exists *mysql socket*. If not,
you will need to process files manually via Adminer. Then re-run *install.sh*
or type commands directly into CLI. The waiting time is around 20 seconds.

Now I halt with authentication problems - it throws an error somewhere
passing through the core and I do not understand what I shall pass.

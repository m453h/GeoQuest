# GeoQuest

## 🏁 Introduction

GeoQuest is a web-based interactive game designed to test and expand your knowledge of countries around the world. The target audience of this game are Students, educators, geography enthusiasts of all ages.
The game features Multiple quiz formats e.g. Multiple choice, true or false

## 🤝 Project Team
This project has been developed by Michael Hudson Nkotagu, Connect with the developer via Github, Linked.

## 🔧 Installation
### ⬇️ Downloading and installing steps:

To get the project working, follow these steps:

1. **Download Composer dependencies**

    Make sure you have [Composer installed](https://getcomposer.org/download/) and then run:
    ```
    composer install
    ```
    You may alternatively need to run `php composer.phar install`, depending
    on how you installed Composer.


2. **Setup the Database**
    Open `.env` and make sure the `DATABASE_URL` setting is
    correct for your system.
    
    Then, create the database and the schema!
    
    ```
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load
    ```

    If you get an error that the database exists, that should
    be ok. But if you have problems, completely drop the
    database (`doctrine:database:drop --force`) and try again.


3. **Build your Assets**

    To build your assets, install the dependencies with yarn and then
    run encore:
    
    ```
    yarn install
    yarn run encore dev --watch
    ```

4. **Start the built-in web server**
    You can use Nginx or Apache, but the built-in web server works
    great:
    
    ```
    php bin/console server:run
    ```
   Now check out the site at `http://localhost:8000`

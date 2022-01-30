# Weather API Assignment

A simple web application, build maily with Symfony, for a full-stack job application to FoodPanda.

> Create a PHP web application where you can add Alerts, which contains email addresses, cities and a temperature limit. 
>
> A weather API should check the local temperatures each hour and send an email to the Alert's email address.
>
> The emails should be reduced to once per day.
>
> If someone types in the same email address and city, it should ask the user back if they want to override the current value.
>
> Host somewhere and share the GitHub repository

| Used Tech | Website |
| --- | --- |
| Symfony PHP | [https://symfony.com/](https://symfony.com/) |
| Twig templete engine | [https://twig.symfony.com/](https://twig.symfony.com/) |
| SQLite database | [https://www.sqlite.org/index.html](https://www.sqlite.org/index.html) |
| JQuery for AJAX interaction | [https://jquery.com/](https://jquery.com/) |
| Openweathermap free API | [https://openweathermap.org/](https://openweathermap.org/) |
| Bootstrap for a basic look | [https://getbootstrap.com/](https://getbootstrap.com/) |
| Sass | [https://sass-lang.com/](https://sass-lang.com/) |

### Requirements:
- Composer
- Symfony-CLI
- PHP ^7.2

### Steps to set up the project locally:

- set the default enviromental parameters, database connection, mailing service credidentials, etc.

Create a local .env file from the exemplary .env.dist in app root.

- Install the php components
`composer install`

- Create the SQLite database file
`php bin/console doctrine:database:create`

- Run the default migrations
`php bin/console doctrine:migrations:migrate`

- Install the neccessary JS packages in /public
```
cd public
npm install
```

- Serve the application to a port
`symfony serve`

- Open in the browser
`localhost:8000`

### Images of the app working
![Image of the index interface](https://github.com/Archeloth/weather-api-assignment/blob/master/public/img/Screenshot_index.png)
![Image of the notices interface](https://github.com/Archeloth/weather-api-assignment/blob/master/public/img/Screenshot_notices.png)
![Image of the sent out Email](https://github.com/Archeloth/weather-api-assignment/blob/master/public/img/Screenshot_email.png)

### Things that needs more work, or I didn't have time for
- [ ] Modal interface for user friendliness, instead of the current button clicking twice for data overwriting.
- [ ] A real caching system (exp: PHP FileCache) for the API calls, to reduce traffic.
- [ ] The 1 mail / 24h limit. Currently every Alert stored lives individually, and the 24h limit applies onto to themselves, not across linked email addresses.

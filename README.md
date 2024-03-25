# TinkerList
A Laravel App for TkinkerList hiring process


## Steps to boot locally
* <strong>Docker</strong>
    - Use ```docker compose build``` and ```docker compose up -d``` to run the app.
    - Then use ```docker exec -it app bash``` and ```cd /app/public ``` to run the other commands.
    - Run ```composer install```.
    - Use the configuration set in the ```.env.example```

* <strong>local PHP and DB</strong>
    - Set the database connection in the ```.env```

Run ```composer install```

Create the key for JWT authentication
```php artisan jwt:secret```

Run migration with ``` php artisan migrate ```. If you wish to have some mock data, there is a seeder you can use by running ``` php artisan db:seed ```.

# Testing
## Automatic
Run ``` php artisan test ``` to run automated tests. Depending on your Phpunit.xml settings, this might reset your Database clean

## Postman
Postman collection: [Postman-collection](https://drive.google.com/drive/folders/1pjSektlMsgep1aEX4ceXqUMdIiiTuXu4?usp=sharing)
* Make sure you set the environment variables ```bearerToken``` and ```baseUrl```.
* Set ```baseUrl``` to ```127.0.0.1:8000``` if using local PHP, or ```127.0.0.1:8080``` if using Docker
* The bearerToken will be automatically set when running the Register or Login requests. 
* To Login as a mock user (in the seeder), use the email and ```password``` as the password.

# Architectural Choices
* Laravel 10 API
* [SPATIE LaravelData](https://spatie.be/docs/laravel-data/v4/introduction) packeage for Requests, DTOs and Resources
* Actions (\App\Actions) for CRUD basic actions (like create, update and delete)
* JWT Atuhentication based on [tymon/jwt-auth](https://jwt-auth.readthedocs.io/en/develop/)
* Locations are independant entities with it's own model and basic CRUD API.
* The events have only basic information recorded on the Database. The users invited to the events are stored in a pivot table, and the weather is stored on a separate table. Since the weather information is retreived on a Job, this can be done asynchroneosly.
* Since the invitees to an event are stored on a pivot table, when you invite someone who does not have an account, an user is created and attached to the event in the pivot table (the idea being that the user will update his user information uppon first sign in)

# AWS Suggestions
* EC2 load balancer with at least 2 instances or an auto-scaling group to avoid bottlenecks. You could use different availability zones to ensure quick access from all around the world.
* A relational database, probably RDS with Aurora or MySQL. Depending on the demand this could scale to a Multi A-Z with one reader instance and one writer instance.
* SES for sending the email invitations
* A VPC with a public and a private security group. The private for the DB, the public for the EC2 instances, with routing tables allowing access on port 443 for HTTPS and 22 for SSH connection.

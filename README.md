# TinkerList
A Laravel App for TkinkerList hiring process


# Steps to boot locally
## Docker
Use ```docker compose build``` and ```docker compose up -d``` to run the app.
Then use ```docker exec -it app bash``` and ```cd /app/public ``` to run the other commands.
Run ```composer install```.
Use the configuration set in the ```.env.example```

## local PHP and DB
Set the database connection in the ```.env```

Run ```composer install```

Create the key for JWT authentication
```php artisan jwt:secret```


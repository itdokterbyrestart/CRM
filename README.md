# CRM
## Installation:
Setup the .env file with the correct database credentials

Run composer
```
composer install 
```

Generate key
```
php artisan key:generate
```

Run migrations
```
php artisan migrate:fresh --seed
```
OR
```
php artisan migrate
```

Link storage
```
php artisan storage:link
```

Run npm
```
npm install && npm run dev
```

Serve project
```
php artisan serve
```


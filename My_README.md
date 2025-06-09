###  2. Install Dependencies (One-Time or After Pull)
```
composer install --optimize-autoloader --no-dev
```
### (optional) if frontend assets need rebuild
```
npm install && npm run build
```
###  4. Generate App Key (Only Once)
```
php artisan key:generate
```
###  5. Create Storage Links
```
php artisan storage:link
```
### 6. Run Migrations and Seeders
```
php artisan migrate --seed
```

### for future update
```
php artisan migrate
```
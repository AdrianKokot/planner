# Planner project
Trello board [here](https://trello.com/b/TIhWNcfD/bbraunplanner)

## To run project:
  - Download repository
  - Run ```composer install```
  - Copy .env.example and rename it to .env
  - Configure database connection in .env
  - Run ```php artisan key:generate```
  - Run ```php artisan migrate:fresh --seed --seeder=PermissionsAndRolesSeeder```
  - Run ```php artisan serve --port 80```
  - Enter ```http://localhost```

## Default users
 - User: L: user@planner.com P: User123
 - Admin: L: admin@planner.com P: Admin123

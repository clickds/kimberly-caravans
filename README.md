## Development Setup Instructions

Most of the setup including installing composer packages should work automatically.

#### Instructions:

1. Copy .env.example to .env (no details need changing)
 
  
2. Lunch docker: **docker-compose up**


3. Generate laravel key: **php artisan key:generate**


4. Install node modules: **npm install**


5. Build webpack: **npm run watch****


5. Connect to container: **docker exec -it kimberly-web bash**


6. Run migrations: **php artisan migrate**


7. visit http://localhost

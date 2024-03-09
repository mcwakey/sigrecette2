```markdown
# sigrecette Starter

Welcome to your new Laravel sigrecette! Follow the steps below to get started.  

## Prerequisites

Before you begin, ensure you have the following installed : 


- [Git](https://git-scm.com/downloads)
- [PHP](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/)

 
   ```>
   ```

## Installation
1. **Clone the repository:**
   ```bash
   git clone https://github.com/mcwakey/sigrecette2
   ```

2. **Change into the sigrecette directory:**
   ```bash
   cd sigrecette2
   ```

3. **Install PHP dependencies:**
   ```bash
   composer install
   ```

4. **Copy the `.env.example` file to `.env` and configure your database:**
   ```bash
   cp .env.example .env
   ```
   Update the `.env` file with your database credentials.

5. **Generate the application key:**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations and seed:**
   ```bash
   php artisan migrate --seed
   ```

7. **Install JavaScript dependencies:**
   ```bash
   npm install && npm run dev
   ```

## Serve the Application

To start the development server, run:

```bash
php artisan serve
```

Your Laravel application should now be running at [http://localhost:8000](http://localhost:8000).





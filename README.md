# Doctor Management System with Laravel

A comprehensive doctor appointment management system built with Laravel 10 that allows doctors, patients and admins to manage medical appointments, schedules, and more.

## Features

- **Multi-user Authentication**: Separate login portals for doctors, patients, and administrators
- **Doctor Management**: Create, update, view, and delete doctor profiles with specialties
- **Patient Management**: Patient registration, profile management, and appointment history
- **Appointment Scheduling**: Schedule, manage, and track appointments between doctors and patients
- **Medical Specialties**: Categorize doctors by medical specialties
- **Chat System**: Real-time messaging between doctors and patients using Pusher
- **Payment Integration**: Process payments via Stripe and PayPal
- **Dashboard Analytics**: Visualize appointment data and statistics using charts
- **Responsive Design**: Built with Tailwind CSS for a mobile-friendly experience

## System Requirements

- PHP 8.1 or higher
- Composer 2.x
- Node.js 16.x or higher
- MariaDB 10.x or MySQL
- Web server (Nginx or Apache)

## Installation

1. Clone the repository
```bash
git clone https://github.com/Ivans51/doctor-management-laravel.git
cd doctor-management-laravel
```

2. Install PHP dependencies
```bash
composer install
```

3. Install JavaScript dependencies
```bash
npm install
```

4. Create a copy of the environment file and configure it
```bash
cp .env.example .env
```
   
5. Generate an application key
```bash
php artisan key:generate
```

6. Configure your database in the `.env` file
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=doctor_management
DB_USERNAME=root
DB_PASSWORD=
```

7.  Apply Uuidable Trait Patch:**
*(Note: This step manually replaces a file within the `vendor` directory.).*
```bash
cp vendor_files/Uuidable.php vendor/your-app-rocks/eloquent-uuid/src/Traits/Uuidable.php
```

8. Configure Pusher, Stripe, and PayPal credentials in the `.env` file for real-time features and payment processing

9. Run migrations and seed the database
```bash
php artisan migrate --seed
```

10. Generate IDE helper files (optional but recommended)
```bash
php artisan ide-helper:generate
php artisan ide-helper:models
```

11. Start the development server
```bash
php artisan serve
```

12. Compile assets
```bash
npm run dev
```

## Docker Setup

The project includes Docker configuration for easy deployment:

```bash
# Build and start the containers
docker-compose up -d
```

## Directory Structure

- `app/` - Contains the core code of the application
  - `Models/` - Eloquent models (Doctor, Patient, User, etc.)
  - `Http/Controllers/` - Request handlers for each section
  - `Http/Middleware/` - Custom middleware for authorization
- `database/` - Migrations and seeders
- `resources/views/` - Blade templates for the frontend
- `routes/` - Application routes definition
  - `web.php` - Main web routes for doctors, patients, and admin
- `public/` - Publicly accessible files
- `config/` - Configuration files
- `tests/` - Testing suite

## User Roles

- **Doctors**: Can manage their schedule, view appointments, chat with patients
- **Patients**: Can book appointments, make payments, chat with doctors
- **Administrators**: Can manage doctors, patients, medical specialties, and review system analytics

## Testing

Run tests using PHPUnit:

```bash
php artisan test
```

## Deployment

For production deployment, follow these steps:

1. Set up your production environment
2. Configure your web server (Nginx/Apache)
3. Set appropriate environment variables for production
4. Optimize Laravel for production
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Contributing

Please read the [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgements

- [Laravel](https://laravel.com) - The PHP framework used
- [Tailwind CSS](https://tailwindcss.com) - For styling
- [Blade Icons](https://blade-ui-kit.com/blade-icons) - For UI icons
- [Charts](https://charts.erik.cat/) - For data visualization
- [Stripe](https://stripe.com) - Payment processing
- [PayPal](https://www.paypal.com) - Payment processing

---

Created by [Ivans](https://github.com/Ivans51) ❤️

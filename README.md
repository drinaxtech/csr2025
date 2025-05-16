# CSR2025 – Corporate Social Responsibility Platform

CSR2025 is a Laravel-based web application designed to empower employees of large corporations to engage in social and environmental initiatives. The platform facilitates the creation and management of fundraising campaigns, allows employees to make donations, and provides administrative tools for overseeing these activities.

## 🚀 Features

- **Campaign Management**: Authenticated users can create, view, edit, and delete fundraising campaigns.
- **Donations**: Employees can donate to campaigns and receive confirmations.
- **User Profiles**: Users can manage their personal information and view their donation history.
- **Administration Panel**: Admins have access to dashboards and can manage application parameters.
- **Authentication**: Secure login and registration processes.

## 🛠️ Tech Stack

- **Backend**: Laravel 12, PHP 8.4
- **Frontend**: Vue.js 3, Inertia.js, Tailwind CSS
- **Database**: MySQL 8
- **Tooling**: Composer, Vite, Node.js, NPM

## 📦 Requirements

- **PHP**: 8.4 or higher
- **MySQL**: 8.0 or higher
- **Node.js**: 16.x or higher
- **NPM**: 8.x or higher
- **Composer**: 2.x or higher

## 🧰 Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/drinaxtech/csr2025.git
   cd csr2025
   ```

2. **Install PHP Dependencies**:
   ```bash
   composer install
   ```

3. **Install Node.js Dependencies**:
   ```bash
   npm install
   ```

4. **Environment Configuration**:
   - Copy the example environment file and modify it as needed:
     ```bash
     cp .env.example .env
     ```
   - Generate the application key:
     ```bash
     php artisan key:generate
     ```
   - Set up your database credentials in the `.env` file.

5. **Database Migration**:
   ```bash
   php artisan migrate --seed
   ```

6. **Build Assets**:
   ```bash
   npm run build
   ```

7. **Start the Development Server**:
   ```bash
   php artisan serve
   ```

   Access the application at `http://localhost:8000`.

## 🔐 Authentication

The application uses Laravel Breeze for authentication, providing features like login, registration, password reset, and email verification.

## 🧪 Testing

- **PHPUnit**: For backend testing.
- **Pest**: A delightful PHP Testing Framework.
- **PHPStan**: For static analysis (Level 8).

Run tests using:

```bash
php artisan test
```

## 📂 Project Structure

- **routes/web.php**: Defines web routes for the application.
- **app/Http/Controllers**: Contains controller classes.
- **resources/js/Pages**: Vue.js components for different pages.
- **resources/views**: Blade templates.
- **database/migrations**: Database migration files.

## 📄 License

This project is open-source and available under the [MIT license](LICENSE).
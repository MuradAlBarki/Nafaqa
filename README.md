# Nafaqa App

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.3-blue)
![MySQL](https://img.shields.io/badge/MySQL-8-green)
![Docker](https://img.shields.io/badge/Docker-yes-lightblue)

**Nafaqa** is a web application designed to manage alimony payments for divorced women. It provides a secure, user-friendly interface for administrators and users to track, manage, and review alimony cases efficiently.

---

## Features

- **Divorce Case Management**: Create, update, and track divorce cases.
- **Alimony Payments**: Record payments, review statuses, track late payments.
- **User Roles & Permissions**: Admins, case managers, and regular users with role-based access.
- **Notifications**: Alerts for new payments, updates, or overdue cases.
- **Reports & Export**: Export case and payment data to Excel or PDF.
- **Multilingual Support**: Arabic and English interfaces.

---

## Tech Stack

- **Backend**: Laravel 12, PHP 8.3
- **Frontend**: Blade templates, Vue.js, Tailwind CSS
- **Database**: MySQL
- **Testing**: k6 for load testing
- **Docker**: Containerized deployment

---

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/nafaqa-app.git
   cd nafaqa-app
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Configure your database settings in `.env`.

4. **Run migrations**
   ```bash
   php artisan migrate --seed
   ```

5. **Serve the application**
   ```bash
   php artisan serve
   ```
   The app will be available at `http://localhost:8000`.

---

## Usage

- Admins can manage cases, users, and payments.
- Regular users can view their cases and payment status.
- Notifications will appear in the dashboard.
- Use the language toggle to switch between Arabic and English.

---

## Testing

- **Load Testing**
  ```bash
  k6 run tests/K6/load-test.js
  ```
- **Unit & Feature Testing**
  ```bash
  php artisan test
  ```

---

## Contributing

1. Fork the repository
2. Create a new branch (`git checkout -b feature/YourFeature`)
3. Make your changes
4. Commit your changes (`git commit -am 'Add new feature'`)
5. Push to the branch (`git push origin feature/YourFeature`)
6. Open a Pull Request

---

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

## Contact

Developed by **Murad AlBarki**  
College of Computer Technology – Tripoli  
Email: `pe.murad@gmail.com`


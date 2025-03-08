# Vsmart Tune-Up Sales Tracker

A Laravel-based customer and device management system for Vsmart Tune-Up, featuring:

- Customer management with contact details and social media links
- Device tracking for each customer
- Repair status monitoring
- Modern, responsive UI with dark mode support
- Real-time updates using AJAX
- Smooth animations and transitions

_Last updated: March 9, 2025_

## Features

- **Customer Management**
  - Add, edit, and delete customers
  - Track customer contact information
  - Link Facebook profiles
  - Store customer addresses

- **Device Management**
  - Track multiple devices per customer
  - Monitor repair status
  - Device history tracking
  - Quick actions for device management

- **User Interface**
  - Modern, clean design
  - Responsive layout
  - Dark mode support
  - Smooth animations
  - Real-time updates

## Technical Stack

- Laravel (Backend Framework)
- Tailwind CSS (Styling)
- Alpine.js (JavaScript Framework)
- AJAX for real-time updates
- CSS animations for smooth transitions

## Installation

1. Clone the repository
```bash
git clone https://github.com/jalalon11/vsmart-tuneup-sales.git
cd vsmart-tuneup-sales
```

2. Install dependencies
```bash
composer install
npm install
```

3. Set up environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env`

5. Run migrations
```bash
php artisan migrate
```

6. Compile assets
```bash
npm run dev
```

7. Serve the application
```bash
php artisan serve
```

## License

[MIT License](LICENSE.md)

## Author

Jalal Jalalon

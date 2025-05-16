# Product Reservation System

A modern web application that allows users to browse products and make reservations. This full-stack system demonstrates CRUD operations with React on the frontend and PHP with Flight framework on the backend.

![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)

## 📋 Table of Contents

- [Features](#features)
- [Demo](#demo)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
  - [Prerequisites](#prerequisites)
  - [Backend Setup](#backend-setup)
  - [Frontend Setup](#frontend-setup)
  - [Database Setup](#database-setup)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Project Structure](#project-structure)
- [Contributing](#contributing)
- [License](#license)

## ✨ Features

- **Product Management**: View detailed product listings with inventory information
- **Reservation System**: Make reservations for specific products with quantity selection
- **Reservation Management**: View, update, and cancel your reservations
- **Single User Experience**: Simplified interface designed for a single user without the need to input name for each reservation
- **Responsive Design**: Tailwind CSS ensures a beautiful experience on all devices
- **Robust Validation**: Both client and server-side validation for all operations
- **Error Handling**: Comprehensive error handling throughout the application

## 🚀 Technology Stack

### Frontend
- **React 18+** with JSX files
- **React Router 6** for navigation
- **Vite** as the build tool
- **Tailwind CSS** for styling
- **ES6+** features and modern JavaScript patterns

### Backend
- **PHP 7.4+**
- **Flight PHP** microframework
- **PDO** for database interactions
- **MySQL** database

### Communication
- **AJAX** via the Fetch API
- **RESTful API** design principles

## 💻 Installation

### Prerequisites

- **XAMPP** or similar local server environment with PHP 7.4+ and MySQL
- **Node.js** 16+ and npm
- **Composer** for PHP dependencies
- **Git** for version control

### Clone the Repository

```bash
# Clone the repository
git clone https://github.com/yourusername/product-reservation-system.git

# Navigate to the project directory
cd product-reservation-system
```

### Backend Setup

```bash
# Navigate to the backend directory
cd backend

# Install PHP dependencies
composer install

# (Optional) If you need to configure your environment, copy and modify .env.example
# cp .env.example .env
```

### Frontend Setup

```bash
# Navigate to the frontend directory
cd frontend

# Install dependencies
npm install

# Create .env file for environment variables (if needed)
echo "VITE_API_URL=http://localhost/product-reservation-system/backend" > .env

# Start the development server
npm run dev
```

### Database Setup

1. Start XAMPP and ensure MySQL service is running
2. Import the database schema from `reservation-schema.sql`:

```bash
# Using MySQL CLI (alternative to phpMyAdmin)
mysql -u root -p < reservation-schema.sql
```

Alternatively, use phpMyAdmin or HeidiSQL:
1. Open your database management tool
2. Create a new database called `product_reservation_system`
3. Import the `reservation-schema.sql` file

## 🔍 Usage

1. Start your MySQL server and Apache (via XAMPP)
2. Ensure the backend is accessible at `http://localhost/product-reservation-system/backend`
3. Start the frontend development server: `cd frontend && npm run dev`
4. Access the application at `http://localhost:3000`

### Key Functionality

- **Browse Products**: The home page displays all available products
- **Make Reservation**: Click "Reserve" on any product to create a reservation
- **View Reservations**: Navigate to "My Reservations" to see your bookings
- **Update/Cancel**: Edit or cancel your reservations as needed

## 📚 API Documentation

The API is available at `http://localhost/product-reservation-system/backend/api`:

### Products
- `GET /products` - List all products
- `GET /products/{id}` - Get product details

### Reservations
- `GET /reservations` - List all reservations
- `GET /reservations/{id}` - Get reservation details
- `POST /reservations` - Create a new reservation
- `PUT /reservations/{id}` - Update a reservation
- `DELETE /reservations/{id}` - Delete a reservation

## 📁 Project Structure

```
product-reservation-system/
├── backend/                # PHP backend
│   ├── composer.json       # PHP dependencies
│   ├── .htaccess           # Apache configuration
│   ├── index.php           # Main entry point
│   └── src/                # Source files
│       ├── config/         # Database configuration
│       ├── controllers/    # Request controllers
│       ├── models/         # Data models
│       └── routes/         # API routes
├── frontend/               # React frontend
│   ├── index.html          # HTML entry point
│   ├── package.json        # Node dependencies
│   ├── vite.config.js      # Vite configuration
│   └── src/                # Source files
│       ├── components/     # React components
│       └── services/       # API services
└── reservation-schema.sql  # Database schema
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

Developed with ❤️ using React, PHP, and Tailwind CSS 
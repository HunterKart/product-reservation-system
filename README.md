# Product Reservation System

A simple product reservation system with full CRUD functionality built with React, PHP (Flight framework), and MySQL.

## Project Structure

- `backend/` - PHP backend API using Flight framework
- `frontend/` - React frontend with Vite and Tailwind CSS
- `reservation-schema.sql` - Database schema and sample data

## Features

- View a list of available products
- Make a reservation by selecting a product and specifying a quantity
- View all reservations
- Update an existing reservation
- Delete or cancel a reservation

## Requirements

- XAMPP or similar local server environment with PHP 7.4+ and MySQL
- Node.js 16+ and npm for the frontend
- Composer for PHP dependencies

## Setup Instructions

### 1. Clone the repository

Clone this repository to your XAMPP htdocs directory.

### 2. Database Setup

1. Start XAMPP and ensure MySQL service is running
2. Open HeidiSQL or phpMyAdmin
3. Import the `reservation-schema.sql` file to create the database and tables with sample data

### 3. Backend Setup

1. Navigate to the backend directory:
```
cd backend
```

2. Install PHP dependencies using Composer:
```
composer install
```

3. (Optional) You can run the setup-db.php script to set up the database:
```
php setup-db.php
```

### 4. Frontend Setup

1. Navigate to the frontend directory:
```
cd frontend
```

2. Install dependencies:
```
npm install
```

3. (Optional) Create a `.env` file in the frontend directory for environment variables:
```
# frontend/.env
VITE_API_URL=http://localhost/reservation-system-final/backend
```

4. Start the development server:
```
npm run dev
```

5. The frontend application will be accessible at `http://localhost:3000`

### 5. API Endpoints

The API will be available at `http://localhost/reservation-system-final/backend/api`:

- Products:
  - GET `/products` - List all products
  - GET `/products/{id}` - Get product details

- Reservations:
  - GET `/reservations` - List all reservations
  - GET `/reservations/{id}` - Get reservation details
  - POST `/reservations` - Create a new reservation
  - PUT `/reservations/{id}` - Update a reservation
  - DELETE `/reservations/{id}` - Delete a reservation

## Security Considerations

- The API uses PDO with prepared statements to prevent SQL injection
- Input validation is performed on both client and server side
- Full error handling with try/catch blocks

## Technology Stack

- **Frontend**:
  - React 18+ with JSX files
  - React Router for navigation
  - Vite as the build tool (faster and more modern than Create React App)
  - Tailwind CSS for styling
  - Modern JavaScript with ES6+ features

- **Backend**:
  - PHP 7.4+
  - Flight PHP microframework
  - PDO for database interactions
  - MySQL database
  
- **API Communication**:
  - AJAX via the fetch API

## Notes

- This is a basic implementation without authentication or admin features
- In a real-world scenario, you would want to add user authentication and role-based access control
- The frontend URL in the API service (`frontend/src/services/api.js`) may need to be adjusted based on your local environment setup 
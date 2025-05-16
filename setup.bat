@echo off
echo Setting up Product Reservation System...
echo.

rem Set up backend dependencies
cd backend
echo Installing backend dependencies...
call composer install
cd ..
echo.

rem Set up frontend dependencies
cd frontend
echo Installing frontend dependencies...
call npm install
echo.

echo Setup completed successfully! Follow these steps to start the application:
echo 1. Import reservation-schema.sql into MySQL
echo 2. cd frontend
echo 3. npm run dev
echo 4. Open http://localhost:3000 in your browser 
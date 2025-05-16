<?php
require_once 'src/controllers/ReservationController.php';

$reservationController = new ReservationController();

// Get all reservations
Flight::route('GET /api/reservations', function() use ($reservationController) {
    $reservationController->getAllReservations();
});

// Get single reservation
Flight::route('GET /api/reservations/@id', function($id) use ($reservationController) {
    $reservationController->getReservationById($id);
});

// Create reservation
Flight::route('POST /api/reservations', function() use ($reservationController) {
    $reservationController->createReservation();
});

// Update reservation
Flight::route('PUT /api/reservations/@id', function($id) use ($reservationController) {
    $reservationController->updateReservation($id);
});

// Delete reservation
Flight::route('DELETE /api/reservations/@id', function($id) use ($reservationController) {
    $reservationController->deleteReservation($id);
});
?> 
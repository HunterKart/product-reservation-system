<?php
require_once 'src/models/Reservation.php';
require_once 'src/models/Product.php';
require_once 'src/config/database.php';

class ReservationController {
    private $db;
    private $reservation;
    private $product;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->reservation = new Reservation($this->db);
        $this->product = new Product($this->db);
    }

    // Get all reservations
    public function getAllReservations() {
        try {
            // Query reservations
            $stmt = $this->reservation->read();
            $num = $stmt->rowCount();

            // Check if any reservations exist
            if($num > 0) {
                // Reservations array
                $reservations_arr = array();
                $reservations_arr["records"] = array();

                // Retrieve table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    // Extract row
                    extract($row);

                    $reservation_item = array(
                        "id" => $id,
                        "product_id" => $product_id,
                        "product_name" => $product_name,
                        "name" => $name,
                        "quantity" => $quantity,
                        "date" => $date,
                        "status" => $status
                    );

                    array_push($reservations_arr["records"], $reservation_item);
                }

                // Set response code - 200 OK
                http_response_code(200);

                // Return JSON
                echo json_encode($reservations_arr);
            } else {
                // No reservations found
                http_response_code(404);
                echo json_encode(array("message" => "No reservations found."));
            }
        } catch(Exception $e) {
            // Set response code - 500 Internal Server Error
            http_response_code(500);
            echo json_encode(array("message" => "An error occurred: " . $e->getMessage()));
        }
    }

    // Get single reservation
    public function getReservationById($id) {
        try {
            // Set ID property
            $this->reservation->id = $id;

            // Get reservation
            if($this->reservation->readOne()) {
                // Create array
                $reservation_arr = array(
                    "id" => $this->reservation->id,
                    "product_id" => $this->reservation->product_id,
                    "name" => $this->reservation->name,
                    "quantity" => $this->reservation->quantity,
                    "date" => $this->reservation->date,
                    "status" => $this->reservation->status
                );

                // Set response code - 200 OK
                http_response_code(200);

                // Return JSON
                echo json_encode($reservation_arr);
            } else {
                // Not found
                http_response_code(404);
                echo json_encode(array("message" => "Reservation not found."));
            }
        } catch(Exception $e) {
            // Set response code - 500 Internal Server Error
            http_response_code(500);
            echo json_encode(array("message" => "An error occurred: " . $e->getMessage()));
        }
    }

    // Create reservation
    public function createReservation() {
        try {
            // Get posted data
            $data = json_decode(Flight::request()->getBody());

            // Validate data
            if(
                !empty($data->product_id) &&
                !empty($data->quantity)
            ) {
                // Check if product exists and has sufficient stock
                $this->product->id = $data->product_id;
                if($this->product->readOne()) {
                    // Check quantity
                    if($this->product->quantity >= $data->quantity) {
                        // Set reservation property values
                        $this->reservation->product_id = $data->product_id;
                        $this->reservation->name = $data->name ?? 'Jimarnie Branzuela'; // Always use specific name if not provided
                        $this->reservation->quantity = $data->quantity;
                        $this->reservation->status = isset($data->status) ? $data->status : "pending";

                        // Create the reservation
                        if($this->reservation->create()) {
                            // Update product quantity
                            $this->product->quantity -= $data->quantity;
                            if($this->product->updateQuantity()) {
                                // Set response code - 201 created
                                http_response_code(201);

                                // Tell the user
                                echo json_encode(array("message" => "Reservation was created."));
                            } else {
                                // Set response code - 503 service unavailable
                                http_response_code(503);

                                // Tell the user
                                echo json_encode(array("message" => "Unable to update product quantity."));
                            }
                        } else {
                            // Set response code - 503 service unavailable
                            http_response_code(503);

                            // Tell the user
                            echo json_encode(array("message" => "Unable to create reservation."));
                        }
                    } else {
                        // Insufficient stock
                        http_response_code(400);
                        echo json_encode(array("message" => "Insufficient stock for this product."));
                    }
                } else {
                    // Product not found
                    http_response_code(404);
                    echo json_encode(array("message" => "Product not found."));
                }
            } else {
                // Set response code - 400 bad request
                http_response_code(400);

                // Tell the user
                echo json_encode(array("message" => "Unable to create reservation. Data is incomplete."));
            }
        } catch(Exception $e) {
            // Set response code - 500 Internal Server Error
            http_response_code(500);
            echo json_encode(array("message" => "An error occurred: " . $e->getMessage()));
        }
    }

    // Update reservation
    public function updateReservation($id) {
        try {
            // Get posted data
            $data = json_decode(Flight::request()->getBody());

            // Set ID property
            $this->reservation->id = $id;

            // Check if reservation exists
            if($this->reservation->readOne()) {
                // Get original values for comparison
                $original_product_id = $this->reservation->product_id;
                $original_quantity = $this->reservation->quantity;

                // Validate data - only quantity and status are required for updates
                if(!empty($data->quantity)) {
                    // Handle quantity changes
                    $quantity_diff = $data->quantity - $original_quantity;

                    // Check if we need more products
                    if($quantity_diff > 0) {
                        // Load current product details
                        $this->product->id = $original_product_id;
                        $this->product->readOne();

                        // Check if enough stock
                        if($this->product->quantity < $quantity_diff) {
                            // Not enough stock
                            http_response_code(400);
                            echo json_encode(array("message" => "Insufficient stock for this product."));
                            return;
                        }

                        // Update product quantity
                        $this->product->quantity -= $quantity_diff;
                        $this->product->updateQuantity();
                    } else if($quantity_diff < 0) {
                        // Returning products to stock
                        $this->product->id = $original_product_id;
                        $this->product->readOne();
                        $this->product->quantity += abs($quantity_diff);
                        $this->product->updateQuantity();
                    }

                    // Set reservation property values - keeping original product_id and name
                    $this->reservation->product_id = $original_product_id;
                    $this->reservation->name = 'Jimarnie Branzuela'; // Always use this specific name
                    $this->reservation->quantity = $data->quantity;
                    $this->reservation->status = isset($data->status) ? $data->status : $this->reservation->status;

                    // Update the reservation
                    if($this->reservation->update()) {
                        // Set response code - 200 ok
                        http_response_code(200);

                        // Tell the user
                        echo json_encode(array("message" => "Reservation was updated."));
                    } else {
                        // Set response code - 503 service unavailable
                        http_response_code(503);

                        // Tell the user
                        echo json_encode(array("message" => "Unable to update reservation."));
                    }
                } else {
                    // Set response code - 400 bad request
                    http_response_code(400);

                    // Tell the user
                    echo json_encode(array("message" => "Unable to update reservation. Data is incomplete."));
                }
            } else {
                // Not found
                http_response_code(404);
                echo json_encode(array("message" => "Reservation not found."));
            }
        } catch(Exception $e) {
            // Set response code - 500 Internal Server Error
            http_response_code(500);
            echo json_encode(array("message" => "An error occurred: " . $e->getMessage()));
        }
    }

    // Delete reservation
    public function deleteReservation($id) {
        try {
            // Set reservation ID
            $this->reservation->id = $id;

            // Check if reservation exists and get its details
            if($this->reservation->readOne()) {
                // Store reservation details for updating product
                $product_id = $this->reservation->product_id;
                $quantity = $this->reservation->quantity;

                // Delete the reservation
                if($this->reservation->delete()) {
                    // Return product quantity to stock
                    $this->product->id = $product_id;
                    if($this->product->readOne()) {
                        $this->product->quantity += $quantity;
                        $this->product->updateQuantity();
                    }

                    // Set response code - 200 ok
                    http_response_code(200);

                    // Tell the user
                    echo json_encode(array("message" => "Reservation was deleted."));
                } else {
                    // Set response code - 503 service unavailable
                    http_response_code(503);

                    // Tell the user
                    echo json_encode(array("message" => "Unable to delete reservation."));
                }
            } else {
                // Reservation not found
                http_response_code(404);
                echo json_encode(array("message" => "Reservation not found."));
            }
        } catch(Exception $e) {
            // Set response code - 500 Internal Server Error
            http_response_code(500);
            echo json_encode(array("message" => "An error occurred: " . $e->getMessage()));
        }
    }
}
?> 
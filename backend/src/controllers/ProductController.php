<?php
require_once 'src/models/Product.php';
require_once 'src/config/database.php';

class ProductController {
    private $db;
    private $product;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
    }

    // Get all products
    public function getAllProducts() {
        try {
            // Query products
            $stmt = $this->product->read();
            $num = $stmt->rowCount();

            // Check if any products
            if($num > 0) {
                // Products array
                $products_arr = array();
                $products_arr["records"] = array();

                // Retrieve table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    // Extract row
                    extract($row);

                    $product_item = array(
                        "id" => $id,
                        "name" => $name,
                        "quantity" => $quantity,
                        "description" => $description
                    );

                    array_push($products_arr["records"], $product_item);
                }

                // Set response code - 200 OK
                http_response_code(200);

                // Return JSON 
                echo json_encode($products_arr);
            } else {
                // No products found
                http_response_code(404);
                echo json_encode(array("message" => "No products found."));
            }
        } catch(Exception $e) {
            // Set response code - 500 Internal Server Error
            http_response_code(500);
            echo json_encode(array("message" => "An error occurred: " . $e->getMessage()));
        }
    }

    // Get single product
    public function getProductById($id) {
        try {
            // Set ID property
            $this->product->id = $id;

            // Get product
            if($this->product->readOne()) {
                // Create array
                $product_arr = array(
                    "id" =>  $this->product->id,
                    "name" => $this->product->name,
                    "quantity" => $this->product->quantity,
                    "description" => $this->product->description
                );

                // Set response code - 200 OK
                http_response_code(200);

                // Return JSON
                echo json_encode($product_arr);
            } else {
                // Not found
                http_response_code(404);
                echo json_encode(array("message" => "Product not found."));
            }
        } catch(Exception $e) {
            // Set response code - 500 Internal Server Error
            http_response_code(500);
            echo json_encode(array("message" => "An error occurred: " . $e->getMessage()));
        }
    }
}
?> 
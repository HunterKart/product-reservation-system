<?php
class Product {
    private $conn;
    private $table_name = "products";

    // Product properties
    public $id;
    public $name;
    public $quantity;
    public $description;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all products
    public function read() {
        // Create query
        $query = "SELECT
                   id, name, quantity, description
                FROM
                    " . $this->table_name . "
                ORDER BY
                    id DESC";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get single product
    public function readOne() {
        // Create query
        $query = "SELECT
                    id, name, quantity, description
                FROM
                    " . $this->table_name . "
                WHERE
                    id = ?
                LIMIT 0,1";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        // Get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        if($row) {
            $this->name = $row['name'];
            $this->quantity = $row['quantity'];
            $this->description = $row['description'];
            return true;
        }

        return false;
    }

    // Update product quantity
    public function updateQuantity() {
        // Create query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    quantity = :quantity
                WHERE
                    id = :id";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Sanitize and bind data
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":id", $this->id);

        // Execute query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?> 
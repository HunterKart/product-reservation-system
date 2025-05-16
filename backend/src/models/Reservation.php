<?php
class Reservation {
    private $conn;
    private $table_name = "reservations";

    // Reservation properties
    public $id;
    public $product_id;
    public $name;
    public $quantity;
    public $date;
    public $status;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create reservation
    public function create() {
        // Create query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    product_id = :product_id,
                    name = :name,
                    quantity = :quantity,
                    status = :status";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Bind data
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":status", $this->status);

        // Execute query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read all reservations
    public function read() {
        // Create query
        $query = "SELECT
                    r.id, r.product_id, r.name, r.quantity, r.date, r.status,
                    p.name as product_name
                FROM
                    " . $this->table_name . " r
                    LEFT JOIN
                        products p ON r.product_id = p.id
                ORDER BY
                    r.id DESC";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Read single reservation
    public function readOne() {
        // Create query
        $query = "SELECT
                    r.id, r.product_id, r.name, r.quantity, r.date, r.status,
                    p.name as product_name
                FROM
                    " . $this->table_name . " r
                    LEFT JOIN
                        products p ON r.product_id = p.id
                WHERE
                    r.id = ?
                LIMIT 0,1";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        // Get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            // Set properties
            $this->id = $row['id'];
            $this->product_id = $row['product_id'];
            $this->name = $row['name'];
            $this->quantity = $row['quantity'];
            $this->date = $row['date'];
            $this->status = $row['status'];
            return true;
        }

        return false;
    }

    // Update reservation
    public function update() {
        // Create query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    product_id = :product_id,
                    name = :name,
                    quantity = :quantity,
                    status = :status
                WHERE
                    id = :id";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        // Execute query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete reservation
    public function delete() {
        // Create query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind id
        $stmt->bindParam(1, $this->id);

        // Execute query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?> 
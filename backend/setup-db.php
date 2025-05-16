<?php
// Include database configuration
require_once 'src/config/database.php';

// Create a new database connection
$database = new Database();
$conn = $database->getConnection();

// Read the schema SQL file
$sql = file_get_contents('../reservation-schema.sql');

// Execute the schema SQL commands
try {
    $conn->exec($sql);
    echo "Database setup completed successfully!";
} catch(PDOException $e) {
    echo "Database setup error: " . $e->getMessage();
}
?> 
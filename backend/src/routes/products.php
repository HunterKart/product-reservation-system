<?php
require_once 'src/controllers/ProductController.php';

$productController = new ProductController();

// Get all products
Flight::route('GET /api/products', function() use ($productController) {
    $productController->getAllProducts();
});

// Get single product
Flight::route('GET /api/products/@id', function($id) use ($productController) {
    $productController->getProductById($id);
});
?> 
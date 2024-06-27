<?php
// Include necessary files (e.g., database connection, Size model)
include_once 'backend/config/db.php';
include_once 'backend/models/Size.php';

// Create a new database connection
$database = new Database();
$db = $database->getConnection();

// Create a new Size object
$size = new Size($db);

// Define the sizes to be inserted
$sizes = array('XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL');

// Loop through the sizes and insert them into the database
foreach ($sizes as $sizeName) {
    $size->name = $sizeName;
    $size->create();
}

echo "Sizes seeded successfully.";

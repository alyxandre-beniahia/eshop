<?php
// Extract the URL parameter correctly
$url_param = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url_parts = explode('/', $url_param);

// Prepare a debug response
$debugResponse = array(
    "initial_url" => $url_param,
    "url_array" => $url_parts,
    "get_parameters" => $_GET
);

if (!empty($url_parts[0]) && $url_parts[0] === 'products') {
    // Endpoint found
    $debugResponse["message"] = "Endpoint found: " . $url_parts[0];
    
    // Buffer output to capture JSON responses
    ob_start();
    
    // Include the routes file if endpoint found
    include_once '../routes/productRoutes.php';
    
    // Capture output from included file
    $output = ob_get_clean();
    
    // Check if JSON response from included file
    $json_response = json_decode($output, true);
    if ($json_response && isset($json_response['message'])) {
        $debugResponse['product_message'] = $json_response['message'];
    } else {
        $debugResponse['product_message'] = "No product message found.";
    }
    
    echo json_encode($debugResponse);
} else {
    // Endpoint not found
    $debugResponse["message"] = "Endpoint not found.";
    echo json_encode($debugResponse);
}

// Ensure no additional output
exit;
?>

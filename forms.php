<?php
//@include_once dirname(__FILE__) . "/scripts.php";
function sendFormData(string $endpointUrl, array $formData): array 
{
    // Initialize cURL session
    $ch = curl_init();

    // Configure cURL options
    curl_setopt_array($ch, [
        CURLOPT_URL            => $endpointUrl,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($formData), // Form-urlencoded payload
        CURLOPT_RETURNTRANSFER => true,                        // Return response as string
        CURLOPT_TIMEOUT        => 30,                          // Timeout in seconds
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ]
    ]);

    // Execute the request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error    = curl_error($ch);

    curl_close($ch);

    // Handle cURL connection errors
    if ($error) {
        return [
            'success' => false,
            'message' => 'cURL Error: ' . $error
        ];
    }

    // Decode the JSON response
    $decodedResponse = json_decode($response, true);

    // Check if JSON decoding succeeded
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'success' => false,
            'message' => 'Invalid JSON response from server',
            'raw'     => $response,
            'status'  => $httpCode
        ];
    }

    return $decodedResponse;
}

$url = "https://beta-embedded.varsitymarket.co.za/api/form_handler.php?website_hash=d8e986f6a6953a97d724f5bfe044c1eac209d11c3da99d9d71e0517b0d9c0fee";

// Form field data you want to POST
$dataToSubmit = [
    'name'  => 'John Doe',
    'email' => 'john.doe@example.com',
    'phone' => '0821234567',
    // Add any other specific form fields expected by the handler here
];

// 2. Safely capture incoming POST data
if (!empty($_POST)) {
    // Sanitize/clean input array
    $incomingData = $_POST;

    // Remove form submit buttons if they exist so they aren't sent to the API
    unset($incomingData['submit'], $incomingData['btn_submit'], $incomingData['action']);

    // Option A: Replace entirely with the form data
    $dataToSubmit = $incomingData;

    // Option B (Alternative): Merge with defaults so required fields are never missing
    // $dataToSubmit = array_merge($dataToSubmit, $incomingData);
}else{
    $dataToSubmit = $_POST; 
}

$dataToSubmit = [];

if (!empty($_POST)) {
    // Collect all field names and sanitize string inputs
    foreach ($_POST as $key => $value) {
        // If it's an array (e.g., checkboxes), process all values inside
        if (is_array($value)) {
            $dataToSubmit[$key] = array_map('trim', $value);
        } else {
            $dataToSubmit[$key] = trim((string)$value);
        }
    }
}

// Call the function
$result = sendFormData($url, $dataToSubmit);

if (isset($result['success']) && $result['success'] === true) {
    echo "<script>window.location.href = '#';</script>"; 
} else {
    echo "<script>window.location.href = '#';</script>"; 
}

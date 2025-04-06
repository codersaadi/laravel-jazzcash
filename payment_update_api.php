<?php
header('Content-Type: application/json');

// Try these endpoints one by one to find which works
$possibleEndpoints = [
    'https://training.friendsitsolutions.com/api/v1/jazzcash/initiate',
    'https://training.friendsitsolutions.com/jazzcash/initiate',
    'https://training.friendsitsolutions.com/api/jazzcash/initiate'
];

$defaultDuration = '1 month';
$success = false;

foreach ($possibleEndpoints as $laravelEndpoint) {
    try {
        // Ensure POST data exists and fallback to default if not provided
        $postData = [
            'product_id' => (int)($_POST['plan_id'] ?? 0),
            'name' => $_POST['name'] ?? 'Quickin',
            'email' => $_POST['user_email'] ?? 'admin@admin.com',
            'amount' => (float)($_POST['amount'] ?? 0),
            'source_site_url' => 'https://quickinn.fisapps.net',
            'duration' => $_POST['plain'] ?? $defaultDuration
        ];

        // Check if required parameters are present
        if (empty($postData['product_id']) || empty($postData['amount'])) {
            throw new Exception('Missing required data (product_id or amount)');
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $laravelEndpoint,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $result = json_decode($response, true);
            
            // Check if the payment_url is present in the response
            if (!isset($result['payment_url'])) {
                throw new Exception('Payment URL not returned from the API');
            }

            // Return the success response with payment_url and txn_ref
            echo json_encode([
                'success' => true,
                'endpoint' => $laravelEndpoint,
                'payment_url' => $result['payment_url'],
                'txn_ref' => $result['txn_ref'] ?? ''
            ]);
            $success = true;
            break;
        } else {
            throw new Exception('Invalid response from the API, HTTP Code: ' . $httpCode);
        }

    } catch (Exception $e) {
        // Log the error message (for debugging purposes)
        error_log('Error with endpoint ' . $laravelEndpoint . ': ' . $e->getMessage());
        // Continue to next endpoint
        continue;
    }
}

// If no successful response, return failure
if (!$success) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'All API endpoints failed (404 Not Found)',
        'tested_endpoints' => $possibleEndpoints,
        'suggestion' => 'Check Laravel routes and server configuration'
    ]);
}
?>
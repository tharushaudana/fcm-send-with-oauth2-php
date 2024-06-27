<?php

require 'access_token.php';

//exit();

$apiUrl = 'https://fcm.googleapis.com/v1/projects/easywedding-b48f7/messages:send';

$headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json',
];

$firebase_data = [
    'validate_only' => false,
    'message' => [
        'topic' => 'user_4',
        'notification' => [
            'title' => 'test title',
            'bodyy' => 'test body',
        ],
        'android' => [
            'collapse_key' => 'type_a',
        ],
        'data' => [
            'test' => 'test value'
        ]
    ]
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($firebase_data));

$response = curl_exec($ch);

curl_close($ch);

// Print response as JSON
header('Content-Type: application/json');
echo json_encode(['response' => json_decode($response)]);
<?php

$creds = file_get_contents('easywedding-b48f7-a50fd8a34e62.json'); 
$creds = json_decode($creds, true); 
  
$private_key = $creds["private_key"]; // private_key of JSON file retrieved by creating Service Account
$client_email = $creds["client_email"]; // client_email of JSON file retrieved by creating Service Account
$scopes = ["https://www.googleapis.com/auth/firebase.messaging"]; // Sample scope

$url = "https://oauth2.googleapis.com/token";
$header = array("alg" => "RS256", "typ" => "JWT");
$now = floor(time());
$claim = array(
	"iss" => $client_email,
	"sub" => $client_email,
	"scope" => implode(" ", $scopes),
	"aud" => $url,
	"exp" => (string)($now + 10),
	"iat" => (string)$now,
);

$signature = base64_encode(json_encode($header, JSON_UNESCAPED_SLASHES)) . "." . base64_encode(json_encode($claim, JSON_UNESCAPED_SLASHES));
$b = "";
openssl_sign($signature, $b, $private_key, "SHA256");
$jwt = $signature . "." . base64_encode($b);

$curl_handle = curl_init();

curl_setopt_array($curl_handle, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => array(
        "assertion" => $jwt,
        "grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer"
    ),
]);

$res = curl_exec($curl_handle);

curl_close($curl_handle);

$obj = json_decode($res);
$accessToken = $obj -> {'access_token'};

//print($accessToken . "\n");
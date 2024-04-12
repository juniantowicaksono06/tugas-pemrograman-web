<?php
function parse_env($file) {
    $data = [];
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        list($key, $value) = explode('=', $line, 2);
        $data[$key] = $value;
    }
    return $data;
}

function UUIDv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function jsonResponse($statusCode = 200, $data = []) {
    $jsonResponse = null;
    if($data !== null) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        $jsonResponse = json_encode($data);
        echo $jsonResponse;
    }
    return $jsonResponse;
}

function redirect(string $url) {
    header("Location: " . $url);
}
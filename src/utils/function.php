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

function imageUpload($file, $uploadDirectory = "assets/image/admin-profile-picture/") {
    // Define the directory to save the uploaded file
    try {
        // Check if the upload directory exists, if not, create it
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        // Get file information
        $fileName = basename($file["name"]);
        $fileSize = $file["size"];
        $fileTmpName = $file["tmp_name"];
        $fileType = $file["type"];
        $fileError = $file["error"];

        // Specify the allowed file types (you can add more types if needed)
        $allowedFileTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB
        $fileType = trim(str_replace("\\", "", $fileType));

        // Validate file type
        if (!in_array($fileType, $allowedFileTypes)) {
            // return "Error: Only JPG, PNG, Webp, and GIF files are allowed.";
            return [
                'status'    => 2,
                'message'   => 'Error: Hanya boleh upload gambar JPG, PNG, Webp, dan GIF'
            ];
        }

        // Validate file size
        if ($fileSize > $maxFileSize) {
            // return "Error: The file size exceeds the limit of 2 MB.";
            return [
                "status"    => 2,
                'message'   => 'Error: Ukuran gambar melebihi limit 2MB'
            ];
        }

        // Check for upload errors
        if ($fileError !== UPLOAD_ERR_OK) {
            // return "Error: There was an error uploading your file.";
            return [
                "status"    => 2,
                'message'   => 'Error: Gagal upload gambar'
            ];
        }

        // Generate a unique file name to avoid conflicts
        $uniqueFileName = uniqid('img_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

        // Define the path to save the file
        $newFilePath = $uploadDirectory . $uniqueFileName;
        $uploadDirectory = getcwd() . "/" . $uploadDirectory;
        $uploadFilePath = $uploadDirectory . $uniqueFileName;
        $move = move_uploaded_file($fileTmpName, $uploadFilePath);

        // Move the uploaded file to the designated directory
        if ($move) {
            // return "Success: The file " . htmlspecialchars($fileName) . " has been uploaded.";
            // return true;
            return [
                'status'        => 1,
                'message'       => "Berhasil upload gambar",
                'uploadedFile'  => $newFilePath
            ];
        } else {
            // var_dump($_SERVER["DOCUMENT_ROOT"]);exit;
            if(!is_writable($uploadFilePath)) {
                return [
                    "status"    => 2,
                    'message'   => 'Error: Directory ' . $uploadDirectory . " tidak bisa ditulis."
                ];
            }
            // return "Error: There was an error moving the uploaded file.";
            return [
                "status"    => 2,
                'message'   => 'Error: Gagal melakukan upload file ' .  $file['error']
            ];
        }
    } catch (\Exception $th) {
        //throw $th;
        return [
            "status"    => 2,
            'message'   => $th->getMessage()
        ];
    }
}

function generateToken() {
    // Define the length of each segment and the number of segments
    $segmentLength = 4;
    $segmentCount = 4;

    // Define possible characters (letters and numbers)
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    
    $randomString = '';

    // Generate each segment
    for ($i = 0; $i < $segmentCount; $i++) {
        // Generate each character in the segment
        for ($j = 0; $j < $segmentLength; $j++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        // Add a hyphen after each segment except the last one
        if ($i < $segmentCount - 1) {
            $randomString .= '-';
        }
    }

    return $randomString;
}

function getBaseURL() {
    $host = $_SERVER['HTTP_HOST'];

    // Parse the host to extract the hostname and port
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    $protocol = $isSecure ? 'https://' : 'http://';
    $hostParts = parse_url('http://' . $host);
    $hostname = $hostParts['host'];
    $port = isset($hostParts['port']) ? $hostParts['port'] : null;

    // Determine if the port should be displayed
    $displayPort = ($port && $port != 80 && $port != 443);

    // Construct the final host string
    $finalHost = $protocol . $hostname;
    if ($displayPort) {
        $finalHost .= ':' . $port;
    }
    return $finalHost;
}
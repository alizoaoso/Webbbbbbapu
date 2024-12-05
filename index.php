<?php
error_reporting(0);

function generateImage(string $prompt): ?string
{
    $url = "https://ai-api.magicstudio.com/api/ai-art-generator";
    $boundary = "----WebKitFormBoundaryGXtYBPRK8jSiu6FI";

    $payload = buildPayload($boundary, $prompt);

    $headers = [
        'Content-Type: multipart/form-data; boundary=' . $boundary,
        'Accept: application/json, text/plain, */*',
        'Origin: https://magicstudio.com',
        'Referer: https://magicstudio.com/ai-art-generator/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, br");

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        error_log("cURL Error: " . curl_error($ch));
        curl_close($ch);
        return null;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($httpCode === 200) ? $response : null;
}

function buildPayload(string $boundary, string $prompt): string
{
    return "--$boundary\r\n"
        . "Content-Disposition: form-data; name=\"prompt\"\r\n\r\n"
        . "$prompt\r\n"
        . "--$boundary\r\n"
        . "Content-Disposition: form-data; name=\"output_format\"\r\n\r\n"
        . "bytes\r\n"
        . "--$boundary\r\n"
        . "Content-Disposition: form-data; name=\"user_profile_id\"\r\n\r\n"
        . "null\r\n"
        . "--$boundary\r\n"
        . "Content-Disposition: form-data; name=\"anonymous_user_id\"\r\n\r\n"
        . "38717863-5af8-4806-b04b-f87e5cae88f6\r\n"
        . "--$boundary\r\n"
        . "Content-Disposition: form-data; name=\"request_timestamp\"\r\n\r\n"
        . "1733430659.826\r\n"
        . "--$boundary\r\n"
        . "Content-Disposition: form-data; name=\"user_is_subscribed\"\r\n\r\n"
        . "false\r\n"
        . "--$boundary\r\n"
        . "Content-Disposition: form-data; name=\"client_id\"\r\n\r\n"
        . "pSgX7WgjukXCBoYwDM8G8GLnRRkvAoJlqa5eAVvj95o\r\n"
        . "--$boundary--";
}

function handleRequest()
{
    $prompt = isset($_GET['prompt']) ? trim($_GET['prompt']) : null;

    if (empty($prompt)) {
        http_response_code(400);
        echo json_encode(['error' => 'Prompt parameter is required.']);
        return;
    }

    $imageData = generateImage($prompt);

    if ($imageData === null) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to generate image.']);
        return;
    }

    header("Content-Type: image/jpeg");
    echo $imageData;
}

handleRequest();
?>

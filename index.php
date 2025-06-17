<?php
error_reporting(0);

function generateImage(string $prompt): ?string
{
    $url = "https://api-inference.huggingface.co/models/stabilityai/stable-diffusion-3-medium-diffusers";

    $payload = buildPayload($prompt);

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer YOUR_HF_TOKEN_PLACEHOLDER',
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

function buildPayload(string $prompt): string
{
    return json_encode(['inputs' => $prompt]);
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

# Simple Text-to-Image Web Application

This application allows you to generate images from text prompts using the Hugging Face API.

## Features

- Enter a text prompt and get an image.
- Simple web interface.

## Setup

1.  **Get a Hugging Face API Token:**
    *   You need an API token from Hugging Face to use their Inference API.
    *   Go to your Hugging Face account settings: [https://huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
    *   Create a new token. A 'read' role token should be sufficient for using the Inference API, but you might want to create one with 'write' if you plan to use other HF services or if 'read' doesn't work. **It's recommended to create a fine-grained token with the scope to "Make calls to Inference Providers"**.

2.  **Configure `index.php`:**
    *   Open the `index.php` file in a text editor.
    *   Find the following line:
        ```php
        $headers = [
            // ... other headers
            'Authorization: Bearer YOUR_HF_TOKEN_PLACEHOLDER',
            // ... other headers
        ];
        ```
    *   Replace `YOUR_HF_TOKEN_PLACEHOLDER` with the actual Hugging Face API token you obtained in step 1. For example, if your token is `hf_abc123xyz`, the line should look like:
        ```php
        'Authorization: Bearer hf_abc123xyz',
        ```
    *   Save the `index.php` file.

## How to Use

1.  **Host the files:**
    *   You need a web server with PHP support (e.g., Apache, Nginx with PHP-FPM, or even the built-in PHP development server).
    *   Place `index.php` and `frontend.html` in the web server's document root or a subdirectory.

2.  **Access the application:**
    *   Open `frontend.html` in your web browser. If you placed the files in the root, this would be something like `http://localhost/frontend.html` or `http://yourdomain.com/frontend.html`.

3.  **Generate Images:**
    *   Type your desired text prompt into the input field on the `frontend.html` page.
    *   Click the "Generate Image" button.
    *   Wait for the image to be generated and displayed below the button. This might take some time depending on the API load and the complexity of the prompt.

## Notes

*   The application currently uses the `stabilityai/stable-diffusion-3-medium-diffusers` model from Hugging Face. You can change the model by modifying the `$url` variable in `index.php` to point to a different Hugging Face model endpoint. Make sure the chosen model is compatible with the text-to-image task and the expected JSON payload structure.
*   Error handling is basic. If image generation fails, an alert will be shown. Check your browser's developer console for more details if needed.
*   Ensure your PHP environment has the cURL extension enabled, as it's used to make the API request.

```

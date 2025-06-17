# Simple Text-to-Image Web Application (Serverless Node.js Backend)

This application allows you to generate images from text prompts using the Hugging Face API, with a serverless Node.js backend.

## Features

- Enter a text prompt and get an image.
- Simple web interface (`frontend.html`).
- Backend logic handled by a Node.js serverless function.

## Setup & Deployment

1.  **Get a Hugging Face API Token:**
    *   You need an API token from Hugging Face to use their Inference API.
    *   Go to your Hugging Face account settings: [https://huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
    *   Create a new token. **It's recommended to create a fine-grained token with the scope to "Make calls to Inference Providers"**. Keep this token secure.

2.  **Deploy to a Serverless Platform (e.g., Vercel):**
    *   **Sign up/Log in:** Create an account or log in to [Vercel](https://vercel.com) (or a similar platform like Netlify).
    *   **Import Project:**
        *   Click on "Add New..." or "Import Project".
        *   Import your Git repository where this project is located (e.g., from GitHub, GitLab, Bitbucket).
    *   **Configure Project:**
        *   Vercel should automatically detect it as a Node.js project due to `package.json` and `api` directory structure. The `vercel.json` file provides specific deployment settings.
        *   **Set Environment Variable:** Before deploying, or in the project settings after import, you **MUST** set the Hugging Face API token.
            *   Go to your project settings in Vercel.
            *   Navigate to "Environment Variables".
            *   Add a new variable:
                *   Name: `HF_TOKEN`
                *   Value: Paste your Hugging Face API token here (the one you got in Step 1, starting with `hf_`).
    *   **Deploy:** Click the "Deploy" button. Vercel will build your project and deploy the serverless function and static assets.

3.  **Access the application:**
    *   Once deployed, Vercel will provide you with a public URL (e.g., `your-project-name.vercel.app`). Open this URL in your web browser to access `frontend.html`.

## How to Use

1.  Navigate to the deployed URL provided by your hosting platform (e.g., Vercel).
2.  Type your desired text prompt into the input field on the page.
3.  Click the "Generate Image" button.
4.  Wait for the image to be generated and displayed below the button. This might take some time depending on the API load and the complexity of the prompt.

## Notes

*   The application currently uses the `stabilityai/stable-diffusion-3-medium-diffusers` model from Hugging Face. This is configured in `api/generate-image.js`. You can change the model by modifying the `apiUrl` variable in that file. Ensure the chosen model is compatible with the text-to-image task.
*   Error handling is basic. If image generation fails, an alert will be shown. Check your browser's developer console and the Vercel function logs for more details if needed.
*   The `index.php` file is no longer used by this serverless version of the application and can be removed if you only intend to use this Node.js backend.

```

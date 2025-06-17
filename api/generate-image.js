const fetch = require('node-fetch');

module.exports = async (req, res) => {
    const prompt = req.query.prompt;

    if (!prompt) {
        res.status(400).json({ error: 'Prompt parameter is required.' });
        return;
    }

    const hfToken = process.env.HF_TOKEN;
    if (!hfToken) {
        console.error('Hugging Face API token (HF_TOKEN) is not set.');
        res.status(500).json({ error: 'API token not configured on the server.' });
        return;
    }

    // Using the same model as in the PHP version for consistency
    const apiUrl = 'https://api-inference.huggingface.co/models/stabilityai/stable-diffusion-3-medium-diffusers';

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${hfToken}`,
                'Content-Type': 'application/json',
                // It's good practice to include an Accept header if you expect a specific content type for the image
                // However, for raw image bytes, this might not be strictly necessary or could vary.
                // For now, we'll rely on Hugging Face returning the correct content type or just raw bytes.
            },
            body: JSON.stringify({ inputs: prompt })
        });

        if (!response.ok) {
            let errorBody = "Unknown error from Hugging Face API.";
            try {
                // Try to get more detailed error from Hugging Face response
                const errorData = await response.json(); // Hugging Face often returns JSON errors
                if (errorData && errorData.error) {
                    errorBody = `Hugging Face API Error: ${errorData.error}`;
                    if (errorData.estimated_time) {
                        errorBody += ` (Model might be loading, estimated time: ${errorData.estimated_time}s)`;
                    }
                } else {
                     errorBody = `Hugging Face API Error: ${response.status} ${response.statusText}`;
                }
            } catch (e) {
                // If error response is not JSON, use the status text.
                errorBody = `Hugging Face API Error: ${response.status} ${response.statusText}`;
            }
            console.error(errorBody);
            res.status(response.status).json({ error: errorBody });
            return;
        }

        // Assuming the API returns raw image bytes directly.
        // And that the content type is something like image/jpeg.
        // We need to pipe the response stream to the serverless response.
        const imageContentType = response.headers.get('content-type') || 'image/jpeg'; // Default if not provided
        res.setHeader('Content-Type', imageContentType);

        // Stream the image data back to the client
        response.body.pipe(res);

    } catch (error) {
        console.error('Error calling Hugging Face API:', error);
        res.status(500).json({ error: 'Failed to generate image due to an internal server error.' });
    }
};

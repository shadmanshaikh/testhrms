<div>
@vite(['resources/css/app.css', 'resources/js/app.js'])


<x-card>
    <x-textarea class="mt-3" type="text" id="promptInput" placeholder="Enter your prompt here" inline />
    <x-button id="submitButton" class="mt-3">Submit</x-button>
</x-card>
</div>
<div id="responseContainer" style="margin-top: 20px;">
    <x-card title="Response">
        <pre id="responseOutput"></pre>
    </x-card>
</div>

<script>
    document.getElementById('submitButton').addEventListener('click', async () => {
        const button = document.getElementById('submitButton');
        const prompt = document.getElementById('promptInput').value;
        const responseContainer = document.getElementById('responseOutput');

        button.disabled = true;
        button.textContent = "Submitting...";

        try {
            const response = await fetch('http://127.0.0.1:11434/api/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    model: 'llama2',
                    prompt: prompt.trim(),
                }),
            });

            if (response.ok) {
                const responseText = await response.text();
                const jsonObjects = responseText.split('\n').filter(line => line.trim() !== '');
                const responses = jsonObjects.map(jsonStr => JSON.parse(jsonStr).response).join('');
                responseContainer.textContent = responses;
                document.getElementById('promptInput').value = '';
            } else {
                const errorText = await response.text();
                responseContainer.textContent = `Error: ${response.status}\n${errorText}`;
            }
        } catch (error) {
            responseContainer.textContent = `Error: ${error.message}`;
        } finally {
            button.disabled = false;
            button.textContent = "Submit";
        }
    });

    // Submit on Enter
    document.getElementById('promptInput').addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            document.getElementById('submitButton').click();
        }
    });
</script>
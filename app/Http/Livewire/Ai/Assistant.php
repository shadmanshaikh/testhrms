<?php
namespace App\Http\Livewire\Ai;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\Employeeinfo;

class Assistant extends Component
{
    public $prompt;
    public $response;

    public function submit()
    {
        $this->validate([
            'prompt' => 'required|string',
        ]);

        // Fetch data from the database
        $data = Employeeinfo::all()->toArray(); // Convert to array

        // Prepare the data to be sent to the API
        $apiData = [
            'model' => 'llama2',
            'prompt' => trim($this->prompt),
            'data' => $data, // Include the fetched data
        ];

        $response = Http::post('http://127.0.0.1:11434/api/generate', $apiData);

        if ($response->successful()) {
            $responseText = $response->body();
            $jsonObjects = explode("\n", $responseText);
            $responses = collect($jsonObjects)->filter()->map(function ($jsonStr) {
                return json_decode($jsonStr)->response;
            })->implode('');
            $this->response = $responses;
        } else {
            $this->response = 'Error: ' . $response->status();
        }
    }

    public function render()
    {
        return view('livewire.ai.assistant');
    }
}
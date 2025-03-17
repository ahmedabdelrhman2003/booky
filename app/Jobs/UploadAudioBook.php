<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UploadAudioBook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Book $book)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {



        $pdfMedia = $this->book->getFirstMedia('book');
        if ($pdfMedia) {
            $pdfFile = file_get_contents($pdfMedia->getPath());

            $response = Http::attach('file', $pdfFile, $pdfMedia->file_name)
               ->timeout(50000)
                ->post('http://127.0.0.2:8000/textSpeech/upload/', ['language' => 'en']);

            Log::info($response->body());
            Log::info('Sending request to text-to-speech server', [
                'url' => 'http://127.0.0.2:8000/textSpeech/upload/',
                'file_name' => $pdfMedia->file_name,
                'language' => 'en',
            ]);
            $audio_url = $response->json('audio_path');
            Log::info($audio_url);
            $this->book
                ->addMediaFromUrl($audio_url)
                ->toMediaCollection('audio');
            Log::info('success', ['the audio uploaded successfully']);

        }
    }

}

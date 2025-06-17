<?php

namespace App\Jobs;

use App\Enums\BookLangEnum;
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
use Illuminate\Support\Facades\Storage;

class UploadAudioBook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $backoff = 3;

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
        try {
            $pdfMedia = $this->book->getFirstMedia('book');
            if ($pdfMedia) {
                $pdfFile = Storage::disk('s3')->get($pdfMedia->getPath());
                if (!$pdfFile) {
                    Log::error('Failed to retrieve the PDF file from S3.', [
                        'file_name' => $pdfMedia->file_name,
                    ]);
                    return;
                }
                $response = Http::attach('file', $pdfFile, $pdfMedia->file_name)
                    ->timeout(50000)
                    ->post(config('services.tts.url').'/textSpeech/upload/', [ 'language' => BookLangEnum::from($this->book->language)->shortCode()]);

                Log::info('Sending request to text-to-speech server', [
                    'url' => 'http://127.0.0.2:8000/textSpeech/upload/',
                    'file_name' => $pdfMedia->file_name,
                    'language' => 'en',
                    'status' => $response->status(),
                ]);

                if ($response->successful()) {
                    $audio_url = $response->json('audio_path');

                    $audioContent = Http::timeout(50000)->get($audio_url);

                    if (!$audioContent->successful()) {
                        Log::error('Failed to download the audio file from local server.', [
                            'audio_url' => $audio_url,
                            'status' => $audioContent->status(),
                        ]);
                        return;
                    }

                    $tempPath = storage_path('app/temp_audio.mp3');
                    file_put_contents($tempPath, $audioContent->body());

                    $this->book
                        ->addMedia($tempPath)
                        ->usingFileName('audio_' . uniqid() . '.mp3')
                        ->toMediaCollection('audio');

                    unlink($tempPath);
                    Log::info('Success: The audio file was uploaded successfully.', [
                        'audio_url' => $audio_url,
                    ]);
                } else {
                    Log::error('Failed to process text-to-speech conversion.', [
                        'status' => $response->status(),
                        'response_body' => $response->body(),
                    ]);
                }
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('HTTP Request failed', [
                'error_message' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred', [
                'error_message' => $e->getMessage(),
            ]);
        }
    }


}

<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use App\Jobs\UploadAudioBook;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;


    protected function afterCreate(): void
    {
        UploadAudioBook::dispatch($this->record);
    }
}

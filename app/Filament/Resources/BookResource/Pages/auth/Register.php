<?php

namespace App\Filament\Resources\BookResource\Pages\auth;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Components\Component;
use Filament\Support\Enums\MaxWidth;
use Symfony\Component\Console\Input\Input;

class Register extends BaseRegister
{
    protected ?string $maxWidth = '3xl';

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getBioFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getImageFormComponent(),
                        $this->getPhoneFormComponent(),
                        $this->getIBANFormComponent()
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getBioFormComponent(): Component
    {
        return TextInput::make('bio')->maxLength(255)
            ->disableGrammarly()->required();
    }

    protected function getImageFormComponent(): Component
    {
        return SpatieMediaLibraryFileUpload::make('author-image')
            ->collection('author-image')
            ->conversion('thumb')->required();
    }
    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->minLength(9)->maxLength(13)->required();
    }

    protected function getIBANFormComponent(): Component
    {
        return TextInput::make('iban')->label('IBAN for your bank account')
            ->minLength(9)->maxLength(29)
//            ->regex('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/')->length('29')
            ->required();
    }

}

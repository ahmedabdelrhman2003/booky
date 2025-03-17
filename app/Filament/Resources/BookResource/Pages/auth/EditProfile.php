<?php

namespace App\Filament\Resources\BookResource\Pages\auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;


class EditProfile extends BaseEditProfile
{
    protected ?string $maxWidth = '3xl';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                $this->getNameFormComponent(),
                $this->getImageFormComponent(),
                $this->getPhoneFormComponent(),
                $this->getBioFormComponent(),
                $this->getIBANFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);

    }
    protected function getBioFormComponent(): Component
    {
        return RichEditor::make('bio')
           ->maxLength(65365)->required();
    }

    protected function getIBANFormComponent(): Component
    {
        return TextInput::make('iban')->label('IBAN')
            ->minLength(9)->maxLength(29)
//            ->regex('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/')->length('29')
            ->required();
    }

    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->minLength(9)->maxLength(13)->required();
    }

    protected function getImageFormComponent(): Component
    {
        return SpatieMediaLibraryFileUpload::make('author-image')
            ->collection('author-image')
            ->conversion('thumb')->required();
    }
}

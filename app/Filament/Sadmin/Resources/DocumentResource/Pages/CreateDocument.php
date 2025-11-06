<?php

namespace App\Filament\Sadmin\Resources\DocumentResource\Pages;

use App\Filament\Sadmin\Resources\DocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }

}

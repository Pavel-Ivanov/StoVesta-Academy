<?php

namespace App\Filament\Sadmin\Resources\DocumentResource\Pages;

use App\Filament\Sadmin\Resources\DocumentResource;
use Filament\Resources\Pages\EditRecord;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }

}

<?php

namespace App\Filament\Intern\Resources\HandbookResource\Pages;

use App\Filament\Intern\Resources\HandbookResource;
use Filament\Resources\Pages\ListRecords;

class ListHandbook extends ListRecords
{
    protected static string $resource = HandbookResource::class;
    protected ?string $heading = 'Справочник';
}

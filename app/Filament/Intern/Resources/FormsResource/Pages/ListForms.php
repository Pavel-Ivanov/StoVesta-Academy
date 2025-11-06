<?php

namespace App\Filament\Intern\Resources\FormsResource\Pages;

use App\Filament\Intern\Resources\FormsResource;
use Filament\Resources\Pages\ListRecords;

class ListForms extends ListRecords
{
    protected static string $resource = FormsResource::class;
    protected ?string $heading = 'Формы';
}

<?php

namespace App\Filament\Intern\Resources\CertificatesResource\Pages;

use App\Filament\Intern\Resources\CertificatesResource;
use Filament\Resources\Pages\ListRecords;

class ListCertificates extends ListRecords
{
    protected static string $resource = CertificatesResource::class;
    protected ?string $heading = 'Сертификаты';
}

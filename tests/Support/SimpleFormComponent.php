<?php

namespace Tests\Support;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class SimpleFormComponent implements HasForms
{
    use InteractsWithForms;
}

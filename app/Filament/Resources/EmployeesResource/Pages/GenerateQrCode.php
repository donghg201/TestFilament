<?php

namespace App\Filament\Resources\EmployeesResource\Pages;

use App\Filament\Resources\EmployeesResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class GenerateQrCode extends Page
{
    use InteractsWithRecord;

    protected static string $resource = EmployeesResource::class;

    protected static string $view = 'filament.resources.employees-resource.pages.generate-qr-code';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        static::authorizeResourceAccess();
    }
}

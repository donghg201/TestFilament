<x-filament-panels::page>
    {!! QrCode::size(300)->generate($this->getRecord()->email); !!}
</x-filament-panels::page>

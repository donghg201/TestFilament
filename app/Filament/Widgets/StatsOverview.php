<?php

namespace App\Filament\Widgets;

use App\Models\Employees;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Roles', User::count()),
            Stat::make('Total Employees', Employees::count()),
        ];
    }
}

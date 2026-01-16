<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class OccupancyReport extends Page
{
    protected string $view = 'filament-panels::filament.pages.occupancy-report';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}

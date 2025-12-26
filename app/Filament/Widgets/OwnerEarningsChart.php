<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class OwnerEarningsChart extends ChartWidget
{
    protected ?string $heading = 'Owner Earnings Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}

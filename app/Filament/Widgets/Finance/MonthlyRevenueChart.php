<?php

namespace App\Filament\Widgets\Finance;

use App\Models\BookingPayment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyRevenueChart extends ChartWidget
{
    protected static ?int $sort = 9;
    // protected function getHeading(): string
    // {
    //     return 'Monthly Revenue';
    // }

    protected function getData(): array
    {
        $data = BookingPayment::where('status', 'success')
            ->selectRaw('SUM(amount) as total, MONTH(created_at) as month')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data->pluck('total')->toArray(),
                ],
            ],
            'labels' => $data->map(
                fn ($row) => Carbon::create()->month($row->month)->format('M')
            )->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

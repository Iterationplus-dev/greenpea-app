<?php

namespace App\Filament\Widgets;
use App\Models\Booking;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentBookingsTable extends TableWidget
{
    protected static ?int $sort = 9;
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Booking::query())
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Components;

use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class CompactModeAction
{
    public static function make(): Action
    {
        return Action::make('compactMode')
            ->label('Compact Mode')
            ->icon('heroicon-o-arrows-pointing-in')
            ->color('gray')

            ->action(function () {
                $admin = Auth::guard('admin')->user();

                if (! $admin) {
                    return;
                }

                $admin->compact_tables = ! $admin->compact_tables;
                $admin->save();

                // Force page refresh so UI updates
                // return redirect()->back();
            })

            ->badge(function () {
                return Auth::guard('admin')->user()?->compact_tables
                    ? 'ON'
                    : 'OFF';
            });
    }
}

<?php

namespace App\Filament\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;
use App\Enums\GroupLabel;
use Filament\Schemas\Schema;
use App\Models\InvoiceSetting;

use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;

use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;

class InvoiceSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Invoice Settings';

    protected static string | UnitEnum | null $navigationGroup = GroupLabel::SETTINGS;

    protected string $view = 'filament.pages.invoice-settings';

    protected static ?int $navigationSort = 3;

    public ?array $data = [];

    public function mount()
    {
        // $settings = InvoiceSetting::first();

        // if ($settings) {
        //     $this->form->fill($settings->toArray());
        // }

        $settings = InvoiceSetting::firstOrCreate(['id' => 1]);
        $this->form->fill($settings->toArray());
    }

    protected function form(Schema $schema): Schema
{
    return $schema
        ->schema($this->getFormSchema())
        ->statePath('data');
}

    protected function getFormSchema(): array
    {
        return [

            Section::make('Invoice Reminder Settings')
                ->description('Configure how invoice reminders are sent to customers')
                ->schema([

                    Grid::make(2)
                        ->schema([

                            Toggle::make('reminders_enabled')
                                ->label('Enable Invoice Reminders')
                                ->columnSpan(2),

                            TextInput::make('days_before_reminder')
                                ->label('Days Before First Reminder')
                                ->numeric()
                                ->required(),

                            TextInput::make('reminder_interval_days')
                                ->label('Reminder Interval (Days)')
                                ->numeric()
                                ->required(),

                            TextInput::make('max_reminders')
                                ->label('Maximum Reminders Per Invoice')
                                ->numeric()
                                ->required(),

                            TextInput::make('admin_email')
                                ->label('Admin Notification Email')
                                ->email(),

                        ]),
                ]),
        ];
    }

    public function save()
    {
        InvoiceSetting::updateOrCreate(
            ['id' => 1],
            $this->form->getState()
        );

        Notification::make()
            ->title('Settings Updated')
            ->body('Invoice details updated successfully')
            ->success()
            ->send();
    }
}

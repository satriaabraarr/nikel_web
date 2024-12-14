<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingsExport;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('vehicle_id')
                    ->required()
                    ->label('Vehicle')
                    ->options(Vehicle::where('status', 'Available')
                    ->pluck('name', 'id')),

                TextInput::make('driver')
                    ->required()
                    ->label('Driver Name')
                    ->placeholder('Enter driver name'),

                DatePicker::make('rental_date')
                    ->required()
                    ->label('Rental Date')
                    ->minDate(now()),

                DatePicker::make('return_date')
                    ->required()
                    ->label('Return Date')
                    ->minDate(now()),

                Hidden::make('admin_id')
                    ->default(auth()->id()),

                Hidden::make('approved_by_1')
                    ->default(null),

                Hidden::make('approved_by_2')
                    ->default(null),

                Hidden::make('status')
                    ->default('pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn () => Booking::query()
            ->orderBy('created_at', 'desc')) 

            ->columns([
                TextColumn::make('vehicle.name')
                    ->searchable()
                    ->sortable()
                    ->label('Vehicle'),

                TextColumn::make('driver')
                    ->searchable()
                    ->sortable()
                    ->label('Driver Name'),

                TextColumn::make('rental_date')
                    ->searchable()
                    ->sortable()
                    ->date()
                    ->label('Rental Date'),
                
                TextColumn::make('return_date')
                    ->searchable()
                    ->sortable()
                    ->date()
                    ->label('Return Date'),

                BadgeColumn::make('status')
                    ->searchable()
                    ->sortable()
                    ->label('Status')
                    ->colors([
                        'primary' => 'Pending',
                        'success' => 'Approved',
                        'danger' => 'Rejected',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->button()
                ->color('success')
                ->visible(fn ($record) =>
                    (auth()->user()->role === 'approve_level_1' && ($record->approve_level_1_status === 'Pending' || $record->approve_level_1_status === null)) || 
                    (auth()->user()->role === 'approve_level_2' && ($record->approve_level_2_status === 'Pending' || $record->approve_level_2_status === null))
                )
                ->action(function ($record) {
                    if (auth()->user()->role === 'approve_level_1') {
                        $record->update([
                            'approve_level_1_status' => 'Approved',
                        ]);
                    } else {
                        $record->update([
                            'approve_level_2_status' => 'Approved',
                        ]);
                    }

                    if ($record->approve_level_1_status === 'Approved' && $record->approve_level_2_status === 'Approved') {
                        $record->update(['status' => 'Approved']);
                    }

                    $record->setStatus();
                }),

            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->button()
                ->color('danger')
                ->visible(fn ($record) =>
                    (auth()->user()->role === 'approve_level_1' && ($record->approve_level_1_status === 'Pending' || $record->approve_level_1_status === null)) || 
                    (auth()->user()->role === 'approve_level_2' && ($record->approve_level_2_status === 'Pending' || $record->approve_level_2_status === null))
                )
                ->action(function ($record) {
                    if (auth()->user()->role === 'approve_level_1') {
                        $record->update([
                            'approve_level_1_status' => 'Rejected',
                        ]);
                    } else {
                        $record->update([
                            'approve_level_2_status' => 'Rejected',
                        ]);
                    }

                    if ($record->approve_level_1_status === 'Rejected' || $record->approve_level_2_status === 'Rejected') {
                        $record->update(['status' => 'Rejected']);
                    }

                    $record->setStatus();
                }),

                // Tables\Actions\EditAction::make()
                //     ->button()
                //     ->visible(fn () => auth()->user()->role === 'admin'),

                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->visible(fn () => auth()->user()->role === 'admin'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
            'reports' => Pages\Reports::route('/reports'),
        ];
    }
}

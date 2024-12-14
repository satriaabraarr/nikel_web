<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Hidden;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function canViewAny(): bool
    {
        return Auth::user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Vehicle Name')
                    ->maxLength(255)
                    ->placeholder('Enter vehicle name'),

                Select::make('type')
                    ->required()
                    ->label('Vehicle Type')
                    ->options([
                        'Passenger' => 'Passenger',
                        'Goods' => 'Goods',
                    ]),

                Select::make('owner')
                    ->required()
                    ->label('Owner')
                    ->options([
                        'Company' => 'Company',
                        'Rental' => 'Rental',
                    ]),

                TextInput::make('fuel_consumption')
                    ->label('Fuel Consumption (liters/km)')
                    ->numeric()
                    ->nullable()
                    ->placeholder('Enter fuel consumption'),

                DatePicker::make('service_schedule')
                    ->nullable()
                    ->label('Next Service Schedule'),

                // Select::make('status')
                //     ->required()
                //     ->label('Status')
                //     ->options([
                //         'Available' => 'Available',
                //         'Booked' => 'Booked',
                //     ]),
                
                Hidden::make('status')
                    ->default('Available'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Vehicle Name'),

                TextColumn::make('type')
                    ->searchable()
                    ->sortable()
                    ->label('Vehicle Type'),

                TextColumn::make('owner')
                    ->searchable()
                    ->sortable()
                    ->label('Owner'),

                TextColumn::make('fuel_consumption')
                    ->searchable()
                    ->sortable()
                    ->label('Fuel Consump'),

                TextColumn::make('service_schedule')
                    ->searchable()
                    ->sortable()
                    ->date()
                    ->label('Service Schedule'),

                BadgeColumn::make('status')
                    ->searchable()
                    ->sortable()
                    ->label('Status')
                    ->colors([
                        'success' => 'Available',
                        'danger' => 'Booked',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button(),
                // Tables\Actions\DeleteAction::make()
                //     ->button(),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}

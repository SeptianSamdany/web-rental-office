<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Faker\Provider\ar_EG\Text;
use Filament\Resources\Resource;
use App\Models\BookingTransaction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use BladeUI\Icons\Components\Icon;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('booking_trx_id')
                    ->label('Booking Transaction ID')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('phone_number')
                    ->label('Phone Number')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->required()
                    ->numeric()
                    ->prefix('IDR'),
                
                TextInput::make('duration')
                    ->label('Duration')
                    ->required()
                    ->numeric()
                    ->prefix('days'),

                DatePicker::make('started_date')
                    ->label('Start Date')
                    ->required(),
                
                DatePicker::make('ended_at')
                    ->label('End Date')
                    ->required(),

                Select::make('is_paid')
                    ->label('Is Paid')
                    ->options([
                        true => 'Paid',
                        false => 'Not Paid',
                    ])
                    ->required(),
                
                Select::make('office_space_id')
                    ->label('Office Space')
                    ->relationship('officeSpace', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_trx_id')
                    ->label('Booking Transaction ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('officeSpace.name'),

                TextColumn::make('started_date')
                ->date('d-m-Y')
                ->searchable()
                ->sortable(),

                IconColumn::make('is_paid')
                    ->boolean()
                    ->trueColor('scucess')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label('Is Paid?'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}

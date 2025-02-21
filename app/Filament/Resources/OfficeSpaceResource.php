<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\OfficeSpace;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OfficeSpaceResource\Pages;
use App\Filament\Resources\OfficeSpaceResource\RelationManagers;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class OfficeSpaceResource extends Resource
{
    protected static ?string $model = OfficeSpace::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('address')
                    ->label('Address')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('office-space-thumbnail')
                    ->required(),

                TextInput::make('about')
                    ->label('About')
                    ->required()
                    ->rows(10)
                    ->cols(20),

                Repeater::make('photos')
                    ->relationship('photos')
                    ->schema([
                        FileUpload::make('photo')
                            ->image()
                            ->disk('public')
                            ->directory('office-space-photos')
                            ->required(),
                    ]),

                Repeater::make('benefits')
                    ->relationship('benefits')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                    ]),
                
                Select::make('city_id')
                    ->label('City')
                    ->relationship('city', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->prefix('IDR'),
                
                TextInput::make('duration')
                    ->label('Duration')
                    ->required()
                    ->numeric()
                    ->suffix('Days'),
                
                Select::make('is_open')
                ->options([
                    true => 'Open',
                    false => 'Close',
                ])
                ->required(),

                Select::make('is_full_booked')
                ->options([
                    true => 'Not Available',
                    false => 'Available',
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                
                ImageColumn::make('thumbnail')->getStateUsing(fn ($record) => asset('storage/' . $record->thumbnail)),

                TextColumn::make('city.name')
                    ->label('City')
                    ->searchable()
                    ->sortable(),
                
                IconColumn::make('is_full_booked')
                    ->label('Available')
                    ->boolean()
                    ->falseColor('success')
                    ->trueColor('danger')
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle'),
                    
            ])
            ->filters([
                SelectFilter::make('city_id')
                ->label('City')
                ->relationship('city', 'name')
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
            'index' => Pages\ListOfficeSpaces::route('/'),
            'create' => Pages\CreateOfficeSpace::route('/create'),
            'edit' => Pages\EditOfficeSpace::route('/{record}/edit'),
        ];
    }
}

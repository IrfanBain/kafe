<?php

namespace App\Filament\Cashier\Resources;

use App\Filament\Cashier\Resources\TableResource\Pages;
use App\Models\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as FilamentTable;

class TableResource extends Resource
{
    protected static ?string $model = Table::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationLabel = 'Tables';

    protected static ?string $navigationGroup = 'Restaurant Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('capacity')
                    ->numeric()
                    ->required(),
                Forms\Components\Toggle::make('is_available')
                    ->label('Available')
                    ->default(true),
            ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('qr_code')
                    ->label('QR Code')
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->qr_code),
                Tables\Columns\ToggleColumn::make('is_available')
                    ->label('Available'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_available'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('qr_code')
                    ->label('QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->url(fn (Table $record): string => $record->qr_code ? route('qr.show', $record->qr_code) : '#')
                    ->visible(fn (Table $record): bool => !empty($record->qr_code))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('number');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTables::route('/'),
            'view' => Pages\ViewTable::route('/{record}'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Only admin can create tables
    }

    public static function canDelete($record = null): bool
    {
        return false; // Only admin can delete tables
    }
}

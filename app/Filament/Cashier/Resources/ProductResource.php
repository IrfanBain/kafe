<?php

namespace App\Filament\Cashier\Resources;

use App\Filament\Cashier\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Menu';

    protected static ?string $navigationGroup = 'Menu Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description'),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\Toggle::make('is_available')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->square()
                    ->size(60),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_available')
                    ->label('Available'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_available'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('category_id');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Only view, creation should be done in admin panel
    }

    public static function canEdit($record = null): bool
    {
        return false; // Only view, editing should be done in admin panel
    }

    public static function canDelete($record = null): bool
    {
        return false; // Only view, deletion should be done in admin panel
    }
}

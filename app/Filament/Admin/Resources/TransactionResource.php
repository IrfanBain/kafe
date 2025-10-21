<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TransactionResource\Pages;
use App\Filament\Admin\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'order_number')
                    ->required(),
                Forms\Components\TextInput::make('transaction_id')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('payment_method')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                        'expired' => 'Expired',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('midtrans_response')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->helperText('JSON response from Midtrans'),
                Forms\Components\TextInput::make('snap_token')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('paid_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.customer_name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'success' => 'success',
                        'danger' => ['failed', 'cancelled'],
                        'warning' => 'expired',
                    ]),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                        'expired' => 'Expired',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'bank_transfer' => 'Bank Transfer',
                        'e_wallet' => 'E-Wallet',
                        'credit_card' => 'Credit Card',
                        'qris' => 'QRIS',
                    ]),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}

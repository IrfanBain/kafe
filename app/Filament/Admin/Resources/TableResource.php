<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TableResource\Pages;
use App\Filament\Admin\Resources\TableResource\RelationManagers;
use App\Models\Table as TableModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TableResource extends Resource
{
    protected static ?string $model = TableModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationLabel = 'Tables';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('capacity')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->options([
                        'available' => 'Available',
                        'occupied' => 'Occupied',
                        'reserved' => 'Reserved',
                    ])
                    ->default('available')
                    ->required(),
                Forms\Components\Textarea::make('location')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('qr_code')
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('QR Code will be generated automatically'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'available',
                        'warning' => 'reserved',
                        'danger' => 'occupied',
                    ]),
                Tables\Columns\TextColumn::make('location')
                    ->limit(30),
                Tables\Columns\TextColumn::make('orders_count')
                    ->counts('orders')
                    ->label('Total Orders'),
                Tables\Columns\TextColumn::make('qr_code_url')
                    ->label('QR Link')
                    ->formatStateUsing(fn ($state, $record) => $record->qr_code_url)
                    ->url(fn ($record) => $record->qr_code_url)
                    ->openUrlInNewTab()
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'occupied' => 'Occupied',
                        'reserved' => 'Reserved',
                        'maintenance' => 'Maintenance',
                    ]),
                Tables\Filters\SelectFilter::make('location')
                    ->options([
                        'indoor' => 'Indoor',
                        'outdoor' => 'Outdoor',
                        'terrace' => 'Terrace',
                        'vip' => 'VIP Room',
                        'private' => 'Private Room',
                    ]),
                Tables\Filters\Filter::make('has_orders')
                    ->label('Has Orders')
                    ->query(fn ($query) => $query->has('orders')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('viewQR')
                    ->label('View QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->url(fn (TableModel $record) => route('qr.show', $record->qr_code))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('downloadQR')
                    ->label('Download QR')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (TableModel $record) {
                        $url = route('table.menu', $record->qr_code);
                        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                            ->size(400)
                            ->color(40, 40, 40)
                            ->backgroundColor(255, 255, 255)
                            ->margin(2)
                            ->generate($url);

                        return response($qrCode)
                            ->header('Content-Type', 'image/png')
                            ->header('Content-Disposition', 'attachment; filename="table-' . $record->number . '-qr.png"');
                    }),
                Tables\Actions\Action::make('generateQR')
                    ->label('Generate QR')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (TableModel $record) {
                        $record->update(['qr_code' => \Illuminate\Support\Str::uuid()]);

                        return redirect()->back()->with('success', 'QR Code regenerated successfully!');
                    })
                    ->modalHeading('Regenerate QR Code')
                    ->modalDescription('Are you sure you want to generate a new QR code? The old QR code will no longer work.')
                    ->modalSubmitActionLabel('Yes, generate new QR'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('regenerateQR')
                        ->label('Regenerate QR Codes')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $record) {
                                $record->update(['qr_code' => \Illuminate\Support\Str::uuid()]);
                            }

                            return redirect()->back()->with('success', 'QR Codes regenerated successfully!');
                        })
                        ->modalHeading('Regenerate QR Codes')
                        ->modalDescription('Are you sure you want to generate new QR codes for selected tables? The old QR codes will no longer work.')
                        ->modalSubmitActionLabel('Yes, regenerate QR codes'),
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
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }
}

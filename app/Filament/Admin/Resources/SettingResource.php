<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationLabel = 'Pengaturan';
    
    protected static ?string $modelLabel = 'Pengaturan';
    
    protected static ?string $pluralModelLabel = 'Pengaturan';

    protected static ?int $navigationSort = 99;

    protected static bool $shouldRegisterNavigation = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengaturan')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Kunci Pengaturan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('type')
                            ->label('Tipe Data')
                            ->options([
                                'string' => 'Text',
                                'text' => 'Text Panjang',
                                'image' => 'Gambar',
                                'boolean' => 'Ya/Tidak',
                            ])
                            ->required()
                            ->reactive(),
                        
                        Forms\Components\TextInput::make('description')
                            ->label('Deskripsi')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Nilai Pengaturan')
                    ->schema([
                        Forms\Components\TextInput::make('value')
                            ->label('Nilai')
                            ->required()
                            ->visible(fn ($get) => in_array($get('type'), ['string']))
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('value')
                            ->label('Nilai')
                            ->required()
                            ->visible(fn ($get) => $get('type') === 'text')
                            ->rows(4),
                        
                        Forms\Components\Group::make([
                            Forms\Components\FileUpload::make('image_value')
                                ->label('Upload Gambar')
                                ->image()
                                ->directory('store')
                                ->disk('public')
                                ->visibility('public')
                                ->maxSize(2048)
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    '1:1',
                                    '16:9',
                                ])
                                ->helperText('Upload logo toko (maksimal 2MB)'),
                            
                            Forms\Components\Hidden::make('value'),
                        ])
                        ->visible(fn ($get) => $get('type') === 'image'),
                        
                        Forms\Components\Toggle::make('value')
                            ->label('Aktif')
                            ->visible(fn ($get) => $get('type') === 'boolean'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Kunci')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipe')
                    ->colors([
                        'primary' => 'string',
                        'success' => 'text',
                        'warning' => 'image',
                        'danger' => 'boolean',
                    ]),
                
                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->limit(30)
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->type === 'image' && $state) {
                            return 'Gambar tersimpan';
                        }
                        if ($record->type === 'boolean') {
                            return $state ? 'Ya' : 'Tidak';
                        }
                        return $state;
                    }),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'string' => 'Text',
                        'text' => 'Text Panjang',
                        'image' => 'Gambar',
                        'boolean' => 'Ya/Tidak',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('key');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}

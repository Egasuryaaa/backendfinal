<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerLocationResource\Pages;
use App\Filament\Resources\SellerLocationResource\RelationManagers;
use App\Models\SellerLocation;
use App\Filament\Forms\Components\GoogleMapsLocationPicker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Filament\Tables\Columns\TextColumn;

class SellerLocationResource extends Resource
{
    protected static ?string $model = SellerLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    
    protected static ?string $navigationGroup = 'Manajemen Penjual';
    
    protected static ?string $recordTitleAttribute = 'nama_usaha';
    
    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return 'Lokasi Penjual';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Lokasi Penjual';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Dasar')
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Penjual')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->default(fn () => auth()->user() && auth()->user()->isSeller() ? auth()->id() : null)
                                    ->disabled(fn () => auth()->user() && auth()->user()->isSeller()),
                                
                                Forms\Components\TextInput::make('nama_usaha')
                                    ->label('Nama Usaha')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\Select::make('jenis_penjual')
                                    ->label('Jenis Penjual')
                                    ->options(SellerLocation::$sellerTypes)
                                    ->required(),
                                
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->rows(4)
                                    ->columnSpan('full'),
                                
                                Forms\Components\TextInput::make('telepon')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\Toggle::make('aktif')
                                    ->label('Status Aktif')
                                    ->default(true)
                                    ->required(),
                            ])
                            ->columns([
                                'sm' => 2,
                            ]),
                        
                        Forms\Components\Section::make('Alamat')
                            ->schema([
                                Forms\Components\Textarea::make('alamat_lengkap')
                                    ->label('Alamat Lengkap')
                                    ->required()
                                    ->rows(3)
                                    ->columnSpan('full'),
                                
                                Forms\Components\TextInput::make('provinsi')
                                    ->label('Provinsi')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('kota')
                                    ->label('Kota')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('kecamatan')
                                    ->label('Kecamatan')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('kode_pos')
                                    ->label('Kode Pos')
                                    ->required()
                                    ->maxLength(10),
                            ])
                            ->columns([
                                'sm' => 2,
                            ]),

                        Forms\Components\Section::make('Lokasi pada Peta')
                            ->schema([
                                GoogleMapsLocationPicker::make('coordinates')
                                    ->label('Pilih Lokasi pada Peta')
                                    ->center(-7.1192, 112.4186) // Lamongan center
                                    ->zoom(12)
                                    ->searchable(true)
                                    ->height('400px')
                                    ->live()
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->latitude && $record->longitude) {
                                            $component->state([
                                                'lat' => (float) $record->latitude,
                                                'lng' => (float) $record->longitude,
                                                'address' => $record->alamat_lengkap ?? 'Lokasi Terpilih'
                                            ]);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state && isset($state['lat']) && isset($state['lng'])) {
                                            $set('latitude', $state['lat']);
                                            $set('longitude', $state['lng']);
                                        }
                                    })
                                    ->dehydrated(false)
                                    ->helperText('Klik pada peta untuk memilih lokasi atau gunakan fitur pencarian'),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('latitude')
                                            ->label('Latitude')
                                            ->numeric()
                                            ->step(0.000001)
                                            ->readonly()
                                            ->required()
                                            ->default(-7.1192)
                                            ->rules(['required', 'numeric', 'between:-90,90']),

                                        Forms\Components\TextInput::make('longitude')
                                            ->label('Longitude')
                                            ->numeric()
                                            ->step(0.000001)
                                            ->readonly()
                                            ->required()
                                            ->default(112.4186)
                                            ->rules(['required', 'numeric', 'between:-180,180']),
                                    ]),
                            ])
                            ->columnSpan('full'),
                    ])
                    ->columnSpan([
                        'sm' => 2,
                    ]),
                
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Jam Operasional')
                            ->schema([
                                Forms\Components\Repeater::make('jam_operasional')
                                    ->label('Jadwal Operasional')
                                    ->schema([
                                        Forms\Components\Select::make('hari')
                                            ->label('Hari')
                                            ->options([
                                                'Senin' => 'Senin',
                                                'Selasa' => 'Selasa',
                                                'Rabu' => 'Rabu',
                                                'Kamis' => 'Kamis',
                                                'Jumat' => 'Jumat',
                                                'Sabtu' => 'Sabtu',
                                                'Minggu' => 'Minggu',
                                            ])
                                            ->required(),
                                        
                                        Forms\Components\TimePicker::make('jam_buka')
                                            ->label('Jam Buka')
                                            ->seconds(false)
                                            ->required(),
                                        
                                        Forms\Components\TimePicker::make('jam_tutup')
                                            ->label('Jam Tutup')
                                            ->seconds(false)
                                            ->required(),
                                    ])
                                    ->columns(3)
                                    ->columnSpan('full')
                                    ->defaultItems(7),
                            ]),
                        
                        Forms\Components\Section::make('Foto Lokasi')
                            ->schema([
                                Forms\Components\FileUpload::make('foto')
                                    ->label('Foto Lokasi Usaha')
                                    ->image()
                                    ->multiple()
                                    ->maxFiles(5)
                                    ->directory('seller_locations')
                                    ->columnSpan('full'),
                            ]),
                    ])
                    ->columnSpan([
                        'sm' => 1,
                    ]),
            ])
            ->columns([
                'sm' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->getStateUsing(fn ($record) => $record->foto ? $record->foto[0] : null),
                
                Tables\Columns\TextColumn::make('nama_usaha')
                    ->label('Nama Usaha')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Jenis Penjual')
                    ->formatStateUsing(fn (string $state): string => SellerLocation::$sellerTypes[$state] ?? $state)
                    ->colors([
                        'primary' => 'nelayan',
                        'success' => 'pembudidaya',
                        'warning' => 'grosir',
                        'info' => 'ritel',
                    ]),
                
                Tables\Columns\TextColumn::make('kota')
                    ->label('Kota')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('telepon')
                    ->label('Telepon')
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('aktif')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_penjual')
                    ->label('Jenis Penjual')
                    ->options(SellerLocation::$sellerTypes),
                
                Tables\Filters\SelectFilter::make('aktif')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ]),
                
                Tables\Filters\SelectFilter::make('provinsi')
                    ->label('Provinsi')
                    ->options(function () {
                        return SellerLocation::distinct()->pluck('provinsi', 'provinsi')->toArray();
                    }),
                
                Tables\Filters\SelectFilter::make('kota')
                    ->label('Kota')
                    ->options(function () {
                        return SellerLocation::distinct()->pluck('kota', 'kota')->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('aktifkan')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Collection $records) => $records->each->update(['aktif' => true]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    
                    Tables\Actions\BulkAction::make('nonaktifkan')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Collection $records) => $records->each->update(['aktif' => false]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\AppointmentsRelationManager::class,
        ];
    }
    
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['admin', 'seller']));
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['admin', 'seller']));
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            return true;
        }
        
        if ($user->isSeller()) {
            return $record->user_id === $user->id;
        }
        
        return false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            return true;
        }
        
        if ($user->isSeller()) {
            return $record->user_id === $user->id;
        }
        
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        $user = auth()->user();
        
        // Jika user adalah seller, hanya tampilkan lokasi mereka sendiri
        if ($user && $user->isSeller()) {
            $query->where('user_id', $user->id);
        }
        
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellerLocations::route('/'),
            'create' => Pages\CreateSellerLocation::route('/create'),
            'edit' => Pages\EditSellerLocation::route('/{record}/edit'),
        ];
    }    
}

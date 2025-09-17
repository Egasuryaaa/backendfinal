<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FishFarmResource\Pages;
use App\Filament\Resources\FishFarmResource\RelationManagers;
use App\Models\FishFarm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FishFarmResource extends Resource
{
    protected static ?string $model = FishFarm::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    
    protected static ?string $navigationLabel = 'Tambak Ikan';
    
    protected static ?string $modelLabel = 'Tambak Ikan';
    
    protected static ?string $pluralModelLabel = 'Tambak Ikan';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pemilik Tambak')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->description('Pilih pemilik tambak ikan'),
                            
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Tambak')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Tambak Lele Makmur'),
                            
                        Forms\Components\TextInput::make('no_telepon')
                            ->label('No. Telepon')
                            ->tel()
                            ->placeholder('Contoh: 08123456789')
                            ->helperText('Format: 08xxxxxxxxxx'),
                            
                        Forms\Components\Select::make('status')
                            ->label('Status Tambak')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Non-Aktif',
                                'maintenance' => 'Maintenance'
                            ])
                            ->default('aktif')
                            ->required(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Detail Produksi')
                    ->schema([
                        Forms\Components\TextInput::make('jenis_ikan')
                            ->label('Jenis Ikan')
                            ->placeholder('Contoh: Lele, Nila, Patin')
                            ->helperText('Pisahkan dengan koma jika lebih dari satu jenis'),
                            
                        Forms\Components\TextInput::make('banyak_bibit')
                            ->label('Jumlah Bibit')
                            ->numeric()
                            ->suffix('ekor')
                            ->placeholder('Contoh: 5000'),
                            
                        Forms\Components\TextInput::make('luas_tambak')
                            ->label('Luas Tambak')
                            ->numeric()
                            ->suffix('m²')
                            ->step(0.01)
                            ->placeholder('Contoh: 500.50'),
                    ])
                    ->columns(3),
                    
                Forms\Components\Section::make('Informasi Lokasi')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Tambak')
                            ->rows(3)
                            ->placeholder('Masukkan alamat lengkap tambak')
                            ->columnSpanFull(),
                            
                        Forms\Components\KeyValue::make('lokasi_koordinat')
                            ->label('Koordinat Lokasi')
                            ->addActionLabel('Tambah Koordinat')
                            ->keyLabel('Parameter')
                            ->valueLabel('Nilai')
                            ->default([
                                'lat' => '',
                                'lng' => ''
                            ])
                            ->helperText('Masukkan koordinat latitude dan longitude')
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Deskripsi & Media')
                    ->schema([
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Tambak')
                            ->rows(4)
                            ->placeholder('Deskripsikan kondisi tambak, fasilitas yang tersedia, dll.')
                            ->columnSpanFull(),
                            
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Tambak')
                            ->image()
                            ->directory('fish-farms')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120) // 5MB
                            ->helperText('Upload foto tambak (maksimal 5MB)')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Tambak')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->default('Belum diisi'),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->searchable()
                    ->sortable()
                    ->default('Tidak ada')
                    ->description(fn ($record) => $record->user?->email ?? 'Email tidak tersedia'),
                    
                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('No. Telepon')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone')
                    ->default('Belum diisi'),
                    
                Tables\Columns\TextColumn::make('jenis_ikan')
                    ->label('Jenis Ikan')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->jenis_ikan)
                    ->badge()
                    ->color('info')
                    ->default('Belum diisi'),
                    
                Tables\Columns\TextColumn::make('banyak_bibit')
                    ->label('Jumlah Bibit')
                    ->numeric(0)
                    ->sortable()
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 0) . ' ekor' : 'Belum diisi'),
                    
                Tables\Columns\TextColumn::make('luas_tambak')
                    ->label('Luas Tambak')
                    ->numeric(2)
                    ->sortable()
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) . ' m²' : 'Belum diisi'),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'danger',
                        'maintenance' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non-Aktif',
                        'maintenance' => 'Maintenance',
                        default => ucfirst($state ?? 'Tidak diset')
                    }),
                    
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->alamat)
                    ->default('Belum diisi'),
                    
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('/images/default-farm.png')
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non-Aktif',
                        'maintenance' => 'Maintenance'
                    ]),
                    
                Tables\Filters\SelectFilter::make('jenis_ikan')
                    ->label('Jenis Ikan')
                    ->options(function () {
                        return FishFarm::whereNotNull('jenis_ikan')
                            ->pluck('jenis_ikan', 'jenis_ikan')
                            ->unique()
                            ->sort()
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Detail')
                    ->icon('heroicon-m-eye')
                    ->modalHeading(fn ($record) => 'Detail Tambak: ' . ($record->nama ?? 'Tidak ada nama'))
                    ->modalContent(fn ($record) => view('filament.fish-farm-detail', ['record' => $record])),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum ada data tambak ikan')
            ->emptyStateDescription('Silakan tambah data tambak ikan baru dengan mengklik tombol "New fish farm"')
            ->emptyStateIcon('heroicon-o-building-office-2');
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
            'index' => Pages\ListFishFarms::route('/'),
            'create' => Pages\CreateFishFarm::route('/create'),
            'edit' => Pages\EditFishFarm::route('/{record}/edit'),
        ];
    }
}

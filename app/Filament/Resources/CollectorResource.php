<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectorResource\Pages;
use App\Filament\Resources\CollectorResource\RelationManagers;
use App\Models\Collector;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CollectorResource extends Resource
{
    protected static ?string $model = Collector::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    
    protected static ?string $navigationLabel = 'Pengepul';
    
    protected static ?string $modelLabel = 'Pengepul';
    
    protected static ?string $pluralModelLabel = 'Pengepul';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $navigationGroup = 'Manajemen Usaha';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pemilik')
                            ->relationship('user', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name} ({$record->email})")
                            ->required()
                            ->searchable(['name', 'email']),
                            
                        Forms\Components\TextInput::make('nama_usaha')
                            ->label('Nama Usaha')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('no_telepon')
                            ->label('No. Telepon')
                            ->tel()
                            ->maxLength(20),
                            
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Non-Aktif',
                                'pending' => 'Pending'
                            ])
                            ->default('aktif')
                            ->required(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Informasi Operasional')
                    ->schema([
                        Forms\Components\TagsInput::make('jenis_ikan_diterima')
                            ->label('Jenis Ikan yang Diterima')
                            ->separator(',')
                            ->placeholder('Contoh: Lele, Nila, Gurame'),
                            
                        Forms\Components\TextInput::make('rate_per_kg')
                            ->label('Rate per Kg (Rp)')
                            ->numeric()
                            ->step(0.01)
                            ->prefix('Rp'),
                            
                        Forms\Components\TextInput::make('kapasitas_maximum')
                            ->label('Kapasitas Maximum (kg)')
                            ->numeric()
                            ->step(0.01)
                            ->suffix(' kg'),
                            
                        Forms\Components\TextInput::make('jam_operasional')
                            ->label('Jam Operasional')
                            ->placeholder('Contoh: 08:00 - 17:00'),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Alamat & Lokasi')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->rows(3)
                            ->columnSpanFull(),
                            
                        Forms\Components\TextInput::make('lokasi_koordinat.lat')
                            ->label('Latitude')
                            ->numeric()
                            ->step(0.000001),
                            
                        Forms\Components\TextInput::make('lokasi_koordinat.lng')
                            ->label('Longitude')
                            ->numeric()
                            ->step(0.000001),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Deskripsi')
                    ->schema([
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Usaha')
                            ->rows(4)
                            ->columnSpanFull(),
                            
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Usaha')
                            ->image()
                            ->directory('collectors')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_usaha')
                    ->label('Nama Usaha')
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
                    
                Tables\Columns\TextColumn::make('jenis_ikan_diterima')
                    ->label('Jenis Ikan')
                    ->limit(30)
                    ->default('Belum diisi')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return implode(', ', $state);
                        }
                        return $state ?? 'Belum diisi';
                    }),
                    
                Tables\Columns\TextColumn::make('rate_per_kg')
                    ->label('Rate per Kg')
                    ->prefix('Rp ')
                    ->numeric(0)
                    ->sortable()
                    ->default(0)
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : 'Belum diisi'),
                    
                Tables\Columns\TextColumn::make('kapasitas_maximum')
                    ->label('Kapasitas Max')
                    ->suffix(' kg')
                    ->numeric(1)
                    ->sortable()
                    ->default(0)
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) . ' kg' : 'Belum diisi'),
                    
                Tables\Columns\TextColumn::make('jam_operasional')
                    ->label('Jam Operasional')
                    ->limit(20)
                    ->default('Belum diisi'),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non-Aktif',
                        'pending' => 'Pending',
                        default => ucfirst($state ?? 'Tidak diset')
                    }),
                    
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->alamat)
                    ->default('Belum diisi'),
                    
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('/images/default-collector.png')
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
                        'pending' => 'Pending'
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Detail')
                    ->icon('heroicon-m-eye')
                    ->modalHeading(fn ($record) => 'Detail Pengepul: ' . ($record->nama_usaha ?? 'Tidak ada nama'))
                    ->modalContent(fn ($record) => view('filament.collector-detail', ['record' => $record])),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum ada data pengepul')
            ->emptyStateDescription('Silakan tambah data pengepul baru dengan mengklik tombol "New collector"')
            ->emptyStateIcon('heroicon-o-building-storefront');
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
            'index' => Pages\ListCollectors::route('/'),
            'create' => Pages\CreateCollector::route('/create'),
            'edit' => Pages\EditCollector::route('/{record}/edit'),
        ];
    }
}

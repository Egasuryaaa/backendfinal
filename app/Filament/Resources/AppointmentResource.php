<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
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


class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    
    protected static ?string $navigationLabel = 'Appointments';
    
    protected static ?string $modelLabel = 'Appointment';
    
    protected static ?string $pluralModelLabel = 'Appointments';
    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $recordTitleAttribute = 'id';

    public static function getModelLabel(): string
    {
        return 'Janji Temu';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Janji Temu';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Janji Temu')
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Pemilik Tambak')
                                    ->relationship('pemilikTambak', 'name')
                                    ->required()
                                    ->helperText('Pemilik tambak yang membuat appointment'),
                                
                                Forms\Components\Select::make('fish_farm_id')
                                    ->label('Tambak Ikan')
                                    ->relationship('fishFarm', 'nama')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->nama . ' - ' . $record->user->name . ' (' . $record->jenis_ikan . ')')
                                    ->searchable(['nama', 'jenis_ikan'])
                                    ->required()
                                    ->helperText('Pilih tambak ikan yang terkait dengan appointment ini'),
                                
                                Forms\Components\Select::make('collector_id')
                                    ->label('Pengepul')
                                    ->relationship('collector', 'nama')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->nama . ' - ' . $record->user->name)
                                    ->searchable(['nama'])
                                    ->required()
                                    ->helperText('Pilih pengepul yang terkait dengan appointment ini'),
                                
                                Forms\Components\DateTimePicker::make('tanggal_janji')
                                    ->label('Tanggal dan Waktu')
                                    ->required(),
                                
                                Forms\Components\TextInput::make('waktu_janji')
                                    ->label('Waktu Tambahan')
                                    ->placeholder('19:56')
                                    ->helperText('Format HH:mm'),
                                
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options(Appointment::$statuses)
                                    ->required(),
                                
                                Forms\Components\Select::make('appointment_type')
                                    ->label('Jenis Appointment')
                                    ->options([
                                        'penjualan_produk' => 'Penjualan Produk',
                                        'pengepulan_ikan' => 'Pengepulan Ikan'
                                    ])
                                    ->default('pengepulan_ikan'),
                                
                                Forms\Components\TextInput::make('tujuan')
                                    ->label('Tujuan')
                                    ->maxLength(255),
                                
                                Forms\Components\Textarea::make('catatan')
                                    ->label('Catatan')
                                    ->maxLength(65535)
                                    ->columnSpan('full'),
                                
                                Forms\Components\Textarea::make('pesan_pemilik')
                                    ->label('Pesan dari Pemilik Tambak')
                                    ->maxLength(65535)
                                    ->columnSpan('full'),
                            ])
                            ->columns([
                                'sm' => 2,
                            ]),
                        
                        Forms\Components\Section::make('Informasi Berat & Harga')
                            ->schema([
                                Forms\Components\TextInput::make('estimated_weight')
                                    ->label('Perkiraan Berat (kg)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->nullable()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $harga = $get('price_per_kg');
                                        if ($state && $harga) {
                                            $set('total_estimasi', $state * $harga);
                                        }
                                    }),
                                
                                Forms\Components\TextInput::make('price_per_kg')
                                    ->label('Harga per Kg (Rp)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->nullable()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $perkiraan = $get('estimated_weight');
                                        if ($state && $perkiraan) {
                                            $set('total_estimasi', $perkiraan * $state);
                                        }
                                    }),
                                
                                Forms\Components\TextInput::make('total_estimasi')
                                    ->label('Total Estimasi (Rp)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->nullable()
                                    ->disabled()
                                    ->dehydrated(),
                                
                                Forms\Components\TextInput::make('kualitas_ikan')
                                    ->label('Kualitas Ikan')
                                    ->nullable()
                                    ->placeholder('A, B, C, atau deskripsi kualitas'),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Section::make('WhatsApp & Informasi Tambahan')
                            ->schema([
                                Forms\Components\Textarea::make('whatsapp_summary')
                                    ->label('Ringkasan WhatsApp')
                                    ->nullable()
                                    ->rows(3)
                                    ->columnSpanFull(),
                                
                                Forms\Components\Toggle::make('whatsapp_sent')
                                    ->label('WhatsApp Sudah Dikirim')
                                    ->default(false),
                                
                                Forms\Components\DateTimePicker::make('whatsapp_sent_at')
                                    ->label('Waktu WhatsApp Dikirim')
                                    ->nullable(),
                            ])
                            ->columns(2)
                            ->columnSpan('full'),
                    ])
                    ->columnSpan([
                        'sm' => 2,
                    ]),
                
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Tambak')
                            ->schema([
                                Forms\Components\Placeholder::make('info_tambak')
                                    ->label('Informasi Tambak')
                                    ->content(function ($record) {
                                        if (!$record || !$record->fishFarm) {
                                            return 'Pilih tambak ikan terlebih dahulu';
                                        }
                    
                                        $fishFarm = $record->fishFarm;
                                        return "
                                            Nama Tambak: {$fishFarm->nama} <br>
                                            Pemilik: {$fishFarm->user->name} <br>
                                            Jenis Ikan: {$fishFarm->jenis_ikan} <br>
                                            Banyak Bibit: " . number_format($fishFarm->banyak_bibit ?? 0) . " ekor
                                        ";
                                    }),
                                
                                Forms\Components\Placeholder::make('info_collector')
                                    ->label('Informasi Pengepul')
                                    ->content(function ($record) {
                                        if (!$record || !$record->collector) {
                                            return 'Pilih pengepul terlebih dahulu';
                                        }
                    
                                        $collector = $record->collector;
                                        return "
                                            Nama: {$collector->nama} <br>
                                            User: {$collector->user->name} <br>
                                            Deskripsi: {$collector->deskripsi}
                                        ";
                                    }),
                            ]),
                        
                        Forms\Components\Section::make('Tanggal')
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->content(fn ($record) => $record ? $record->created_at->format('d M Y H:i') : '-'),
                                
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Terakhir Diperbarui')
                                    ->content(fn ($record) => $record ? $record->updated_at->format('d M Y H:i') : '-'),
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
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('pemilikTambak.name')
                    ->label('Pemilik Tambak')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->pemilikTambak?->email),
                
                Tables\Columns\TextColumn::make('fishFarm.nama')
                    ->label('Tambak Ikan')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->fishFarm ? 
                        'Jenis: ' . $record->fishFarm->jenis_ikan . ' | Bibit: ' . number_format($record->fishFarm->banyak_bibit ?? 0) : '-')
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('collector.nama')
                    ->label('Pengepul')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->collector ? 
                        'User: ' . $record->collector->user->name : '-')
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('tanggal_janji')
                    ->label('Tanggal & Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('waktu_janji')
                    ->label('Waktu Tambahan')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => Appointment::$statuses[$state] ?? $state)
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'dikonfirmasi',
                        'primary' => 'selesai',
                        'danger' => 'dibatalkan',
                    ]),
                
                Tables\Columns\TextColumn::make('appointment_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'penjualan_produk' => 'Penjualan Produk',
                        'pengepulan_ikan' => 'Pengepulan Ikan',
                        default => $state ?? 'Tidak diketahui'
                    })
                    ->colors([
                        'primary' => 'penjualan_produk',
                        'success' => 'pengepulan_ikan',
                    ])
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('estimated_weight')
                    ->label('Perkiraan Berat')
                    ->suffix(' kg')
                    ->numeric(2)
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('price_per_kg')
                    ->label('Harga per Kg')
                    ->prefix('Rp ')
                    ->numeric(0)
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('tujuan')
                    ->label('Tujuan')
                    ->limit(30)
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('pesan_pemilik')
                    ->label('Pesan Pemilik')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('whatsapp_sent_at')
                    ->label('WhatsApp')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !empty($record->whatsapp_sent_at))
                    ->trueIcon('heroicon-o-chat-bubble-left-right')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(Appointment::$statuses),
                
                Tables\Filters\SelectFilter::make('appointment_type')
                    ->label('Jenis Appointment')
                    ->options([
                        'penjualan_produk' => 'Penjualan Produk',
                        'pengepulan_ikan' => 'Pengepulan Ikan'
                    ]),
                
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Pemilik Tambak')
                    ->relationship('pemilikTambak', 'name'),
                
                Tables\Filters\SelectFilter::make('fish_farm_id')
                    ->label('Tambak Ikan')
                    ->relationship('fishFarm', 'nama')
                    ->searchable(),
                
                Tables\Filters\SelectFilter::make('collector_id')
                    ->label('Pengepul')
                    ->relationship('collector', 'nama')
                    ->searchable(),
                
                Tables\Filters\Filter::make('tanggal_janji')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_janji', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_janji', '<=', $date),
                            );
                    }),
                
                Tables\Filters\Filter::make('upcoming')
                    ->label('Akan Datang')
                    ->query(fn (Builder $query): Builder => $query->where('tanggal_janji', '>=', now()))
                    ->toggle(),
                
                Tables\Filters\Filter::make('past')
                    ->label('Sudah Lewat')
                    ->query(fn (Builder $query): Builder => $query->where('tanggal_janji', '<', now()))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => 'Detail Appointment #' . $record->id)
                    ->modalContent(function ($record) {
                        $content = '<div class="space-y-4">';
                        
                        // Informasi Dasar
                        $content .= '<div class="bg-gray-50 p-4 rounded-lg">';
                        $content .= '<h3 class="font-semibold text-lg mb-2">Informasi Dasar</h3>';
                        $content .= '<div class="grid grid-cols-2 gap-4">';
                        $content .= '<div><strong>Pembeli:</strong><br>' . ($record->buyer?->name ?? 'Tidak ada') . '<br><span class="text-sm text-gray-600">' . ($record->buyer?->email ?? '') . '</span></div>';
                        $content .= '<div><strong>Penjual:</strong><br>' . ($record->seller?->name ?? 'Tidak ada') . '<br><span class="text-sm text-gray-600">' . ($record->seller?->email ?? '') . '</span></div>';
                        $content .= '</div>';
                        $content .= '<div class="mt-2"><strong>Tanggal Janji:</strong> ' . $record->tanggal_janji->format('d M Y H:i') . '</div>';
                        $content .= '<div><strong>Status:</strong> <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">' . (Appointment::$statuses[$record->status] ?? $record->status) . '</span></div>';
                        $content .= '</div>';
                        
                        // Informasi Fish Farm
                        if ($record->fishFarm) {
                            $content .= '<div class="bg-green-50 p-4 rounded-lg">';
                            $content .= '<h3 class="font-semibold text-lg mb-2 text-green-800">Informasi Tambak</h3>';
                            $content .= '<div class="grid grid-cols-2 gap-4">';
                            $content .= '<div><strong>Nama Tambak:</strong><br>' . $record->fishFarm->nama . '</div>';
                            $content .= '<div><strong>Pemilik:</strong><br>' . $record->fishFarm->user->name . '</div>';
                            $content .= '<div><strong>Jenis Ikan:</strong><br>' . ($record->fishFarm->jenis_ikan ?? 'Tidak ada') . '</div>';
                            $content .= '<div><strong>Luas Tambak:</strong><br>' . ($record->fishFarm->luas_tambak ?? 'Tidak ada') . ' m²</div>';
                            $content .= '</div>';
                            $content .= '<div class="mt-2"><strong>Alamat:</strong> ' . ($record->fishFarm->alamat ?? 'Tidak ada') . '</div>';
                            $content .= '</div>';
                        }
                        
                        // Informasi Collector
                        if ($record->collector) {
                            $content .= '<div class="bg-orange-50 p-4 rounded-lg">';
                            $content .= '<h3 class="font-semibold text-lg mb-2 text-orange-800">Informasi Pengepul</h3>';
                            $content .= '<div class="grid grid-cols-2 gap-4">';
                            $content .= '<div><strong>Nama Usaha:</strong><br>' . $record->collector->nama_usaha . '</div>';
                            $content .= '<div><strong>Pemilik:</strong><br>' . $record->collector->user->name . '</div>';
                            $content .= '<div><strong>Rate per Kg:</strong><br>Rp ' . number_format($record->collector->rate_per_kg ?? 0, 0) . '</div>';
                            $content .= '<div><strong>Kapasitas Max:</strong><br>' . ($record->collector->kapasitas_maximum ?? 'Tidak ada') . ' kg</div>';
                            $content .= '</div>';
                            $content .= '<div class="mt-2"><strong>Alamat:</strong> ' . ($record->collector->alamat ?? 'Tidak ada') . '</div>';
                            $content .= '<div><strong>No. Telepon:</strong> ' . ($record->collector->no_telepon ?? 'Tidak ada') . '</div>';
                            $content .= '</div>';
                        }
                        
                        // Informasi Seller Location (jika ada)
                        if ($record->sellerLocation) {
                            $content .= '<div class="bg-blue-50 p-4 rounded-lg">';
                            $content .= '<h3 class="font-semibold text-lg mb-2 text-blue-800">Informasi Lokasi Penjual</h3>';
                            $content .= '<div><strong>Nama Usaha:</strong> ' . $record->sellerLocation->nama_usaha . '</div>';
                            $content .= '<div><strong>Jenis:</strong> ' . $record->sellerLocation->seller_type_text . '</div>';
                            $content .= '<div><strong>Alamat:</strong> ' . $record->sellerLocation->alamat_lengkap . '</div>';
                            $content .= '<div><strong>Telepon:</strong> ' . ($record->sellerLocation->telepon ?? 'Tidak ada') . '</div>';
                            $content .= '</div>';
                        }
                        
                        // Informasi Berat dan Harga
                        if ($record->perkiraan_berat || $record->total_estimasi || $record->harga_per_kg) {
                            $content .= '<div class="bg-purple-50 p-4 rounded-lg">';
                            $content .= '<h3 class="font-semibold text-lg mb-2 text-purple-800">Informasi Berat & Harga</h3>';
                            $content .= '<div class="grid grid-cols-3 gap-4">';
                            if ($record->perkiraan_berat) $content .= '<div><strong>Perkiraan Berat:</strong><br>' . $record->perkiraan_berat . ' kg</div>';
                            if ($record->harga_per_kg) $content .= '<div><strong>Harga per Kg:</strong><br>Rp ' . number_format($record->harga_per_kg, 0) . '</div>';
                            if ($record->total_estimasi) $content .= '<div><strong>Total Estimasi:</strong><br>Rp ' . number_format($record->total_estimasi, 0) . '</div>';
                            $content .= '</div>';
                            $content .= '</div>';
                        }
                        
                        // Catatan
                        if ($record->catatan || $record->tujuan) {
                            $content .= '<div class="bg-gray-50 p-4 rounded-lg">';
                            $content .= '<h3 class="font-semibold text-lg mb-2">Catatan</h3>';
                            if ($record->tujuan) $content .= '<div><strong>Tujuan:</strong> ' . $record->tujuan . '</div>';
                            if ($record->catatan) $content .= '<div><strong>Catatan:</strong> ' . $record->catatan . '</div>';
                            $content .= '</div>';
                        }
                        
                        $content .= '</div>';
                        return new \Illuminate\Support\HtmlString($content);
                    }),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                Tables\Actions\Action::make('update_status')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-arrows-right-left')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status Baru')
                            ->options(Appointment::$statuses)
                            ->required(),
                    ])
                    ->action(function (Appointment $record, array $data): void {
                        $record->updateStatus($data['status']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Ubah Status')
                        ->icon('heroicon-o-arrows-right-left')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status Baru')
                                ->options(Appointment::$statuses)
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->updateStatus($data['status']);
                            }
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
    
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['admin', 'seller']));
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['admin', 'pemilik_tambak', 'pengepul']));
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Pemilik tambak dapat edit appointment mereka sendiri
        if ($user->hasRole('pemilik_tambak')) {
            return $record->user_id === $user->id;
        }
        
        // Collector dapat edit appointment yang ditujukan kepada mereka
        if ($user->hasRole('pengepul')) {
            return $record->collector_id === $user->id;
        }
        
        return false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Pemilik tambak dapat delete appointment mereka sendiri
        if ($user->hasRole('pemilik_tambak')) {
            return $record->user_id === $user->id;
        }
        
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        $user = auth()->user();
        
        // Jika user adalah pemilik tambak, hanya tampilkan appointment mereka sendiri
        if ($user && $user->hasRole('pemilik_tambak')) {
            $query->where('user_id', $user->id);
        }
        
        // Jika user adalah pengepul, tampilkan appointment yang ditujukan kepada mereka
        if ($user && $user->hasRole('pengepul')) {
            $query->where('collector_id', $user->id);
        }
        
        return $query;
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['menunggu', 'dikonfirmasi'])->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'menunggu')->exists()
            ? 'warning'
            : 'primary';
    }
}

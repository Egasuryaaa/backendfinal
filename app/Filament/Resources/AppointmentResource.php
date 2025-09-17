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
                                Forms\Components\Select::make('penjual_id')
                                    ->label('Penjual')
                                    ->relationship('seller', 'name')
                                    ->required(),
                                
                                Forms\Components\Select::make('pembeli_id')
                                    ->label('Pembeli')
                                    ->relationship('buyer', 'name')
                                    ->required(),
                                
                                Forms\Components\Select::make('lokasi_penjual_id')
                                    ->label('Lokasi Penjual')
                                    ->relationship('sellerLocation', 'nama_usaha')
                                    ->required(),
                                
                                Forms\Components\Select::make('fish_farm_id')
                                    ->label('Tambak Ikan')
                                    ->relationship('fishFarm', 'nama')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->nama . ' - ' . $record->user->name . ' (' . $record->jenis_ikan . ')')
                                    ->searchable(['nama', 'jenis_ikan'])
                                    ->nullable()
                                    ->helperText('Pilih tambak ikan yang terkait dengan appointment ini'),
                                
                                Forms\Components\Select::make('collector_id')
                                    ->label('Pengepul')
                                    ->relationship('collector', 'nama_usaha')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->nama_usaha . ' - ' . $record->user->name . ' (Rp' . number_format($record->rate_per_kg ?? 0, 0) . '/kg)')
                                    ->searchable(['nama_usaha'])
                                    ->nullable()
                                    ->helperText('Pilih pengepul yang terkait dengan appointment ini'),
                                
                                Forms\Components\DateTimePicker::make('tanggal_janji')
                                    ->label('Tanggal dan Waktu')
                                    ->required(),
                                
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options(Appointment::$statuses)
                                    ->required(),
                                
                                Forms\Components\Select::make('jenis')
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
                            ])
                            ->columns([
                                'sm' => 2,
                            ]),
                        
                        Forms\Components\Section::make('Lokasi Pertemuan')
                            ->schema([
                                GoogleMapsLocationPicker::make('meeting_location')
                                    ->label('Pilih Lokasi Pertemuan (Opsional)')
                                    ->center(-7.1192, 112.4186) // Lamongan center
                                    ->zoom(12)
                                    ->searchable(true)
                                    ->height('300px')
                                    ->helperText('Pilih lokasi khusus untuk pertemuan, atau kosongkan untuk menggunakan lokasi penjual'),
                            ]),
                        
                        Forms\Components\Section::make('Informasi Berat & Harga')
                            ->schema([
                                Forms\Components\TextInput::make('perkiraan_berat')
                                    ->label('Perkiraan Berat (kg)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->nullable()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $harga = $get('harga_per_kg');
                                        if ($state && $harga) {
                                            $set('total_estimasi', $state * $harga);
                                        }
                                    }),
                                
                                Forms\Components\TextInput::make('harga_per_kg')
                                    ->label('Harga per Kg (Rp)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->nullable()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $perkiraan = $get('perkiraan_berat');
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
                        Forms\Components\Section::make('Informasi Lokasi')
                            ->schema([
                                Forms\Components\Placeholder::make('info_lokasi')
                                    ->label('Informasi Lokasi')
                                    ->content(function ($record) {
                                        if (!$record || !$record->sellerLocation) {
                                            return 'Pilih lokasi penjual terlebih dahulu';
                                        }
                    
                                        $location = $record->sellerLocation;
                                        return "
                                            Nama Usaha: {$location->nama_usaha} <br>
                                            Jenis: {$location->seller_type_text} <br>
                                            Alamat: {$location->alamat_lengkap}, {$location->kecamatan}, {$location->kota}, {$location->provinsi} {$location->kode_pos} <br>
                                            Telepon: {$location->telepon}
                                        ";
                                    }),
                                
                                Forms\Components\Placeholder::make('jam_operasional')
                                    ->label('Jam Operasional')
                                    ->content(function ($record) {
                                        if (!$record || !$record->sellerLocation) {
                                            return 'Pilih lokasi penjual terlebih dahulu';
                                        }
                    
                                        return $record->sellerLocation->formatted_operating_hours;
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
                
                Tables\Columns\TextColumn::make('buyer.name')
                    ->label('Pembeli')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->buyer?->email),
                
                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Penjual')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->seller?->email),
                
                Tables\Columns\TextColumn::make('sellerLocation.nama_usaha')
                    ->label('Lokasi Penjual')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->sellerLocation ? 
                        $record->sellerLocation->seller_type_text . ' | ' . $record->sellerLocation->alamat_lengkap : '-')
                    ->wrap(),
                
                // Tambahkan informasi Fish Farm jika ada
                Tables\Columns\TextColumn::make('fishFarm.nama')
                    ->label('Tambak Ikan')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->fishFarm ? 
                        'Pemilik: ' . $record->fishFarm->user->name . ' | Jenis: ' . $record->fishFarm->jenis_ikan : '-')
                    ->wrap()
                    ->toggleable(),
                
                // Tambahkan informasi Collector jika ada  
                Tables\Columns\TextColumn::make('collector.nama_usaha')
                    ->label('Pengepul')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->collector ? 
                        'Pemilik: ' . $record->collector->user->name . ' | Rate: Rp' . number_format($record->collector->rate_per_kg ?? 0, 0) . '/kg' : '-')
                    ->wrap()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('tanggal_janji')
                    ->label('Tanggal & Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                
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
                
                Tables\Columns\TextColumn::make('jenis')
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
                
                Tables\Columns\TextColumn::make('perkiraan_berat')
                    ->label('Perkiraan Berat')
                    ->suffix(' kg')
                    ->numeric(2)
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('total_estimasi')
                    ->label('Total Estimasi')
                    ->prefix('Rp ')
                    ->numeric(0)
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('tujuan')
                    ->label('Tujuan')
                    ->limit(30)
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('meeting_location')
                    ->label('Lokasi Pertemuan')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !empty($record->meeting_location))
                    ->trueIcon('heroicon-o-map-pin')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('whatsapp_sent')
                    ->label('WhatsApp')
                    ->boolean()
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
                
                Tables\Filters\SelectFilter::make('jenis')
                    ->label('Jenis Appointment')
                    ->options([
                        'penjualan_produk' => 'Penjualan Produk',
                        'pengepulan_ikan' => 'Pengepulan Ikan'
                    ]),
                
                Tables\Filters\SelectFilter::make('penjual_id')
                    ->label('Penjual')
                    ->relationship('seller', 'name'),
                
                Tables\Filters\SelectFilter::make('pembeli_id')
                    ->label('Pembeli')
                    ->relationship('buyer', 'name'),
                
                Tables\Filters\SelectFilter::make('fish_farm_id')
                    ->label('Tambak Ikan')
                    ->relationship('fishFarm', 'nama')
                    ->searchable(),
                
                Tables\Filters\SelectFilter::make('collector_id')
                    ->label('Pengepul')
                    ->relationship('collector', 'nama_usaha')
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
        return $user && ($user->hasRole(['admin', 'seller']));
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            return true;
        }
        
        if ($user->isSeller()) {
            return $record->penjual_id === $user->id;
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
            return $record->penjual_id === $user->id;
        }
        
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        $user = auth()->user();
        
        // Jika user adalah seller, hanya tampilkan appointment mereka sendiri
        if ($user && $user->isSeller()) {
            $query->where('penjual_id', $user->id);
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

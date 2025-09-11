<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Closure;

class MapboxLocationPicker extends Field
{
    protected string $view = 'filament.forms.components.mapbox-location-picker';

    protected array | Closure | null $center = null;
    protected int | Closure | null $zoom = null;
    protected bool | Closure $searchable = true;
    protected string | Closure | null $placeholder = null;

    public function center(array | Closure | null $center): static
    {
        $this->center = $center;

        return $this;
    }

    public function zoom(int | Closure | null $zoom): static
    {
        $this->zoom = $zoom;

        return $this;
    }

    public function searchable(bool | Closure $condition = true): static
    {
        $this->searchable = $condition;

        return $this;
    }

    public function placeholder(string | Closure | null $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getCenter(): array
    {
        return $this->evaluate($this->center) ?? [-6.2088, 106.8456]; // Default Jakarta
    }

    public function getZoom(): int
    {
        return $this->evaluate($this->zoom) ?? 10;
    }

    public function isSearchable(): bool
    {
        return $this->evaluate($this->searchable);
    }

    public function getPlaceholder(): ?string
    {
        return $this->evaluate($this->placeholder) ?? 'Cari alamat...';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule('json');
        $this->rule(function ($attribute, $value, $fail) {
            if ($value) {
                $decoded = json_decode($value, true);
                if (!isset($decoded['lat']) || !isset($decoded['lng'])) {
                    $fail('Lokasi harus memiliki koordinat latitude dan longitude.');
                }
                if (!is_numeric($decoded['lat']) || !is_numeric($decoded['lng'])) {
                    $fail('Koordinat harus berupa angka.');
                }
            }
        });
    }
}

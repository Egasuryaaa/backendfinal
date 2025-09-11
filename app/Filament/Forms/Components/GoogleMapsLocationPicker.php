<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class GoogleMapsLocationPicker extends Field
{
    protected string $view = 'filament.forms.components.google-maps-location-picker';

    protected float $centerLat = -7.1192;  // Lamongan coordinates
    protected float $centerLng = 112.4186; // Lamongan coordinates
    protected int $zoom = 10;
    protected bool $searchable = true;
    protected ?string $height = '400px';

    /**
     * Set the initial center point of the map
     */
    public function center(float $lat, float $lng): static
    {
        $this->centerLat = $lat;
        $this->centerLng = $lng;
        
        return $this;
    }

    /**
     * Set the initial zoom level
     */
    public function zoom(int $zoom): static
    {
        $this->zoom = $zoom;
        
        return $this;
    }

    /**
     * Enable/disable location search
     */
    public function searchable(bool $searchable = true): static
    {
        $this->searchable = $searchable;
        
        return $this;
    }

    /**
     * Set the height of the map
     */
    public function height(string $height): static
    {
        $this->height = $height;
        
        return $this;
    }

    /**
     * Get the center latitude
     */
    public function getCenterLat(): float
    {
        return $this->centerLat;
    }

    /**
     * Get the center longitude
     */
    public function getCenterLng(): float
    {
        return $this->centerLng;
    }

    /**
     * Get the zoom level
     */
    public function getZoom(): int
    {
        return $this->zoom;
    }

    /**
     * Check if searchable
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * Get the height
     */
    public function getHeight(): string
    {
        return $this->height;
    }

    /**
     * Configure validation rules
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->rule('nullable');
        $this->rule('array');
        $this->rule(function (string $attribute, $value, \Closure $fail) {
            if (!is_array($value)) {
                return;
            }

            if (isset($value['lat']) && (!is_numeric($value['lat']) || $value['lat'] < -90 || $value['lat'] > 90)) {
                $fail('Latitude harus berupa angka antara -90 dan 90.');
            }

            if (isset($value['lng']) && (!is_numeric($value['lng']) || $value['lng'] < -180 || $value['lng'] > 180)) {
                $fail('Longitude harus berupa angka antara -180 dan 180.');
            }
        });
    }
}

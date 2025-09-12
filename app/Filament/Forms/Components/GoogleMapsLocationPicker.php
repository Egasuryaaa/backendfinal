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

        // Make the field nullable and set basic rules
        $this->nullable()
             ->default(null);
        
        // Use afterStateUpdated for data validation and cleaning
        $this->afterStateUpdated(function ($state, $set, $get) {
            // Only process if state is not null and is array
            if (is_array($state) && !empty($state)) {
                $cleanState = [];
                
                // Validate and clean latitude
                if (isset($state['lat']) && is_numeric($state['lat'])) {
                    $lat = (float) $state['lat'];
                    if ($lat >= -90 && $lat <= 90) {
                        $cleanState['lat'] = $lat;
                    }
                }
                
                // Validate and clean longitude
                if (isset($state['lng']) && is_numeric($state['lng'])) {
                    $lng = (float) $state['lng'];
                    if ($lng >= -180 && $lng <= 180) {
                        $cleanState['lng'] = $lng;
                    }
                }
                
                // Only update if we have both valid coordinates
                if (count($cleanState) === 2) {
                    $set($this->getName(), $cleanState);
                }
            }
        });
    }
}

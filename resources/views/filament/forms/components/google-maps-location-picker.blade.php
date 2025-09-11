@php
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $centerLat = $getCenterLat();
    $centerLng = $getCenterLng();
    $zoom = $getZoom();
    $searchable = $isSearchable();
    $height = $getHeight();
    $apiKey = config('services.google_maps.api_key', env('GOOGLE_MAPS_API_KEY'));
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="openStreetMapLocationPicker({
        statePath: '{{ $statePath }}',
        isDisabled: {{ json_encode($isDisabled) }},
        centerLat: {{ $centerLat }},
        centerLng: {{ $centerLng }},
        zoom: {{ $zoom }},
        searchable: {{ json_encode($searchable) }},
        @if ($getState())
            initialLocation: {{ json_encode($getState()) }}
        @endif
    })" class="openstreetmap-location-picker">
        
        <!-- Search input (if searchable) -->
        <template x-if="searchable">
            <div class="mb-4">
                <div class="relative">
                    <input
                        type="text"
                        x-model="searchQuery"
                        @input.debounce.500ms="searchLocation()"
                        placeholder="Cari lokasi..."
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :disabled="isDisabled"
                    >
                    
                    <!-- Search results dropdown -->
                    <div
                        x-show="searchResults.length > 0"
                        x-transition
                        class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg"
                    >
                        <template x-for="result in searchResults" :key="result.place_id">
                            <div
                                @click="selectSearchResult(result)"
                                class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                x-text="result.display_name"
                            ></div>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <!-- Map container -->
        <div class="relative">
            <div
                x-ref="mapContainer"
                class="w-full rounded-lg border border-gray-300 leaflet-container"
                style="height: {{ $height }}; background: #f8f9fa; position: relative; z-index: 1;"
                :id="'map-container-' + Math.random().toString(36).substr(2, 9)"
            ></div>

            <!-- Loading overlay -->
            <div
                x-show="loading"
                class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center rounded-lg"
            >
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            </div>

            <!-- Error message -->
            <div
                x-show="error"
                x-text="error"
                class="absolute inset-0 bg-red-50 border-2 border-red-200 rounded-lg flex items-center justify-center text-red-600"
            ></div>
        </div>

        <!-- Selected location info -->
        <div x-show="selectedLocation" class="mt-4 p-3 bg-gray-50 rounded-lg">
            <div class="text-sm font-medium text-gray-700 mb-1">Lokasi Terpilih:</div>
            <div x-text="selectedLocation?.address" class="text-sm text-gray-600 mb-2"></div>
            <div class="text-xs text-gray-500">
                Koordinat: <span x-text="selectedLocation?.lat?.toFixed(6)"></span>, <span x-text="selectedLocation?.lng?.toFixed(6)"></span>
            </div>
        </div>

        <!-- Hidden input for form state -->
        <input
            type="hidden"
            x-model="stateValue"
            {{ $attributes->merge([
                'id' => $getId(),
            ]) }}
        >
    </div>
</x-dynamic-component>

@once
<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Custom styles to fix map rendering issues -->
<style>
    .leaflet-container {
        font: 12px/1.5 "Helvetica Neue", Arial, Helvetica, sans-serif;
        background-color: #f8f9fa !important;
    }
    
    .leaflet-tile-container {
        opacity: 1 !important;
    }
    
    .leaflet-tile {
        filter: none !important;
        opacity: 1 !important;
        background-color: #f8f9fa;
    }
    
    .leaflet-tile-pane {
        opacity: 1 !important;
        filter: none !important;
    }
    
    .leaflet-map-pane {
        opacity: 1 !important;
    }
    
    .leaflet-control-container {
        opacity: 1 !important;
    }
    
    /* Ensure tiles are visible */
    .leaflet-layer {
        opacity: 1 !important;
        filter: none !important;
    }
    
    /* Fix for dark/black tiles */
    .leaflet-tile-loaded {
        opacity: 1 !important;
        visibility: visible !important;
    }
</style>
@endonce

@push('scripts')
<script>
// Define missing Filament form components to prevent errors
window.textareaFormComponent = window.textareaFormComponent || function(config) {
    return {
        state: config.state || '',
        initialHeight: config.initialHeight || 4,
        shouldAutosize: config.shouldAutosize || false,
        
        init() {
            // Basic textarea functionality
            if (this.shouldAutosize) {
                this.autosize();
            }
        },
        
        autosize() {
            // Simple autosize implementation
            const textarea = this.$el.querySelector('textarea');
            if (textarea) {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            }
        }
    };
};

// Define state helper if not defined
window.state = window.state || {};

function openStreetMapLocationPicker(config) {
    return {
        statePath: config.statePath,
        isDisabled: config.isDisabled,
        centerLat: parseFloat(config.centerLat) || -7.117245,
        centerLng: parseFloat(config.centerLng) || 112.418323,
        zoom: parseInt(config.zoom) || 10,
        searchable: config.searchable,
        
        map: null,
        marker: null,
        loading: true,
        error: null,
        
        searchQuery: '',
        searchResults: [],
        
        selectedLocation: config.initialLocation || null,
        
        // Validate coordinates
        isValidCoordinate(lat, lng) {
            return !isNaN(lat) && !isNaN(lng) && 
                   lat >= -90 && lat <= 90 && 
                   lng >= -180 && lng <= 180 &&
                   lat !== null && lng !== null &&
                   lat !== undefined && lng !== undefined;
        },
        
        get stateValue() {
            return this.selectedLocation ? JSON.stringify(this.selectedLocation) : '';
        },
        
        set stateValue(value) {
            try {
                this.selectedLocation = value ? JSON.parse(value) : null;
            } catch (e) {
                this.selectedLocation = null;
            }
        },

        init() {
            // Prevent multiple initialization
            if (this.map) {
                console.log('Map already initialized, skipping...');
                return;
            }

            // Ensure Leaflet is loaded before initializing
            if (typeof L === 'undefined') {
                // Wait for Leaflet to load
                const checkLeaflet = setInterval(() => {
                    if (typeof L !== 'undefined') {
                        clearInterval(checkLeaflet);
                        this.initializeMap();
                    }
                }, 100);
                
                // Timeout after 10 seconds
                setTimeout(() => {
                    clearInterval(checkLeaflet);
                    if (typeof L === 'undefined') {
                        this.error = 'Leaflet library gagal dimuat.';
                        this.loading = false;
                    }
                }, 10000);
            } else {
                this.initializeMap();
            }
            
            // Watch for state changes
            this.$watch('selectedLocation', (newValue) => {
                this.updateState();
            });
            
            // Set initial state if exists
            if (this.selectedLocation) {
                this.updateState();
            }
        },

        initializeMap() {
            try {
                // Check if Leaflet is available
                if (typeof L === 'undefined') {
                    throw new Error('Leaflet library is not loaded');
                }

                // Check if map container already has a map instance
                const container = this.$refs.mapContainer;
                if (!container) {
                    throw new Error('Map container not found');
                }

                if (container._leaflet_id) {
                    // Map already exists, remove it first
                    console.log('Removing existing map instance...');
                    if (this.map) {
                        this.map.off();
                        this.map.remove();
                    }
                    // Clear the container's leaflet ID
                    delete container._leaflet_id;
                    // Clear container content
                    container.innerHTML = '';
                }

                // Clear any existing map reference
                this.map = null;
                this.marker = null;

                // Add a small delay to ensure container is ready
                setTimeout(() => {
                    try {
                        // Initialize Leaflet map with proper options
                        this.map = L.map(container, {
                            zoomControl: true,
                            scrollWheelZoom: true,
                            doubleClickZoom: true,
                            touchZoom: true,
                            preferCanvas: false
                        });

                        // Validate center coordinates before setting view
                        if (this.isValidCoordinate(this.centerLat, this.centerLng)) {
                            this.map.setView([this.centerLat, this.centerLng], this.zoom);
                        } else {
                            console.warn('Invalid center coordinates, using default');
                            this.map.setView([-7.117245, 112.418323], 10);
                        }

                        // Primary tile layer - OpenStreetMap
                        const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors',
                            maxZoom: 19,
                            subdomains: ['a', 'b', 'c'],
                            crossOrigin: true,
                            opacity: 1.0,
                            errorTileUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjU2IiBoZWlnaHQ9IjI1NiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjU2IiBoZWlnaHQ9IjI1NiIgZmlsbD0iI2Y4ZjlmYSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjNjY2Ij5UaWxlIEVycm9yPC90ZXh0Pjwvc3ZnPg=='
                        });

                        // Add tile layer to map
                        osmLayer.addTo(this.map);

                        // Handle tile loading events
                        osmLayer.on('loading', () => {
                            console.log('Tiles loading...');
                        });

                        osmLayer.on('load', () => {
                            console.log('Tiles loaded successfully');
                            this.loading = false;
                        });

                        osmLayer.on('tileerror', (e) => {
                            console.log('Tile error:', e);
                            // Try alternative tile source
                            setTimeout(() => {
                                const altLayer = L.tileLayer('https://tile.openstreetmap.de/{z}/{x}/{y}.png', {
                                    attribution: '© OpenStreetMap contributors',
                                    maxZoom: 18
                                });
                                this.map.removeLayer(osmLayer);
                                altLayer.addTo(this.map);
                            }, 1000);
                        });

                        // Force multiple refreshes to ensure tiles load
                        setTimeout(() => {
                            if (this.map) {
                                this.map.invalidateSize();
                                console.log('First refresh completed');
                            }
                        }, 250);

                        setTimeout(() => {
                            if (this.map) {
                                this.map.invalidateSize();
                                console.log('Second refresh completed');
                            }
                        }, 500);

                        setTimeout(() => {
                            if (this.map) {
                                this.map.invalidateSize();
                                console.log('Third refresh completed');
                            }
                        }, 1000);

                        if (!this.isDisabled) {
                            this.map.on('click', (e) => {
                                if (e.latlng && e.latlng.lat !== undefined && e.latlng.lng !== undefined) {
                                    this.selectCoordinates(e.latlng.lat, e.latlng.lng);
                                } else {
                                    console.error('Invalid click coordinates:', e.latlng);
                                }
                            });
                        }

                        this.loading = false;
                        this.addExistingMarker();
                    } catch (innerE) {
                        console.error('Error in delayed map initialization:', innerE);
                        this.error = 'Gagal menginisialisasi peta: ' + innerE.message;
                        this.loading = false;
                    }
                }, 100);

            } catch (e) {
                console.error('Error initializing map:', e);
                this.error = 'Gagal menginisialisasi peta: ' + e.message;
                this.loading = false;
            }
        },

        addExistingMarker() {
            if (this.selectedLocation && 
                this.selectedLocation.lat && 
                this.selectedLocation.lng &&
                this.isValidCoordinate(this.selectedLocation.lat, this.selectedLocation.lng)) {
                
                this.addMarker(this.selectedLocation.lat, this.selectedLocation.lng);
                this.map.setView([this.selectedLocation.lat, this.selectedLocation.lng], 15);
                
                // Force map refresh after adding existing marker
                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                }, 300);
            }
        },

        addMarker(lat, lng) {
            // Validate coordinates before adding marker
            if (!this.isValidCoordinate(lat, lng)) {
                console.error('Invalid coordinates for marker:', lat, lng);
                return;
            }

            if (this.marker) {
                this.map.removeLayer(this.marker);
            }

            this.marker = L.marker([lat, lng], {
                draggable: !this.isDisabled
            }).addTo(this.map);

            if (!this.isDisabled) {
                this.marker.on('dragend', (e) => {
                    const latlng = e.target.getLatLng();
                    if (latlng && this.isValidCoordinate(latlng.lat, latlng.lng)) {
                        this.selectCoordinates(latlng.lat, latlng.lng);
                    }
                });
            }

            // Multiple forced refreshes to ensure tiles reload
            setTimeout(() => {
                if (this.map) {
                    this.map.invalidateSize(true);
                    this.map._resetView();
                }
            }, 50);
            
            setTimeout(() => {
                if (this.map) {
                    this.map.invalidateSize(true);
                }
            }, 200);
        },

        async selectCoordinates(lat, lng) {
            // Validate coordinates first
            if (!this.isValidCoordinate(lat, lng)) {
                console.error('Invalid coordinates selected:', lat, lng);
                this.error = 'Koordinat tidak valid';
                return;
            }

            this.loading = true;
            this.error = null;

            try {
                // Use Nominatim for reverse geocoding
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=id`
                );
                
                if (!response.ok) {
                    throw new Error('Gagal mendapatkan alamat');
                }
                
                const data = await response.json();
                const address = data.display_name || `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

                this.selectedLocation = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng),
                    address: address
                };

                this.addMarker(lat, lng);
                
                // Force map refresh after selecting coordinates
                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                }, 150);
            } catch (e) {
                console.error('Error in reverse geocoding:', e);
                this.error = 'Gagal mendapatkan informasi alamat.';
                
                // Still save coordinates even if reverse geocoding fails
                this.selectedLocation = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng),
                    address: `${lat.toFixed(6)}, ${lng.toFixed(6)}`
                };
                
                this.addMarker(lat, lng);
                
                // Force map refresh even if geocoding fails
                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                }, 150);
            }

            this.loading = false;
        },

        async searchLocation() {
            if (!this.searchQuery.trim()) {
                this.searchResults = [];
                return;
            }

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}&countrycodes=id&limit=5&accept-language=id`
                );
                
                if (!response.ok) {
                    throw new Error('Gagal mencari lokasi');
                }
                
                const data = await response.json();
                this.searchResults = data || [];
            } catch (e) {
                console.error('Error searching location:', e);
                this.searchResults = [];
            }
        },

        selectSearchResult(result) {
            const lat = parseFloat(result.lat);
            const lng = parseFloat(result.lon);
            
            // Validate coordinates from search result
            if (!this.isValidCoordinate(lat, lng)) {
                console.error('Invalid coordinates from search result:', lat, lng);
                return;
            }
            
            this.selectedLocation = {
                lat: lat,
                lng: lng,
                address: result.display_name
            };
            
            this.addMarker(lat, lng);
            this.map.setView([lat, lng], 15);
            
            // Force map refresh after search result selection
            setTimeout(() => {
                if (this.map) {
                    this.map.invalidateSize();
                }
            }, 200);
            
            this.searchQuery = '';
            this.searchResults = [];
        },

        updateState() {
            this.$wire.set(this.statePath, this.selectedLocation);
        },

        // Cleanup method to prevent memory leaks
        destroy() {
            if (this.map) {
                // Remove all event listeners
                this.map.off();
                // Remove map instance
                this.map.remove();
                this.map = null;
            }
            if (this.marker) {
                this.marker = null;
            }
        },

        // Alpine.js lifecycle hook
        $cleanup() {
            this.destroy();
        }
    }
}
</script>
@endpush

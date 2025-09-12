@php
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $centerLat = $getCenterLat();
    $centerLng = $getCenterLng();
    $zoom = $getZoom();
    $searchable = $isSearchable();
    $height = $getHeight();
@endphp

<!-- Include Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
    crossorigin="" />

<!-- Include Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
    crossorigin=""></script>

<style>
.openstreetmap-location-picker {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

.openstreetmap-location-picker .map-container {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.openstreetmap-location-picker .leaflet-container {
    height: {{ $height }}px;
    width: 100%;
    background: #f8fafc;
}

.openstreetmap-location-picker .selected-location {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px;
    border-radius: 8px;
    margin-top: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.openstreetmap-location-picker .selected-location h4 {
    margin: 0 0 8px 0;
    font-weight: 600;
    font-size: 14px;
}

.openstreetmap-location-picker .selected-location p {
    margin: 0;
    font-size: 13px;
    opacity: 0.9;
}

.openstreetmap-location-picker .coordinates {
    font-family: 'Courier New', Courier, monospace;
    font-size: 12px;
    opacity: 0.8;
    margin-top: 4px;
}

.openstreetmap-location-picker .error-message {
    background: #fef2f2;
    color: #dc2626;
    padding: 12px;
    border-radius: 8px;
    margin-top: 12px;
    border-left: 4px solid #dc2626;
    font-size: 14px;
}

.openstreetmap-location-picker .loading {
    text-align: center;
    padding: 20px;
    color: #6b7280;
    font-size: 14px;
}

.openstreetmap-location-picker .loading::after {
    content: '';
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 2px solid #f3f4f6;
    border-radius: 50%;
    border-top-color: #3b82f6;
    animation: spin 1s ease-in-out infinite;
    margin-left: 8px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.openstreetmap-location-picker input {
    transition: all 0.2s ease-in-out;
}

.openstreetmap-location-picker input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.openstreetmap-location-picker .search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-top: none;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    max-height: 200px;
    overflow-y: auto;
}

.openstreetmap-location-picker .search-result-item {
    padding: 12px;
    cursor: pointer;
    border-bottom: 1px solid #f3f4f6;
    transition: background-color 0.2s ease-in-out;
}

.openstreetmap-location-picker .search-result-item:hover {
    background-color: #f8fafc;
}

.openstreetmap-location-picker .search-result-item:last-child {
    border-bottom: none;
}

/* Ensure Leaflet controls are properly styled */
.leaflet-control-zoom a,
.leaflet-control-attribution {
    color: #374151 !important;
}

.leaflet-popup-content-wrapper {
    border-radius: 8px;
}

/* Fix for blank/missing tiles */
.leaflet-container {
    background: #f8fafc !important;
    font: 12px/1.5 "Helvetica Neue", Arial, Helvetica, sans-serif !important;
}

.leaflet-tile-container {
    opacity: 1 !important;
}

.leaflet-tile {
    filter: none !important;
    opacity: 1 !important;
    visibility: visible !important;
    background-color: transparent;
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

/* Ensure tiles are visible and loaded properly */
.leaflet-layer {
    opacity: 1 !important;
    filter: none !important;
}

.leaflet-tile-loaded {
    opacity: 1 !important;
    visibility: visible !important;
}
</style>

<script>
// =============================================
// GLOBAL DEBUGGING AND UTILITY FUNCTIONS
// =============================================

// Development debug mode flag
window.MAP_DEBUG_MODE = {{ config('app.debug') ? 'true' : 'false' }};

// Global coordinate validation utility with detailed logging
function validateCoordinate(coord, context = 'unknown') {
    if (window.MAP_DEBUG_MODE) {
        console.group(`🔍 Coordinate Validation - ${context}`);
        console.log('Input coordinate:', coord);
    }
    
    const isValid = coord && 
           typeof coord === 'object' && 
           typeof coord.lat === 'number' && 
           typeof coord.lng === 'number' &&
           !isNaN(coord.lat) && 
           !isNaN(coord.lng) &&
           coord.lat >= -90 && coord.lat <= 90 &&
           coord.lng >= -180 && coord.lng <= 180;
    
    if (window.MAP_DEBUG_MODE) {
        console.log('Validation result:', isValid);
        if (!isValid) {
            console.error('❌ Invalid coordinate details:', {
                hasCoord: !!coord,
                isObject: typeof coord === 'object',
                hasLat: coord && typeof coord.lat === 'number',
                hasLng: coord && typeof coord.lng === 'number',
                latValid: coord && !isNaN(coord.lat) && coord.lat >= -90 && coord.lat <= 90,
                lngValid: coord && !isNaN(coord.lng) && coord.lng >= -180 && coord.lng <= 180
            });
        }
        console.groupEnd();
    }
    
    return isValid ? coord : { lat: -7.1192, lng: 112.4186 }; // Return default coordinates if invalid
}

// Enhanced Leaflet operation wrapper with detailed logging
function safeLeafletOperationWithDebug(operation, fallback = null, operationName = 'Unknown Operation') {
    if (window.MAP_DEBUG_MODE) {
        console.group(`🗺️ Leaflet Operation: ${operationName}`);
        console.time(operationName);
    }
    
    try {
        if (typeof operation !== 'function') {
            throw new Error('Operation must be a function');
        }
        
        const result = operation();
        
        if (window.MAP_DEBUG_MODE) {
            console.log('✅ Operation completed successfully');
            console.timeEnd(operationName);
            console.groupEnd();
        }
        
        return result;
    } catch (error) {
        console.error(`❌ Leaflet operation failed: ${operationName}`, error);
        
        if (window.MAP_DEBUG_MODE) {
            console.log('Operation details:', {
                operationName,
                errorMessage: error.message,
                stack: error.stack,
                hasLeaflet: typeof L !== 'undefined'
            });
            console.timeEnd(operationName);
            console.groupEnd();
        }
        
        if (typeof fallback === 'function') {
            try {
                return fallback();
            } catch (fallbackError) {
                console.error(`❌ Fallback operation also failed: ${operationName}`, fallbackError);
                if (window.MAP_DEBUG_MODE) {
                    console.groupEnd();
                }
            }
        } else {
            if (window.MAP_DEBUG_MODE) {
                console.groupEnd();
            }
        }
        return null;
    }
}

// =============================================
// FILAMENT COMPONENT COMPATIBILITY
// =============================================

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

// Make textareaFormComponent available globally for Alpine.js
window.textareaFormComponent = window.textareaFormComponent;

// Register with Alpine.js if available
if (typeof window.Alpine !== 'undefined') {
    window.Alpine.data('textareaFormComponent', window.textareaFormComponent);
} else {
    // If Alpine is not loaded yet, add it when Alpine is ready
    document.addEventListener('alpine:init', () => {
        if (window.Alpine && window.Alpine.data) {
            window.Alpine.data('textareaFormComponent', window.textareaFormComponent);
        }
    });
}

// =============================================
// MAIN COMPONENT FUNCTION
// =============================================

function openStreetMapLocationPicker(config) {
    if (window.MAP_DEBUG_MODE) {
        console.group('🚀 OpenStreetMap Location Picker Initialization');
        console.log('Config received:', config);
    }
    
    // Validate and sanitize config
    const safeConfig = {
        statePath: config.statePath || 'unknown',
        isDisabled: Boolean(config.isDisabled),
        centerLat: parseFloat(config.centerLat) || -7.1192,
        centerLng: parseFloat(config.centerLng) || 112.4186,
        zoom: parseInt(config.zoom) || 12,
        searchable: Boolean(config.searchable),
        initialLocation: config.initialLocation || null
    };
    
    // Validate center coordinates
    const centerCoords = validateCoordinate({
        lat: safeConfig.centerLat, 
        lng: safeConfig.centerLng
    }, 'initialization center');

    return {
        // =============================================
        // COMPONENT STATE
        // =============================================
        
        statePath: safeConfig.statePath,
        isDisabled: safeConfig.isDisabled,
        center: centerCoords,
        zoom: safeConfig.zoom,
        searchable: safeConfig.searchable,
        
        // Runtime state
        map: null,
        marker: null,
        tileLayer: null,
        fallbackTileUsed: false,
        loading: false,
        error: null,
        searchQuery: '',
        searchResults: [],
        selectedLocation: safeConfig.initialLocation,
        
        // =============================================
        // ALPINE.JS LIFECYCLE METHODS
        // =============================================
        
        init() {
            if (window.MAP_DEBUG_MODE) {
                console.group('🎯 Component Initialization');
                console.log('Component state:', {
                    statePath: this.statePath,
                    isDisabled: this.isDisabled,
                    center: this.center,
                    zoom: this.zoom,
                    searchable: this.searchable,
                    initialLocation: this.selectedLocation
                });
            }
            
            // Initialize the map
            this.initializeMap();
            
            // Set up state synchronization
            this.syncState();
            
            if (window.MAP_DEBUG_MODE) {
                console.log('✅ Component initialization completed');
                console.groupEnd();
            }
        },

        // =============================================
        // UTILITY METHODS
        // =============================================
        
        // Wrapper for safe Leaflet operations using global function
        safeLeafletOperation(operation, fallback, operationName) {
            return safeLeafletOperationWithDebug(operation, fallback, operationName);
        },

        // Use global coordinate validation
        isValidCoordinate(lat, lng, context = 'generic') {
            return validateCoordinate({ lat, lng }, context);
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

        syncState() {
            if (this.selectedLocation) {
                const coords = validateCoordinate(this.selectedLocation, 'sync state');
                if (coords) {
                    this.selectedLocation = coords;
                    // Sync with Filament state
                    this.$wire?.set(this.statePath, this.selectedLocation);
                }
            }
        },

        // =============================================
        // MAP INITIALIZATION
        // =============================================
        
        initializeMap() {
            if (window.MAP_DEBUG_MODE) {
                console.group('🗺️ Map Initialization');
                console.log('Starting map initialization...');
            }
            
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
                    if (window.MAP_DEBUG_MODE) {
                        console.log('🔄 Removing existing map instance...');
                    }
                    
                    this.safeLeafletOperation(() => {
                        if (this.map) {
                            this.map.off();
                            this.map.remove();
                        }
                    }, null, 'Remove Existing Map');
                    
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
                        if (window.MAP_DEBUG_MODE) {
                            console.log('🏗️ Creating new map instance...');
                        }
                        
                        // Initialize Leaflet map with proper options
                        this.map = this.safeLeafletOperation(() => {
                            return L.map(container, {
                                zoomControl: true,
                                scrollWheelZoom: true,
                                doubleClickZoom: true,
                                touchZoom: true,
                                preferCanvas: false
                            });
                        }, null, 'Create Map Instance');

                        if (!this.map) {
                            throw new Error('Failed to create map instance');
                        }

                        // Validate center coordinates before setting view
                        const initialCoords = validateCoordinate({ lat: this.center.lat, lng: this.center.lng });
                        
                        if (window.MAP_DEBUG_MODE) {
                            console.log('📍 Setting initial view:', initialCoords);
                        }

                        this.safeLeafletOperation(() => {
                            this.map.setView([initialCoords.lat, initialCoords.lng], this.zoom);
                        }, null, 'Set Initial View');

                        // Add tile layer with robust error handling
                        this.safeLeafletOperation(() => {
                            const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© OpenStreetMap contributors',
                                maxZoom: 19,
                                subdomains: ['a', 'b', 'c'],
                                crossOrigin: true,
                                opacity: 1.0,
                                keepBuffer: 2,
                                updateWhenIdle: false,
                                updateWhenZooming: true,
                                updateInterval: 200,
                                errorTileUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjU2IiBoZWlnaHQ9IjI1NiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjU2IiBoZWlnaHQ9IjI1NiIgZmlsbD0iI2Y4ZjlmYSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjNjY2Ij5UaWxlIEVycm9yPC90ZXh0Pjwvc3ZnPg=='
                            });

                            // Add tile loading event handlers
                            tileLayer.on('loading', () => {
                                if (window.MAP_DEBUG_MODE) console.log('🔄 Tiles loading...');
                            });

                            tileLayer.on('load', () => {
                                if (window.MAP_DEBUG_MODE) console.log('✅ All tiles loaded successfully');
                                this.loading = false;
                            });

                            tileLayer.on('tileerror', (e) => {
                                console.warn('⚠️ Tile loading error:', e);
                                
                                // Try alternative tile server if primary fails
                                if (!this.fallbackTileUsed) {
                                    console.log('🔄 Switching to fallback tile server...');
                                    this.fallbackTileUsed = true;
                                    
                                    setTimeout(() => {
                                        if (this.map) {
                                            // Remove current tile layer
                                            this.map.removeLayer(tileLayer);
                                            
                                            // Add fallback tile layer
                                            const fallbackLayer = L.tileLayer('https://tile.openstreetmap.de/{z}/{x}/{y}.png', {
                                                attribution: '© OpenStreetMap contributors',
                                                maxZoom: 18,
                                                opacity: 1.0
                                            }).addTo(this.map);
                                            
                                            this.tileLayer = fallbackLayer;
                                        }
                                    }, 500);
                                } else {
                                    // Just try to redraw if already using fallback
                                    setTimeout(() => {
                                        if (this.map && tileLayer) {
                                            tileLayer.redraw();
                                        }
                                    }, 1000);
                                }
                            });

                            // Add layer to map
                            tileLayer.addTo(this.map);
                            
                            // Store reference for later use
                            this.tileLayer = tileLayer;
                            
                            return tileLayer;
                        }, null, 'Add Tile Layer');

                        // Add existing marker if coordinates exist
                        if (this.hasValidCoordinates()) {
                            this.addMarker();
                        }

                        // Bind map events
                        this.bindMapEvents();

                        // Initialize search if searchable
                        if (this.searchable) {
                            this.initializeSearch();
                        }

                        if (window.MAP_DEBUG_MODE) {
                            console.log('✅ Map initialization completed successfully');
                            console.groupEnd();
                        }

                        // Multiple forced refreshes to ensure tiles reload properly
                        setTimeout(() => {
                            if (this.map) {
                                this.map.invalidateSize(true);
                                this.map.fire('resize');
                                if (window.MAP_DEBUG_MODE) console.log('🔄 First refresh completed');
                            }
                        }, 100);

                        setTimeout(() => {
                            if (this.map) {
                                this.map.invalidateSize(true);
                                this.map.fire('move');
                                if (this.tileLayer) {
                                    this.tileLayer.redraw();
                                }
                                if (window.MAP_DEBUG_MODE) console.log('🔄 Second refresh completed');
                            }
                        }, 300);

                        setTimeout(() => {
                            if (this.map) {
                                this.map.invalidateSize(true);
                                this.map.fire('zoomend');
                                if (window.MAP_DEBUG_MODE) console.log('🔄 Third refresh completed');
                            }
                        }, 500);

                    } catch (error) {
                        console.error('❌ Error during delayed map initialization:', error);
                        if (window.MAP_DEBUG_MODE) {
                            console.log('Debug Info:', {
                                container: container,
                                hasLeaflet: typeof L !== 'undefined',
                                centerCoords: this.center,
                                zoom: this.zoom
                            });
                            console.groupEnd();
                        }
                        
                        // Emit error event for parent component handling
                        this.$dispatch('map-error', {
                            message: error.message,
                            context: 'Delayed Initialization'
                        });
                    }
                }, 100); // 100ms delay

            } catch (error) {
                console.error('❌ Error during map initialization:', error);
                if (window.MAP_DEBUG_MODE) {
                    console.log('Initial Error Debug Info:', {
                        hasLeaflet: typeof L !== 'undefined',
                        hasContainer: !!this.$refs.mapContainer,
                        centerCoords: this.center
                    });
                    console.groupEnd();
                }
                
                // Emit error event for parent component handling
                this.$dispatch('map-error', {
                    message: error.message,
                    context: 'Initial Setup'
                });
            }
        },

        hasValidCoordinates() {
            return this.selectedLocation && validateCoordinate(this.selectedLocation, 'hasValidCoordinates check');
        },

        initializeSearch() {
            if (window.MAP_DEBUG_MODE) {
                console.group('🔍 Initializing Search');
            }
            
            // Initialize search functionality if searchable is enabled
            if (this.searchable && this.$refs.searchInput) {
                // Add search functionality here if needed
                if (window.MAP_DEBUG_MODE) {
                    console.log('✅ Search functionality ready');
                }
            }
            
            if (window.MAP_DEBUG_MODE) {
                console.groupEnd();
            }
        },

        addMarker(lat, lng) {
            if (window.MAP_DEBUG_MODE) {
                console.group('📍 Adding Marker');
                console.log('Input coordinates:', { lat, lng });
            }
            
            // Use current coordinates if none provided
            if (lat === undefined && lng === undefined && this.selectedLocation) {
                lat = this.selectedLocation.lat;
                lng = this.selectedLocation.lng;
            }
            
            // Validate coordinates before adding marker
            const coords = validateCoordinate({ lat, lng }, 'addMarker input');
            if (!coords) {
                console.error('❌ Invalid coordinates for marker:', lat, lng);
                if (window.MAP_DEBUG_MODE) {
                    console.groupEnd();
                }
                return;
            }

            this.safeLeafletOperation(() => {
                if (this.marker) {
                    this.map.removeLayer(this.marker);
                }

                this.marker = L.marker([coords.lat, coords.lng], {
                    draggable: !this.isDisabled
                }).addTo(this.map);

                if (!this.isDisabled) {
                    this.marker.on('dragend', (e) => {
                        try {
                            const latlng = e.target.getLatLng();
                            const dragCoords = validateCoordinate({ 
                                lat: latlng.lat, 
                                lng: latlng.lng 
                            }, 'marker drag end');
                            
                            if (dragCoords) {
                                this.selectCoordinates(dragCoords.lat, dragCoords.lng);
                            } else {
                                console.error('❌ Invalid drag coordinates:', latlng);
                            }
                        } catch (dragError) {
                            console.error('❌ Error handling marker drag:', dragError);
                        }
                    });
                }

                if (window.MAP_DEBUG_MODE) {
                    console.log('✅ Marker added successfully at:', coords);
                    console.groupEnd();
                }

                // Force map refresh after adding marker to prevent blank tiles
                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize(true);
                        // Force tile reload by triggering map events
                        this.map.fire('resize');
                        this.map.fire('move');
                        this.map.fire('zoomend');
                    }
                }, 50);
            }, null, 'Add Marker');
        },

        bindMapEvents() {
            if (window.MAP_DEBUG_MODE) {
                console.group('🔗 Binding Map Events');
            }
            
            if (!this.map) {
                console.error('❌ Cannot bind events: map not initialized');
                if (window.MAP_DEBUG_MODE) {
                    console.groupEnd();
                }
                return;
            }

            if (!this.isDisabled) {
                this.safeLeafletOperation(() => {
                    this.map.on('click', (e) => {
                        try {
                            const clickCoords = validateCoordinate({ 
                                lat: e.latlng.lat, 
                                lng: e.latlng.lng 
                            }, 'map click');
                            
                            if (clickCoords) {
                                this.selectCoordinates(clickCoords.lat, clickCoords.lng);
                            } else {
                                console.error('❌ Invalid click coordinates:', e.latlng);
                            }
                        } catch (clickError) {
                            console.error('❌ Error handling map click:', clickError);
                        }
                    });
                }, null, 'Bind Click Events');
            }

            if (window.MAP_DEBUG_MODE) {
                console.log('✅ Map events bound successfully');
                console.groupEnd();
            }
        },

        async selectCoordinates(lat, lng) {
            if (window.MAP_DEBUG_MODE) {
                console.group('📍 Selecting Coordinates');
                console.log('Input coordinates:', { lat, lng });
            }
            
            // Validate coordinates first
            const coords = validateCoordinate({ lat, lng }, 'selectCoordinates input');
            if (!coords) {
                console.error('❌ Invalid coordinates selected:', lat, lng);
                this.error = 'Koordinat tidak valid';
                if (window.MAP_DEBUG_MODE) {
                    console.groupEnd();
                }
                return;
            }

            this.loading = true;
            this.error = null;

            try {
                if (window.MAP_DEBUG_MODE) {
                    console.log('🌍 Fetching address via reverse geocoding...');
                }
                
                // Use Nominatim for reverse geocoding
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${coords.lat}&lon=${coords.lng}&accept-language=id`
                );
                
                if (!response.ok) {
                    throw new Error('Gagal mendapatkan alamat');
                }
                
                const data = await response.json();
                const address = data.display_name || `${coords.lat.toFixed(6)}, ${coords.lng.toFixed(6)}`;

                this.selectedLocation = {
                    lat: coords.lat,
                    lng: coords.lng,
                    address: address
                };

                this.safeLeafletOperation(() => {
                    this.addMarker(coords.lat, coords.lng);
                }, null, 'Add Marker After Selection');

                // Force map refresh after adding marker
                setTimeout(() => {
                    if (this.map) {
                        this.safeLeafletOperation(() => {
                            this.map.invalidateSize(true);
                            // Force tile reload
                            this.map.eachLayer((layer) => {
                                if (layer._url) { // This is a tile layer
                                    layer.redraw();
                                }
                            });
                        }, null, 'Refresh Map After Marker');
                    }
                }, 100);

                // Sync with Filament state
                this.$wire?.set(this.statePath, this.selectedLocation);

                if (window.MAP_DEBUG_MODE) {
                    console.log('✅ Coordinates selected successfully:', this.selectedLocation);
                    console.groupEnd();
                }

            } catch (e) {
                console.error('❌ Error in reverse geocoding:', e);
                this.error = 'Gagal mendapatkan informasi alamat.';
                
                // Still save coordinates even if reverse geocoding fails
                this.selectedLocation = {
                    lat: coords.lat,
                    lng: coords.lng,
                    address: `${coords.lat.toFixed(6)}, ${coords.lng.toFixed(6)}`
                };
                
                this.safeLeafletOperation(() => {
                    this.addMarker(coords.lat, coords.lng);
                }, null, 'Add Marker After Geocoding Error');

                // Force map refresh after adding marker (even on error)
                setTimeout(() => {
                    if (this.map) {
                        this.safeLeafletOperation(() => {
                            this.map.invalidateSize(true);
                            // Force tile reload
                            this.map.eachLayer((layer) => {
                                if (layer._url) { // This is a tile layer
                                    layer.redraw();
                                }
                            });
                        }, null, 'Refresh Map After Error Marker');
                    }
                }, 100);

                // Sync with Filament state
                this.$wire?.set(this.statePath, this.selectedLocation);

                if (window.MAP_DEBUG_MODE) {
                    console.log('⚠️ Coordinates saved despite geocoding error:', this.selectedLocation);
                    console.groupEnd();
                }
            }

            this.loading = false;
        },

        async searchLocation() {
            // Implement search functionality if needed
            console.log('Search functionality not yet implemented');
        }
    };
}

// Ensure the function is available globally immediately
window.openStreetMapLocationPicker = openStreetMapLocationPicker;

// Also ensure it's available after DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.openStreetMapLocationPicker = openStreetMapLocationPicker;
});

// And ensure it's available for Alpine.js
document.addEventListener('alpine:init', () => {
    window.openStreetMapLocationPicker = openStreetMapLocationPicker;
});
</script>

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="openStreetMapLocationPicker({
        statePath: '{{ $statePath }}',
        isDisabled: {{ json_encode($isDisabled) }},
        centerLat: {{ is_numeric($centerLat) ? $centerLat : -7.1192 }},
        centerLng: {{ is_numeric($centerLng) ? $centerLng : 112.4186 }},
        zoom: {{ is_numeric($zoom) ? $zoom : 12 }},
        searchable: {{ json_encode($searchable) }},
        @if ($getState() && is_array($getState()) && isset($getState()['lat']) && isset($getState()['lng']) && is_numeric($getState()['lat']) && is_numeric($getState()['lng']))
            initialLocation: {{ json_encode($getState()) }}
        @else
            initialLocation: null
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
                        x-ref="searchInput"
                    >
                    
                    <!-- Search results dropdown -->
                    <div x-show="searchResults.length > 0" class="search-results">
                        <template x-for="result in searchResults" :key="result.place_id">
                            <div 
                                class="search-result-item"
                                @click="selectSearchResult(result)"
                                x-text="result.display_name"
                            ></div>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <!-- Map container -->
        <div class="map-container" style="position: relative;">
            <div 
                x-ref="mapContainer" 
                class="w-full"
                style="height: {{ $height }}px; min-height: 300px;"
            ></div>
            
            <!-- Loading overlay -->
            <div x-show="loading" 
                class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center"
            >
                <div class="loading">Memuat peta...</div>
            </div>
            
            <!-- Error overlay -->
            <div x-show="error" 
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

/**
 * Authentication Token Management for IwakMart
 * Manages Bearer tokens across the application
 */

// Safe JSON parsing utility
function safeJsonParse(jsonString, fallback = null) {
    try {
        return JSON.parse(jsonString);
    } catch (error) {
        console.warn('JSON parsing failed:', error);
        return fallback;
    }
}

// Token utility functions
function getAuthToken() {
    return localStorage.getItem('auth_token') ||
           sessionStorage.getItem('auth_token') ||
           getCookie('auth_token');
}

function setAuthToken(token, remember = false) {
    if (remember) {
        localStorage.setItem('auth_token', token);
        sessionStorage.removeItem('auth_token');
    } else {
        sessionStorage.setItem('auth_token', token);
        localStorage.removeItem('auth_token');
    }

    // Also set as cookie for server-side access
    const cookieDays = remember ? 7 : 1;
    setCookie('auth_token', token, cookieDays);

    // Verify cookie was set
    const cookieValue = getCookie('auth_token');
    if (!cookieValue) {
        console.warn('Auth token cookie was not set properly');
    }
}

function clearAuthToken() {
    localStorage.removeItem('auth_token');
    sessionStorage.removeItem('auth_token');
    localStorage.removeItem('user_data');
    sessionStorage.removeItem('user_data');

    // Clear cookie too
    setCookie('auth_token', '', -1);
}

// Cookie utility functions
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }

    const encodedValue = encodeURIComponent(value || "");
    const cookieString = name + "=" + encodedValue + expires + "; path=/; SameSite=Lax";
    document.cookie = cookieString;
}

function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) {
            const encodedValue = c.substring(nameEQ.length, c.length);
            // Decode the URI component
            try {
                return decodeURIComponent(encodedValue);
            } catch (e) {
                console.error('Error decoding cookie value:', e);
                return encodedValue; // Return raw value if decoding fails
            }
        }
    }
    return null;
}

function clearAuthToken() {
    localStorage.removeItem('auth_token');
    sessionStorage.removeItem('auth_token');
    localStorage.removeItem('user_data');
    sessionStorage.removeItem('user_data');
}

function isAuthenticated() {
    return !!getAuthToken();
}

// Alias for backward compatibility
function getToken() {
    const token = getAuthToken();
    if (!token) {
        console.warn('No authentication token found');
        return null;
    }
    return token;
}

// Check if user is authenticated with valid token
function isLoggedIn() {
    const token = getToken();
    return token && token.length > 10; // Basic validation
}

function getUserData() {
    const userData = localStorage.getItem('user_data') || sessionStorage.getItem('user_data');
    return userData ? safeJsonParse(userData, null) : null;
}

function setUserData(userData, remember = false) {
    const dataString = JSON.stringify(userData);
    if (remember) {
        localStorage.setItem('user_data', dataString);
        sessionStorage.removeItem('user_data');
    } else {
        sessionStorage.setItem('user_data', dataString);
        localStorage.removeItem('user_data');
    }
}

// CSRF token utility
async function getCsrfToken() {
    try {
        // First try to get from meta tag
        const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (metaToken) {
            return metaToken;
        }

        // If not available, get from API
        const response = await fetch('/api/csrf-cookie', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            // Get CSRF token from cookie after the request
            const cookies = document.cookie.split(';');
            for (let cookie of cookies) {
                const [name, value] = cookie.trim().split('=');
                if (name === 'XSRF-TOKEN') {
                    return decodeURIComponent(value);
                }
            }
        }

        return '';
    } catch (error) {
        console.error('Failed to get CSRF token:', error);
        return '';
    }
}

// HTTP utilities with Bearer token and CSRF protection
async function authenticatedFetch(url, options = {}) {
    const token = getAuthToken();
    const csrfToken = await getCsrfToken();

    const isFormData = options.body instanceof FormData;

    const defaultHeaders = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };

    // Only set Content-Type for non-FormData requests
    if (!isFormData) {
        defaultHeaders['Content-Type'] = 'application/json';
    }

    // Add CSRF token for state-changing requests
    if (['POST', 'PUT', 'PATCH', 'DELETE'].includes((options.method || 'GET').toUpperCase())) {
        if (csrfToken) {
            defaultHeaders['X-CSRF-TOKEN'] = csrfToken;
        }
        // Also try meta tag as fallback
        const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (metaToken && !defaultHeaders['X-CSRF-TOKEN']) {
            defaultHeaders['X-CSRF-TOKEN'] = metaToken;
        }

        // For FormData, add CSRF token as form field too
        if (isFormData && csrfToken) {
            options.body.append('_token', csrfToken);
        }
    }

    if (token) {
        defaultHeaders['Authorization'] = `Bearer ${token}`;
    }

    const finalOptions = {
        credentials: 'same-origin', // Include cookies for CSRF protection
        ...options,
        headers: {
            ...defaultHeaders,
            ...options.headers
        }
    };

    try {
        const response = await fetch(url, finalOptions);

        // Handle CSRF token mismatch (419)
        if (response.status === 419) {
            console.log('CSRF token mismatch, retrying...');
            // Clear any cached token and retry once
            const newCsrfToken = await getCsrfToken();
            if (newCsrfToken && newCsrfToken !== csrfToken) {
                finalOptions.headers['X-CSRF-TOKEN'] = newCsrfToken;
                if (isFormData) {
                    // Update FormData with new token
                    finalOptions.body.delete('_token');
                    finalOptions.body.append('_token', newCsrfToken);
                }
                const retryResponse = await fetch(url, finalOptions);
                if (retryResponse.ok || retryResponse.status !== 419) {
                    return retryResponse;
                }
            }
            showAlert('CSRF token tidak valid. Silakan refresh halaman.', 'error');
            return null;
        }

        // Check if token is invalid (401 Unauthorized)
        if (response.status === 401) {
            clearAuthToken();

            // Redirect to login if not already on login page
            if (!window.location.pathname.includes('login') && !window.location.pathname.includes('register')) {
                showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'error');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            }
            return null;
        }

        return response;
    } catch (error) {
        console.error('Authenticated fetch error:', error);
        throw error;
    }
}

// Cart utilities with authentication
async function addToCart(productId, quantity = 1) {
    if (!isAuthenticated()) {
        showAlert('Silakan login terlebih dahulu untuk menambahkan ke keranjang', 'error');
        setTimeout(() => {
            window.location.href = '/login';
        }, 2000);
        return null;
    }

    try {
        const response = await authenticatedFetch('/api/cart/add', {
            method: 'POST',
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        });

        if (response && response.ok) {
            const data = await response.json();
            showAlert('Produk berhasil ditambahkan ke keranjang', 'success');
            return data;
        } else if (response) {
            const data = await response.json();
            showAlert(data.message || 'Gagal menambahkan ke keranjang', 'error');
        }

        return null;
    } catch (error) {
        console.error('Add to cart error:', error);
        showAlert('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
        return null;
    }
}

async function getCartItems() {
    if (!isAuthenticated()) {
        return [];
    }

    try {
        const response = await authenticatedFetch('/api/cart');

        if (response && response.ok) {
            const data = await response.json();
            return data.data || [];
        }

        return [];
    } catch (error) {
        console.error('Get cart items error:', error);
        return [];
    }
}

async function updateCartItem(cartItemId, quantity) {
    if (!isAuthenticated()) {
        return null;
    }

    try {
        const response = await authenticatedFetch(`/api/cart/${cartItemId}`, {
            method: 'PUT',
            body: JSON.stringify({
                quantity: quantity
            })
        });

        if (response && response.ok) {
            const data = await response.json();
            showAlert('Keranjang berhasil diperbarui', 'success');
            return data;
        } else if (response) {
            const data = await response.json();
            showAlert(data.message || 'Gagal memperbarui keranjang', 'error');
        }

        return null;
    } catch (error) {
        console.error('Update cart item error:', error);
        showAlert('Terjadi kesalahan saat memperbarui keranjang', 'error');
        return null;
    }
}

async function removeFromCart(cartItemId) {
    if (!isAuthenticated()) {
        return null;
    }

    try {
        const response = await authenticatedFetch(`/api/cart/${cartItemId}`, {
            method: 'DELETE'
        });

        if (response && response.ok) {
            const data = await response.json();
            showAlert('Item berhasil dihapus dari keranjang', 'success');
            return data;
        } else if (response) {
            const data = await response.json();
            showAlert(data.message || 'Gagal menghapus dari keranjang', 'error');
        }

        return null;
    } catch (error) {
        console.error('Remove from cart error:', error);
        showAlert('Terjadi kesalahan saat menghapus dari keranjang', 'error');
        return null;
    }
}

// Logout function - supports both API and session-based auth
async function logout() {
    const token = getAuthToken();

    // Try API logout first if we have a token
    if (token) {
        try {
            console.log('Attempting API logout...');
            await authenticatedFetch('/api/logout', {
                method: 'POST'
            });
            console.log('API logout successful');
        } catch (error) {
            console.error('Logout API error:', error);
        }
    }

    // Always try session-based logout for web auth
    try {
        const response = await fetch('/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            console.warn('Session logout failed with status:', response.status);
        }
    } catch (error) {
        console.error('Session logout error:', error);
    }

    // Clear all tokens regardless of logout success
    clearAuthToken();
    showAlert('Logout berhasil', 'success');

    setTimeout(() => {
        window.location.href = '/login';
    }, 1500);
}

// Confirm logout function for user interaction
function confirmLogout() {
    if (confirm('Apakah Anda yakin ingin keluar dari akun?')) {
        logout();
        return true;
    }
    return false;
}

// Universal alert function
function createAlert(message, type = 'info') {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;

    const iconMap = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-circle',
        'warning': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle'
    };

    alertDiv.innerHTML = `
        <i class="${iconMap[type] || iconMap['info']}"></i>
        <span>${message}</span>
    `;

    // Add styles if not already present
    if (!document.querySelector('#alert-styles')) {
        const style = document.createElement('style');
        style.id = 'alert-styles';
        style.textContent = `
            .alert {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 16px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 14px;
                font-weight: 500;
                z-index: 10000;
                animation: slideInRight 0.3s ease-out;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }
            .alert-success { background-color: #e8f5e8; color: #2e7d32; border-left: 4px solid #4caf50; }
            .alert-error { background-color: #ffeaea; color: #c62828; border-left: 4px solid #f44336; }
            .alert-warning { background-color: #fff8e1; color: #f57c00; border-left: 4px solid #ff9800; }
            .alert-info { background-color: #e3f2fd; color: #1565c0; border-left: 4px solid #2196f3; }
            @keyframes slideInRight {
                from { opacity: 0; transform: translateX(100%); }
                to { opacity: 1; transform: translateX(0); }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(alertDiv);

    // Remove alert after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Global showAlert function
function showAlert(message, type = 'info') {
    // Check if we're on login page and use local showAlert
    if (typeof window.localShowAlert === 'function') {
        return window.localShowAlert(message, type);
    }

    // Otherwise use the global alert function
    return createAlert(message, type);
}

// Make functions globally available
window.getAuthToken = getAuthToken;
window.setAuthToken = setAuthToken;
window.clearAuthToken = clearAuthToken;
window.isAuthenticated = isAuthenticated;
window.getUserData = getUserData;
window.setUserData = setUserData;
window.authenticatedFetch = authenticatedFetch;
window.addToCart = addToCart;
window.getCartItems = getCartItems;
window.updateCartItem = updateCartItem;
window.removeFromCart = removeFromCart;
window.logout = logout;
window.confirmLogout = confirmLogout;
window.showAlert = showAlert;

// Auto-check authentication on page load
document.addEventListener('DOMContentLoaded', function() {
    // Only do token management, no automatic redirects
    if (isAuthenticated()) {
        const userData = getUserData();
        if (userData) {
            console.log('User authenticated:', userData.name);
        }

        // Update cookie to ensure it's set for web routes
        const token = getAuthToken();
        if (token) {
            const isRemembered = localStorage.getItem('auth_token') !== null;
            setCookie('auth_token', token, isRemembered ? 30 : 1); // Use 1 day instead of 0
            console.log('Token cookie updated for web routes');
        }
    }

    // Log current auth status for debugging
    console.log('Auth check - Current path:', window.location.pathname);
    console.log('Auth check - Is authenticated:', isAuthenticated());
    console.log('Auth check - Token exists:', !!getAuthToken());
});

/**
 * Safely parse JSON response with proper error handling
 * This prevents "Unexpected token '<'" errors when server returns HTML error pages
 */
async function safeParseJSON(response) {
    try {
        // Check if response has JSON content type
        const contentType = response.headers.get('Content-Type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            throw new Error(`Expected JSON but received ${contentType || 'unknown content type'}. Response: ${text.substring(0, 200)}`);
        }

        return await response.json();
    } catch (error) {
        // If JSON parsing fails, try to get the text content for better error reporting
        if (error.name === 'SyntaxError' || error.message.includes('Unexpected token')) {
            try {
                const text = await response.text();
                throw new Error(`Invalid JSON response. Server returned: ${text.substring(0, 200)}...`);
            } catch (textError) {
                throw new Error(`Failed to parse response as JSON and couldn't read as text: ${error.message}`);
            }
        }
        throw error;
    }
}

/**
 * Improved fetch wrapper with better error handling
 */
async function apiRequest(url, options = {}) {
    try {
        const token = getToken();
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            ...options.headers
        };

        if (token) {
            headers['Authorization'] = 'Bearer ' + token;
        }

        const response = await fetch(url, {
            ...options,
            headers
        });

        const data = await safeParseJSON(response);

        if (!response.ok) {
            throw new Error(data.message || `HTTP ${response.status}: ${response.statusText}`);
        }

        return { response, data };
    } catch (error) {
        console.error('API Request failed:', error);
        throw error;
    }
}

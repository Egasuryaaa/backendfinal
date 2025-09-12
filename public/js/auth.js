/**
 * Authentication Token Management for IwakMart
 * Manages Bearer tokens across the application
 */

// Token utility functions
function getAuthToken() {
    return localStorage.getItem('auth_token') || 
           sessionStorage.getItem('auth_token') || 
           getCookie('auth_token');
}

function setAuthToken(token, remember = false) {
    console.log('setAuthToken called with token:', token ? token.substring(0, 10) + '...' : 'null');
    console.log('setAuthToken remember:', remember);
    
    if (remember) {
        localStorage.setItem('auth_token', token);
        sessionStorage.removeItem('auth_token');
        console.log('Token saved to localStorage, removed from sessionStorage');
    } else {
        sessionStorage.setItem('auth_token', token);
        localStorage.removeItem('auth_token');
        console.log('Token saved to sessionStorage, removed from localStorage');
    }
    
    // Also set as cookie for web route authentication
    const cookieDays = remember ? 30 : 1; // Use 1 day instead of 0 for session
    setCookie('auth_token', token, cookieDays);
    console.log('Token saved to cookie with days:', cookieDays);
    
    // Verify cookie was set immediately
    setTimeout(() => {
        const cookieValue = getCookie('auth_token');
        console.log('Cookie verification:', cookieValue ? 'Found' : 'NOT FOUND');
        if (cookieValue) {
            console.log('Cookie value preview:', cookieValue.substring(0, 10) + '...');
        }
    }, 10);
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
    if (days && days > 0) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    
    // Ensure value is properly encoded
    const encodedValue = encodeURIComponent(value || "");
    const cookieString = name + "=" + encodedValue + expires + "; path=/; SameSite=Lax";
    document.cookie = cookieString;
    console.log('setCookie called:', name, 'with expires:', expires ? expires : 'session');
    console.log('Cookie string set:', cookieString.substring(0, 50) + '...');
    console.log('Original value length:', (value || "").length);
    console.log('Encoded value length:', encodedValue.length);
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

function getUserData() {
    const userData = localStorage.getItem('user_data') || sessionStorage.getItem('user_data');
    return userData ? JSON.parse(userData) : null;
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

// HTTP utilities with Bearer token
async function authenticatedFetch(url, options = {}) {
    const token = getAuthToken();
    
    const defaultHeaders = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    };
    
    if (token) {
        defaultHeaders['Authorization'] = `Bearer ${token}`;
    }
    
    const finalOptions = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...options.headers
        }
    };
    
    try {
        const response = await fetch(url, finalOptions);
        
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
        console.log('Attempting session logout...');
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
        
        if (response.ok) {
            console.log('Session logout successful');
        } else {
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

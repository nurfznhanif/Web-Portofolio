import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Enhanced CSRF Token handling
function setupCSRFToken() {
    // Try to get CSRF token from meta tag first
    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
        return token.content;
    }

    // Fallback: try to get from hidden input
    const tokenInput = document.querySelector('input[name="_token"]');
    if (tokenInput) {
        const tokenValue = tokenInput.value;

        // Create meta tag if it doesn't exist
        const metaTag = document.createElement("meta");
        metaTag.name = "csrf-token";
        metaTag.content = tokenValue;
        document.head.appendChild(metaTag);

        window.axios.defaults.headers.common["X-CSRF-TOKEN"] = tokenValue;
        return tokenValue;
    }

    console.warn(
        "CSRF token not found. Please ensure the CSRF meta tag is present in your HTML head."
    );
    return null;
}

// Setup CSRF token on page load
setupCSRFToken();

// Re-setup CSRF token when navigating with Inertia (SPA behavior)
document.addEventListener("inertia:success", () => {
    setupCSRFToken();
});

// Add request interceptor to ensure CSRF token is always present
window.axios.interceptors.request.use(
    function (config) {
        // Ensure CSRF token is set for state-changing requests
        if (["post", "put", "patch", "delete"].includes(config.method)) {
            const token = document.head.querySelector(
                'meta[name="csrf-token"]'
            );
            if (token) {
                config.headers["X-CSRF-TOKEN"] = token.content;
            }
        }
        return config;
    },
    function (error) {
        return Promise.reject(error);
    }
);

// Add response interceptor to handle CSRF token mismatch
window.axios.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        if (error.response && error.response.status === 419) {
            console.error("CSRF token mismatch. Reloading page...");
            // Optionally reload the page to get a fresh CSRF token
            window.location.reload();
        }
        return Promise.reject(error);
    }
);

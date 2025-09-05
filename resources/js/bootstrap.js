import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// CSRF Token handling with better error checking
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    // Create CSRF token meta tag if not exists
    const metaTag = document.createElement("meta");
    metaTag.name = "csrf-token";
    metaTag.content =
        document.querySelector('input[name="_token"]')?.value || "";
    document.head.appendChild(metaTag);

    if (metaTag.content) {
        window.axios.defaults.headers.common["X-CSRF-TOKEN"] = metaTag.content;
    } else {
        console.warn(
            "CSRF token not found. Please ensure the CSRF meta tag is present in your HTML head."
        );
    }
}

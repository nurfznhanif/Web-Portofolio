import axios from "axios";

// Create axios instance untuk Laravel 12
const api = axios.create({
    baseURL: "/api",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: true, // Important untuk Laravel 12 session-based auth
});

// Get CSRF token dari meta tag
const getCSRFToken = () => {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute("content") : null;
};

// Add request interceptor untuk CSRF token
api.interceptors.request.use(
    (config) => {
        const token = getCSRFToken();
        if (token) {
            config.headers["X-CSRF-TOKEN"] = token;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add response interceptor untuk handle errors
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Redirect ke admin login jika tidak authenticated
            window.location.href = "/admin";
        } else if (error.response?.status === 419) {
            // CSRF token mismatch - reload page
            window.location.reload();
        }
        return Promise.reject(error);
    }
);

// Auth methods untuk Laravel 12
export const auth = {
    login: (credentials) => api.post("/login", credentials),
    logout: () => api.post("/logout"),
    check: () => api.get("/auth/check"),
};

// Profile methods
export const profile = {
    get: () => api.get("/profile"),
    update: (data) => api.put("/profile", data),
};

// Experience methods
export const experiences = {
    getAll: () => api.get("/experiences"),
    create: (data) => api.post("/experiences", data),
    update: (id, data) => api.put(`/experiences/${id}`, data),
    delete: (id) => api.delete(`/experiences/${id}`),
};

// Skill methods
export const skills = {
    getAll: () => api.get("/skills"),
    create: (data) => api.post("/skills", data),
    update: (id, data) => api.put(`/skills/${id}`, data),
    delete: (id) => api.delete(`/skills/${id}`),
};

// Project methods
export const projects = {
    getAll: () => api.get("/projects"),
    get: (id) => api.get(`/projects/${id}`),
    create: (data) => api.post("/projects", data),
    update: (id, data) => api.put(`/projects/${id}`, data),
    delete: (id) => api.delete(`/projects/${id}`),
};

export default {
    auth,
    profile,
    experiences,
    skills,
    projects,
};

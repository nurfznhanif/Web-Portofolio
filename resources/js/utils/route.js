// resources/js/utils/route.js - FIXED VERSION tanpa duplicate export
// Simple route helper jika tidak menggunakan Ziggy

const routes = {
    // Dashboard
    dashboard: "/dashboard",
    "admin.dashboard": "/admin/dashboard",

    // Portfolio
    "admin.portfolio.index": "/admin/portfolio",
    "admin.portfolio.create": "/admin/portfolio/create",
    "admin.portfolio.store": "/admin/portfolio",
    "admin.portfolio.show": (id) => `/admin/portfolio/${id}`,
    "admin.portfolio.edit": (id) => `/admin/portfolio/${id}/edit`,
    "admin.portfolio.update": (id) => `/admin/portfolio/${id}`,
    "admin.portfolio.destroy": (id) => `/admin/portfolio/${id}`,

    // Experience
    "admin.experience.index": "/admin/experience",
    "admin.experience.create": "/admin/experience/create",
    "admin.experience.store": "/admin/experience",
    "admin.experience.edit": (id) => `/admin/experience/${id}/edit`,
    "admin.experience.update": (id) => `/admin/experience/${id}`,
    "admin.experience.destroy": (id) => `/admin/experience/${id}`,

    // Skills
    "admin.skill.index": "/admin/skill",
    "admin.skill.create": "/admin/skill/create",
    "admin.skill.store": "/admin/skill",
    "admin.skill.edit": (id) => `/admin/skill/${id}/edit`,
    "admin.skill.update": (id) => `/admin/skill/${id}`,
    "admin.skill.destroy": (id) => `/admin/skill/${id}`,

    // Messages
    "admin.messages.index": "/admin/messages",
    "admin.messages.show": (id) => `/admin/messages/${id}`,
    "admin.messages.mark-read": (id) => `/admin/messages/${id}/mark-read`,
    "admin.messages.mark-replied": (id) => `/admin/messages/${id}/mark-replied`,
    "admin.messages.destroy": (id) => `/admin/messages/${id}`,
    "admin.messages.bulk-actions": "/admin/messages/bulk-actions",
    "admin.messages.reply": (id) => `/admin/messages/${id}/reply`,
    "admin.messages.export": "/admin/messages/export",

    // Analytics
    "admin.analytics.index": "/admin/analytics",
    "admin.analytics.dashboard": "/admin/analytics/dashboard",
    "admin.analytics.visitors": "/admin/analytics/visitors",
    "admin.analytics.projects": "/admin/analytics/projects",
    "admin.analytics.contact-forms": "/admin/analytics/contact-forms",
    "admin.analytics.downloads": "/admin/analytics/downloads",
    "admin.analytics.export": "/admin/analytics/export",
    "admin.analytics.api.stats": "/admin/analytics/api/stats",
    "admin.analytics.api.chart-data": "/admin/analytics/api/chart-data",

    // Profile
    "profile.edit": "/profile",
    "profile.update": "/profile",
    "profile.destroy": "/profile",
    "admin.profile.edit": "/admin/profile",
    "admin.profile.index": "/admin/profile",

    // System
    "admin.system.index": "/admin/system",
    "admin.system.cache": "/admin/system/cache",
    "admin.system.clear-cache": "/admin/system/clear-cache",
    "admin.system.backup": "/admin/system/backup",
    "admin.system.optimize-database": "/admin/system/optimize-database",

    // API
    "admin.api.quick-stats": "/admin/api/quick-stats",
    "admin.api.search": "/admin/api/search",

    // Auth
    login: "/login",
    logout: "/logout",
    register: "/register",
};

// Basic route function
function routeFunction(name, params = null) {
    // Handle undefined route names
    if (!name) {
        console.warn("Route name is undefined or null");
        return "#";
    }

    let url = routes[name];

    if (!url) {
        console.warn(`Route '${name}' not found`);
        return "#";
    }

    // If it's a function (has parameters)
    if (typeof url === "function") {
        return url(params);
    }

    return url;
}

// Function to check current route
function routeCurrent(routeName = null, params = null) {
    const currentPath = window.location.pathname;

    if (!routeName) {
        // Return current route name by matching path
        for (const [name, path] of Object.entries(routes)) {
            if (typeof path === "string" && path === currentPath) {
                return name;
            }
        }
        return null;
    }

    // Check if current route matches the given route name
    const targetRoute = routeFunction(routeName, params);
    return currentPath === targetRoute;
}

// Create enhanced route function with current method
const routeHelper = function (name, params = null) {
    return routeFunction(name, params);
};

// Add current as a property of the function
routeHelper.current = routeCurrent;

// Make it available globally
window.route = routeHelper;

// Single export
export { routeHelper as route };
export default routeHelper;

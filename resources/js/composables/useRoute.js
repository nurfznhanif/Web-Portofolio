// resources/js/composables/useRoute.js - UPDATED SAFE VERSION
import { inject } from "vue";
import { route as routeHelper } from "../utils/route";

export function useRoute() {
    // Try to get route from injection first, fallback to helper
    const injectedRoute = inject("route", null);

    if (injectedRoute) {
        return injectedRoute;
    }

    // Return safe wrapper of route helper
    const safeRoute = (name, params = null) => {
        try {
            return routeHelper(name, params);
        } catch (error) {
            console.warn("Route helper error:", error);
            return "#";
        }
    };

    // Add current method safely
    safeRoute.current = (routeName = null, params = null) => {
        try {
            return routeHelper.current
                ? routeHelper.current(routeName, params)
                : false;
        } catch (error) {
            console.warn("Route current check error:", error);
            return false;
        }
    };

    return safeRoute;
}

export default useRoute;

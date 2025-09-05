// resources/js/app.js - GANTI SELURUH ISI FILE INI
import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

// Import route helper dan buat global
import { route } from "./utils/route";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) }).use(plugin);

        // Make route function available globally in Vue components
        app.config.globalProperties.route = route;
        app.provide("route", route);

        // Also make it available on window for debugging
        window.route = route;

        return app.mount(el);
    },
    progress: {
        color: "#4F46E5",
    },
});

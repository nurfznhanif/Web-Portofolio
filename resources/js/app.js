import "./bootstrap";
import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import Portfolio from "./views/Portfolio.vue";
import Admin from "./views/Admin.vue";
import "../css/app.css";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: "/", name: "Portfolio", component: Portfolio },
        { path: "/admin", name: "Admin", component: Admin },
    ],
});

createApp({}).use(router).mount("#app");

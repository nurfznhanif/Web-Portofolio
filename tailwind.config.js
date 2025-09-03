/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/**/*.ts",
    ],
    theme: {
        extend: {
            fontFamily: {
                cinzel: ["Cinzel", "serif"],
                crimson: ["Crimson Text", "serif"],
            },
            colors: {
                "gothic-red": "#dc2626",
                "gothic-dark": "#991b1b",
                "gothic-bg": "#1a1a1a",
            },
        },
    },
    plugins: [],
};

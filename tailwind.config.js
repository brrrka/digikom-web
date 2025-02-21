import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    safelist: ["bg-[url(/images/bg.png)]"],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Poppins", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "#A7C87A",
                secondary: "#ECE9E0",
                light: "#FFFDCC",
                "light-2": "#FAF9F9",
                "light-blue": "#EBF5FF",
                "green-digikom": "#617A40",
                "light-green": "#D7EEB9",
                "light-green-2": "#C4DCA4",
                "light-green-3": "#FBF8B5",
                "dark-green": "#7B9952",
                "dark-green-2": "#7E8F65",
                "dark-green-3": "#50524D",
                "dark-green-4": "#536836",
                "dark-green-4": "#8EA66D",
                "dark-green-5": "#718B6D",
                "dark-green-6": "#5D745A",
                "dark-digikom": "#3A3738",
                "red-digikom": "#F14E3A",
            },
            minWidth: {
                md: "768px",
                xl: "1600px",
            },
            minHeight: {
                125: "500px",
                150: "600px",
            },
            inset: {
                15: "60px",
            },
        },
    },

    plugins: [forms],
};

import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

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
                green: "#617A40",
                dark: "#3A3738",
                red: "#F14E3A",
            },
            backgroundImage: {
                "hero-pattern":
                    "url('" + process.env.MIX_ASSET_URL + "/images/Item5.jpg')",
            },
            minWidth: {
                md: "768px",
                xl: "1600px",
            },
        },
    },

    plugins: [forms],
};

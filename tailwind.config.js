/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/views/**/*.blade.php"],
    theme: {
        extend: {
            colors: {
                primary: "#000dff",
            },
            boxShadow: {
                zero: "0 0 0 0 rgba(0, 13, 255, 0.3)",
                focus: "0 0 0 3px rgba(0, 13, 255, 0.3)",
            },
        },
    },
    plugins: [],
};

import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import path from "path";
export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/views/react/app.jsx"],
            refresh: true,
        }),
        react(),
    ],
    resolve: {
        alias: {
            "@master": path.resolve(__dirname, "resources/views/react/systems/master"),
            "@tenant": path.resolve(__dirname, "resources/views/react/systems/tenant"),
            "@common": path.resolve(__dirname, "resources/views/react/common"),
        },
    },
});

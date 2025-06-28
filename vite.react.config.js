import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import path from "path";
export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/views/react/App.jsx"],
            refresh: true,
            buildDirectory: "react",
        }),
        react(),
    ],
    resolve: {
        alias: {
            "@reactMaster": path.resolve(__dirname, "resources/views/react/systems/master"),
            "@reactTenant": path.resolve(__dirname, "resources/views/react/systems/tenant"),
            "@reactCommon": path.resolve(__dirname, "resources/views/react/common"),

            "@assetsMaster": path.resolve(__dirname, "resources/assets/systems/master"),
            "@assetsTenant": path.resolve(__dirname, "resources/assets/systems/tenant"),
            "@assetsCommon": path.resolve(__dirname, "resources/assets/common"),
        },
    },
});

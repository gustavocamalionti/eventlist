import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import path from "path";

const commonLegacyInputs = [
    "common/scss/common_app.scss",
    "common/fonts/common_fonts.css",
    "common/css/common_app.css",
    "common/js/common_app.js",
    "common/plugins/js/common_plugins.js",
    "common/plugins/js/common_datatables.js",
    "common/errors/js/errors.js",
    "common/js/utils/filters.js",
];

const tenantAdminInputs = [
    "systems/tenant/modules/admin/fonts/fonts.css",
    "systems/tenant/modules/admin/css/admin.css",
    "systems/tenant/modules/admin/js/admin.js",
    "systems/tenant/modules/admin/plugins/plugins.js",
];

const tenantEspecificInputs = [
    "systems/tenant/modules/admin/pages/dashboard/css/dashboard.css",
    "systems/tenant/modules/admin/pages/dashboard/js/dashboard.js",
    "systems/tenant/modules/admin/pages/logs/js/log_audits_list.js",
    "systems/tenant/modules/admin/pages/logs/js/log_emails_list.js",
    "systems/tenant/modules/admin/pages/logs/js/log_webhooks_list.js",
    "systems/tenant/modules/admin/pages/logs/js/log_errors_list.js",
    "systems/tenant/modules/admin/pages/users/js/users_list.js",
    "systems/tenant/modules/admin/pages/users/js/users_maintenance.js",
    "systems/tenant/modules/admin/pages/profile/js/profile_maintenance.js",
];

const masterAdminInputs = [
    "systems/master/modules/admin/fonts/fonts.css",
    "systems/master/modules/admin/css/admin.css",
    "systems/master/modules/admin/js/admin.js",
    "systems/master/modules/admin/plugins/plugins.js",
];

const masterEspecificInputs = [
    "systems/master/modules/admin/pages/dashboard/css/dashboard.css",
    "systems/master/modules/admin/pages/dashboard/js/dashboard.js",
    "systems/master/modules/admin/pages/logs/js/log_audits_list.js",
    "systems/master/modules/admin/pages/logs/js/log_emails_list.js",
    "systems/master/modules/admin/pages/logs/js/log_webhooks_list.js",
    "systems/master/modules/admin/pages/logs/js/log_errors_list.js",
    "systems/master/modules/admin/pages/users/js/users_list.js",
    "systems/master/modules/admin/pages/users/js/users_maintenance.js",
    "systems/master/modules/admin/pages/profile/js/profile_maintenance.js",

    "systems/master/modules/admin/pages/configs/js/parameters.js",
    "systems/master/modules/admin/pages/configs/js/colors.js",
    "systems/master/modules/admin/pages/configs/js/contents.js",
];

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/views/react/App.jsx",
                ...commonLegacyInputs,
                ...tenantAdminInputs,
                ...tenantEspecificInputs,
                ...masterAdminInputs,
                ...masterEspecificInputs,
            ].map((file) =>
                file.startsWith("resources/")
                    ? path.resolve(__dirname, file)
                    : path.resolve(__dirname, "resources/assets", file),
            ),
            refresh: true,
        }),
        react(),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                includePaths: ["node_modules/@fortawesome/fontawesome-free/scss"],
            },
        },
    },
    resolve: {
        alias: {
            "@reactMaster": path.resolve(__dirname, "resources/views/react/systems/master"),
            "@reactTenant": path.resolve(__dirname, "resources/views/react/systems/tenant"),
            "@reactCommon": path.resolve(__dirname, "resources/views/react/common"),
            $: "jQuery",
            "@assetsMaster": path.resolve(__dirname, "resources/assets/systems/master"),
            "@assetsTenant": path.resolve(__dirname, "resources/assets/systems/tenant"),
            "@assetsCommon": path.resolve(__dirname, "resources/assets/common"),
        },
    },
});

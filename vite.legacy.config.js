import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

const common = [
    "common/fonts/common_fonts.css",
    "common/css/LegacyApp.css",
    "common/js/common_app.js",
    "common/plugins/js/common_plugins.js",
    "common/plugins/js/common_datatables.js",
    "common/errors/js/errors.js",
    "common/js/utils/filters.js",
];

// const panel = ["panel/css/app_panel.css", "panel/js/app_panel.js", "panel/js/plugins_panel.js"];

// const panel_especific = [
//     "panel/fonts/css/fonts_panel.css",

//     "panel/pages/auth/js/users.js",

//     "panel/pages/dashboard/css/dashboard.css",
//     "panel/pages/dashboard/js/dashboard.js",

//     "panel/pages/banners/css/banners_maintenance.css",
//     "panel/pages/banners/js/banners_list.js",
//     "panel/pages/banners/js/banners_maintenance.js",

//     "panel/pages/stores/js/stores_list.js",
//     "panel/pages/stores/js/stores_maintenance.js",

//     "panel/pages/links/js/links_list.js",
//     "panel/pages/links/js/links_maintenance.js",

//     "panel/pages/forms/js/form_configs_list.js",
//     "panel/pages/forms/js/form_configs_maintenance.js",

//     "panel/pages/forms/js/form_contacts_list.js",

//     "panel/pages/event/js/buys_list.js",
//     "panel/pages/event/js/vouchers_list.js",

//     "panel/pages/logs/js/log_audits_list.js",
//     "panel/pages/logs/js/log_emails_list.js",
//     "panel/pages/logs/js/log_errors_list.js",
//     "panel/pages/logs/js/log_webhooks_list.js",

//     "panel/pages/parameters/js/parameters_list.js",

//     "panel/pages/users/js/users_list.js",
//     "panel/pages/users/js/users_maintenance.js",
// ];

//  plugins: [
//         laravel({
//             input: [...common, ...panel, ...panel_especific].map((file) => path.resolve(`resources/assets/${file}`)),
//             refresh: true,
//         }),
//     ],

export default defineConfig({
    server: {
        port: 5173,
    },
    plugins: [
        laravel({
            input: [...common].map((file) => path.resolve(`resources/assets/${file}`)),
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            $: "jQuery",
        },
    },
});

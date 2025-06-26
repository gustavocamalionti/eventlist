<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ $parameters->page_title }} | {{ $pageTitle }}</title>
        {{--
            <link rel="shortcut icon" href="{{ Vite::asset('resources/assets/common/images/content/favicon.png') }}"
            type="image/x-icon" />
        --}}
        @vite(
            [
                /**
                 * COMMON CSS
                 */
                "resources/assets/common/scss/common_app.scss",
                "resources/assets/common/css/common_app.css",
                "resources/assets/common/fonts/common_fonts.css",

                /**
                 * PANEL GENERAL/ESPECIFIC CSS
                 */
                "resources/assets/systems/tenant/modules/admin/fonts/tenant_admin_fonts.css",
                "resources/assets/systems/tenant/modules/admin/css/tenant_admin.css",
            ],
            "legacy"
        )
        {!! $customizations["styles"] !!}

        @yield("styles")
    </head>

    <body>
        <header>
            @include("legacy.systems.tenant.modules.admin.layouts.header")
        </header>

        <main class="container mt-5 mb-4">
            @yield("content")
        </main>

        <footer>
            @include("legacy.systems.tenant.modules.admin.layouts.footer")
        </footer>

        @vite(
            [
                /**
                 * COMMON JS
                 */
                "resources/assets/common/js/common_app.js",
                "resources/assets/common/plugins/js/common_plugins.js",
                "resources/assets/common/plugins/js/common_datatables.js",
                /**
                 * PANEL GENERAL/ESPECIFIC JS
                 */
                "resources/assets/systems/tenant/modules/admin/js/tenant_admin.js",
                "resources/assets/systems/tenant/modules/admin/plugins/tenant_admin_plugins.js",
            ],
            "legacy"
        )
        @yield("scripts")
    </body>
</html>

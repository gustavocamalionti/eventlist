<!DOCTYPE html>
<html class="no-js" lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ env("APP_NAME") }}</title>
        <meta name="description" content="{{ env("APP_NAME") }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        {{--
            <link
            rel="shortcut icon"
            href="{{ Vite::asset("resources/assets/common/images/content/favicon.png") }}"
            type="image/x-icon" />
        --}}
        <link
            href="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/css/swiffy-slider.min.css"
            rel="stylesheet"
            crossorigin="anonymous" />
        {{--
            @vite([
            /**
            * COMMON CSS
            */
            'resources/assets/common/sass/app_common.scss',
            'resources/assets/common/css/app_common.css',
            'resources/assets/common/fonts/css/fonts_common.css',
            
            /**
            * SITE GENERAL/ESPECIFIC CSS
            */
            'resources/assets/site/fonts/css/fonts_site.css',
            'resources/assets/site/sass/app_site.scss',
            'resources/assets/site/css/app_site.css',
            ])
        --}}
        {!! $customizations["styles"] !!}
        @yield("styles")
    </head>

    <body>
        <header>
            @include("legacy.common.layouts._header")
        </header>

        <main>
            @yield("content")
        </main>

        <footer class="">
            @include("legacy.common.layouts._footer")
        </footer>

        <script
            src="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/js/swiffy-slider.min.js"
            crossorigin="anonymous"
            defer></script>
        {{--
            @vite([
            /**
            * COMMON JS
            */
            'resources/assets/common/js/app_common.js',
            'resources/assets/common/js/plugins_common.js',
            
            /**
            * SITE GENERAL/ESPECIFIC JS
            */
            'resources/assets/site/js/app_site.js',
            'resources/assets/site/js/plugins_site.js',
            ])
        --}}

        @yield("scripts")
    </body>
</html>

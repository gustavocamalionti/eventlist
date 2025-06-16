<nav class="navbar navbar-expand-xl navbar-light shadow-sm">
    <div class="container-fluid me-5 ms-5 pt-2 pb-2">
        <!-- Logo com tamanho fixo -->
        <div class="flex-shrink-0 effect-reveal-to-right"></div>

        <!-- Botão do menu responsivo -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="{{ __("Toggle navigation") }}">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Menu com comportamento responsivo -->
        <div class="collapse navbar-collapse mt-2" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto w-100 d-flex justify-content-center align-items-center flex-grow-1 flex-wrap">
                {{-- @include('site.includes.navbar_items') --}}
            </ul>

            <!-- Redes sociais com comportamento fixo -->
            {{--
                <ul
                class="navbar-nav d-flex flex-row justify-content-center align-items-center gap-3 flex-shrink-0 effect-reveal-to-left">
                @guest
                <li class="nav-item">
                <a class="nav-link" href="{{ getLinksSlug(App\Libs\Enums\EnumLinksSlug::FACEBOOK) }}">
                <img src="{{ Vite::asset('resources/assets/common/images/social/facebook.png') }}"
                alt="Facebook" class="img-fluid" style="max-height: 30px" />
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="{{ getLinksSlug(App\Libs\Enums\EnumLinksSlug::INSTAGRAM) }}">
                <img src="{{ Vite::asset('resources/assets/common/images/social/instagram.png') }}"
                alt="Instagram" class="img-fluid" style="max-height: 30px" />
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="{{ getLinksSlug(App\Libs\Enums\EnumLinksSlug::IFOOD) }}">
                <img src="{{ Vite::asset('resources/assets/common/images/social/ifood.png') }}" alt="iFood"
                class="img-fluid" style="max-height: 30px" />
                </a>
                </li>
                @endguest
                </ul>
            --}}
        </div>
    </div>
</nav>

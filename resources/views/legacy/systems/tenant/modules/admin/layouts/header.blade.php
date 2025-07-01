<nav class="navbar navbar-expand-md navbar-light bg-dark shadow-default" data-bs-theme="dark" style="z-index: 9">
    <div class="container">
        {{--
            <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ Vite::asset('resources/assets/site/images/home/logo.png') }}" alt="" class="img-fluid"
            style="max-width: 90px" />
            </a>
        --}}
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="{{ __("Toggle navigation") }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto w-100 d-flex justify-content-evenly align-items-center flex-grow-1 flex-wrap">
                <li class="nav-item effect-reveal-opacity">
                    <a
                        class="nav-link text-nowrap text-truncate text-decoration-none {{ Route::is("tenant.admin.dashboard") ? "active" : "" }}"
                        href="{{ route("tenant.admin.dashboard") }}">
                        <i class="fas fa-chart-bar"></i>
                        Dashboard
                    </a>
                </li>

                {{--
                    @can('read_banners', Auth::user())
                    <li class="nav-item effect-reveal-opacity">
                    <a class="nav-link text-nowrap text-truncate text-decoration-none {{ Route::is('tenant.admin.banners.*') ? 'active' : '' }}"
                    href="{{ route('banners.list') }}">
                    <i class="fas fa-image"></i>
                    Banners
                    </a>
                    </li>
                    @endcan
                --}}

                {{--
                    @can('read_links', Auth::user())
                    <li class="nav-item effect-reveal-opacity">
                    <a class="nav-link text-nowrap text-truncate text-decoration-none {{ Route::is('tenant.admin.links.*') ? 'active' : '' }}"
                    href="{{ route('links.list') }}">
                    <i class="fas fa-link"></i>
                    Links
                    </a>
                    </li>
                    @endcan
                --}}

                {{--
                    @can('read_form_contents_contact', Auth::user())
                    <li class="nav-item dropdown effect-reveal-opacity">
                    <a
                    id="navbarDropdownLog"
                    class="nav-link text-nowrap text-truncate text-decoration-none dropdown-toggle {{ Route::is("form.*") ? "active" : "" }}"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    v-pre>
                    <i class="fas fa-envelope"></i>
                    Formulários
                    </a>
                    
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownLog">
                    @can('read_form_contents_contact', Auth::user())
                    <a
                    class="dropdown-item {{ Route::is("form.contacts.list") ? "active" : "" }}"
                    href="{{ route("form.contacts.list") }}">
                    Fale Conosco
                    </a>
                    @endcan
                    
                    @can('read_form_configs', Auth::user())
                    <a
                    class="dropdown-item {{ Route::is("form.configs.list") ? "active" : "" }}"
                    href="{{ route("form.configs.list") }}">
                    Configurações
                    </a>
                    @endcan
                    </div>
                    </li>
                    @endcan
                --}}

                {{--
                    @can('read_event_buys', Auth::user())
                    <li class="nav-item dropdown effect-reveal-opacity">
                    <a
                    id="navbarDropdownLog"
                    class="nav-link text-nowrap text-truncate text-decoration-none dropdown-toggle {{ Route::is("form.*") ? "active" : "" }}"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    v-pre>
                    <i class="fas fa-envelope"></i>
                    Evento
                    </a>
                    
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownLog">
                    @can('read_event_buys', Auth::user())
                    <a
                    class="dropdown-item {{ Route::is("event.buys.list") ? "active" : "" }}"
                    href="{{ route("event.buys.list") }}">
                    Vendas
                    </a>
                    @endcan
                    
                    @can('read_event_vouchers', Auth::user())
                    <a
                    class="dropdown-item {{ Route::is("event.vouchers.list") ? "active" : "" }}"
                    href="{{ route("event.vouchers.list") }}">
                    Ingressos
                    </a>
                    @endcan
                    </div>
                    </li>
                    @endcan
                    
                    @can('read_stores', Auth::user())
                    <li class="nav-item effect-reveal-opacity">
                    <a
                    class="nav-link text-nowrap text-truncate text-decoration-none {{ Route::is("stores.*") ? "active" : "" }}"
                    href="{{ route("stores.list") }}">
                    <i class="fas fa-store"></i>
                    Lojas
                    </a>
                    </li>
                    @endcan
                --}}

                @can("read_users", Auth::user())
                    <li class="nav-item effect-reveal-opacity">
                        <a
                            class="nav-link text-nowrap text-truncate text-decoration-none {{ Route::is("tenant.admin.users.*") || Route::is("tenant.admin.users.*") ? "active" : "" }}"
                            href="{{ route("tenant.admin.users.list") }}">
                            <i class="fas fa-users"></i>
                            Usuários
                        </a>
                    </li>
                @endcan

                @can("read_log_emails", Auth::user())
                    <li class="nav-item dropdown effect-reveal-opacity">
                        <a
                            id="navbarDropdownLog"
                            class="nav-link text-nowrap text-truncate text-decoration-none dropdown-toggle {{ Route::is("tenant.admin.log.*") ? "active" : "" }}"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            v-pre>
                            <i class="fas fa-exclamation-triangle"></i>
                            Log
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownLog">
                            @can("read_log_emails", Auth::user())
                                <a
                                    class="dropdown-item {{ Route::is("tenant.admin.tenant.admin.log.emails.list") ? "active" : "" }}"
                                    href="{{ route("tenant.admin.log.emails.list") }}">
                                    Envio de Emails
                                </a>
                            @endcan

                            @can("read_log_audits", Auth::user())
                                <a
                                    class="dropdown-item {{ Route::is("tenant.admin.log.audits.list") ? "active" : "" }}"
                                    href="{{ route("tenant.admin.log.audits.list") }}">
                                    Auditoria
                                </a>
                            @endcan

                            @can("read_log_errors", Auth::user())
                                <a
                                    class="dropdown-item {{ Route::is("tenant.admin.log.errors.list") ? "active" : "" }}"
                                    href="{{ route("tenant.admin.log.errors.list") }}">
                                    Erros
                                </a>
                            @endcan

                            @can("read_log_webhooks", Auth::user())
                                <a
                                    class="dropdown-item {{ Route::is("tenant.admin.log.webhooks.list") ? "active" : "" }}"
                                    href="{{ route("tenant.admin.log.webhooks.list") }}">
                                    Webhooks
                                </a>
                            @endcan
                        </div>
                    </li>
                @endcan
            </ul>

            <!-- Right Side Of Navbar -->
            <ul
                class="navbar-nav d-flex flex-row justify-content-center align-items-center gap-3 flex-shrink-0 effect-reveal-to-left">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has("login"))
                        <li class="nav-item">
                            <a
                                class="nav-link text-nowrap text-truncate text-decoration-none"
                                href="{{ route("login") }}">
                                {{ __("Login") }}
                            </a>
                        </li>
                    @endif

                    @if (Route::has("register"))
                        <li class="nav-item">
                            <a
                                class="nav-link text-nowrap text-truncate text-decoration-none"
                                href="{{ route("register") }}">
                                {{ __("Registrar-se") }}
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a
                            id="navbarDropdown"
                            class="nav-link text-nowrap text-truncate text-decoration-none dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a
                                class="dropdown-item {{ Route::is("tenant.admin.profile.edit") ? "active" : "" }}"
                                href="{{ route("tenant.admin.profile.edit") }}">
                                {{ __("Perfil") }}
                            </a>

                            <a
                                class="dropdown-item"
                                href="{{ route("tenant.auth.logout") }}"
                                onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __("Sair") }}
                            </a>

                            <a class="dropdown-item" style="font-size: 11px">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ "Versão " . Config::get("app.version") . "." . Config::get("app.build") }}
                            </a>
                            <form
                                id="logout-form"
                                action="{{ route("tenant.auth.logout") }}"
                                method="POST"
                                class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

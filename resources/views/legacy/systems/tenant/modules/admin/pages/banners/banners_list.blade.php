@extends("legacy.systems.tenant.modules.admin.layouts.main")

@section("content")
    <div class="card card-default mt-4">
        <div class="card-header">
            {{ $pageTitle . " | " }}
            Listagem
        </div>
        <div class="card-body">
            <div id="bar_buttons" class="inline">
                @can("create_banners", Auth::user())
                    <a
                        id="btnNew"
                        type="button"
                        class="btn btn-sm btn-primary mb-1"
                        href="{{ url("/panel/banners-manut") }}">
                        <span class="fas fa-plus"></span>
                        Novo
                    </a>
                @endcan

                @can("update_banners", Auth::user())
                    <button
                        id="btnUnlock"
                        type="button"
                        class="btn btn-primary btn-sm mb-1"
                        style="{{ count($listBanners) == 0 ? "display: d-none" : "" }}">
                        Desbloquear
                    </button>

                    <i
                        id="btnInterrogate"
                        style="{{ count($listBanners) == 0 ? "display: d-none" : "" }}"
                        class="fa fa-question-circle align-item-right"
                        aria-hidden="true"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Ao clicar em desbloquear, será liberado a opção de arrastar os banners na ordem que desejar."></i>
                @endcan
            </div>

            {{-- Filtros --}}
            <div class="divFilters mt-2 mb-2 glass">
                <div class="divFiltersTitle pt-2 pb-2">
                    <div style="font-size: inherit; max-width: 100%">
                        <a
                            data-bs-toggle="collapse"
                            href="#divFiltersContent"
                            aria-expanded="false"
                            aria-controls="divFiltersContent">
                            <i id="iconFilter" class="fas fa-minus-square"></i>
                            <i class="fa fa-filter" style="margin-left: 10px"></i>
                            Filtros
                        </a>
                    </div>
                </div>
                <div class="row divFiltersContent ps-2 collapse show" id="divFiltersContent">
                    <form method="post" class="form-filters row pt-2 pb-3" id="haliparForm">
                        {{ csrf_field() }}

                        @include("panel.pages.banners._partials._filters")

                        <div class="col-12 col-md-2 ps-md-0 d-flex justify-content-start align-items-center">
                            <button id="btnFilter" type="button" class="btn btn-sm btn-primary">
                                <i class="fa fa-filter"></i>
                                Aplicar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Grid --}}
            <div id="divGrid">
                @include("panel.pages.banners._partials._grid")
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    @vite(["resources/assets/panel/pages/banners/js/banners_list.js", "resources/assets/common/js/utils/filters.js"])
@endsection

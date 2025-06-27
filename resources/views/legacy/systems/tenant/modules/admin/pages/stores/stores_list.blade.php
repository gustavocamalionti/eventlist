@extends("legacy.systems.tenant.modules.admin.layouts.main")

@section("styles")
    
@endsection

@section("content")
    <div class="card card-default mt-4">
        <div class="card-header">
            {{ $pageTitle . " | " }}
            Listagem
        </div>
        <div class="card-body">
            <div id="bar_buttons" class="inline">
                @can("create_stores", Auth::user())
                    <a
                        id="btnNew"
                        type="button"
                        class="btn btn-sm btn-primary"
                        href="{{ url("/panel/stores-manut") }}">
                        <span class="fas fa-plus"></span>
                        Novo
                    </a>
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

                        @include("panel.pages.stores._partials._filters")

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
                <div class="card mb-0 border-top">
                    <div class="card-body table-responsive divElementGridFather">
                        <table id="zero_config" class="table table-sm table-striped table-hover align-middle">
                            @include("panel.pages.stores._partials._grid")
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    @vite(["resources/assets/panel/pages/stores/js/stores_list.js", "resources/assets/common/js/utils/filters.js"])
@endsection

@extends("panel.layouts.master_panel")

@section("styles")
    
@endsection

@section("content")
    <div class="card card-default">
        <div class="card-header">
            {{ $pageTitle . " | " . $subTitle }}
        </div>
        <div class="card-body">
            <div id="bar_buttons" class="inline mb-3">
                <button id="btnLinksSave" type="button" class="btn btn-success btn-sm">Salvar</button>
                <a id="btnCancel" href="{{ route("links.list") }}" type="button" class="btn btn-secondary btn-sm">
                    Cancelar
                </a>
            </div>

            <div class="card mb-0 border-top">
                <div class="card-body table-responsive divElementGridFather p-1" style="overflow-x: hidden">
                    @include("panel.pages.links._partials._links_maintenance_form")
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    @vite(["resources/assets/panel/pages/links/js/links_maintenance.js"])
@endsection

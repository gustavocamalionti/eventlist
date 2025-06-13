@extends("site.layouts.master_site")

@section("content")
    @include(
        "site.layouts._content_errors",
        [
            "title" => "404 - Página não encontrada",
            "icon" => "fas fa-exclamation-triangle",
        ]
    )
@endsection

@section("scripts")
    @vite(["resources/assets/common/errors/js/errors.js"])
@endsection

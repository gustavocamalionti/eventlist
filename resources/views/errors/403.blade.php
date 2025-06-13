@extends("site.layouts.master_site")

@section("content")
    @include(
        "site.layouts._content_errors",
        [
            "title" => "403 - Acesso negado",
            "icon" => "fas fa-ban",
        ]
    )
@endsection

@section("scripts")
    @vite(["resources/assets/common/errors/js/errors.js"])
@endsection

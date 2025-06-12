@extends("site.layouts.master_site")

@section("content")
    @include(
        "site.layouts._content_errors",
        [
            "title" => "503 - Estamos em manutenção",
            "icon" => "fas fa-tools",
            "returnPage" => false,
        ]
    )
@endsection

@section("scripts")
    @vite(["resources/assets/common/errors/js/errors.js"])
@endsection

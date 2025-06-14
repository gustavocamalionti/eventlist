@extends('legacy.common.layouts.master_site')

@section('content')
    @include('legacy.common.layouts._content_errors', [
        'title' => '500 - Ocorreu Alguma Falha',
        'icon' => 'fas fa-bug',
    ])
@endsection

@section('scripts')
    @vite(['resources/assets/common/errors/js/errors.js'])
@endsection

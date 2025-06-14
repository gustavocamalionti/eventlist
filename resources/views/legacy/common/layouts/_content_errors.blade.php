<div class="container pb-5">
    <div class="row">
        <div class="col-12 text-center mb-0 divClientFeedback" style="position: relative">
            {{-- <div style="position: absolute; top: -1px; left: -1px" class="padding:0">
                <img src="{{ Vite::asset('resources/assets/site/images/home/component-form-left.png') }}" alt=""
                    class="img-fluid" style="width: 65px" />
            </div>
            <div style="position: absolute; bottom: -1px; right: -1px" class="padding:0">
                <img src="{{ Vite::asset('resources/assets/site/images/home/component-form-right.png') }}"
                    alt="" class="img-fluid" style="width: 85px" />
            </div> --}}
            <div class="text-center mb-3">
                <i class="{{ $icon ?? 'fas fa-exclamation-circle' }}"></i>
            </div>
            <h1 class="text-uppercase">{{ $title }}</h1>
            @if ($returnPage ?? true)
                <p class="fs-4">Acesse o menu superior ou clique no botão abaixo para voltar à página inicial.</p>
                <a type="button" class="btn btnHome btnDefault text-uppercase" href="">
                    <i class="fas fa-home"></i>
                    Página Inicial
                </a>
            @endif

        </div>
    </div>
</div>

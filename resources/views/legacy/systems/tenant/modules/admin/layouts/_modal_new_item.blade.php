<div
    class="modal fade"
    id="{{ $idModal }}"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="{{ $idModal }}Label"
    aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="{{ $idModal }}Label">Adicionar {{ $title }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBodyContent{{ $idModal }}">
                @include($pathForm, ["prefixName" => $prefixName])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button id="{{ $idButtonSave }}" type="button" class="btn btn-success btn-sm">Salvar</button>
            </div>
        </div>
    </div>
</div>

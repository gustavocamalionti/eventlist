<div
    class="modal fade"
    id="exampleModal{{ $item->id }}"
    attr-boleto-id="{{ $item->id }}"
    tabindex="{{ $item->id }}"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel{{ $item->id }}">
                    Histórico |
                    {{ isset($item->title) ? $item->title : (isset($item->formSubject->form->name) ? $item->formSubject->form->name . " - " . $item->formSubject->name : (isset($item->name) ? $item->name : "")) }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body bg-light">
                <div id="divGridHistory{{ $item->id }}">
                    <div class="card mb-0">
                        <div class="card-body table-responsive divElementGridFatherHistory{{ $item->id }}">
                            <table
                                id="zero_config_history{{ $item->id }}"
                                class="table table-sm table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="290">Responsável</th>
                                        <th class="text-center" width="290">Email</th>
                                        <th class="text-center" width="290">Descrição</th>
                                        <th class="text-center" width="350">Detalhes</th>
                                        <th class="text-center" width="190">IP</th>
                                        <th class="text-center" width="90">Data</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyHistoryModal{{ $item->id }}">
                                    <!-- Conteúdo dinâmico da tabela vai aqui -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

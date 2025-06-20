<div class="modal fade" id="exampleModal{{ $item->id }}" attr-boleto-id="{{ $item->id }}"
    tabindex="{{ $item->id }}" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel{{ $item->id }}">
                    Compra N° {{ $item->id }} | {{ $item->stores->name }} / {{ optional($item->customers)->email }}

                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body bg-light">
                <nav class="mb-3">
                    <div class="nav nav-tabs" id="nav-tab{{ $item->id }}" role="tablist">
                        <button class="nav-link active" id="nav-vouchers-tab{{ $item->id }}" data-bs-toggle="tab"
                            data-bs-target="#nav-vouchers{{ $item->id }}" type="button" role="tab"
                            aria-controls="nav-vouchers{{ $item->id }}" aria-selected="true">Ingressos</button>

                        <button class="nav-link" id="nav-emails-tab{{ $item->id }}" data-bs-toggle="tab"
                            data-bs-target="#nav-emails{{ $item->id }}" type="button" role="tab"
                            aria-controls="nav-emails{{ $item->id }}" aria-selected="false">Emails</button>

                        @can('read_log_webhooks', Auth::user())
                            <button class="nav-link" id="nav-webhooks-tab{{ $item->id }}" data-bs-toggle="tab"
                                data-bs-target="#nav-webhooks{{ $item->id }}" type="button" role="tab"
                                aria-controls="nav-webhooks{{ $item->id }}" aria-selected="false">Webhooks</button>
                        @endcan
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-vouchers{{ $item->id }}" role="tabpanel"
                        aria-labelledby="nav-vouchers-tab{{ $item->id }}">
                        <div id="divGridVoucher{{ $item->id }}">
                            <div class="mb-0">
                                <div class="table-responsive divElementGridFatherVoucher{{ $item->id }}">
                                    <table id="zero_config_voucher{{ $item->id }}"
                                        class="table table-sm table-striped table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="290">Nome</th>
                                                <th class="text-center" width="290">Email</th>
                                                <th class="text-center" width="290">Loja</th>
                                                <th class="text-center" width="350">Função</th>
                                                <th class="text-center" width="190">Camiseta</th>
                                                <th class="text-center" width="90">Valor</th>
                                                <th class="text-center" width="90">Status</th>
                                                <th class="text-center" width="90">Data</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyVoucherModal{{ $item->id }}">
                                            <!-- Conteúdo dinâmico da tabela vai aqui -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-emails{{ $item->id }}" role="tabpanel"
                        aria-labelledby="nav-emails-tab{{ $item->id }}">
                        <div id="divGridEmail{{ $item->id }}">
                            <div class="mb-0">
                                <div class="table-responsive divElementGridFatherEmail{{ $item->id }}">
                                    <table id="zero_config_email{{ $item->id }}"
                                        class="table table-sm table-striped table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="290">UUID</th>
                                                <th class="text-center" width="290">Assunto</th>
                                                <th class="text-center" width="290">Comprador</th>
                                                <th class="text-center" width="290">Email</th>
                                                <th class="text-center" width="350">Status</th>
                                                <th class="text-center" width="190">Detalhes</th>
                                                <th class="text-center" width="90">Data</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyEmailModal{{ $item->id }}">
                                            <!-- Conteúdo dinâmico da tabela vai aqui -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @can('read_log_webhooks', Auth::user())
                        <div class="tab-pane fade" id="nav-webhooks{{ $item->id }}" role="tabpanel"
                            aria-labelledby="nav-webhooks-tab{{ $item->id }}">
                            <div id="divGridWebhook{{ $item->id }}">
                                <div class="mb-0">
                                    <div class="table-responsive divElementGridFatherWebhook{{ $item->id }}">
                                        <table id="zero_config_webhook{{ $item->id }}"
                                            class="table table-sm table-striped table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="290">ID</th>
                                                    <th class="text-center" width="290">Evento</th>
                                                    <th class="text-center" width="290">Tipo</th>
                                                    {{-- <th class="text-center" width="290">Payload</th> --}}
                                                    <th class="text-center" width="350">Status</th>
                                                    <th class="text-center" width="190">Tratamento</th>
                                                    <th class="text-center" width="90">Data</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodyWebhookModal{{ $item->id }}">
                                                <!-- Conteúdo dinâmico da tabela vai aqui -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

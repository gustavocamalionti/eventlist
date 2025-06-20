@extends("panel.layouts.master_panel")

@section("styles")
    @vite(["resources/assets/panel/pages/dashboard/css/dashboard.css"])
@endsection

@section("content")
    <div class="row g-4">
        <!-- Card: Compras Recebidas -->
        <div class="col-md-6 col-lg-4 effect-reveal-to-right">
            <div class="card shadow-default border-0 rounded-3 h-100">
                <div class="card-header">
                    <h6 class="text-center mt-1 mb-1">
                        <i class="fas fa-check me-2"></i>
                        Recebidas
                    </h6>
                </div>
                <div class="card-body" style="background-color: #c8e6c9bd">
                    <h5 class="mb-1">{{ str_pad($buysReceived["qtd"], 2, "0", STR_PAD_LEFT) }} pedidos</h5>
                    <p class="mb-0 small text-muted">
                        Valor bruto: R$ {{ number_format($buysReceived["value"], 2, ",", ".") }}
                    </p>
                    <p class="mb-0 small text-muted">
                        Valor líquido: R$ {{ number_format($buysReceived["net_value"], 2, ",", ".") }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Card: Compras Confirmadas -->
        <div class="col-md-6 col-lg-4 effect-reveal-to-right">
            <div class="card shadow-default border-0 rounded-3 h-100">
                <div class="card-header">
                    <h6 class="text-center mt-1 mb-1">
                        <i class="fas fa-sync-alt me-2"></i>
                        Confirmadas
                    </h6>
                </div>
                <div class="card-body" style="background-color: #bbdefbc5">
                    <h5 class="mb-1">{{ str_pad($buysConfirmed["qtd"], 2, "0", STR_PAD_LEFT) }} Pedidos</h5>
                    <p class="mb-0 small text-muted">
                        Valor bruto: R$ {{ number_format($buysConfirmed["value"], 2, ",", ".") }}
                    </p>
                    <p class="mb-0 small text-muted">
                        Valor líquido: R$ {{ number_format($buysConfirmed["net_value"], 2, ",", ".") }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Card: Compras Aguardando -->
        {{--
            <div class="col-md-6 col-lg-3 effect-reveal-to-left">
            <div class="card shadow-default border-0 rounded-3 h-100">
            <div class="card-header">
            <h6 class="text-center mt-1 mb-1"><i class="fas fa-clock me-2"></i>Aguardando</h6>
            </div>
            <div class="card-body" style="background-color:#fff59dc2">
            <h5 class="mb-1">{{ str_pad($buysAwaiting['qtd'], 2, '0', STR_PAD_LEFT) }} pedidos</h5>
            <p class="mb-0 small text-muted">Valor bruto: R$
            {{ number_format($buysAwaiting['value'], 2, ',', '.') }}</p>
            <p class="mb-0 small text-muted">Valor líquido: R$
            {{ number_format($buysAwaiting['net_value'], 2, ',', '.') }}</p>
            </div>
            </div>
            </div>
        --}}

        <!-- Card: Vouchers Ativos -->
        <div class="col-md-6 col-lg-4 effect-reveal-to-left">
            <div class="card shadow-default border-0 rounded-3 h-100">
                <div class="card-header">
                    <h6 class="text-center mt-1 mb-1">
                        <i class="fas fa-ticket me-2"></i>
                        Informações Gerais
                    </h6>
                </div>
                <div class="card-body" style="background-color: #fff59dc2">
                    <h5 class="mb-2">{{ str_pad($qtdStores, 3, "0", STR_PAD_LEFT) }} - Lojas Confirmadas</h5>
                    <h5 class="mb-0">{{ str_pad(count($vouchers), 3, "0", STR_PAD_LEFT) }} - Convidados Ativos</h5>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    @vite(["resources/assets/panel/pages/dashboard/js/dashboard.js"])
@endsection

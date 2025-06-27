<div class="border-0- p-0">
    {{-- <input type="hidden" value="{{ $unlock }}" id ="show_unlock"> --}}
    <form method="POST" id="formDraggable" action="">
        @csrf
        <ul id="draggablePanelList" class="list-unstyled pt-2 mb-0">
            @if (isset($listBanners))
                @forelse ($listBanners as $item)
                    <li class="card bg-light cards-exist mb-3">
                        <div class="card-header text-light text-left">
                            <input type="hidden" name="banner_id[]" value="{{ $item->id }}" />
                            <div class="d-flex align-middle">
                                <div class="flex-grow-1 pt-1">
                                    <strong>
                                        <i class="fa-solid fa-grip-vertical icon-draggable hide me-2"></i>
                                        {{ $item->name }}
                                    </strong>
                                </div>
                                @can("update_banners", Auth::user())
                                    <div class="div-action">
                                        @can("read_banners_audit", Auth::user())
                                            <button
                                                type="button"
                                                class="dropdown-item btn btn-sm btn-secondary btn-history p-2 rounded-2 text-center align-middle"
                                                style="display: inline; width: inherit"
                                                data-placement="top"
                                                title="Histórico do Usuário"
                                                data-bs-toggle="modal"
                                                data-id="{{ $item->id }}"
                                                data-bs-target="#exampleModal{{ $item->id }}">
                                                <i class="fas fa-history"></i>
                                            </button>
                                            <!-- Modal -->
                                            @include(
                                            "legacy.systems.tenant.modules.admin.layouts._modal_history", $item                                            )
                                        @endcan

                                        @if ($item->active == 1)
                                            @can("update_banners", Auth::user())
                                                <a
                                                    class="btn btn-sm btn-primary"
                                                    href="{{ route("banners.maintenance", ["id" => $item->id]) }}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Editar">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <a
                                                    class="btn btn-sm btn-warning lnk-archive"
                                                    data-bs-value="{{ $item->id }}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Arquivar"
                                                    attr-title="Arquivar"
                                                    attr-url="{{ route("banners.active.desactive", [$item->id, "decision" => 0]) }}">
                                                    <i class="fas fa-folder"></i>
                                                </a>
                                            @endcan
                                        @else
                                            @can("update_banners", Auth::user())
                                                <a
                                                    class="btn btn-sm btn-dark"
                                                    href="{{ route("banners.maintenance", ["id" => $item->id]) }}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Editar">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <a
                                                    class="btn btn-sm btn-success lnk-archive"
                                                    data-bs-value="{{ $item->id }}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Desarquivar"
                                                    attr-title="Desarquivar"
                                                    attr-url="{{ route("banners.active.desactive", [$item->id, "decision" => 1]) }}">
                                                    <i class="fas fa-folder-open"></i>
                                                </a>
                                            @endcan
                                        @endif

                                        @can("delete_banners", Auth::user())
                                            <a
                                                id="btnExcluir"
                                                class="btn btn-sm btn-danger lnk-delete"
                                                data-bs-value="{{ $item->id }}"
                                                data-bs-toggle="tooltip"
                                                attr-url="{{ route("banners.delete", [$item->id]) }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Excluir">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        @endcan
                                    </div>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body p-0 ps-3 pe-3 pb-3">
                            <div class="row">
                                <div class="col-12 col-md-4 mt-3">
                                    <div class="">
                                        <img
                                            src="{{ asset("storage/site/banners/" . $item->image_desktop) }}"
                                            class="img-fluid rounded"
                                            alt="{{ $item->name }}" />
                                    </div>
                                </div>

                                <div class="col-12 col-md-8 mt-3">
                                    <div class="row">
                                        <div class="col-12 col-md-8">
                                            <p class="">
                                                <strong>
                                                    Status:
                                                    <span
                                                        class="badge {{ $item->isActive == "1" ? "bg-success" : "bg-danger" }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ $item->isActive == "1" ? "O Banner está ativo!" : "O Banner está inativo!" }}">
                                                        {{ $item->isActive == "1" ? "ATIVO" : "INATIVO" }}
                                                    </span>
                                                    - {{ $item->active ? "Desarquivado" : "Arquivado" }}
                                                </strong>
                                            </p>
                                            @if (Auth::user()->roles->id == App\Libs\Enums\Systems\Tenant\EnumTenantRoles::ADMIN)
                                                <p class="">
                                                    <strong>Arquivo Mobile:</strong>
                                                    {{ $item->image_mobile }}
                                                </p>
                                                <p class="">
                                                    <strong>Arquivo Desktop:</strong>
                                                    {{ $item->image_desktop }}
                                                </p>
                                            @endif

                                            @if (isset($item->links_id))
                                                <p class="">
                                                    <strong>Link:</strong>
                                                    <a
                                                        href="{{ getLinksSlug($item->links_id) }}"
                                                        class="text-decoration-none">
                                                        {{ getLinksSlug($item->links_id) }}
                                                    </a>
                                                </p>
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            @if ($item->is_schedule == 1)
                                                @if ($item->date_start != null and $item->date_end != null)
                                                    @if (

                                                        date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $myDateTime))) >
                                                            date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $item->date_start))) and
                                                        date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $myDateTime))) <
                                                            date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $item->date_end)))                                                    )
                                                        <p class="">
                                                            <strong>
                                                                Agendamento:
                                                                <span
                                                                    class="badge bg-primary"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    title="Estamos no ar!">
                                                                    EM EXECUÇÃO
                                                                </span>
                                                            </strong>
                                                        </p>
                                                    @else
                                                        @if (

                                                            date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $myDateTime))) <
                                                            date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $item->date_start)))                                                        )
                                                            <p class="">
                                                                <strong>
                                                                    Agendamento:
                                                                    <span
                                                                        class="badge bg-warning"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="A programação do banner começará em {{ date("d/m/Y H:i:s", strtotime(str_replace("/", "-", $item->date_start))) }}">
                                                                        AGUARDANDO
                                                                    </span>
                                                                </strong>
                                                            </p>
                                                        @endif

                                                        @if (

                                                            date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $myDateTime))) >
                                                            date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $item->date_end)))                                                        )
                                                            <p class="">
                                                                <strong>
                                                                    Agendamento:
                                                                    <span
                                                                        class="badge bg-danger"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="A programação do banner foi encerrada em {{ date("d/m/Y H:i:s", strtotime(str_replace("/", "-", $item->date_end))) }}">
                                                                        ENCERRADO
                                                                    </span>
                                                                </strong>
                                                            </p>
                                                        @endif
                                                    @endif
                                                @endif

                                                @if ($item->date_start != null and $item->date_end == null)
                                                    @if (

                                                        date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $myDateTime))) >
                                                        date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $item->date_start)))                                                    )
                                                        <p class="">
                                                            <strong>
                                                                Agendamento:
                                                                <span
                                                                    class="badge bg-primary"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    title="Estamos no ar!">
                                                                    EM EXECUÇÃO
                                                                </span>
                                                            </strong>
                                                        </p>
                                                    @else
                                                        <p class="">
                                                            <strong>
                                                                Agendamento:
                                                                <span
                                                                    class="badge bg-warning"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    title="A programação do banner começará em {{ date("d/m/Y H:i:s", strtotime(str_replace("/", "-", $item->date_start))) }}">
                                                                    AGUARDANDO
                                                                </span>
                                                            </strong>
                                                        </p>
                                                    @endif
                                                @endif

                                                @if ($item->date_end != null and $item->date_start == null)
                                                    @if (

                                                        date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $myDateTime))) <
                                                        date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $item->date_end)))                                                    )
                                                        <p class="">
                                                            <strong>
                                                                Agendamento:
                                                                <span
                                                                    class="badge bg-primary"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    title="Estamos no ar!">
                                                                    EM EXECUÇÃO
                                                                </span>
                                                            </strong>
                                                        </p>
                                                    @else
                                                        <p class="">
                                                            <strong>
                                                                Agendamento:
                                                                <span
                                                                    class="badge bg-danger"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    title="A programação do banner foi encerrada em {{ date("d/m/Y H:i:s", strtotime(str_replace("/", "-", $item->date_end))) }}">
                                                                    ENCERRADO
                                                                </span>
                                                            </strong>
                                                        </p>
                                                    @endif
                                                @endif
                                            @else
                                                <p class="">
                                                    <strong>
                                                        Agendamento:
                                                        <span
                                                            class="badge bg-dark"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Não existe programação!">
                                                            NÃO AGENDADO
                                                        </span>
                                                    </strong>
                                                </p>
                                            @endif

                                            @if (@isset($item->date_start))
                                                <p class="">
                                                    <strong>Início:</strong>
                                                    {{ date("d/m/Y H:i:s", strtotime($item->date_start)) }}
                                                </p>
                                            @endif

                                            @if (@isset($item->date_end))
                                                <p class="">
                                                    <strong>Final:</strong>
                                                    {{ date("d/m/Y H:i:s", strtotime($item->date_end)) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="card bg-light">
                        <h4 id="empty" class="text-center pt-3 pb-2">Nenhum registro encontrado !</h4>
                    </li>
                @endforelse
            @endif
        </ul>
    </form>
</div>

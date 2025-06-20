@can("update_stores", Auth::user())
    <div class="row mt-2 mb-2 ms-1">
        @can("read_stores_audit", Auth::user())
            <div
                class="offset-1 col-4 d-flex justify-content-end align-items-center p-0"
                style="background-color: rgba(157, 157, 157, 0.064)">
                <button
                    class="dropdown-item link-btn-grid btn-history lnk-default"
                    data-placement="top"
                    title="Histórico do Usuário"
                    data-bs-toggle="modal"
                    data-id="{{ $item->id }}"
                    data-bs-target="#exampleModal{{ $item->id }}">
                    <i class="fas fa-history"></i>
                </button>

                <!-- Modal -->
                @include("panel.layouts._modal_history", $item)
            </div>
        @endcan

        @php
            $canReadAudit = Auth::user()->can("read_stores_audit");
        @endphp

        <div
            class="{{ ! $canReadAudit ? "offset-2" : "" }} col-4 d-flex justify-content-center align-items-center p-0">
            <div class="dropdown h-100 w-100">
                <a
                    class="btn-menu-dropdown dropdown-toggle d-flex justify-content-center align-items-center"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="true"
                    style="border-radius: 0"></a>
                <ul class="dropdown-menu">
                    @can("update_stores", Auth::user())
                        <li>
                            <a
                                class="dropdown-item link-dropdown-item text-dark"
                                href="{{ url("/panel/stores-manut/" . $item->id) }}"
                                target="_blank"
                                style="text-decoration: none"
                                data-bs-toggle="tooltip"
                                data-placement="top"
                                title="Editar">
                                <div class="d-flex">
                                    <div class="" style="width: 17px">
                                        <i
                                            class="fas fa-edit me-1 text-primary"
                                            aria-hidden="true"
                                            style="width: 10%"></i>
                                    </div>
                                    <span>- Editar</span>
                                </div>
                            </a>
                        </li>
                    @endcan

                    @can("delete_stores", Auth::user())
                        <li>
                            <a
                                class="dropdown-item link-dropdown-item text-dark lnk-delete"
                                href="#"
                                data-bs-toggle="tooltip"
                                data-placement="top"
                                title="Excluir"
                                style="text-decoration: none"
                                data-url="{{ url("/panel/stores-delete/" . $item->id) }}">
                                <div class="d-flex">
                                    <div style="width: 17px">
                                        <i class="fas fa-trash-alt text-primary" aria-hidden="true"></i>
                                    </div>
                                    <span>- Excluir</span>
                                </div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>
@endcan

@cannot("update_stores", Auth::user())
    <p>-</p>
@endcannot

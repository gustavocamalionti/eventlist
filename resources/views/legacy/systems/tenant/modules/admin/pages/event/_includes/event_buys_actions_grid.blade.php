{{--
    @can('read_event_buys', Auth::user())
    <div class="row mt-2 mb-2 ms-1">
    @can('read_event_buys', Auth::user())
    <div class="d-flex justify-content-center align-items-center">
    <button class="dropdown-item link-btn-grid btn-history lnk-default" data-placement="top"
    title="Informações úteis relacionadas com a venda" data-bs-toggle="modal" data-id="{{ $item->id }}"
    data-bs-target="#exampleModal{{ $item->id }}">
    <i class="fas fa-list"></i>
    </button>
    
    <!-- Modal -->
    @include('panel.pages.event._includes._modal_buys', $item)
    </div>
    @endcan
    
    @php
    $canReadAudit = Auth::user()->can('read_event_buys');
    @endphp
    
    <div class="{{ !$canReadAudit ? 'offset-2' : '' }} col-4 d-flex justify-content-center align-items-center p-0">
    <div class="dropdown h-100 w-100">
    <a class="btn-menu-dropdown dropdown-toggle d-flex justify-content-center align-items-center" href="#"
    role="button" data-bs-toggle="dropdown" aria-expanded="true" style="border-radius: 0"></a>
    <ul class="dropdown-menu">
    @can('refund_event_buys', Auth::user())
    <li>
    <a class="dropdown-item link-dropdown-item text-dark lnk-delete" href="#"
    data-bs-toggle="tooltip" data-placement="top" title="Reembolsar" style="text-decoration: none"
    data-url="{{ url("/panel/event-buys-refund/" . $item->id) }}">
    <div class="d-flex">
    <div style="width: 17px">
    <i class="fas fa-trash-alt text-primary" aria-hidden="true"></i>
    </div>
    <span>- Reembolsar</span>
    </div>
    </a>
    </li>
    @endcan
    </ul>
    </div>
    </div>
    </div>
    @endcan
--}}

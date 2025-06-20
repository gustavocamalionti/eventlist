@if (isset($vouchers))
    @foreach ($vouchers as $item)
        <tr>
            <td class="text-center align-middle">
                {{ $item->name }}
            </td>
            <td class="text-center align-middle">
                {{ $item->email }}
            </td>
            <td class="text-center align-middle">
                {{ optional($item->stores)->name }}
            </td>

            <td class="text-center align-middle">
                {{ optional($item->positions)->name }}
            </td>

            <td class="text-center align-middle">
                {{ $item->tshirt }}
            </td>
            <td class="text-center align-middle">
                R$ {{ number_format($item->value / 100, 2, '.', ',') }}
            </td>
            <td class="text-center align-middle">
                <span
                    class="{{ $item->active == App\Libs\Enums\EnumStatus::ACTIVE ? 'text-success' : 'text-danger' }}">{!! $item->active == \App\Libs\Enums\EnumStatus::ACTIVE
                        ? '<i class="fas fa-check"></i> Ativo'
                        : '<i class="fas fa-times"></i> Inativo' !!}</span>
            </td>
            <td class="text-center align-middle">
                {{ date('d/m/Y H:i:s', strtotime($item->created_at)) }}
            </td>
        </tr>
    @endforeach
@endif

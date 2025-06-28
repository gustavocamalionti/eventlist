@if (isset($webhooks))
    @foreach ($webhooks as $item)
        <tr>
            <td class="text-center align-middle">
                {{ $item->id }}
            </td>
            <td class="text-center align-middle">
                {{ $item->events_id }}
            </td>
            <td class="text-center align-middle">
                {{ $item->event_type }}
            </td>

            <td class="text-center align-middle">
                <span
                    class="{{ $item->status == App\Libs\Enums\EnumStatus::ACTIVE ? "text-success" : "text-danger" }}">
                    {!! $item->status == \App\Libs\Enums\EnumStatus::ACTIVE ? '<i class="fas fa-check"></i> Processado' : '<i class="fas fa-times"></i> Não Processado' !!}
                </span>
            </td>
            <td class="text-center align-middle">
                <span
                    class="{{ $item->should_treat == App\Libs\Enums\EnumStatus::ACTIVE ? "text-success" : "text-warning" }}">
                    {!! $item->should_treat == \App\Libs\Enums\EnumStatus::ACTIVE ? '<i class="fas fa-check"></i> Tratar' : '<i class="fas fa-times"></i> Não Tratar' !!}
                </span>
            </td>

            <td class="text-center align-middle">
                {{ date("d/m/Y H:i:s", strtotime($item->created_at)) }}
            </td>
        </tr>
    @endforeach
@endif

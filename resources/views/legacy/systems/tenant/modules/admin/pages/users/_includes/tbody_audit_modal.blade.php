@if (isset($userAudit))
    @foreach ($userAudit as $item)
        <tr>
            <td class="text-center align-middle">
                {{ $item->users_name }}
            </td>
            <td class="text-center align-middle">
                {{ $item->users_email }}
            </td>
            <td class="text-center align-middle">
                {{ $item->description }}
            </td>

            <td class="text-start align-middle">
                @include("legacy.systems.tenant.modules.admin.layouts._details_audit", $item)
            </td>

            <td class="text-center align-middle">
                {{ $item->ip }}
            </td>
            <td class="text-center align-middle">
                {{ date("d/m/Y H:i:s", strtotime($item->created_at)) }}
            </td>
        </tr>
    @endforeach
@endif

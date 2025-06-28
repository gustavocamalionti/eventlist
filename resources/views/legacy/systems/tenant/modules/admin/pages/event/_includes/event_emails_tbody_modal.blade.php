@if (isset($emails))
    @foreach ($emails as $item)
        <tr>
            <td class="text-center align-middle">
                {{ $item->uuid }}
            </td>
            <td class="text-center align-middle">
                {{ \App\Libs\Enums\EnumJobs::mapToInternalInfo($item->job_title) }}
            </td>
            <td class="text-center align-middle">
                {{ \App\Models\Customer::find($item->customers_id)->name }}
            </td>
            <td class="text-start align-middle">
                @php
                    $emails = explode(";", $item->email);
                @endphp

                @foreach ($emails as $email)
                    {{ $email }}
                    <br />
                @endforeach
            </td>
            <td class="text-center align-middle">
                {{ $item->status }}
            </td>

            <td class="text-center align-middle">
                {{ $item->details }}
            </td>
            <td class="text-center align-middle">
                {{ date("d/m/Y H:i:s", strtotime($item->created_at)) }}
            </td>
        </tr>
    @endforeach
@endif

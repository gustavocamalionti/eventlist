@foreach ($item->what_heppened as $key => $value)
    @if ($key == "old")
        <div class="text-danger">
            <strong>Valores Antigos:</strong>
            <ul>
                @foreach ($value as $keyOld => $valueOld)
                    <li>
                        @include(
                            "legacy.systems.tenant.modules.admin.layouts._details_audit_fields",
                            [
                                "key" => $keyOld,
                                "value" => $valueOld,
                            ]
                        )
                    </li>
                @endforeach
            </ul>
        </div>
    @elseif ($key == "new")
        <div class="text-success">
            <strong>Valores Novos:</strong>
            <ul>
                @foreach ($value as $keyNew => $valueNew)
                    <li>
                        @include(
                            "legacy.systems.tenant.modules.admin.layouts._details_audit_fields",
                            [
                                "key" => $keyNew,
                                "value" => $valueNew,
                            ]
                        )
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        @if (! isset($audit->what_heppened["old"]))
            <div class="text-success">
                @include(
                    "legacy.systems.tenant.modules.admin.layouts._details_audit_fields",
                    [
                        "key" => $key,
                        "value" => $value,
                    ]
                )
            </div>
        @endif
    @endif
@endforeach

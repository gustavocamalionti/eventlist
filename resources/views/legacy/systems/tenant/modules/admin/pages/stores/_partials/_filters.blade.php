<div class="form-group col-12 col-md-2 mt-2 pe-0">
    <label style="font-weight: normal" for="selFilterBrands">Marca</label>
    <select class="form-select select2" aria-label="Default select example" id="selFilterBrands" name="selFilterBrands">
        <option class="option-custom" value="" selected>Selecione...</option>
        @foreach ($brands as $item)
            @if ($item->id != 999)
                <option value="{{ $item->id }}">
                    {{ $item->title }}
                </option>
            @endif
        @endforeach
    </select>
</div>
<div class="form-group col-12 col-md-2 mt-2 ">
    <label style="font-weight: normal" for="selFilterSubscribe">Confirmada</label>
    <select class="form-select form-select-sm select2" name="selFilterSubscribe" id="selFilterSubscribe">
        <option value="" selected>Todos</option>
        <option value="1">Sim</option>
        <option value="0">Não</option>
    </select>
</div>

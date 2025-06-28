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

<div class="form-group col-12 col-md-2 mt-2">
    <label style="font-weight: normal" for="selFilterStatus">Exibir Status</label>
    <select class="form-select form-select-sm select2" name="selFilterStatus" id="selFilterStatus">
        <option value="" selected>Todos</option>
        <option value="1">Ativos</option>
        <option value="0">Inativos</option>
    </select>
</div>

<div class="form-group col-12 col-md-4 mt-2">
    <div class="col-12">
        <label for="date_start" style="font-weight: normal; margin-bottom: 0">Período de Criação</label>
    </div>
    <div class="input-daterange input-group input-group-sm col-12" id="datepicker" style="float: left">
        <label class="input-group-text span-input-date">De</label>
        <input type="text" class="form-control" id="date_start" name="date_start" />
        <span class="input-group-text span-input-date">à</span>
        <input type="text" class="form-control" id="date_end" name="date_end" />
    </div>
</div>

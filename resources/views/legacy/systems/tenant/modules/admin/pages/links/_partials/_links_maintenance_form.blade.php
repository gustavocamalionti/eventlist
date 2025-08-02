{{-- Validando se é Modal ou Normal --}}

<?php
$prefixName = isset($prefixName) ? $prefixName : "";
?>

<form
    id="linkForm"
    class="form-horizontal"
    attr-save="{{ isset($link) ? route("links.update", ["id" => $link->id]) : route("links.store") }}"
    attr-redirect="{{ $prefixName != "" ? "" : route("links.list") }}">
    <div class="card-body">
        {{ csrf_field() }}
        <input type="hidden" id="prefixNameLinks" name="prefixNameLinks" value="{{ $prefixName }}" />
        <input type="hidden" value="{{ $parameters->apicep }}" id="apicep" />
        <input type="hidden" value="{{ isset($link) ? $link->id : "" }}" id="id" />
        <div class="row">
            <div class="col-4 mb-2">
                <label for="{{ $prefixName }}stores_id" class="form-label">Loja *</label>
                <select
                    class="select2 form-select"
                    id="{{ $prefixName }}stores_id"
                    name="{{ $prefixName }}stores_id"
                    {{ isset($link) && $link->is_fixed == App\Libs\Enums\EnumLinkFixed::FIXED ? "disabled" : "" }}>
                    {{-- <option value="" selected>Selecione...</option> --}}
                    <option value="">Selecione...</option>

                    @foreach ($stores as $store)
                        <option
                            value="{{ $store->id }}"
                            {{ isset($link) ? ($link->stores_id == $store->id ? "selected" : null) : null }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-3 mb-2">
                <label class="form-label" for="{{ $prefixName }}title">Título *</label>
                <input
                    type="text"
                    class="form-control"
                    id="{{ $prefixName }}title"
                    name="{{ $prefixName }}title"
                    value="{{ isset($link) ? $link->title : null }}"
                    {{ isset($link) && $link->is_fixed == App\Libs\Enums\EnumLinkFixed::FIXED ? "disabled" : "" }} />
            </div>

            <div class="col-5 mb-2">
                <label class="form-label" for="{{ $prefixName }}slug">Url de Acesso *</label>
                <div class="input-group">
                    <label
                        class="btn btn-secondary"
                        style="background-color: rgba(150, 150, 150, 0.889); border: none"
                        for="{{ $prefixName }}slug"
                        value=""
                        disabled>
                        {{ env("APP_URL") }}/
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="{{ $prefixName }}slug"
                        name="{{ $prefixName }}slug"
                        value="{{ isset($link) ? $link->slug : null }}"
                        {{ isset($link) && $link->is_fixed == App\Libs\Enums\EnumLinkFixed::FIXED ? "disabled" : "" }} />
                </div>
            </div>
        </div>
        <div class="row">
            {{-- <div style="margin-top:120px;"> --}}
            <div class="col-12 mb-2">
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="{{ $prefixName }}link_type"
                        id="{{ $prefixName }}is_file"
                        name="{{ $prefixName }}is_file"
                        value="0"
                        checked
                        {{ isset($link) && $link->is_fixed == App\Libs\Enums\EnumLinkFixed::FIXED ? "disabled" : "" }} />
                    <label class="form-check-label" for="{{ $prefixName }}is_file">Arquivo</label>
                </div>
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="{{ $prefixName }}link_type"
                        id="{{ $prefixName }}is_link"
                        name="{{ $prefixName }}is_link"
                        value="1"
                        {{ isset($link) && $link->link_type == App\Libs\Enums\EnumLinkType::REDIRECT ? "checked" : "" }}
                        {{ isset($link) && $link->is_fixed == App\Libs\Enums\EnumLinkFixed::FIXED ? "disabled" : "" }} />
                    <label class="form-check-label" for="{{ $prefixName }}is_link">Link</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div
                class="col-12 mb-2 form-group {{ isset($link) && $link->link_type == App\Libs\Enums\EnumLinkType::FILE ? "show" : "hide" }}"
                id="divFile">
                <div class="input-group">
                    <button
                        class="btn btn-secondary {{ $prefixName }}file-click"
                        style="background-color: rgba(150, 150, 150, 0.889); border: none"
                        type="button"
                        id="btnSearchFile">
                        Procurar...
                    </button>
                    <input
                        type="text"
                        class="form-control {{ $prefixName }}file-click"
                        id="{{ $prefixName }}name"
                        name="{{ $prefixName }}name"
                        value="{{ isset($link) ? $link->name : null }}" />
                    <button
                        class="btn btn-secondary"
                        type="button"
                        style="background-color: rgba(150, 150, 150, 0.889); border: none"
                        type="button"
                        id="btnClearFile">
                        <span class="fas fa-eraser"></span>
                    </button>
                    <input
                        type="file"
                        class="form-control"
                        id="{{ $prefixName }}file"
                        name="{{ $prefixName }}file"
                        style="display: none" />
                </div>
            </div>

            <div
                class="col-12 mb-2 form-group {{ isset($link) && $link->link_type == App\Libs\Enums\EnumLinkType::REDIRECT ? "show" : "hide" }}"
                id="divLink">
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control"
                        id="{{ $prefixName }}link"
                        name="{{ $prefixName }}link"
                        value="{{ isset($link) ? $link->link : null }}" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <div class="form-group mb-2">
                    <div>
                        <label for="{{ $prefixName }}active" class="control-label col-form-label">Status</label>
                    </div>
                    <div>
                        <input
                            type="checkbox"
                            id="{{ $prefixName }}active"
                            name="{{ $prefixName }}active"
                            class="toggle-input"
                            data-onvalue="1"
                            data-offvalue="0"
                            data-toggle="toggle"
                            data-onlabel="Ativo"
                            data-offlabel="Inativo"
                            data-onstyle="primary"
                            {{ ! isset($link) || $link->active == 1 ? "checked" : null }}
                            {{ (isset($link) && $link->is_fixed == App\Libs\Enums\EnumLinkFixed::FIXED) || $prefixName != "" ? "disabled" : "" }} />
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

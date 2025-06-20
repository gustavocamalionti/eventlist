@extends("panel.layouts.master_panel")

@section("styles")
    @vite(["resources/assets/panel/pages/banners/css/banners_maintenance.css"])
@endsection

@section("content")
    <div class="card card-default">
        <div class="card-header">
            {{ $pageTitle . " | " . $subTitle }}
        </div>
        <div class="card-body">
            <div id="bar_buttons" class="inline mb-3">
                <button id="btnSave" type="button" class="btn btn-success btn-sm">Salvar</button>
                <a id="btnCancel" href="{{ route("banners.list") }}" type="button" class="btn btn-secondary btn-sm">
                    Cancelar
                </a>
            </div>

            <div class="card mb-0 border-top">
                <div class="card-body table-responsive divElementGridFather p-1" style="overflow-x: hidden">
                    <form
                        id="formPage"
                        class="form-horizontal"
                        enctype="multipart/form-data"
                        attr-save="{{ isset($banner) ? route("banners.update", ["id" => $banner->id]) : route("banners.store") }}"
                        attr-list="{{ route("banners.list") }}">
                        @csrf
                        <input
                            type="hidden"
                            id="image_desktop"
                            name="image_desktop"
                            value="{{ isset($banner) ? $banner->image_desktop : null }}" />
                        <input
                            type="hidden"
                            id="image_mobile"
                            name="image_mobile"
                            value="{{ isset($banner) ? $banner->image_mobile : null }}" />

                        <div class="card-body">
                            <div class="row g-5">
                                <div class="col-12 col-lg-6">
                                    <fieldset class="border rounded-3 p-3 mt-1 mb-1">
                                        <legend class="float-none w-auto px-3 m-1">
                                            <strong>Informações Gerais</strong>
                                        </legend>
                                        <div class="form-group">
                                            <label for="name" class="control-label col-form-label">Nome *</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="name"
                                                name="name"
                                                maxlength="100"
                                                value="{{ isset($banner) ? $banner->name : "" }}" />
                                        </div>

                                        <div class="form-group">
                                            <label for="links_id" class="control-label col-form-label">Link</label>

                                            <div class="d-flex align-items-stretch w-100">
                                                <select name="links_id" id="links_id" class="select2 form-select">
                                                    <option value="">Selecione...</option>
                                                    @foreach ($links as $reg)
                                                        <option
                                                            value="{{ $reg->id }}"
                                                            {{ isset($banner) && $banner->links_id == $reg->id ? "selected" : null }}>
                                                            {{ $reg->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button
                                                    type="button"
                                                    class="btn btn-primary d-flex justify-content-center align-items-center ms-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalNewItemLinks"
                                                    style="width: 42px"
                                                    id="btnOpenModalNewLinks">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div>
                                                <label for="active" class="control-label col-form-label">Status</label>
                                            </div>
                                            <div>
                                                <input
                                                    type="checkbox"
                                                    name="active"
                                                    {{ isset($banner) == null || $banner->active == 1 ? "checked" : null }}
                                                    class="active toggle-input"
                                                    data-onvalue="1"
                                                    data-offvalue="0"
                                                    data-toggle="toggle"
                                                    data-onlabel="Desarquivado"
                                                    data-offlabel="Arquivado"
                                                    data-onstyle="primary"
                                                    data-offstyle="secondary" />
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="border rounded-3 p-3 mt-3 mb-1">
                                        <legend class="float-none w-auto px-3 m-1">
                                            <strong>Configurações de Agendamento</strong>
                                        </legend>

                                        <div class="form-group text-right mb-2">
                                            <div>
                                                <label for="is_schedule" class="control-label col-form-label">
                                                    Realizar Programação?
                                                </label>
                                            </div>
                                            <div>
                                                <input
                                                    type="checkbox"
                                                    id="is_schedule"
                                                    name="is_schedule"
                                                    class="is_schedule toggle-input"
                                                    {{ isset($banner) && $banner->is_schedule == 1 ? "checked" : null }}
                                                    data-onvalue="1"
                                                    data-offvalue="0"
                                                    data-toggle="toggle"
                                                    data-onlabel="Sim"
                                                    data-offlabel="Não"
                                                    data-onstyle="primary"
                                                    data-offstyle="secondary" />
                                            </div>
                                        </div>
                                        <div
                                            class="divSchedule {{ isset($banner) && $banner->is_schedule ? "" : "d-none" }}">
                                            <div class="row form-group">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="date_start" class="control-label col-form-label">
                                                            Data Inicial
                                                        </label>
                                                        <div class="input-group">
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="date_start"
                                                                name="date_start"
                                                                placeholder="dd/mm/yyyy"
                                                                date
                                                                value="{{ isset($banner->date_start) ? date("d/m/Y", strtotime(str_replace("/", "-", $banner->date_start))) : "" }}"
                                                                aria-describedby="basic-addon2" />
                                                            <span
                                                                for="date_start"
                                                                id="icon_date_start"
                                                                class="input-group-text icon-input">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="date_end" class="control-label col-form-label">
                                                            Data Final
                                                        </label>
                                                        <div class="input-group">
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="date_end"
                                                                name="date_end"
                                                                placeholder="dd/mm/yyyy"
                                                                date
                                                                value="{{ isset($banner->date_end) ? date("d/m/Y", strtotime(str_replace("/", "-", $banner->date_end))) : "" }}"
                                                                aria-describedby="basic-addon2" />
                                                            <span
                                                                for="date_end"
                                                                id="icon_date_end"
                                                                class="input-group-text icon-input">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="time_start" class="control-label col-form-label">
                                                            Hora Inicial
                                                        </label>
                                                        <div class="input-group">
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="time_start"
                                                                name="time_start"
                                                                placeholder="hh:mm"
                                                                time
                                                                value="{{ isset($banner->date_start) ? date("H:i", strtotime(str_replace("/", "-", $banner->date_start))) : "" }}"
                                                                aria-describedby="basic-addon2" />
                                                            <span
                                                                for="time_start"
                                                                id="icon_time_start"
                                                                class="input-group-text icon-input">
                                                                <i class="fas fa-clock"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="time_end" class="control-label col-form-label">
                                                            Hora Final
                                                        </label>
                                                        <div class="input-group">
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="time_end"
                                                                name="time_end"
                                                                placeholder="hh:mm"
                                                                time
                                                                value="{{ isset($banner->date_end) ? date("H:i", strtotime(str_replace("/", "-", $banner->date_end))) : "" }}"
                                                                aria-describedby="basic-addon2" />
                                                            <span
                                                                for="time_end"
                                                                id="icon_time_end"
                                                                class="input-group-text icon-input">
                                                                <i class="fas fa-clock"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="row">
                                        <fieldset class="border rounded-3 p-3 mt-1 mb-1">
                                            <legend class="float-none w-auto px-3 m-1">
                                                <strong>Imagem Desktop</strong>
                                            </legend>
                                            <div class="col-12">
                                                <div class="d-flex justify-content-center">
                                                    <label for="" class="file-desktop-click mb-2">
                                                        Dimensões * - ( Largura: {{ env("BANNER_WIDTH_DESKTOP") }}px |
                                                        Altura: {{ env("BANNER_HEIGHT_DESKTOP") }}px )
                                                    </label>
                                                </div>
                                                <div class="justify-content-center d-flex">
                                                    <input
                                                        type="file"
                                                        class="form-control file"
                                                        id="file_desktop"
                                                        name="file_desktop"
                                                        maxlength="100" />
                                                </div>
                                                <div class="form-group">
                                                    <div class="d-flex justify-content-center">
                                                        <div
                                                            class="file-hover"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            data-bs-title="{{ isset($banner) ? "Clique para adicionar uma image!" : "Clique para alterar a image!" }}">
                                                            <img
                                                                id="img-display-desktop"
                                                                src="{{ ! isset($banner) ? Vite::asset("resources/assets/panel/images/banner/banner-default.jpg") : asset("storage/site/banners/" . $banner->image_desktop) }}"
                                                                class="img-fluid img-default-desktop rounded-2 file-desktop-click file-hover"
                                                                alt="{{ isset($banner) ? $banner->title : "image-default" }}"
                                                                style="max-height: 350px" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <fieldset class="border rounded-3 p-3 mt-1 mb-1">
                                            <legend class="float-none w-auto px-3 m-1">
                                                <strong>Imagem Mobile</strong>
                                            </legend>
                                            <div class="col-12">
                                                <div class="d-flex justify-content-center">
                                                    <label for="" class="file-mobile-click mb-2">
                                                        Dimensões * - ( Largura: {{ env("BANNER_WIDTH_MOBILE") }}px |
                                                        Altura: {{ env("BANNER_HEIGHT_MOBILE") }}px )
                                                    </label>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <input
                                                        type="file"
                                                        class="form-control file"
                                                        id="file_mobile"
                                                        name="file_mobile"
                                                        maxlength="100" />
                                                </div>
                                                <div class="form-group">
                                                    <div class="d-flex justify-content-center">
                                                        <div
                                                            class="file-hover d-flex align-items-center justify-content-center"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="{{ isset($banner) ? "Clique para adicionar uma image!" : "Clique para alterar a image!" }}">
                                                            <img
                                                                id="img-display-mobile"
                                                                src="{{ ! isset($banner) ? Vite::asset("resources/assets/panel/images/banner/banner-default.jpg") : asset("storage/site/banners/" . $banner->image_mobile) }}"
                                                                class="img-fluid img-default-mobile rounded-2 file-mobile-click file-hover"
                                                                alt="{{ isset($banner) ? $banner->name : "image-default" }}"
                                                                style="max-height: 350px" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <aside>
        @include(
            "panel.layouts._modal_new_item",
            [
                "title" => "Link",
                "prefixName" => "modal-link-",
                "idModal" => "modalNewItemLinks",
                "idButtonSave" => "btnLinksSave",
                "pathForm" => "panel.pages.links._partials._links_maintenance_form",
            ]
        )
    </aside>
@endsection

@section("scripts")
    @vite(["resources/assets/panel/pages/banners/js/banners_maintenance.js", "resources/assets/panel/pages/links/js/links_maintenance.js"])
@endsection

@extends("legacy.systems.tenant.modules.admin.layouts.main")
@section("styles")
    
@endsection

@section("content")
    <div class="card card-default">
        <div class="card-header">
            {{ $pageTitle . " | " . $subTitle }}
        </div>
        <div class="card-body">
            <div id="bar_buttons" class="inline mb-3">
                <button id="btnSave" type="button" class="btn btn-success btn-sm">Salvar</button>
            </div>
            <form
                id="formPage"
                class="form-horizontal"
                attr-save="{{ route("tenant.admin.config.parameters.update") }}"
                attr-redirect="{{ route("tenant.admin.config.parameters.index") }}">
                @csrf
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button
                            class="nav-link active"
                            id="nav-home-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#nav-home"
                            type="button"
                            role="tab"
                            aria-controls="nav-home"
                            aria-selected="true">
                            Gerais
                        </button>
                        <button
                            class="nav-link"
                            id="nav-profile-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#nav-profile"
                            type="button"
                            role="tab"
                            aria-controls="nav-profile"
                            aria-selected="false">
                            Mídias Sociais
                        </button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div
                        class="tab-pane fade show active pt-3"
                        id="nav-home"
                        role="tabpanel"
                        aria-labelledby="nav-home-tab"
                        tabindex="0">
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <label for="page_title" class="form-label">Título do Navegador</label>
                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    id="page_title"
                                    name="page_title"
                                    maxlength="200"
                                    value="{{ $parameters->page_title }}" />
                            </div>
                            <div class="col-lg-6 mb-4">
                                <label for="official_site" class="form-label">Site Oficial</label>
                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    id="official_site"
                                    name="official_site"
                                    maxlength="200"
                                    value="{{ $parameters->official_site }}" />
                            </div>

                            <div class="col-lg-6 mb-4">
                                <label for="apicep" class="form-label">API | Buscar Endereços</label>
                                <select
                                    class="select2 form-select mb-3"
                                    aria-label="Api de Endereço"
                                    id="apicep"
                                    name="apicep">
                                    <option value="" selected>Selecione...</option>
                                    <option value="0" {{ $parameters->apicep == "0" ? "selected" : "" }}>
                                        ViaCEP
                                    </option>
                                    <option value="1" {{ $parameters->apicep == "1" ? "selected" : "" }}>
                                        ApiCEP
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div
                        class="tab-pane fade pt-3"
                        id="nav-profile"
                        role="tabpanel"
                        aria-labelledby="nav-profile-tab"
                        tabindex="0">
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <label for="instagram_link" class="form-label">Url do Instagram</label>
                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    id="instagram_link"
                                    name="instagram_link"
                                    maxlength="200"
                                    value="{{ $parameters->instagram_link }}" />
                            </div>

                            <div class="col-lg-6 mb-4">
                                <label for="facebook_link" class="form-label">Url do Facebook</label>
                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    id="facebook_link"
                                    name="facebook_link"
                                    maxlength="200"
                                    value="{{ $parameters->facebook_link }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section("scripts")
    @vite(["resources/assets/systems/tenant/modules/admin/pages/configs/js/parameters.js"])
@endsection

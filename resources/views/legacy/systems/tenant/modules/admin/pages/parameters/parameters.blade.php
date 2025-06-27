@extends("legacy.systems.tenant.modules.admin.layouts.main")

@section("styles")
    
@endsection

@section("content")
    <div class="card">
        <div class="border-top">
            <div class="card-body">
                <div class="col-sm-12 text-right">
                    <button id="btnSave" type="button" class="btn btn-success btn-sm">Salvar</button>
                </div>
            </div>
        </div>
        <div class="card mb-0 border-top">
            <form
                id="formPage"
                class="form-horizontal"
                attr-save="{{ route("parameters.update") }}"
                attr-redirect="{{ route("parameters.list") }}">
                @csrf
                {{-- GERAL --}}
                <div class="card-body">
                    <div class="card-body">
                        {{-- Field: page_title --}}
                        <div class="form-group row">
                            <div class="col-sm-3 text-right">
                                <label for="page_title" class="control-label col-form-label">Título Navegador</label>
                            </div>
                            <div class="col-sm-8">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="page_title"
                                    name="page_title"
                                    placeholder="Marca"
                                    maxlength="50"
                                    value="{{ $parameters->page_title }}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 text-right">
                                <label for="apicep" class="control-label col-form-label">API | Buscar Endereços</label>
                            </div>
                            <div class="col-sm-8">
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
                </div>
                {{-- MÍDIAS SOCIAIS --}}
                <div class="card-body">
                    <h6>Mídias Sociais</h6>
                    <div class="border-top">
                        <div class="card-body">
                            {{-- Field: official_site --}}
                            <div class="form-group row">
                                <div class="col-sm-3 text-right">
                                    <label for="official_site" class="control-label col-form-label">
                                        Url site da Marca
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input
                                        type="text"
                                        class="form-control text-lowercase"
                                        id="official_site"
                                        name="official_site"
                                        placeholder="https://www.marca.com.br"
                                        maxlength="200"
                                        value="{{ $parameters->official_site }}" />
                                </div>
                            </div>
                            {{-- Field: facebook_title --}}
                            <div class="form-group row">
                                <div class="col-sm-3 text-right">
                                    <label for="facebook_title" class="control-label col-form-label">
                                        Facebook Título
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="facebook_title"
                                        name="facebook_title"
                                        placeholder="/marcaoficial"
                                        maxlength="200"
                                        value="{{ $parameters->facebook_title }}" />
                                </div>
                            </div>
                            {{-- Field: facebook_link --}}
                            <div class="form-group row">
                                <div class="col-sm-3 text-right">
                                    <label for="facebook_link" class="control-label col-form-label">
                                        Facebook Link
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input
                                        type="text"
                                        class="form-control text-lowercase"
                                        id="facebook_link"
                                        name="facebook_link"
                                        placeholder="https://www.facebook.com/marcaoficial"
                                        maxlength="200"
                                        value="{{ $parameters->facebook_link }}" />
                                </div>
                            </div>
                            {{-- Field: facebook_title --}}
                            <div class="form-group row">
                                <div class="col-sm-3 text-right">
                                    <label for="instagram_title" class="control-label col-form-label">
                                        Instagram Título
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="instagram_title"
                                        name="instagram_title"
                                        placeholder="@marcaoficial"
                                        maxlength="200"
                                        value="{{ $parameters->instagram_title }}" />
                                </div>
                            </div>
                            {{-- Field: instagram_link --}}
                            <div class="form-group row">
                                <div class="col-sm-3 text-right">
                                    <label for="instagram_link" class="control-label col-form-label">
                                        Instagram Link
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input
                                        type="text"
                                        class="form-control text-lowercase"
                                        id="instagram_link"
                                        name="instagram_link"
                                        placeholder="https://www.instagram.com/croasonhonomedaloja"
                                        maxlength="200"
                                        value="{{ $parameters->instagram_link }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FERRAMENTAS --}}
                <div class="card-body">
                    <h6>Ferramentas</h6>
                    <div class="border-top">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-3 text-right">
                                    <label for="ifood_site" class="control-label col-form-label">
                                        Url Ifood da Marca
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input
                                        type="text"
                                        class="form-control text-lowercase"
                                        id="ifood_site"
                                        name="ifood_site"
                                        placeholder="https://www.ifood.com.br/delivery/descobrir/lista/..."
                                        maxlength="200"
                                        value="{{ $parameters->ifood_site }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section("scripts")
    @vite(["resources/assets/panel/pages/parameters/js/parameters_list.js"])
@endsection

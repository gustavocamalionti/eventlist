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
                <a
                    id="btnCancel"
                    href="{{ route("form.configs.list") }}"
                    type="button"
                    class="btn btn-secondary btn-sm">
                    Cancelar
                </a>
            </div>

            <div class="card mb-0 border-top">
                <div class="card-body table-responsive divElementGridFather p-1">
                    <form
                        id="formConfigForm"
                        class="form-horizontal"
                        attr-save="{{ $formConfig != null ? route("form.configs.update", ["id" => $formConfig->id]) : route("form.configs.store") }}"
                        attr-redirect="{{ route("form.configs.list") }}">
                        <div class="card-body">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="mb-2">
                                        <label for="name" class="form-label">Nome *</label>
                                        <input
                                            id="name"
                                            type="text"
                                            class="form-control @error("name") is-invalid @enderror"
                                            name="name"
                                            value="{{ isset($formConfig) ? $formConfig->name : "" }}"
                                            autocomplete="name"
                                            maxlength="255" />
                                    </div>
                                </div>
                                <div class="col-12 col-lg-7">
                                    <div class="mb-2">
                                        <label for="email" class="form-label">E-mail *</label>
                                        <input
                                            id="email"
                                            type="email"
                                            class="form-control @error("email") is-invalid @enderror"
                                            name="email"
                                            value="{{ isset($formConfig) ? $formConfig->email : "" }}"
                                            autocomplete="email"
                                            maxlength="255" />
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="form-group mb-2">
                                        <label for="forms_id" class="form-label">Formulário *</label>
                                        <select name="forms_id" id="forms_id" class="select2 form-select">
                                            <option value="">Selecione...</option>

                                            @foreach ($forms as $form)
                                                <option
                                                    value="{{ $form->id }}"
                                                    {{ isset($formConfig) && $form->id == $formConfig->formSubject->forms_id ? "selected" : null }}>
                                                    {{ $form->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-7">
                                    <div class="form-group mb-2">
                                        <label for="form_subjects_id" class="form-label">Assunto *</label>
                                        <select
                                            name="form_subjects_id"
                                            id="form_subjects_id"
                                            class="select2 form-select">
                                            <option value="">Selecione...</option>

                                            @foreach ($formSubjects as $formSubject)
                                                <option
                                                    value="{{ $formSubject->id }}"
                                                    {{ isset($formConfig) && $formSubject->id == $formConfig->form_subjects_id ? "selected" : null }}>
                                                    {{ $formSubject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group mb-2">
                                        <div>
                                            <label for="active" class="control-label col-form-label">Status</label>
                                        </div>
                                        <div>
                                            <input
                                                type="checkbox"
                                                id="active"
                                                name="active"
                                                class="toggle-input"
                                                data-onvalue="1"
                                                data-offvalue="0"
                                                data-toggle="toggle"
                                                data-onlabel="Ativo"
                                                data-offlabel="Inativo"
                                                data-onstyle="primary"
                                                {{ ! isset($formConfig) || $formConfig->active == 1 ? "checked" : null }} />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    @vite(["resources/assets/panel/pages/forms/js/form_configs_maintenance.js"])
@endsection

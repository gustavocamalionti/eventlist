@extends("legacy.systems.tenant.modules.admin.layouts.main")

@section("content")
    <div class="card card-default mt-4">
        <div class="card-header">Perfil | Senha</div>
        <div class="card-body">
            <div id="bar_buttons" class="inline mb-3">
                <button id="btnAlterPassword" type="button" class="btn btn-success btn-sm">Alterar Senha</button>
            </div>
            <div class="card mb-0 border-top">
                <div class="card-body table-responsive divElementGridFather p-1">
                    <form
                        method="POST"
                        name="editAlterPassword"
                        id="editAlterPassword"
                        attr-save="{{ route("tenant.admin.profile.update.password") }}"
                        attr-list="{{ route("tenant.admin.profile.edit") }}">
                        <div class="card-body">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $user->id }}" id="user_id" name="user_id" />
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Senha</label>
                                        <input
                                            id="password"
                                            type="password"
                                            class="form-control @error("password") is-invalid @enderror"
                                            name="password"
                                            autocomplete="new-password" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="password-confirm" class="form-label">Confirmar Senha</label>
                                        <input
                                            id="password-confirm"
                                            type="password"
                                            class="form-control"
                                            name="password_confirmation"
                                            autocomplete="new-password" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-default mt-4">
        <div class="card-header">Perfil | Dados Pessoais</div>
        <div class="card-body">
            <div id="bar_buttons" class="inline mb-3">
                <button id="btnSave" type="button" class="btn btn-success btn-sm">Atualizar Dados</button>
                <button id="btnDeleteUser" type="button" class="btn btn-danger btn-sm">Excluir Conta</button>
            </div>
            <div class="card mb-0 border-top">
                <div class="card-body table-responsive divElementGridFather p-1">
                    <form
                        method="POST"
                        name="editForm"
                        id="editForm"
                        attr-save="{{ route("tenant.admin.profile.update") }}"
                        attr-list="{{ route("tenant.admin.profile.edit") }}">
                        <div class="card-body">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $parameters->apicep }}" id="apicep" />
                            <input type="hidden" value="{{ $user->id }}" id="id" name="id" />
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="cpf" class="form-label">CPF *</label>
                                        <input
                                            type="text"
                                            name="cpf"
                                            id="cpf"
                                            class="form-control @error("cpf") is-invalid @enderror"
                                            data-mask-cpf
                                            value="{{ MaskFields($user->cpf, "###.###.###-##") }}"
                                            autofocus
                                            {{ $user->cpf != null ? "disabled" : null }}
                                            inputmode="text" />
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="mb-4">
                                        <label for="email" class="form-label">E-mail *</label>
                                        <input
                                            id="email"
                                            type="email"
                                            class="form-control @error("email") is-invalid @enderror"
                                            name="email"
                                            value="{{ $user->email }}"
                                            autocomplete="email"
                                            maxlength="255" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Campo Nome Completo -->
                                <div class="col-lg-5">
                                    <div class="mb-4">
                                        <label for="name" class="form-label">Nome Completo *</label>
                                        <input
                                            id="name"
                                            type="text"
                                            class="form-control @error("name") is-invalid @enderror"
                                            name="name"
                                            value="{{ $user->name }}"
                                            autocomplete="name"
                                            maxlength="255" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <!-- Campo Data de Nascimento -->
                                    <div class="mb-4 form-group">
                                        <label for="date_birth" class="form-label">Data de Nascimento</label>

                                        <div id="datepicker" class="input-group" style="float: left">
                                            <input
                                                id="date_birth"
                                                type="text"
                                                value="{{ isset($user->date_birth) ? date("d/m/Y", strtotime($user->date_birth)) : null }}"
                                                class="form-control @error("date_birth") is-invalid @enderror"
                                                name="date_birth"
                                                data-mask-date
                                                date />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="phone_cell" class="form-label">Telefone</label>
                                        <input
                                            id="phone_cell"
                                            type="text"
                                            class="form-control @error("phone_cell") is-invalid @enderror"
                                            name="phone_cell"
                                            value="{{ $user->phone_cell != null ? App\Libs\Utils::MaskPhone($user->phone_cell) : "" }}"
                                            autocomplete="phone_cell"
                                            maxlength="20"
                                            data-mask-phone-cell />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <!-- Campo CEP -->
                                    <div class="mb-4">
                                        <label for="zipcode" class="form-label">CEP</label>
                                        <input
                                            id="zipcode"
                                            type="text"
                                            class="form-control @error("zipcode") is-invalid @enderror"
                                            name="zipcode"
                                            data-mask-cep
                                            value="{{ $user->zipcode }}" />
                                    </div>
                                </div>
                                <!-- Campo Endereço -->
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="address" class="form-label">Endereço</label>
                                        <input
                                            id="address"
                                            type="text"
                                            class="form-control @error("address") is-invalid @enderror"
                                            name="address"
                                            maxlength="255"
                                            value="{{ $user->address }}" />
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <!-- Campo Número -->
                                    <div class="mb-4">
                                        <label for="number" class="form-label">Número</label>
                                        <input
                                            id="number"
                                            type="text"
                                            class="form-control @error("number") is-invalid @enderror"
                                            name="number"
                                            data-only-numbers
                                            maxlength="15"
                                            value="{{ $user->number }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-4">
                                        <label for="district" class="form-label">Bairro</label>
                                        <input
                                            id="district"
                                            type="text"
                                            class="form-control @error("district") is-invalid @enderror"
                                            name="district"
                                            maxlength="255"
                                            value="{{ $user->district }}" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <!-- Campo Complemento -->
                                    <div class="mb-4">
                                        <label for="complement" class="form-label">Complemento</label>
                                        <input
                                            id="complement"
                                            type="text"
                                            class="form-control @error("complement") is-invalid @enderror"
                                            name="complement"
                                            maxlength="255"
                                            value="{{ $user->complement }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-5">
                                    <!-- Campo Estado -->
                                    <div class="mb-4">
                                        <label for="states_id" class="form-label">Estado</label>
                                        <select name="states_id" id="states_id" class="select2 form-select">
                                            <option value="">Selecione...</option>
                                            @foreach ($states as $state)
                                                @if (isset($user->cities->states_id) && $state->id == $user->cities->states_id)
                                                    <option value="{{ $state->id }}" selected>
                                                        {{ $state->initials }}
                                                    </option>
                                                @else
                                                    <option value="{{ $state->id }}">
                                                        {{ $state->initials }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-7">
                                    <!-- Campo Cidade -->
                                    <div class="mb-4">
                                        <label for="cities_id" class="form-label">Cidade</label>

                                        <select name="cities_id" id="cities_id" class="select2 form-select">
                                            <option value="">Selecione...</option>

                                            @foreach ($cities as $citie)
                                                @if (isset($user->cities_id) && $citie->id == $user->cities_id)
                                                    <option value="{{ $citie->id }}" selected>
                                                        {{ $citie->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $citie->id }}">
                                                        {{ $citie->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-2">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value=""
                                                name="permission_accept"
                                                id="permission_accept"
                                                {{ $user->permission_accept ? "checked" : "" }} />
                                            <label class="form-check-label" for="permission_accept">
                                                Concordo com a coleta e o uso dos meus dados pessoais conforme a
                                                <a href="{{ route("tenant.site.privacy.policy") }}">
                                                    Política de Privacidade.
                                                </a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <!-- Campo Complemento -->
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value=""
                                                name="news_accept"
                                                id="news_accept"
                                                {{ $user->news_accept ? "checked" : "" }} />
                                            <label class="form-check-label" for="news_accept">
                                                Quero receber notícias, ofertas e atualizações por e-mail.
                                            </label>
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
    @vite(["resources/assets/systems/tenant/modules/admin/pages/profile/js/profile_maintenance.js"])
@endsection

@extends('panel.layouts.master_panel')

@section('styles')
@endsection

@section('content')
    <div class="card card-default">
        <div class="card-header">
            {{ $pageTitle . ' | ' . $subTitle }}
        </div>
        <div class="card-body">
            <div id="bar_buttons" class="inline mb-3">
                <button id="btnSave" type="button" class="btn btn-success btn-sm">Salvar</button>
                <a id="btnCancel" href="{{ route('stores.list') }}" type="button" class="btn btn-secondary btn-sm">
                    Cancelar
                </a>
            </div>

            <div class="card mb-0 border-top">
                <div class="card-body table-responsive divElementGridFather p-1">
                    <form id="storeForm" class="form-horizontal"
                        attr-save="{{ $store != null ? route('stores.update', ['id' => $store->id]) : route('stores.store') }}"
                        attr-list="{{ route('stores.list') }}">
                        <div class="card-body">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $parameters->apicep }}" id="apicep" />
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="mb-2">
                                        <label for="cnpj" class="form-label">CNPJ</label>
                                        <input type="text" name="cnpj" id="cnpj"
                                            class="form-control @error('cnpj') is-invalid @enderror" data-mask-cnpj
                                            value="{{ isset($store) ? MaskFields($store->cnpj, '##.###.###/####-##') : '' }}"
                                            autocomplete="cnpj" autofocus />
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="mb-2">
                                        <label for="name" class="form-label">Nome Completo *</label>
                                        <input id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ isset($store) ? $store->name : '' }}" autocomplete="name"
                                            maxlength="255" />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-2">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ isset($store) ? $store->email : '' }}" autocomplete="email"
                                            maxlength="255" />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-2">
                                        <label for="phone1" class="form-label">Telefone *</label>
                                        <input id="phone1" type="text"
                                            class="form-control @error('phone1') is-invalid @enderror" name="phone1"
                                            value="{{ isset($store) ? App\Libs\Utils::MaskPhone($store->phone1) : '' }}"
                                            autocomplete="phone1" maxlength="20" data-mask-phone-cell />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <!-- Campo CEP -->
                                    <div class="mb-2">
                                        <label for="zipcode" class="form-label">CEP *</label>
                                        <input id="zipcode" type="text"
                                            class="form-control @error('zipcode') is-invalid @enderror" name="zipcode"
                                            data-mask-cep value="{{ isset($store) ? $store->zipcode : '' }}" />
                                    </div>
                                </div>
                                <!-- Campo Endereço -->
                                <div class="col-lg-3">
                                    <div class="mb-2">
                                        <label for="address" class="form-label">Endereço *</label>
                                        <input id="address" type="text"
                                            class="form-control @error('address') is-invalid @enderror" name="address"
                                            maxlength="255" value="{{ isset($store) ? $store->address : '' }}" />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <!-- Campo Número -->
                                    <div class="mb-2">
                                        <label for="number" class="form-label">Número *</label>
                                        <input id="number" type="text"
                                            class="form-control @error('number') is-invalid @enderror" name="number"
                                            data-only-numbers maxlength="15"
                                            value="{{ isset($store) ? $store->number : '' }}" />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-2">
                                        <label for="district" class="form-label">Bairro *</label>
                                        <input id="district" type="text"
                                            class="form-control @error('district') is-invalid @enderror" name="district"
                                            maxlength="255" value="{{ isset($store) ? $store->district : '' }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <!-- Campo Complemento -->
                                    <div class="mb-2">
                                        <label for="complement" class="form-label">Complemento</label>
                                        <input id="complement" type="text"
                                            class="form-control @error('complement') is-invalid @enderror"
                                            name="complement" maxlength="255"
                                            value="{{ isset($store) ? $store->complement : '' }}" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <!-- Campo Estado -->
                                    <div class="mb-2">
                                        <label for="states_id" class="form-label">Estado *</label>
                                        <select name="states_id" id="states_id" class="select2 form-select">
                                            <option value="">Selecione...</option>
                                            @foreach ($states as $state)
                                                @if (isset($store) && $state->id == $store->cities->states_id)
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

                                <div class="col-lg-4">
                                    <!-- Campo Cidade -->
                                    <div class="mb-2">
                                        <label for="cities_id" class="form-label">Cidade *</label>
                                        <select name="cities_id" id="cities_id" class="select2 form-select">
                                            <option value="">Selecione...</option>
                                            @if (isset($store))
                                                @foreach ($cities as $citie)
                                                    @if (isset($store) && $citie->id == $store->cities_id)
                                                        <option value="{{ $citie->id }}" selected>
                                                            {{ $citie->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $citie->id }}">
                                                            {{ $citie->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <!-- Campo Cidade -->
                                    <div class="mb-2">
                                        <label for="brands_id" class="form-label d-flex">Marca</label>
                                        <select class="form-select select2" aria-label="Default select example"
                                            id="brands_id" name="brands_id">
                                            <option class="option-custom" value="" selected>Selecione...</option>
                                            @foreach ($brands as $item)
                                                @if ($item->id != \App\Libs\Enums\EnumBrand::HALIPAR)
                                                    <option class="option-custom" value="{{ $item->id }}"
                                                        {{ isset($store) && $store->brands_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->title }}
                                                    </option>
                                                @endif
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
                                            <input type="checkbox" id="active" name="active" class="toggle-input"
                                                data-onvalue="1" data-offvalue="0" data-toggle="toggle"
                                                data-onlabel="Ativo" data-offlabel="Inativo" data-onstyle="primary"
                                                {{ !isset($store) || $store->active == 1 ? 'checked' : null }} />
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

@section('scripts')
    @vite(['resources/assets/panel/pages/stores/js/stores_maintenance.js'])
@endsection

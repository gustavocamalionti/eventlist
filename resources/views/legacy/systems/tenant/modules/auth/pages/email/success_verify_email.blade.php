@extends('legacy.systems.tenant.general.layouts.email_master')

@section('content')
    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">Olá,</td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 12])

    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">
            Seu e-mail foi verificado com sucesso! Agora você pode acessar todos os recursos da nossa plataforma.
        </td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 20])

    <tr>
        <td align="center">
            <a href="{{ env('APP_URL') }}" target="_blank"
                style="
                    display: inline-block;
                    font-size: 16px;
                    color: #11202b;
                    background-color: #fff;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                ">
                Acessar Aplicação
            </a>
        </td>
    </tr>
@endsection

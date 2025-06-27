@extends('legacy.systems.tenant.general.layouts.email_master')

@section('content')
    <tr>
        <td align="center" style="font-size: 24px; font-weight: bold; color: #11202b; text-align: center">
            Nome do Evento 2025
        </td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 12])

    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">Olá,</td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 12])

    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">
            Recebemos uma solicitação para redefinir sua senha. Clique no botão abaixo para continuar:
        </td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 20])

    <tr>
        <td align="center">
            <a href="{{ route('tenant.auth.password.reset', $token) }}" target="_blank"
                style="
                    display: inline-block;
                    font-size: 16px;
                    color: #11202b;
                    background-color: #fff;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                ">
                Redefinir Senha
            </a>
        </td>
    </tr>
@endsection

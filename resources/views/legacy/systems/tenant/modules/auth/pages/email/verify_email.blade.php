@extends('legacy.systems.tenant.general.layouts.email_master')

@section('content')
    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">Olá, {{ $data['name'] }},</td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 12])

    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">Confirme seu endereço de e-mail clicando no botão abaixo:
        </td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 20])

    <tr>
        <td align="center">
            <a href="{{ $data['verificationUrl'] }}" target="_blank"
                style="
                    display: inline-block;
                    font-size: 16px;
                    color: #11202b;
                    background-color: #fff;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                ">
                Verificar E-mail
            </a>
        </td>
    </tr>
@endsection

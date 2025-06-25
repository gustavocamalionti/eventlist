@extends('legacy.systems.tenant.general.layouts.email_master')

@section('content')
    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">
            27 de Janeiro de 2025 <br style="padding:0; margin:0;">
        </td>
    </tr>
    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 3])
    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">
            Santo Antônio, Indaiatuba SP <br style="padding:0; margin:0;">
        </td>
    </tr>
    {{-- @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 3])
    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">
            Das 08h00 às 22h00 <br style="padding:0; margin:0;">
        </td>
    </tr>
    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 3])
    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">
            Traje: Casual
        </td>
    </tr> --}}

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 12])
    <tr>
        <td align="center" style="font-size: 24px; font-weight: bold; color: #fff; text-align: center">
            Ingressos Confirmados
        </td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 24])

    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">Olá, {{ $data['buy']->customers->name }}</td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 12])

    <tr>
        <td style="font-size: 16px; color: #fff; text-align: left">Segue abaixo todos os seus ingressos:</td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 20])

    <tr>
        <td>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" border="0"
                style="font-family: Arial, sans-serif; font-size: 14px">
                <tbody>
                    @php
                        $count = 0;
                    @endphp

                    @foreach ($data['vouchers'] as $voucher)
                        @php
                            $count += 1;
                        @endphp

                        <!-- Caixa externa simulando um card -->
                        <tr>
                            <td bgcolor="#F8FAFC">
                                <table width="100%" cellspacing="0" cellpadding="0" bgcolor="#F9F9F9"
                                    style="
                                        font-family: Arial, sans-serif;
                                        font-size: 14px;
                                        border-collapse: collapse;
                                        border: 1px solid #d0cece;
                                    ">
                                    <tr height="20">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr height="25">
                                        <td width="20"></td>
                                        <td style="font-weight: bold; text-align: left">Loja:</td>
                                        <td style="text-align: right">
                                            {{ Str::upper($voucher->stores->brands->title) }} -
                                            {{ $voucher->stores->name }}
                                        </td>
                                    </tr>
                                    <tr height="25">
                                        <td width="20"></td>
                                        <td style="font-weight: bold; text-align: left">Nome:</td>
                                        <td style="text-align: right">{{ $voucher->name }}</td>
                                        <td width="20"></td>
                                    </tr>
                                    <tr height="25">
                                        <td width="20"></td>
                                        <td style="font-weight: bold; text-align: left">Email:</td>
                                        <td style="text-align: right">{{ $voucher->email }}</td>
                                        <td width="20"></td>
                                    </tr>
                                    <tr height="25">
                                        <td width="20"></td>
                                        <td style="font-weight: bold; text-align: left">Camiseta:</td>
                                        <td style="text-align: right">{{ $voucher->tshirt }}</td>
                                        <td width="20"></td>
                                    </tr>

                                    <tr height="25">
                                        <td width="20"></td>
                                        <td style="font-weight: bold; text-align: left">Valor:</td>
                                        <td style="text-align: right">
                                            R$ {{ number_format($voucher->value / 100, 2, ',', '.') }}
                                        </td>
                                        <td width="20"></td>
                                    </tr>
                                    <tr height="20">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- Espaço entre cards -->
                        @include('legacy.systems.tenant.general.includes._email_space_height', [
                            'height' => 20,
                        ])
                    @endforeach
                </tbody>
            </table>
        </td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 20])

    <tr>
        <td align="center" style="font-size: 24px; font-weight: bold; color: #fff; text-align: center">Parabéns! Nos
            encontramos em breve!</td>
    </tr>
    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 12])

    <tr>
        <td style="font-size: 16px; color: #fff; text-align: center">Em caso de dúvidas é só entrar em contato com nosso
            time <br>
            através do e-mail: <b>eventos@halipar.com.br</b></td>
    </tr>

    @include('legacy.systems.tenant.general.includes._email_space_height', ['height' => 20])
@endsection

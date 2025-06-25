<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield(env('APP_NAME'), 'Minha Aplicação')</title>
</head>

<body style="margin: 0; padding: 0; background-color: #ffffff; font-family: Arial, sans-serif">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        @include('legacy.systems.master.general.includes._email_space_height', ['height' => 10])
        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" border="0" bgcolor="#11202B"
                    style="max-width: 600px; border-spacing: 0; text-align: center">
                    <tr>
                        <td style="text-align: center; width: 600px" colspan="3">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                @include('legacy.systems.master.general.layouts.email_header')
                            </table>
                        </td>
                    </tr>

                    @include('legacy.systems.master.general.includes._email_space_height', [
                        'height' => 20,
                    ])

                    <tr>
                        <td style="width: 30px"></td>
                        <td style="width: 540px; text-align: center">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                @yield('content')
                            </table>
                        </td>
                        <td style="width: 30px"></td>
                    </tr>

                    @include('legacy.systems.master.general.includes._email_space_height', [
                        'height' => 20,
                    ])

                    <tr>
                        <td style="width: 30px"></td>
                        <td style="width: 540px; text-align: center">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                @include('legacy.systems.master.general.layouts.email_footer')
                            </table>
                        </td>
                        <td style="width: 30px"></td>
                    </tr>

                    @include('legacy.systems.master.general.includes._email_space_height', [
                        'height' => 20,
                    ])
                </table>
            </td>
        </tr>
    </table>
</body>

</html>

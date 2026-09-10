<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    @yield('page-style')
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
        <thead>
            <tr>
                <td colspan="2">
                    <table width="100%" style="border-collapse: collapse;" class="border">
                        <tr>
                            <td style="text-align: center; padding: 15px;">
                                <img src="{{ 'img/provinsi-rs.svg' }}" width="100px" height="100px"
                                    style="display: block;">
                            </td>

                            <td style="text-align: center;">
                                <span style="font-weight: bold;">UOBK RSUD MALANGBONG</span> <br>
                                <span>Jl. Raya Ciawi-Malangbong, Sukamanah, Kec. Malangbong, Kab Garut, Garut, Jawa Barat</span><br>
                                <span>02625705007</span><br>
                                <span>E-mail : rsudmalangbonggarut@gmail.com</span>
                            </td>
                            <td style="text-align: center; padding: 15px;">
                                <img src="{{ 'img/logo-rs.png' }}" width="100px" height="100px"
                                    style="display: block;">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">
                    <table class="border" width="100%" cellspacing="0" cellpadding="0"
                        style="border-collapse: collapse;border-top: none;">
                        @yield('content')
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>

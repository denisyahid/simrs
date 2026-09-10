<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    @yield('page-style')
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black;border-collapse: collapse;">
        <tr>
            <td style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;">
                UOBK RSUD MALANGBONG
            </td>
            <td style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                @yield('kode')
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black">
                <table width="100%" style="border-collapse: collapse">
                    <tr style="border: ">
                        <td width="15%" style="text-align: center;padding: 10px">
                            <img src="{{ 'img/provinsi-rs.svg' }}" width="80px" height="80px"
                                style="display: block;">
                        </td>
                        <td width="70%" style="text-align: center;">
                            <span>
                                Jl. Raya Ciawi-Malangbong, Sukamanah, Kec. Malangbong, Kab Garut, Garut, Jawa Barat<br>
                                02625705007
                                E-mail : rsudmalangbonggarut@gmail.com
                            </span>
                        </td>
                        <td width="15%" style="text-align: center;padding: 10px">
                            <img src="{{ 'img/logo-rs.png' }}" width="80px" height="80px" style="display: block;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                    @yield('content')
                </table>
            </td>
        </tr>
    </table>
</body>

</html>

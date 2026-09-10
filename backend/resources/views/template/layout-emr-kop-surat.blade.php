<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    @yield('page-style')
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0"
        style="border: 1px solid black;border-collapse: collapse">
        <thead>
            <tr>
                <td style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;">
                    RSUD MALANGBONG
                </td>
                <td style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                    @yield('kode')
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid black">
                    <img src="{{ 'img/kop-surat.png' }}" width="100%" height="80px" style="display: block !important;">
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0"
                        style="border-collapse: collapse;">
                        @yield('content')
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>

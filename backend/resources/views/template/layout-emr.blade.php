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
                    <table width="100%" style="border-collapse: collapse;border-bottom: none" class="border">
                        <th
                            style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;text-align: left">
                            RSUD MALANGBONG
                        </th>
                        <th
                            style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                            @yield('kode')
                        </th>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" style="border-collapse: collapse;">
                        <td width="10%" style="text-align: center;padding: 10px" class="border">
                            <img src="{{ 'img/logo-rs.png' }}" width="80px" height="80px" style="display: block;">
                        </td>
                        <td width="40%"
                            style="vertical-align: middle;text-align: center;font-weight: bold;font-size: large"
                            class="border">
                            @yield('title')
                        </td>
                        <td width="40%" style="padding: 5px" class="border">
                            <table style="width: 100%">
                                <tr style="font-size: 10pt">
                                    <td style="text-align:left;width: 40%">Nama</td>
                                    <td style="text-align:left;width: 10%;text-align: center">:</td>
                                    <td style="text-align:left;width: 50%">{{ $pasien['namapasien'] }}</td>
                                </tr>
                                <tr style="font-size: 10pt">
                                    <td style="text-align:left;width: 40%">Tanggal Lahir</td>
                                    <td style="text-align:left;width: 10%;text-align: center">:</td>
                                    <td style="text-align:left;width: 50%">{{ $pasien['tgllahir'] }}</td>
                                </tr>
                                <tr style="font-size: 10pt">
                                    <td style="text-align:left;width: 40%">Jenis Kelamin</td>
                                    <td style="text-align:left;width: 10%;text-align: center">:</td>
                                    <td style="text-align:left;width: 50%">{{ $pasien['jeniskelamin'] }}</td>
                                </tr>
                                <tr style="font-size: 10pt">
                                    <td style="text-align:left;width: 40%">No. Rekam Medis</td>
                                    <td style="text-align:left;width: 10%;text-align: center">:</td>
                                    <td style="text-align:left;width: 50%">{{ $pasien['nocm'] }}</td>
                                </tr>
                            </table>
                        </td>
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
                    <table class="table">
                        @yield('content2')
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>

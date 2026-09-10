<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Label Nama
    </title>
    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
    <link rel="stylesheet" href="css/paper.css">
    <link rel="stylesheet" href="css/table-v2.css">
    <link rel="stylesheet" href="css/tabel.css">
    @else
    <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    @endif
</head>
<style>
    /* .page-break {
        page-break-after: always;
    } */

    body,
    td,
    th,
    span,
    p {
        font-family: Tahoma, Geneva, sans-serif !important;
        font-size: 11px;
    }

    @page {
        size: auto;

    }

    @media print {
        table.receipt {
            width: 58mm;
        }
    }

    @media print {
        table.receipt {
            page-break-inside: avoid;
            page-break-before: always;
        }
    }

    /* td {
        border: 1px solid black;
    } */

    /* fix for Chrome */
</style>


<body>
    <table class="receipt" style="width:{{ $pageWidth }};background-color:#FFFFFF;padding:10px; border:0;margin-top:.3cm">
        <tbody>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td>
                                <div style="padding-top: 10px;">
                                    <span style="font-size: 19pt;font-weight: bold; margin-top: 10px">
                                        {{ $dataReport['norm'] }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="padding-top: 4px;">
                                    <span style="font-size: 19pt; font-weight: bold;">
                                        {{ $dataReport['kelompokpasien'] }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="padding-top: 4px;">
                                    <span style="font-size: 19pt;font-weight: bold">
                                        {{ $dataReport['namapasien'] }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="padding-top: 4px;">
                                    <span style="font-size: 19pt;font-weight: bold">
                                        {{ $dataReport['tgllahir'] }} / {{ $dataReport['umur'] }}
                                    </span>
                            </td>
                            </div>
                        </tr>
                        <tr>
                            <td>
                                <div style="padding-top: 4px;">
                                    <span style="font-size: 19pt;font-weight: bold">
                                        {{ $dataReport['jeniskelamin'] }}
                                    </span>
                            </td>
                            </div>
                        </tr>
                        <tr>
                            <td style="max-width: 10%;">
                                <div style="padding-top: 4px;">
                                    <span style="font-size: 14pt;font-weight: bold">
                                        {{ $dataReport['alamatrmh'] }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="max-width: 10%;">
                                <div style="padding-top: 4px;">
                                    <span style="font-size: 14pt;font-weight: bold">
                                        {{ $dataReport['nohp'] }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
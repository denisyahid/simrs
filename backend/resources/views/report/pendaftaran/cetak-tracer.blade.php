<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak tracer</title>
    @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), 'transmedika') !== false)
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@else
    <link href="{{ asset('service/css/style.css') }}" rel="stylesheet">
@endif
    <style>
        div{
            border :solid #6b6a6a 1px;
            border-radius: 5px;
            padding-left: 15px;
            justify-content: center;
            width: 12cm;
            padding: 20px 10px 20px 10px;
        }
        .barcode {
            font-size: 24pt;
            font-family: '3 of 9 Barcode', sans-serif;
            face: Tahoma;
        }

    </style>
    <style type="text/css" media="print">
        @media print {
            @page {
                size: auto;
                margin: 0;
            }

            footer {
                display: none
            }

            header {
                display: none
            }

            body {
                -webkit-print-color-adjust: exact !important;
            }
        }

        body {
            font-family: Tahoma, Geneva, sans-serif;
        }
    </style>
    <link href="https://fonts.cdnfonts.com/css/3-of-9-barcode" rel="stylesheet">

</head>
<body>
    <div>
        @if ($data)
        <table style="width:100%">
            <tr>
                <td width="29%" style="padding-bottom: 8px;">
                    <span style="font-size:10pt; font-weight:bold;"
                    face="Tahoma">Tanggal Daftar</span>
                </td>
                <td width="1%" style="padding-bottom: 8px;">
                    <span style="font-size:10pt; font-weight:bold;"
                    face="Tahoma">:</span>
                </td>
                <td width="70%" style="padding-bottom: 8px;">
                    <span style="font-size:10pt; font-weight:bold;"
                    face="Tahoma">{{$data->tglpendaftaran}}</span>
                </td>
            </tr>
            <tr>
                <td width="29%" style="padding-bottom: 8px;">
                    <span style="font-size:10pt; font-weight:bold;"
                    face="Tahoma">Nama</span>
                </td>
                <td width="1%" style="padding-bottom: 8px;">
                    <span style="font-size:10pt; font-weight:bold;"
                    face="Tahoma">:</span>
                </td>
                <td width="70%" style="padding-bottom: 8px;">
                    <span style="font-size:10pt; font-weight:bold;"
                    face="Tahoma">{{$data->namapasien}}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-bottom: 8px;">
                    <span style="font-size:15pt; font-weight:bold;"
                    face="Tahoma">{{$data->nocm}}</span>
                </td>
                <td style="padding-bottom: 8px;">
                    <span style="font-size:12pt; font-weight:bold;"
                    face="Tahoma">{{$data->kelompokpasien}}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-bottom: 15px;">
                    <span style="font-size:10pt; font-weight:bold;"
                    face="Tahoma">RM</span>
                </td>
                <td style="padding-bottom: 15px;">
                    <span style="font-size:10pt; font-weight:medium;"
                    face="Tahoma">{{$data->tglregistrasi}}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span style="font-size:20pt; font-weight:bold;"
                    face="Tahoma">{{$data->ruangan}}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center">
                    <font style="font-size: 15pt;font-family: '3 of 9 Barcode', sans-serif;"
                    face="Tahoma">{{$data->noregistrasi}}
                </font>
                </td>
            </tr>
            <tr>
                <td width="33%">
                    <span style="font-size:15pt; font-weight:bold;"
                    face="Tahoma">{{$data->noregistrasi}}</span>
                </td>
                <td width="1%">

                </td>
                <td width="33%">
                    Petugas Rs
                </td>
            </tr>
            <tr>
            </tr>
        </table>
        @else
        <table style="width:100%">
            <tr>
                <td colspan="4">Data Tidak Ditemukan</td>
            </tr>
        </table>
        @endif
    </div>
    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>

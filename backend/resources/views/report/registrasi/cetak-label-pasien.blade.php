<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Label Pasien
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
    font-size: 8px;
}

@page {
    size: auto;
    /* margin-bottom: 10mm !important; */
    /* margin-bottom: 6mm !important; */
}

@media print {
    table.receipt {
        page-break-inside: avoid;
        page-break-before: always;
    }

    table.receipt {
        width: 30mm;
        height: 25mm;
    }
}

/* fix for Chrome */
</style>


<body>
    <table class="receipt" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td>
                <table style="margin-left: 10px; margin-top:5px;margin-bottom:5px; font-weight: bold;padding-right: 5px"
                    cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="2">
                            {{-- <font style="font-size: 9pt; white-space: nowrap">{{ strtoupper($dataPasien->namapasien) }} --}}
                                <font style="font-size: 8pt; white-space: pre-line">{{ strtoupper($dataPasien->namapasien) }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <font style="font-size: 8pt;">{{ $dataPasien->nocm }} /
                                {{ date('d-F-Y', strtotime($dataPasien->tanggal_lahir)) }} &emsp;&emsp;&emsp;&emsp;
                                <font style="font-size: 10pt;">({{ $dataPasien->jeniskelamin }})</font>
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <font style="font-size: 8pt;">{{ $dataPasien->umur }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <font style="font-size: 7pt; white-space: nowrap">{{ strtoupper($dataPasien->alamatlengkap) }}
                            </font>
                        </td>
                    </tr>
                    @php
                    $barcode = $dataPasien->nocm;
                    @endphp
                    <tr>
                        <td colspan="2">
                            <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text={{ $barcode }}&scale=1&scaleY=1&scaleX=2"
                                style="height: 25px;width: 150px;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
<script type="text/javascript">
window.onload = function() {
    window.print();
}
</script>

</html>
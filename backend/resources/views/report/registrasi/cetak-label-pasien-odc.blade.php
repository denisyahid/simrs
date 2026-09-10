<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Cetak Label Pasien
    </title>
    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
        <link rel="stylesheet" href="css/paper.css">
        {{-- <link rel="stylesheet" href="css/table-v2.css">
        <link rel="stylesheet" href="css/tabel.css"> --}}
    @else
        <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    @endif
</head>
<style type="text/css">
    font{
        font-family: Tahoma, "Trebuchet MS", sans-serif !important;
        color: #000000 !important;
        font-weight: bold !important;
    }
    .col-4 {
        /* grid-column: span 50; */
        background: #FFFFFF;
        /* border: 1px solid black; */
        border-radius: 5px;
        width: 9.3cm;
        height: 4.6cm;
        min-height: 1;
        margin-left: .3cm;
        margin-top:.5cm;
        /* margin-right: 0px; */
    }

    .card {
        /* padding:1cm; */
        padding-top: .1cm;
        padding-bottom: 2px;
        padding-left: .4cm;
        padding-right: 2px;
        /* display: grid; */
    }
</style>

<body class="A5 lanscape" style="margin-right:0px">
    <table width="100%" cellspacing="0" cellpadding="0" style="padding: .4rem;margin-right:0px; margin-left: 50px">
            <tr>
                <td width="95%"></td>
                <td>
                    <div class="col-4"
                        style="@if (isset($res->blue) && $res->blue) background: #007BFF;color : #FFFFFF @endif">
                        <div class="card">
                            <table style="margin-left: 30px; margin-top:70px" cellspacing="10">
                                <tr>
                                    <td><font style="font-size: 15pt; font-weight:bold;">{{ $dataPasien->nocm }}</font></td>
                                </tr>
                                <tr>
                                    <td><font style="font-size: 15pt; font-weight:bold;">{{ $dataPasien->namapasien }}</font></td>
                                </tr>
                                <tr>
                                    <td><font style="font-size: 15pt; font-weight:bold;" >
                                        {{ date('d/m/Y', strtotime($dataPasien->tanggal_lahir)) }}</font></td>
                                </tr>
                                <tr>
                                    <td><font style="font-size: 15pt; font-weight:bold;">{{ $dataPasien->jeniskelamin }}</font></td>
                                </tr>
                            </table>
                        </div>
                    </div>
            </tr>
    </table>
</body>

</html>

<html>
<head>
    <title>
        Kwitansi
    </title>

@if(stripos(\Request::url(), 'localhost') !== FALSE  || stripos(\Request::url(), 'transmedika') !== FALSE)
    <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
@else
    <link rel="stylesheet" href="{{ asset('service/css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('service/css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('service/css/tabel.css') }}">
@endif

</head>
<style type="text/css" media="print">
 @page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>
<style>
    tr td {
        padding:2px 4px 2px 4px;
    }
    .borderss{
        border-bottom: 1px solid black;
    }
    body{
        font-family: Tahoma, Geneva, sans-serif;
    }
 @page { size: A4 } .garis6 td{padding:3px !important;}
</style>
@php
if(isset($_GET['namafile'])){
    header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=".$_GET['namafile'].".xls");
}
$noreg = $r['noregistrasi'];

@endphp
 <!-- onload="window.print()" -->
<body style="background-color: #CCCCCC" onLoad="window.print()" >

<div align="center">
    <table class="bayangprint" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="{{$pageWidth}}" style="padding:25px">
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td align="center" width="45%">
                                <font style="font-size: 10pt;" color="#000000"  >{{$profile->namapemerintahan}}</font>
                            </td>
                            <td align="right">
                            </td>
                        </tr>
                        <tr>
                            <td align="center"  width="45%">
                                <font style="font-size: 10pt;" color="#000000" >{{$profile->namalengkap}}</font>
                            </td>
                            <td align="right">
                            </td>
                        </tr>
                        <tr>
                            <td align="center"  width="45%">
                                <font style="font-size: 10pt;" color="#000000" >  {!! $profile->alamatlengkap  !!}   </font>
                            </td>
                            <td align="right">
                                <font style="margin-right: 100px">{{ $tglcetak }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td align="center"  width="45%">
                                <hr>
                            </td>
                        </tr>


                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" style="">
                        <tr>
                            <td align="center">
                                <font style="font-size: 14pt; font-weight: bold;" color="#000000">KWITANSI</font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%"><font style="font-size: 12pt;" color="#000000;" >No. Registrasi </font></td>
                            <td width="40%"><font style="font-size: 12pt;" color="#000000" >: {{ $identitas[0]->noregistrasi }}</font></td>

                            <td width="15%"><font style="font-size: 12pt;" color="#000000;" >
                                {{-- No. Kwitansi  --}}
                            </font></td>
                            <td width="25%"><font style="font-size: 12pt;" color="#000000" >
                                {{-- : {{ $identitas[0]->nokwitansi }} --}}
                            </font></td>
                        </tr>
                    </table>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%"><font style="font-size: 12pt;" color="#000000;" >Sudah Terima Dari </font></td>
                            <td width="40%"><font style="font-size: 12pt;" color="#000000" >: {{ strtoupper( $identitas[0]->namapenangung) }}</font></td>


                            <td width="15%"><font style="font-size: 12pt;" color="#000000;" > </font></td>
                            <td width="25%"><font style="font-size: 12pt;" color="#000000" ></font></td>
                        </tr>
                    </table>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%"><font style="font-size: 12pt;" color="#000000;" >Banyaknya Uang </font></td>
                            <td width="80%"><font style="font-size: 12pt;">: </font><font style="font-size: 16pt; font-weight: bold; font-style: italic;" color="#000000" > Rp. {{ number_format((float)$identitas[0]->totaldibayar, 2) }}</font></td>

                        </tr>
                    </table>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%"><font style="font-size: 12pt;" color="#000000;" >Terbilang </font></td>
                            <td width="80%"><font style="font-size: 12pt;" color="#000000" >: {{ strtoupper($terbilang)}} </font></td>

                        </tr>
                    </table>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%"><font style="font-size: 12pt;" color="#000000;" >Untuk Pembayaran</font></td>
                            <td width="80%"><font style="font-size: 12pt;" color="#000000" >: {{ strtoupper($identitas[0]->keteranganlainnya) }}</font></td>
                        </tr>
                    </table>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%"><font style="font-size: 12pt;" color="#000000;" >NAMA/MR/NO.REG </font></td>
                            <td width="80%"><font style="font-size: 12pt;" color="#000000" >: {{ strtoupper($identitas[0]->namapasien) }}/{{ strtoupper($identitas[0]->nocm) }}/{{ strtoupper($identitas[0]->noregistrasi) }}</font></td>
                        </tr>
                    </table>
                    <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 10px;margin-left: 600px;">
                        <tr>
                            <td width="100%"><font style="font-size: 12pt;margin-left: 50px;" color="#000000;" > {{$profile->namakota}}, {{ strtoupper($identitas[0]->tglsbm) }}</font></td>
                        </tr>
                    </table>
                    <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 50px;margin-left: 600px;">
                        <tr>
                            <td width="100%"><font style="font-size: 12pt;margin-left: 50px;" color="#000000;" >{{ ($namapegawai) }}</font></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

</html>

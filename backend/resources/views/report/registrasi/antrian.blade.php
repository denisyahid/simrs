<html>
<head>
    <title>
        Report
    </title>
    <link href="css/style.css" rel="stylesheet">
</head>
<style type="text/css" media="print">
    @media print
    {
        @page
        {
            size: auto;
            margin: 0;
            /* size: portrait; */
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
    tr td {
        /*padding:2px 4px 2px 4px;*/
    }
    .borderss{
        border-bottom: 1px solid black;
    }
    body{
        font-family: Tahoma, Geneva, sans-serif;
    }
</style>
{{----}}
<body style="background-color: #CCCCCC;margin: 0; padding-left: 10px;" onload="window.print()">
    <div align="left">
        @if($dataReport['loket'] == 2)

        <table width="365" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-bottom: 4px solid black;">
            <tr>
                <th align="center">
                    <img src="{{ asset('img/logo-rs.png') }}" width="80px">
                </th>
                <th width="90%" align="center">
                    <table width="100%">
                        <tr>
                            <td class="label-strong">
                                <div style="width: 100%; text-align: left; font-size: 8pt">
                                    <font>RUMAH SAKIT UMUM DAERAH
                                    </font>
                                    <br>
                                    <span style="text-align: center !important"><b>BALI MANDARA</b></span>
                                </div>
                                {{-- <div style="width: 80%; text-align: center">
                                </div> --}}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal" style="text-align:left">
                                <div style="width: 80%">
                                    <font style="font-size: 7pt;">{{$dataReport['alamat']}} <br> Telp. {{$dataReport['fixedphone']}}&nbsp;Fax. {{$dataReport['faksimile']}}</font>
                                </div>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>
        
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
            <tr>
                <td style="text-align: left">
                    <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="border-bottom: 2px solid black" width="365">
                        <tr>
                            <td  style="text-align: center; padding-bottom: 10px; margin-top: 10px;" align="center">
                                <font style="font-size: 14pt;text-align: center;padding-bottom: 5px;" face="Tahoma">{{$dataReport['nosep']}}</font>
                            </td>
                        </tr>

                        @php
                            $jenis = '';
                        @endphp
                            @if($dataReport['kode'] == 'OG')
                                @php
                                    $jenis = 'Pasien Lama Umum';
                                @endphp
                            @elseif($dataReport['kode'] == 'B')
                                @php
                                    $jenis = 'Pasien Baru';
                                @endphp
                            @elseif($dataReport['kode'] == 'LB')
                                @php
                                    $jenis = 'Pasien Lama BPJS';
                                @endphp
                            @elseif($dataReport['kode'] == 'LA')
                                @php
                                    $jenis = 'Pasien Lansia & Anak';
                                @endphp
                            @elseif($dataReport['kode'] == 'PN')
                                @php
                                    $jenis = 'Pasien WNA';
                                @endphp
                            @endif
                        <tr>
                            <td>
                                <div style="width: 70%; text-align: center">
                                    <font style="font-size: 14pt;text-align: left;" face="Tahoma"><b>{{ucwords($jenis)}}</b></font>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td height="40">
                                <div style="width: 70%; text-align: center">
                                    <font style="font-size: 48pt;text-align: left;" face="Tahoma"><b>{{$dataReport['noantrian']}}</b></font>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
                        <tr>
                            <td  style="text-align: left; padding-left: 15px;">
                                <font style="font-size: 10pt;text-align: left;" face="Tahoma">ANTRIAN SEBELUM ANDA {{$dataReport['jmlantrian'] - 1}} NOMOR</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: left">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="width: 75% !important;text-align: center">
                                    <font style="font-size: 7pt;text-align: center;" face="Tahoma"><b>
                                    Apabila menggunakan BPJS atau asuransi mohon<br>
                                    ke bagian pendaftaran terlebih dahulu
                                    </b></font>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: left">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: left">
                                <div style="width: 75%; text-align: center;">
                                    <font style="font-size: 10pt;" face="Tahoma">-TERIMA KASIH-</font>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: left">
                                <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365" style="margin-top: 10px;">
                                    <tr>
                                        <td  style="text-align: left" width="50%">
                                            <font style="font-size: 10pt;text-align: left;" face="Tahoma">{{$dataReport['hari']}}, {{ date('d-m-Y', strtotime($dataReport['tanggal']) ) }} <span style="padding-right: 10px"> - </span> <span style="padding-left: 10px;">Pukul {{ date('H:i') }}</span></font>
                                        </td>
                                        {{-- <td  style="text-align: right" width="50%">
                                            <font style="font-size: 10pt;text-align: left;" face="Tahoma">Pukul {{ date('H:i') }}</font>
                                        </td> --}}
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>

        @else
        @if(!str_contains($dataReport['kelompokpasien'], 'UMUM') || !str_contains($dataReport['kebangsaan'], 'WNI') || $dataReport['tipepasien'] == 'BARU' )
        <table width="365" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-bottom: 4px solid black;">
            <tr>
                <th align="center">
                    <img src="{{ asset('img/logo-rs.png') }}" width="80px">
                </th>
                <th width="90%" align="center">
                    <table width="100%"  style="position:relative">
                        <tr>
                            <td class="label-strong" style="text-align:center; font-size: 12pt;">
                                <font>RUMAH SAKIT UMUM DAERAH<br><b>BALI MANDARA</b></font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal" style="text-align:center">
                                <font style="font-size: 10pt;">{{$dataReport['alamat']}} <br> Telp. {{$dataReport['fixedphone']}}&nbsp;Fax. {{$dataReport['faksimile']}}</font>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>
        
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
            <tr>
                <td style="text-align: center">
                    <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="border-bottom: 2px solid black" width="365">
                        <tr>
                            <td  style="text-align: center; padding-bottom: 10px; margin-top: 10px;" align="center">
                                <font style="font-size: 14pt;text-align: center;padding-bottom: 5px;" face="Tahoma">{{$dataReport['nosep']}}</font>
                            </td>
                        </tr>
                        @php
                            $jenis = '';
                        @endphp
                            @if($dataReport['kode'] == 'OG')
                                @php
                                    $jenis = 'Pasien Lama Umum';
                                @endphp
                            @elseif($dataReport['kode'] == 'B')
                                @php
                                    $jenis = 'Pasien Baru';
                                @endphp
                            @elseif($dataReport['kode'] == 'LB')
                                @php
                                    $jenis = 'Pasien Lama BPJS';
                                @endphp
                            @elseif($dataReport['kode'] == 'LA')
                                @php
                                    $jenis = 'Pasien Lansia & Anak';
                                @endphp
                            @elseif($dataReport['kode'] == 'PN')
                                @php
                                    $jenis = 'Pasien WNA';
                                @endphp
                            @endif
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 16pt;text-align: left;" face="Tahoma">{{ucwords($dataReport['namaruangan'])}}</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 14pt;text-align: left;" face="Tahoma">{{ucwords($dataReport['dpjp'])}}</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 16pt;text-align: left;" face="Tahoma"><b>{{ucwords($jenis)}}</b></font>
                            </td>
                        </tr>
                        <tr>
                            <td height="40" style="text-align: center">
                                <font style="font-size: 50pt;text-align: left;" face="Tahoma"><b>{{$dataReport['noantrian']}}</b></font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 16pt;text-align: left;" face="Tahoma"><b>{{$dataReport['noreservasi']}}</b></font>
                            </td>
                        </tr>
                        @php
                            $norm = $dataReport['nocm'];
                        @endphp
                        <tr>
                            <td  style="text-align: center">
                            @if($dataReport['nocm'] != null)
                            <img src='https://barcode.tec-it.com/barcode.ashx?data={{$norm}}&code=Code39&dpi=96&dataseparator='
                            style=" height: 50px;width: 200px;-webkit-user-select: none;cursor:pointer"/>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center" height="15"></td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 16pt;text-align: left;" face="Tahoma">{{strtoupper($dataReport['namalengkap'])}}</font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">ANTRIAN SEBELUM ANDA {{$dataReport['jmlantrian'] - 1}} NOMOR</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: left">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 9pt;text-align: center;" face="Tahoma"><b>
                                Apabila menggunakan BPJS atau asuransi mohon<br>
                                ke bagian pendaftaran terlebih dahulu
                                </b></font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: left">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 12t;text-align: center;" face="Tahoma">-TERIMA KASIH-</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: left">
                                <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365" style="margin-top: 10px;">
                                    <tr>
                                        <td  style="text-align: left" width="50%">
                                            <font style="font-size: 10pt;text-align: left;" face="Tahoma">{{$dataReport['hari']}}, {{ date('d-m-Y', strtotime($dataReport['tanggal']) ) }}</font>
                                        </td>
                                        <td  style="text-align: right" width="50%">
                                            <font style="font-size: 10pt;text-align: left;" face="Tahoma">Pukul {{ date('H:i') }}</font>
                                        </td>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        @endif

        @if($dataReport['isregistrasi'] == true)
        @if(!str_contains($dataReport['kelompokpasien'], 'UMUM'))
        <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365" style="margin-top: 90px;">
        @else
        <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365" style="margin-top: 10px;">
        @endif
            <tbody>
                <tr>
                    <td style="padding: -px;text-align: left">
                        <table width="100%" cellspacing="0" cellpadding="0" >
                            <tr>
                                <th>
                                <img src="{{ asset('img/logo-rs.png') }}" width="80px">
                                </th>
                                <th width="90%">
                                    <table width="100%"  style="position:relative">
                                        <tr>
                                            <td class="label-strong" style="text-align:left; margin-left: 0px; font-size: 11pt;">
                                                <font>BUKTI PENDAFTARAN <br> {{ $dataReport['namaprofile'] }}</font>
                                            </td>
                                        </tr>
                                    </table>
                                </th>
                            </tr>
                        </table>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
                            @php
                                $noregistrasi = $dataReport['noregistrasi'];
                            @endphp
                            <tr>
                                <td height="10" colspan="2">
                            </tr>
                            <tr>
                                <td height="5" colspan="2">
                                    <img src='https://barcode.tec-it.com/barcode.ashx?data={{$noregistrasi}}&code=Code39&dpi=96&dataseparator='
                                    style=" height: 50px;width: 200px;-webkit-user-select: none;cursor:pointer"/>
                                </td>
                            </tr>
                            <tr>
                                <td height="15" width="50%"></td>
                                <td height="15" width="50%"></td>
                            </tr>
                            <tr>
                                <td height="5" width="50%">
                                    <span style="font-size: 12pt;" color="#000000">{{ date('l, j F, Y', strtotime($dataReport['tglregistrasi'])) }} - {{ date('H:i') }}</span>
                                </td>
                                {{-- <td height="5" width="50%" align="right">
                                    <span style="font-size: 12pt;" color="#000000">{{ date('H:i') }}</span>
                                </td> --}}
                            </tr>
                            @php
                                $norm = $dataReport['norm'];
                            @endphp
                            @if($norm != null && $norm != 'undefined')
                            <tr>
                                <td height="5" colspan="2">
                                    <img src='https://barcode.tec-it.com/barcode.ashx?data={{$norm}}&code=Code39&dpi=96&dataseparator='
                                    style=" height: 50px;width: 180px;-webkit-user-select: none;cursor:pointer"/>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td height="5" colspan="2">
                                    <span style="font-size: 12pt;" color="#000000">{{ strtoupper($dataReport['namapasien']) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2">
                                    <span style="font-size: 12pt;" color="#000000">{{ $dataReport['kebangsaan'] ? strtoupper($dataReport['kebangsaan']) : '' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2">
                                    <span style="font-size: 12pt;" color="#000000">{{ $dataReport['umur'] }}</span>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" style="padding-left: 10px; !important">
                            <tr>
                                <td height="5" colspan="2" style="padding-left:40px">
                                    <span style="font-size: 12pt;" color="#000000"><b>{{ $dataReport['noantrianpoli'] }}</b></span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2" style="padding-left:10px">
                                    <span style="font-size: 12pt;" color="#000000"><b>{{ $dataReport['namaruangan'] }}</b></span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2" style="padding-left:10px">
                                    <span style="font-size: 12pt;" color="#000000">{{ $dataReport['namadokter'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="10" colspan="2">
                            </tr>
                            <tr>
                                <td height="5" colspan="2" style="padding-left: 10px;">
                                    <span style="font-size: 12pt;" color="#000000">{{ $dataReport['kelompokpasien'] }}</span>
                                </td>
                            </tr>
                        </table>
                       
                    </td>
                </tr>
            </tbody>
        </table>
        @endif
        @endif
    </div>
    </body>
    <script type="text/javascript">
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        }
     </script>
</html>
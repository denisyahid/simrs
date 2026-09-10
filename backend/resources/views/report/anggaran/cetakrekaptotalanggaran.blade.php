<html>
<head>
    <title>
        Report
    </title>
        @if(stripos(\Request::url(), 'localhost') !== FALSE)
         <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        @else
         <link href="{{ asset('service/css/style.css') }}" rel="stylesheet">
        @endif
 
</head>
<style>
    @page {
        margin: 10mm 5mm 35mm 17mm;
        size: auto;
    }

    @page :first {
        margin: 10mm 5mm 35mm 17mm;
        size: auto;
    }

    @media print {
    body {
        /* margin: 10mm 5mm 10mm 15mm; */
        margin:0;
        /* width:31cm; */
    }
    }

    body {
        font-family: Arial;
    }

    table{
		font-family:'Arial';
		margin:0;
		padding:.1cm 0;
		font-size:11pt ;
	}	

    .tabel tr td{
        border: 1px solid black;
    }

    .tabel {
        width: 100%;
        border-collapse: collapse;

    }
</style>
<!-- <body style="width:19.5cm;"> -->
<body>
<div>
<table cellpadding="0" cellspacing="0" width="100%"  border="0" class="tabel">
        <tbody >
        <tr>
            <td style="padding: 5px; width: 50px;">
                    <p align="center">
                        @if (stripos(\Request::url(), 'localhost') !== false
                        || stripos(\Request::url(), 'transmedika') !== false)
                            <img alt="" src="{{ asset('img/logo-rs.png') }}" border="0"
                                style="width: 30%;" alt="">
                        @else
                            <img alt="" src="{{ asset('img/logo-rs.png') }}" border="0"
                                style="width: 30%;" alt="">
                        @endif
                      
                    </p>
            </td>
            <td align="center" style="padding: 5px; width: 300px;">
                <b style="font-size: 23px;">
                    RENCANA BISNIS DAN ANGGARAN<br>
                    BADAN LAYANAN UMUM DAERAH<br>
                    {{ strtoupper($settingrba->pengelolakeuanganblud) }}
                </b>
            </td>
            <td align="center" style="padding: 5px; width: 50px;">
                <b style="font-size: 23px;">
                    RBA
                </b><br>
                {{$itahap[0]->tahap}}
            </td>
        </tr>
        <tr >
            <td colspan="3">
                <p align="center" style="margin-top: 10px; margin-bottom:10px">
                    <font style="font-size: 12pt; " color="#000000">
                            <b>REKAP TOTAL ANGGARAN <br> TAHUN ANGGARAN {{ $itahap[0]->thn }}</b>
                    </font>
                    <br>
                </p>
            </td>
        </tr>
        </tbody>
    </table>
    <table cellpadding="0" cellspacing="0" width="100%"  border="0" class="tabel">
        <thead>
                <tr>
                    <td align="center"  colspan="2" style="padding: 10px; font-weight: bold ">NO</td>
                    <td align="center" style="padding: 10px; font-weight: bold; ">ALOKASI BIAYA/PENGELUARAN</td>
                    <td align="center" style="padding: 10px; font-weight: bold ; ">JUMLAH ANGGARAN</td>
                </tr>
        </thead>
                @php
                    $div1 = 'N';
                    $div2 = 'N';
                    $subtotal = 0;
                @endphp
                @foreach ($data as $item)
                    <tr style="padding: 10px;font-weight: {{$item->div2 == '' && $item->div3 == '' ? 'bold': 'normal'}}">
                        <td style="padding: 10px;">{{$item->div1 != $div1 ? $item->div1: '' }}</td>
                        <td style="padding: 10px;">{{$item->div2 != $div2 ? $item->div2: ''}}</td>
                        <td style="padding: 10px;">{{$item->namamataanggaran}}</td>
                        <td style="padding: 10px;text-align: right">{{number_format($item->subtotal,0,',','.')}}</td>
                    </tr>
                    @php
                        $div1 = $item->div1;
                        $div2 = $item->div2;
                        if($item->div2 == ''&& $item->div3 == ''){
                            $subtotal+=$item->subtotal;
                        }
                    @endphp
                @endforeach
                <tr>
                    <td colspan="3"  style="padding: 10px; text-align: left; font-weight: bold;  text-align:center;">JUMLAH TOTAL</td>
                    <td style="padding: 10px; font-weight: bold;text-align: right;">{{ number_format($subtotal,0,',','.') }}</td>
                </tr>
                
        </table>
        <table cellpadding="0" cellspacing="0" width="100%"  border="0">
            <thead>
                <tr>
                    <td width="400">
                    </td>
                    <td>
                    </td>
                    <td>
                        <p align="center" style="margin-top: 50px;">
                            <font style="font-size: 12pt; " color="#000000" face="Arial">
                                    {{$settingrba->kota}}, {{ date('d-m-Y') }}
                            </font>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td width="400">
                    </td>
                    <td>
                    </td>
                    <td>
                        <p align="center" style="margin-bottom:50px">
                            <font style="font-size: 12pt; " color="#000000" face="Arial">
                                    Direktur
                            </font>
                        </p>
                    </td>
                </tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr>
                    <td width="400">
                    </td>
                    <td>
                    </td>
                    <td>
                        <p align="center" style="margin-top: 50px;">
                            <font style="font-size: 12pt; " color="#000000" face="Arial">
                                   <u>{{$settingrba->namalengkap}}</u>
                            </font>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td width="400">
                    </td>
                    <td>
                    </td>
                    <td>
                        <p align="center">
                            <font style="font-size: 12pt; " color="#000000" face="Arial">
                            NIP. {{$settingrba->nippns}}
                            </font>
                        </p>
                    </td>
                </tr>
        </table>
</div>
<script>
    window.print()
</script>
</body>
</html>
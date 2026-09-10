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
        size: landscape;
        /* margin:15mm 15mm 20mm 20mm; */

    }

    @page :first {
        margin: 10mm 5mm 35mm 17mm;
        size: landscape;
        /* margin:15mm 15mm 20mm 20mm; */
    }

    @media print {
    body {
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
        page-break-inside: avoid;
    }
    table thead {
    display: table-header-group;
  }

    .tabel {
        width: 100%;
        border-collapse: collapse;

    }
</style>
<body>
<div>
    <table cellpadding="0" cellspacing="0"  border="0" class="tabel">
        <!-- <tbody > -->
        <tr>
            <td align="center" rowspan="2" width="15%"  style="padding: 30px 30px 30px 40px; border-right: 1px solid black;" align="center">
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
            <td align="center" width="100%">
                <b style="font-size: 25px;">
                ANGGARAN KAS DAN RENCANA JADWAL TIAP SUB SUB KEGIATAN
                </b><br>
                <b style="font-size: 23px;">
                    {{ strtoupper($settingrba->pengelolakeuanganblud) }}
                </b>
            </td>
        </tr>
        <tr align="center">
            <td style="border-top: 1px solid black;">
                @php
                    $str = $subsubkegiatan[0]->keterangan;
                    $str1 = explode("-",$str)[count(explode("-",$str))-1];
                @endphp

                <b>Sub Sub Kegiatan : {{ $str1 }}<br>
                Tahun Anggaran {{ $tahun }}
                </b>
            </td>
        </tr>
        <!-- </tbody> -->
    </table>
    <table cellpadding="0" cellspacing="0" border="0" class = "tabel">
        <tr>
            <td align="center" style="padding: 10px; font-weight: bold ">No</td>
            <td align="center" style="padding: 10px; font-weight: bold; ">Uraian</td>
            <td align="center" style="padding: 10px; font-weight: bold;  ">Jan</td>
            <td align="center" style="padding: 10px; font-weight: bold ; ">Feb</td>
            <td align="center" style="padding: 10px; font-weight: bold;  ">Mar</td>
            <td align="center" style="padding: 10px; font-weight: bold;  ">Apr</td>
            <td align="center" style="padding: 10px; font-weight: bold ; ">Mei</td>
            <td align="center" style="padding: 10px; font-weight: bold;  ">Jun</td>
            <td align="center" style="padding: 10px; font-weight: bold;  ">Jul</td>
            <td align="center" style="padding: 10px; font-weight: bold ; ">Agu</td>
            <td align="center" style="padding: 10px; font-weight: bold;  ">Sep</td>
            <td align="center" style="padding: 10px; font-weight: bold;  ">Okt</td>
            <td align="center" style="padding: 10px; font-weight: bold ; ">Nov</td>
            <td align="center" style="padding: 10px; font-weight: bold;  ">Des</td>
            <td align="center" style="padding: 10px; font-weight: bold;  ">Sub Total</td>
            <td align="center" style="padding: 10px; font-weight: bold;  ">Sumber Dana</td>
        </tr>
                @php
                    $totalanggaran = 0;
                    $totaljan = 0;
                    $totalfeb = 0;
                    $totalmar = 0;
                    $totalapr = 0;
                    $totalmei = 0;
                    $totaljun = 0;
                    $totaljul = 0;
                    $totalagt = 0;
                    $totalsep = 0;
                    $totalokt = 0;
                    $totalnov = 0;
                    $totaldes = 0;
                    $i = 0;
                    $no = 0;
                    $cache = '';
                @endphp
                @foreach ($data as $item)
                @php
                if ($item->bold != 'iya') {
                    $totaljan += $item->jumlahjan;
                    $totalfeb += $item->jumlahfeb;
                    $totalmar += $item->jumlahmar;
                    $totalapr += $item->jumlahapr;
                    $totalmei += $item->jumlahmei;
                    $totaljun += $item->jumlahjun;
                    $totaljul += $item->jumlahjul;
                    $totalagt += $item->jumlahagt;
                    $totalsep += $item->jumlahsep;
                    $totalokt += $item->jumlahokt;
                    $totalnov += $item->jumlahnov;
                    $totaldes += $item->jumlahdes;
                    $totalanggaran += $item->subtotal;
                }
                    if($item->bold != 'iya' && $cache != $item->kodemataanggaran){
                        $item->kodemataanggaran=$no+=1;
                    }else{
                        $no = 0;
                        $cache = $item->kodemataanggaran;
                    }
                @endphp
                    <tr style="font-size: 11px;font-weight: {{$item->bold =='iya' ? 'bold': 'normal'}}">
                        <td align="center" style="padding: 10px;">{{$item->kodemataanggaran}}</td>
                        <td style="padding: 10px;">{{$item->namamataanggaran}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahjan,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahfeb,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahmar,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahapr,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahmei,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahjun,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahjul,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahagt,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahsep,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahokt,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahnov,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->jumlahdes,0,',','.')}}</td>
                        <td align="right" style="padding: 10px;">{{number_format($item->subtotal,0,',','.')}}</td>
                        <td align="center" style="padding: 10px;">{{$item->asalproduk}}</td>
                    </tr>
                @endforeach
                <tr style="font-size: 11px;">
                    <td colspan="2" align="center" style="padding: 10px; font-weight: bold ">Jumlah Total</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totaljan != 0 ? number_format($totaljan,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totalfeb != 0 ? number_format($totalfeb,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totalmar != 0 ? number_format($totalmar,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totalapr != 0 ? number_format($totalapr,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totalmei != 0 ? number_format($totalmei,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totaljun != 0 ? number_format($totaljun,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totaljul != 0 ? number_format($totaljul,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totalagt != 0 ? number_format($totalagt,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totalsep != 0 ? number_format($totalsep,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totalokt != 0 ? number_format($totalokt,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totalnov != 0 ? number_format($totalnov,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totaldes != 0 ? number_format($totaldes,0,',','.') : ''}}</td>
                    <td align="center" style="padding: 10px;font-weight: bold">{{ $totalanggaran != 0 ? number_format($totalanggaran,0,',','.') : '' }}</td>
                    <td align="center" style="padding: 10px;font-weight: bold"></td>
                </tr>
                
        </table>
</div>
<script>
    window.print()
</script>
</body>
</html>
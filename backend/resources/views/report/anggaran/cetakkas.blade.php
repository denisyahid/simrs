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
        margin:15mm 15mm 20mm 20mm;
    }

    @page :first {
        margin:15mm 15mm 20mm 20mm;
    }

    @media print {
    body {
        margin:0;
        width:31cm;
    }
    }

    body {
        font-family: Arial;
    }

    table{
		font-family:'Arial';
		margin:0;
		padding:.1cm 0;
		font-size:10pt ;
	}	

    .tabel tr td{
        border: 1px solid black;
        page-break-inside: avoid;
    }

    .tabel {
        width: 100%;
        border-collapse: collapse;

    }
</style>
<body style="width:100%;">
<table cellpadding="0" cellspacing="0"  border="0" class="tabel" width="100%">
        <tbody >
        <tr >
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
                ANGGARAN KAS {{strtoupper($tahap[0]->tahap) }}
                </b><br>
                <b style="font-size: 23px;">
                    {{ strtoupper($settingrba->pengelolakeuanganblud) }}
                </b>
            </td>
        </tr>
        <tr align="center">
            <td style="border-top: 1px solid black;">
                <b>Tahun Anggaran {{ $tahun }}
                </b>
            </td>
        </tr>
        </tbody>
    </table>
    <table cellpadding="0" cellspacing="0"  border="0" class="tabel" width="100%">
                <tr style="font-size: 11px">
                    <td align="center" style="padding: 3px; font-weight: bold ">Kode Rekening</td>
                    <td align="center" style="padding: 3px; font-weight: bold; ">Uraian</td>
                    <td align="center" style="padding: 3px; font-weight: bold;  ">Pagu</td>
                    <td align="center" style="padding: 3px; font-weight: bold;  ">Jan</td>
                    <td align="center" style="padding: 3px; font-weight: bold ; ">Feb</td>
                    <td align="center" style="padding: 3px; font-weight: bold;  ">Mar</td>
                    <td align="center" style="padding: 3px; font-weight: bold;  ">Apr</td>
                    <td align="center" style="padding: 3px; font-weight: bold ; ">Mei</td>
                    <td align="center" style="padding: 3px; font-weight: bold;  ">Jun</td>
                    <td align="center" style="padding: 3px; font-weight: bold;  ">Jul</td>
                    <td align="center" style="padding: 3px; font-weight: bold ; ">Agu</td>
                    <td align="center" style="padding: 3px; font-weight: bold;  ">Sep</td>
                    <td align="center" style="padding: 3px; font-weight: bold;  ">Okt</td>
                    <td align="center" style="padding: 3px; font-weight: bold ; ">Nov</td>
                    <td align="center" style="padding: 3px; font-weight: bold;  ">Des</td>
                    <td align="center" style="padding: 3px; font-weight: bold;  ">Sub Total</td>
                </tr>
                @foreach ($data as $dt)
                <tr style="font-size: 11px">
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->kode }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->keterangan }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahanggaran != 0? number_format($dt->jumlahanggaran,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahjan!= 0? number_format($dt->jumlahjan,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahfeb!= 0? number_format($dt->jumlahfeb,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahmar!= 0? number_format($dt->jumlahmar,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahapr!= 0? number_format($dt->jumlahapr,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahmei!= 0? number_format($dt->jumlahmei,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahjun!= 0? number_format($dt->jumlahjun,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahjul!= 0? number_format($dt->jumlahjul,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahagt!= 0? number_format($dt->jumlahagt,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahsep!= 0? number_format($dt->jumlahsep,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahokt!= 0? number_format($dt->jumlahokt,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahnov!= 0? number_format($dt->jumlahnov,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->jumlahdes!= 0? number_format($dt->jumlahdes,0,',','.') : '' }}</td>
                    <td style="padding: 3px;font-weight:{{$dt->bold == 'iya' ? 'bold' : 'none'}}">{{ $dt->subtotal != 0? number_format($dt->subtotal,0,',','.') : '' }}</td>
                </tr>
                @endforeach
        </table>
<script>
    window.print()
</script>
</body>
</html>
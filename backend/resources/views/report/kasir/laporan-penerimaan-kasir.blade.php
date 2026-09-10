<!DOCTYPE html>
<html lang="en">

<head>
    <title>Rekap Penerimaan Kasir</title>
</head>

<body>
    <style type="text/css">
        body {
            color: #333;
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse;
            font-size: 12px;
            width: 100%;
        }

        p {
            font-size: 13px;
        }

        .custom-table thead {
            background-color: #e1e1e1;
        }

        .custom-table tr>th,
        .custom-table tr>td {
            border: 1px solid #ccc;
            box-shadow: none;
            padding: 5px;
        }

        .border-tb {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        .border-tb>td.noborder {
            border: none;
        }
        .border-tb>td.noborder:first-child {
            border:none; 
            border-left: 1px solid #ccc
        }
        .border-tb>td.noborder:last-child {
            border:none; 
            border-right: 1px solid #ccc
        }

        .custom-table-no-border thead {
            background-color: #e1e1e1;
        }

        .custom-table-no-border tr>th,
        .custom-table-no-border tr>td {
            box-shadow: none;
            padding: 5px;
        }

        .custom-table.fixed {
            table-layout:fixed;
            width: 100%;
        }
        .custom-table.fixed td { 
            overflow: hidden;
            word-wrap:break-word;
        }

        .text-center {
            text-align: center;
        }

        .top-table {
            margin-bottom: 10px;
        }

        .top-table tr>td {
            padding: 3px 10px;
        }
        .text-red {
            color: rgb(241, 49, 49)
        }
    </style>

    <table class="custom-table" style="width: 100%" border="0">
        <tbody>
            <tr>
                <td>
                    <p>
                        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                            <img src="{{ 'img/logo-rs.png' }}" width="80px" border="0">
                        @else
                            {{-- @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false) --}}
                            <img src="{{ asset('img/logo-rs.png') }}" width="80px" border="0">
                            {{-- @else
                                <img src="{{ asset('service/img/logo-rs.png') }}" width="80px" border="0">
                            @endif --}}
                        @endif
                    </p>
                </td>
                <td>
                    <p>
                        <font style="font-size: 12px;font-weight: 600;" color="#000000" >
                            {!! $profile->namalengkap !!} <br> {!! $profile->alamatlengkap !!} <br> {!! $profile->alamatemail !!}                                    
                        </font>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="text-align:center">
        <h3 style="font-size: 14px"><u>LAPORAN PENDAPATAN UNIT REKAP</u>
            <br>
        Periode {{ $tglAwal }} s/d {{ $tglAkhir }}</h3>
    </div>
    <div>
        <table style="border: none;">
            {{-- <tr style="font-size: 13px;font-weight: 600;">
                <td style="width: 5%">PERIODE</td>
                <td style="width: 1%">:</td>
                <td style="width: 55%">{{ $tglAwal }} s/d {{ $tglAkhir }} </td>
            </tr> --}}
            <tr style="font-size: 13px;font-weight: 600;">
                <td style="width: 5%">USER</td>
                <td style="width: 1%">:</td>
                <td style="width: 55%" class="text-red">
                    {{ \Request::get('namaKasir') ?? '' }}
                </td>
            </tr>
            <tr style="font-size: 13px;font-weight: 600;">
                <td style="width: 5%">KASIR</td>
                <td style="width: 1%">:</td>
                <td style="width: 55%;" class="text-red">
                    {{ \Request::get('tipe') ?? '' }}
                </td>
            </tr>
        </table>       
    </div>
    
    <br>

    <table class="custom-table fixed" style="width: 100%">
        <caption style="display: none"></caption>
        <thead>
            <tr>
                <th scope="col" style="width: 25%">UNIT</th>
                <th scope="col" style="width: 15%">UMUM</th>
                <th scope="col" style="width: 15%">IKS</th>
                <th scope="col" style="width: 15%">WNA</th>
                <th scope="col" style="width: 15%">BPJS</th>
                <th scope="col" style="width: 15%">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandtotal = 0;
            @endphp
            @foreach($data as $dt)
                <tr class="border-tb">
                    @php 
                        $grandtotal += ($dt->jumlahumum + $dt->jumlahiks + $dt->jumlahwna + $dt->jumlahbpjs); 
                    @endphp
                    <td class="noborder">{{ $dt->namaruangan }}</td>
                    <td class="noborder" align="right">{{ \App\Traits\Valet::getMoneyFormatString($dt->jumlahumum) }}</td>
                    <td class="noborder" align="right">{{ \App\Traits\Valet::getMoneyFormatString($dt->jumlahiks) }}</td>
                    <td class="noborder" align="right">{{ \App\Traits\Valet::getMoneyFormatString($dt->jumlahwna) }}</td>
                    <td class="noborder" align="right">{{ \App\Traits\Valet::getMoneyFormatString($dt->jumlahbpjs) }}</td>
                    <td class="noborder" align="right">{{ \App\Traits\Valet::getMoneyFormatString($dt->jumlahumum + $dt->jumlahiks + $dt->jumlahwna + $dt->jumlahbpjs) }}</td>
                </tr>
            @endforeach
            <tr class="border-tb">
                <td class="noborder" colspan="5" align="center">GRAND TOTAL</td>
                <td class="noborder" align="right">{{ \App\Traits\Valet::getMoneyFormatString($grandtotal) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>

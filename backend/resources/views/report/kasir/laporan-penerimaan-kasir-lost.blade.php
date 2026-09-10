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
                <td align="center">
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
        <h3 style="font-size: 14px"><u>LAPORAN HARIAN PEMASUKAN KAS FO PER USER BY SECTION</u></h3>
    </div>
    <div>
        <table style="border: none;">
            <tr style="font-size: 12px;font-weight: 600;">
                <td style="width: 5%">PERIODE</td>
                <td style="width: 1%">:</td>
                <td style="width: 55%">{{ $tglAwal }} s/d {{ $tglAkhir }} </td>
            </tr>
            <tr style="font-size: 12px;font-weight: 600;">
                <td style="width: 5%">USER</td>
                <td style="width: 1%">:</td>
                <td style="width: 55%" class="text-red">
                    {{ \Request::get('user') ?? '' }}
                </td>
            </tr>
            <tr style="font-size: 12px;font-weight: 600;">
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
                <th scope="col" style="width: 5%">NO</th>
                <th scope="col" style="width: 10%">TANGGAL</th>
                <th scope="col" style="width: 15%">NO SEP</th>
                <th scope="col" style="width: 10%">NRM</th>
                <th scope="col" style="width: 15%">PASIEN</th>
                <th scope="col" style="width: 15%">TUNAI</th>
                <th scope="col" style="width: 15%">BON</th>
                <th scope="col" style="width: 15%">PERUSAHAAN</th>
                <th scope="col" style="width: 15%">KARTU KREDIT</th>
                <th scope="col" style="width: 15%">ADD CHARGE</th>
                <th scope="col" style="width: 15%">BEBAN RS</th>
                <th scope="col" style="width: 15%">DIJAMIN BPJS</th>
                <th scope="col" style="width: 15%">LOG</th>
                <th scope="col" style="width: 15%">LAB & RONT</th>
                <th scope="col" style="width: 15%">LOST</th>
                <th scope="col" style="width: 15%">TRANSFER</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandtotaltunai = 0;
                $grandtotalkartukredit = 0;
                $grandtotaltransfer = 0;
                $granddijaminbpjs = 0;
            @endphp
            @if(count($piutang) > 0)
            <tr>
                <td colspan="16" style="border: none; !important">
                    <h3 style="font-size: 14px; margin:0; padding:0">
                        <u><i>PEMBAYARAN PASIEN LOST</i></u>
                    </h3>
                    {{-- <h3 style="font-size: 12px"><u><i>TIPE PASIEN: {{$item[0]->kelompokpasien}}</i></u></h3> --}}
                </td>
            </tr>
            @foreach ($piutang->groupBy('kelompokpasien') as $item)
                <tr>
                    <td colspan="16" style="border: none; !important">
                        <h3 style="font-size: 12px"><u><i>TIPE PASIEN: {{$item[0]->kelompokpasien}}</i></u></h3>
                    </td>
                </tr>
                @php 
                    $nopiu = 0; 
                @endphp
                @foreach($item as $piu)
                    @php 
                        $grandtotaltunai += $piu->totaldibayar;
                        $nopiu++;
                    @endphp
                    <tr class="border-tb">
                        <td class="noborder">{{ $nopiu++ }}</td> {{-- 1 --}}
                        <td class="noborder">{{ $piu->tglsbm }}</td> {{-- 2 --}}
                        <td class="noborder">-</td> {{-- 3 --}}
                        <td class="noborder">-</td> {{-- 4 --}}
                        <td class="noborder">{{ $piu->namapasien }}</td> {{-- 5 --}}
                        <td class="noborder">{{ $piu->jaminbayarid != 11 || $piu->jaminbayarid != 2 || $piu->jaminbayarid != 5 ? \App\Traits\Valet::getMoneyFormatString($piu->totaldibayar) : '0.00' }}</td> {{-- 6 --}}
                        <td class="noborder">0.00</td> {{-- 7 --}}
                        <td class="noborder">{{ $piu->jaminbayarid == 11 || $piu->jaminbayarid == 2  ? \App\Traits\Valet::getMoneyFormatString($piu->totaldibayar) : '0.00' }}</td></td> {{-- 8 --}}
                        <td class="noborder">0.00</td> {{-- 9 --}}
                        <td class="noborder">0.00</td> {{-- 10 --}}
                        <td class="noborder">0.00</td> {{-- 11 --}}
                        <td class="noborder">{{ $piu->jaminbayarid == 5 ? \App\Traits\Valet::getMoneyFormatString($piu->totaldibayar) : '0.00' }}</td> {{-- 12 --}}
                        <td class="noborder">0.00</td> {{-- 13 --}}
                        <td class="noborder">0.00</td> {{-- 14 --}}
                        <td class="noborder">0.00</td> {{-- 15 --}}
                        <td class="noborder">0.00</td> {{-- 16 --}}
                    </tr>
                @endforeach
            @endforeach
            @endif
            @if(count($deposit) > 0)
            <tr>
                <td colspan="16" style="border: none; !important">
                    <h3 style="font-size: 14px; margin:0; padding:0">
                        <u><i>PEMBAYARAN TINDAKAN, LAB, DAN RONTGEN</i></u>
                    </h3>
                </td>
            </tr>
            @foreach ($deposit->groupBy('kelompokpasien') as $item)
                <tr>
                    <td colspan="16" style="border: none; !important">
                        <h3 style="font-size: 12px;">
                            <u><i>TIPE PASIEN: {{$item[0]->kelompokpasien}}</i></u>
                        </h3>
                    </td>
                </tr>
                @php 
                    $nodepo = 0; 
                @endphp
                @foreach($item as $depo)
                    @php 
                        $grandtotaltunai += $depo->totaltunai;
                        $grandtotalkartukredit += $depo->totalkartukredit;
                        $grandtotaltransfer += $depo->totaltransfer;
                        $nodepo++;
                    @endphp
                    <tr class="border-tb">
                        <td class="noborder">{{ $nodepo}}</td>
                        <td class="noborder">{{ $depo->tglsbm }}</td>
                        <td class="noborder">-</td>
                        <td class="noborder">-</td>
                        <td class="noborder">{{ $depo->namapasien }}</td>
                        <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($depo->totaltunai) }}</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($depo->totalkartukredit) }}</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($depo->totaltransfer) }}</td>
                    </tr>
                @endforeach
            @endforeach
            @endif
            <tr class="border-tb" style="background: #e1e1e1; font-weight:bold;">
                <td class="noborder" colspan="5" align="center">GRAND TOTAL</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($grandtotaltunai) }}</td>
                <td class="noborder">0.00</td>
                <td class="noborder">0.00</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($grandtotalkartukredit) }}</td>
                <td class="noborder">0.00</td>
                <td class="noborder">0.00</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($granddijaminbpjs) }}</td>
                <td class="noborder">0.00</td>
                <td class="noborder">0.00</td>
                <td class="noborder">0.00</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($grandtotaltransfer) }}</td>
            </tr>
        </tbody>
    </table>


    <script>
        console.log($data);
    </script>
    <br>
    {{-- <table class="custom-table-no-border" style="width: 100%;">
        <caption style="display: none"></caption>
        <tr>
            <td style="text-align: center"><b>Kasubag. Keuangan</b></td>
            <td></td>
            <td></td>
            <td style="visibility:hidden">-</td>
            <td style="text-align: center"><b>Kasir</b></td>
        </tr>
        <tr>
            <td style="visibility:hidden">4</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="visibility:hidden">4</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: center"><b><u>Nenden Ratnawati, SE., MM</u></b></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: center"><b><u>{{ \Request::get('user') ?? '-' }}</u></b></td>
        </tr>
        <tr>
            <td style="text-align: center"><b>NIP.</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: center"><b>NIP.</td>
        </tr>
    </table> --}}
</body>

</html>

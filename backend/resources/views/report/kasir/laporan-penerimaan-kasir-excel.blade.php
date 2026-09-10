<!DOCTYPE html>
<html lang="en">

<head>
    <title>Rekap Penerimaan Kasir</title>
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
</head>

<body>
    {{-- <table class="custom-table" style="width: 100%" border="0">
        <tbody>
            <tr>
                <td>
                    <p>
                        <font style="font-size: 12px;font-weight: 600;" color="#000000" >
                            {!! $profile->namalengkap !!} <br> {!! $profile->alamatlengkap !!} <br> {!! $profile->alamatemail !!}                                    
                        </font>
                    </p>
                </td>
            </tr>
        </tbody>
    </table> --}}

    <div style="text-align:center">
        <h3 style="font-size: 14px"><u>LAPORAN HARIAN PEMASUKAN KAS FO PER USER BY SECTION</u></h3>
    </div>
    <div>
        <table style="border: none;">
            <tr style="font-size: 12px;font-weight: 600;">
                <td style="">PERIODE</td>
                <td style="">:</td>
                <td style="">{{ $tglAwal }} s/d {{ $tglAkhir }} </td>
            </tr>
            <tr style="font-size: 12px;font-weight: 600;">
                <td style="">USER</td>
                <td style="">:</td>
                <td style="" class="text-red">
                    {{ $userKasir['user'] ?? '' }}
                </td>
            </tr>
            <tr style="font-size: 12px;font-weight: 600;">
                <td style="">KASIR</td>
                <td style="">:</td>
                <td style="" class="text-red">
                    {{ $userKasir['tipe'] ?? '' }}
                </td>
            </tr>
        </table>       
    </div>
    
    <br>
    
    <table class="custom-table fixed" style="width: 100%">
        <thead>
            <tr>
                <th scope="col" >NO</th> {{-- 1 --}}
                <th scope="col" >TANGGAL</th> {{-- 2 --}}
                <th scope="col" >NO SEP</th> {{-- 3 --}}
                <th scope="col" >NRM</th> {{-- 4 --}}
                <th scope="col" >PASIEN</th> {{-- 5 --}}
                <th scope="col" >TUNAI</th> {{-- 6 --}}
                <th scope="col" >BON</th> {{-- 7 --}}
                <th scope="col" >PERUSAHAAN</th> {{-- 8 --}}
                <th scope="col" >KARTU KREDIT</th> {{-- 9 --}}
                <th scope="col" >ADD CHARGE</th> {{-- 10 --}}
                <th scope="col" >BEBAN RS</th> {{-- 11 --}}
                <th scope="col" >DIJAMIN BPJS</th> {{-- 12 --}}
                <th scope="col" >MULTI</th> {{-- 13 --}}
                <th scope="col" >LAB DAN RONT</th> {{-- 14 --}}
                <th scope="col" >LOST</th> {{-- 15 --}}
                <th scope="col" >TRANSFER</th> {{-- 16 --}}
            </tr>
        </thead>
        <tbody>
            @php
                $grandtotaltunai = 0;
                $grandtotalkartukredit = 0;
                $grandtotaltransfer = 0;
                $granddijaminbpjs = 0;
                $grandtotalmulti = 0;
                $grandtotalperusahaan = 0;
                $grandtotallost = 0;
                $grandtotalbon = 0;
            @endphp
            @if(!empty($nonlayanan))
                <tr>
                    <td colspan="16" style="border: none; !important;">
                        <h3 style="font-size: 14px;">
                        <u><i>PEMBAYARAN OBAT BEBAS</i></u>
                        </h3>
                        <h3 style="font-size: 12px"><u><i>TIPE PASIEN: UMUM</i></u></h3>
                    </td>
                </tr>
                @php 
                    $no = 0; 
                @endphp
                @foreach($nonlayanan as $non)
                    <tr class="border-tb">
                        @php 
                            $no++;
                            $grandtotaltunai += (int)$non->totaltunai;
                            $grandtotalkartukredit += (int)$non->totalkartukredit;
                            $grandtotaltransfer += (int)$non->totaltransfer;
                            $granddijaminbpjs += (int)$non->totalharusdibayar;
                            $grandtotalperusahaan += (int)$non->totaliks;
                            $totalBPJSN = 0;
                            $totalIKSN = (int)$non->totaliks;
                            $totalUmumN = 0;
                            $totalTunaiN = (int)$non->totaltunai;
                            $totalTransferN = (int)$non->totaltransfer;
                            $totalKreditN = (int)$non->totalkartukredit;
                            $totalLostN = 0;
                            $totalBonN = 0;
                            $totalBebanN = 0;

                            switch ($non->kdkelompokpasien) {
                                case 5:
                                    $totalBPJSN = $non->totalharusdibayar - ($non->totaltunai + $non->totalkartukredit + $non->totaltransfer); 
                                    $totalBebanN = $non->totalharusdibayar - ($non->totaltunai + $totalBPJSN + $non->totaltransfer + $non->totalkartukredit);
                                    $default = '5';
                                    break;
                                case 3:
                                    $totalIKSN = $non->totaliks; 
                                    $totalKreditN = $non->totalkartukredit;
                                    $totalTunaiN = $non->totaltunai;
                                    $totalTransferN = $non->totaltransfer;
                                    $default = '3';
                                    // $totalLost = $non->totalharusdibayar - ($non->totaltunai + $non->totalkartukredit + $non->totaltransfer);
                                    break;

                                case 1:
                                    // $totalUmum = $non->totalharusdibayar - ($non->totaltunai + $non->totalkartukredit + $non->totaltransfer); 
                                    $totalKreditN = $non->totalkartukredit;
                                    $totalTunaiN = $non->totaltunai;
                                    $totalTransferN = $non->totaltransfer;
                                    $default = '1';
                                    $totalLostN = $non->totalharusdibayar - ($non->totaltunai + $non->totalkartukredit + $non->totaltransfer);
                                    break;
                                
                                default:
                                    $totalKreditN = $non->totalkartukredit;
                                    $totalTunaiN = $non->totaltunai;
                                    $totalTransferN = $non->totaltransfer;
                                    $totalIKSN = $non->totaliks;
                                    $default = 'default caase';
                                    break;
                            }
                        @endphp
                        {{-- <tr> --}}
                        <td class="noborder">{{ $no }}</td>
                        <td class="noborder">{{ $non->tglstruk }}</td>
                        <td class="noborder">-</td>
                        <td class="noborder">-</td>
                        <td class="noborder">{{ $non->namapasien_klien }}</td>
                        <td class="noborder">{{ (int)$totalTunaiN < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalTunaiN) }}</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">{{ (int)$totalIKSN < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalIKSN) }}</td>
                        <td class="noborder">{{ (int)$totalKreditN < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalKreditN) }}</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">{{ (int)$totalBebanN < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalBebanN) }}</td>
                        <td class="noborder">{{ (int)$totalBPJSN < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalBPJSN) }} </td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">{{ (int)$totalLostN < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalLostN) }}</td>
                        <td class="noborder">{{ (int)$totalTransferN < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalTransferN) }}</td>
                        {{-- </tr> --}}
                    </tr>
                @endforeach
            @endif
            @if(count($piutang) > 0)
                <tr>
                    <td colspan="16" style="border: none; !important;">
                        <h3 style="font-size: 14px; margin:0; padding:0">
                            <u><i>PEMBAYARAN PASIEN PIUTANG</i></u>
                        </h3>
                        {{-- <h3 style="font-size: 12px"><u><i>TIPE PASIEN: {{$item[0]->kelompokpasien}}</i></u></h3> --}}
                    </td>
                </tr>
                @foreach ($piutang->groupBy('kelompokpasien') as $item)
                    <tr>
                        <td colspan="16" style="border: none; !important;">
                            <h3 style="font-size: 12px"><u><i>TIPE PASIEN: {{$item[0]->kelompokpasien}}</i></u></h3>
                        </td>
                    </tr>
                    @php 
                        $nopiu = 1; 
                    @endphp
                    @foreach($item as $piu)
                        @php 
                            if($piu->piutangid == 1) {
                                $grandtotallost += $piu->totaldibayarcr;
                            }else if ($piu->piutangid == 2) {
                                $grandtotalbon += $piu->totaldibayarcr;
                            }
                            // $grandtotaltunai += $piu->totaldibayar;
                        @endphp
                        <tr class="border-tb">
                            <td class="noborder">{{ $nopiu++ }}</td> {{-- 1 --}}
                            <td class="noborder">{{ $piu->tglsbm }}</td> {{-- 2 --}}
                            <td class="noborder">-</td> {{-- 3 --}}
                            <td class="noborder">-</td> {{-- 4 --}}
                            <td class="noborder">{{ $piu->namapasien }}</td> {{-- 5 --}}
                            <td class="noborder">0.00</td> {{-- 6 --}}
                            <td class="noborder">{{ $piu->piutangid == 2 ? \App\Traits\Valet::getMoneyFormatString($piu->totaldibayarcr) : '0.00' }}</td> {{-- 7 --}}
                            <td class="noborder">0.00</td> {{-- 8 --}}
                            <td class="noborder">0.00</td> {{-- 9 --}}
                            <td class="noborder">0.00</td> {{-- 10 --}}
                            <td class="noborder">0.00</td> {{-- 11 --}}
                            <td class="noborder">0.00</td> {{-- 12 --}}
                            <td class="noborder">0.00</td> {{-- 13 --}}
                            <td class="noborder">0.00</td> {{-- 14 --}}
                            <td class="noborder">{{ $piu->piutangid == 1 ? \App\Traits\Valet::getMoneyFormatString($piu->totaldibayarcr) : '0.00' }}</td> {{-- 15 --}}
                            <td class="noborder">0.00</td> {{-- 16 --}}
                        </tr>
                    @endforeach
                @endforeach
            @endif
            @if(count($deposit) > 0)
            <tr>
                <td colspan="16" style="border: none; !important;">
                    <h3 style="font-size: 14px; margin:0; padding:0">
                        <u><i>PEMBAYARAN TINDAKAN, LAB, DAN RONTGEN</i></u>
                    </h3>
                </td>
            </tr>
            @foreach ($deposit->groupBy('kelompokpasien') as $item)
                <tr>
                    <td colspan="16" style="border: none; !important;">
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
                        $grandtotalperusahaan += (int)$depo->totaliks;
                        $totalKreditB = $depo->totalkartukredit;
                        $totalTunaiB = $depo->totaltunai;
                        $totalTransferB = $depo->totaltransfer;
                        $totalIKSB = $depo->totaliks;
                        $totalBPJSB = 0;
                        // $totalIKSB = 0;
                        // $totalUmumB = 0;
                        // $totalTunaiB = 0;
                        // $totalKreditB = 0;
                        $totalLostB = 0;
                        // $totalBonB = 0;
                        $totalBebanB = 0;
                        $default = 'default';

                        $nodepo++;
                    @endphp
                    <tr class="border-tb">
                        <td class="noborder">{{ $nodepo}}</td>
                        <td class="noborder">{{ $depo->tglsbm }}</td>
                        <td class="noborder">-</td>
                        <td class="noborder">{{ $depo->nocm }}</td>
                        <td class="noborder">{{ $depo->namapasien }}</td>
                        <td class="noborder">{{ (int)$totalTunaiB < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalTunaiB) }}</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">{{ (int)$totalIKSB < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalIKSB) }}</td>
                        <td class="noborder">{{ (int)$totalKreditB < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalKreditB) }}</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">{{ (int)$totalBebanB < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalBebanB) }}</td>
                        <td class="noborder">{{ (int)$totalBPJSB < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalBPJSB) }} </td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">{{ (int)$totalLostB < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalLostB) }}</td>
                        <td class="noborder">{{ (int)$totalTransferB < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalTransferB) }}</td>
                    </tr>
                @endforeach
            @endforeach
            @endif
            @if(!empty($penerimaan))
            <tr>
                <td colspan="16" style="border: none; !important">
                    <h3 style="font-size: 14px;">
                        <u><i>LAPORAN INSTALASI</i></u>
                    </h3>
                </td>
            </tr>
            @foreach ($penerimaan->groupBy('kelpasien') as $tipe => $groupPasien)
                <tr>
                    <td colspan="16" style="border: none; !important; padding: 0;">
                        <h3 style="font-size: 12px;">
                            <u><i>TIPE PEMBAYARAN: {{ $tipe }}</i></u>
                        </h3>
                    </td>
                </tr>
                @php 
                    $nopen = 0; 
                @endphp
                @foreach ($groupPasien as $pen)
                    @php 
                        $grandtotaltunai += (int)$pen->totaltunai;
                        $grandtotalkartukredit += (int)$pen->totalkartukredit;
                        $grandtotaltransfer += (int)$pen->totaltransfer;
                        $granddijaminbpjs += (int)$pen->totalharusdibayar;
                        $grandtotalmulti += (int)$pen->totalmulti;
                        $grandtotalperusahaan += (int)$pen->totaliks;
                        $totalBPJS = 0;
                        $totalIKS = (int)$pen->totaliks;
                        $totalUmum = 0;
                        $totalTunai = (int)$pen->totaltunai;
                        $totalTransfer = (int)$pen->totaltransfer;
                        $totalKredit = (int)$pen->totalkartukredit;
                        $totalLost = 0;
                        $totalBon = 0;
                        $totalBeban = 0;
                        $totalMulti = (int)$pen->totalmulti;
                        $default = 'default';

                        $nopen++;

                        switch ($pen->kdkelompokpasien) {
                            case 2:
                                $totalBPJS = $pen->totalharusdibayar - ($pen->totaltunai + $pen->totalkartukredit + $pen->totaltransfer); 
                                $totalBeban = $pen->totalharusdibayar - ($pen->totaltunai + $totalBPJS + $pen->totaltransfer + $pen->totalkartukredit);
                                $default = '2';
                                break;
                            case 5:
                                $totalBPJS = $pen->totalharusdibayar - ($pen->totaltunai + $pen->totalkartukredit + $pen->totaltransfer); 
                                $totalBeban = $pen->totalharusdibayar - ($pen->totaltunai + $totalBPJS + $pen->totaltransfer + $pen->totalkartukredit);
                                $default = '5';
                                break;
                            case 3:
                                $totalIKS = $pen->totaliks; 
                                $totalKredit = $pen->totalkartukredit;
                                $totalTunai = $pen->totaltunai;
                                $totalTransfer = $pen->totaltransfer;
                                $default = '3';
                                // $totalLost = $pen->totalharusdibayar - ($pen->totaltunai + $pen->totalkartukredit + $pen->totaltransfer);
                                break;

                            case 1:
                                // $totalUmum = $pen->totalharusdibayar - ($pen->totaltunai + $pen->totalkartukredit + $pen->totaltransfer); 
                                $totalKredit = $pen->totalkartukredit;
                                $totalTunai = $pen->totaltunai;
                                $totalTransfer = $pen->totaltransfer;
                                $default = '1';
                                $totalLost = $pen->totalharusdibayar - ($pen->totaltunai + $pen->totalkartukredit + $pen->totaltransfer);
                                break;
                            
                            default:
                                $totalKredit = $pen->totalkartukredit;
                                $totalTunai = $pen->totaltunai;
                                $totalTransfer = $pen->totaltransfer;
                                $totalIKS = $pen->totaliks;
                                $default = 'default caase';
                                break;
                        }
                    @endphp
                    <tr class="border-tb">
                        <td class="noborder">{{ $nopen }}</td>
                        <td class="noborder">{{ $pen->tglsbm }}</td>
                        <td class="noborder">{{ $pen->nosep }}</td>
                        <td class="noborder">{{ $pen->nocm }}</td>
                        <td class="noborder">{{ $pen->namapasien }}</td>
                        <td class="noborder">{{ (int)$totalTunai < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalTunai) }}</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">{{ (int)$totalIKS < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalIKS) }}</td>
                        <td class="noborder">{{ (int)$totalKredit < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalKredit) }}</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">{{ (int)$totalBeban < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalBeban) }}</td>
                        <td class="noborder">{{ (int)$totalBPJS < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalBPJS) }} </td>
                        <td class="noborder">{{ (int)$totalMulti < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalMulti) }}</td>
                        <td class="noborder">0.00</td>
                        <td class="noborder">{{ (int)$totalLost < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalLost) }}</td>
                        <td class="noborder">{{ (int)$totalTransfer < 0 ? '0' : \App\Traits\Valet::getMoneyFormatString($totalTransfer) }}</td>
                    </tr>
                @endforeach
            @endforeach
            @endif
            <tr class="border-tb" style="background: #e1e1e1; font-weight:bold;">
                <td class="noborder" colspan="5" align="center">GRAND TOTAL</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($grandtotaltunai) }}</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($grandtotalbon) }}</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($grandtotalperusahaan) }}</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($grandtotalkartukredit) }}</td>
                <td class="noborder">0.00</td>
                <td class="noborder">0.00</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($granddijaminbpjs) }}</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($grandtotalmulti) }}</td>
                <td class="noborder">0.00</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($grandtotallost) }}</td>
                <td class="noborder">{{ \App\Traits\Valet::getMoneyFormatString($grandtotaltransfer) }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>

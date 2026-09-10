<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Label Obat
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
        font-family: 'Arial Narrow';
        font-size: 11px;
    }

    .transparan {
    font-size: 9pt;
    color: rgba(0, 0, 0, 0 );
}

    @page {
        size: auto;

    }
    /* .receipt{
        page-break-before: always;
    } */
    /* @media print {
        table.receipt {
            width: 58mm;
        }
    }

    @media print {
        table.receipt {
            page-break-inside: avoid;
            page-break-before: always;
        }
    }

    table {
        font-family: 'Arial Narrow';
    }
    @media print {
        body {
            margin: 0;
            padding: 0;
        }
        .receipt {
            page-break-inside: avoid;
        }
    } */


    /* fix for Chrome */
    .footer {
        position: absolute;
        width: 100%; 
        bottom: 10px;
        text-align: center;
        background-color: transparent;
        padding: 10px 0; 
    }
</style>


<body>

@if ($racikan != '0')
    @php
        $groupedData = $racikan->groupBy('resepke');
        $angka = 0;
    @endphp

    @foreach ($racikan as $item)
        @php
            $angka++;
            $kebangsaan = $item->objectkebangsaanfk;
        @endphp

        <table width="100%" class="receipt" cellspacing="0" cellpadding="0">
            <tr>
                <th colspan="3" style="text-align: center; width: 100%;">
                    <span style="font-size: 8pt;text-transform:uppercase">{{ $profile->namalengkap }} </span>
                </th>
            </tr>
            <tr>
                <th colspan="3" style="text-align: center">
                    <span style="font-size: 8pt;font-weight:400">Jl. By Pass Ngurah Rai No. 548 Garut</span>
                </th>
            </tr>
            <tr>
                <td colspan="3" style="position: relative;">
                    <span style="font-size: 9pt; font-weight: bold; position: absolute; left: 160px;">
                        Tanggal {{ date('d-m-Y') }}
                    </span>
                </td>
            </tr>
            <tr style="color:white;">
                <td>
                    <span>
                        buat space
                    </span>
                </td>
            </tr>
        </table>
        <table class="receipt" cellspacing="1" cellpadding="0"
            style="width:{{ $pageWidth }};background-color:#FFFFFF; border:0; padding-left: 10px; height: 10rem;">
            <tr>
                <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                    <span style="font-size: 9pt;"> Nama </span>
                    <span style="font-size: 9pt; margin-left: 15.5%"> : </span>
                    <font style="font-size: 8.5pt; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $item->namapasien }}</font>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="max-width: 100%; word-wrap: nowrap; white-space: normal;">
                    <span style="font-size: 9pt;">Tgl.Lhr/RM</span>
                    <span style="font-size: 9pt; margin-left: 3%"> : </span>
                    <span style="font-size: 8.5pt;">
                        {{ date('d-m-Y', strtotime($item->tgllahir ?? '')) }}
                        / {{ $item->nocm ?? '' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                    <span style="font-size: 9pt;"> Alamat </span>
                    <span style="font-size: 9pt; margin-left: 13%"> : </span>
                    <span style="font-size: 9pt;">
                        {{ ucfirst($item->alamatlengkap) }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                    <span style="font-size: 9pt;"> Nama Obat </span>
                    <span style="font-size: 9pt; margin-left: 6%"> : </span>
                    <span style="font-size: 9pt;">
                        {{ $item->jeniskemasan ?? '' }} {{ $angka }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                    <span style="font-size: 9pt;"> Ruangan </span>
                    <span style="font-size: 9pt; margin-left: 10.5%"> : </span>
                    <font style="font-size: 8.5pt; white-space: nowrap">
                        {{ $item->namaruangan }}</font>
                </td>
            </tr>
            <!-- <tr>
                <td>
                    <span style="font-size: 9pt;"> Tgl Expired </span>
                    <font style="font-size: 8.5pt; white-space: nowrap; font-weight: bold; margin-left: 20px">
                        {{ $item->tglkadaluarsa ? date('Y-m-d', strtotime($item->tglkadaluarsa)) : '-' }}
                    </font>
                </td>
            </tr> -->
            <tr>
            <td width="100" >
                    @if ($item->tglpemakaian && $item->tglpemakaian != '-')
                        <span style="font-size: 9pt;"> Batas Penggunaan </span>
                        <!-- <span style="font-size: 7pt;"> : </span> -->
                        <font style="font-size: 8.5pt; white-space: nowrap; font-weight: bold">
                            {{ date('Y-m-d', strtotime($item->tglpemakaian)) }}
                        </font>
                    @else
                        <span style="font-size: 9pt;"> Tgl Expired </span>
                        <!-- <span style="font-size: 7pt;"> : </span> -->
                        <font style="font-size: 8.5pt; white-space: nowrap; font-weight: bold">
                            {{ $racikan[0]->tglkadaluarsa ? date('Y-m-d', strtotime($racikan[0]->tglkadaluarsa)) : '-' }}
                        </font>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="3" width="100%">
                    <!-- <span class="transparan">Waktu</span> <br> -->
                    @if ($kebangsaan == 1)
                        <span style="font-size: 8.5pt; max-width:100%; word-wrap: break-word; white-space: normal;"><b>
                            {{ strtoupper($racikan[0]->aturanpakai) ?? '' }},
                            {{strtoupper($racikan[0]->satuanresep) ?? '-' }} <br>
                            <!-- {{ strtoupper($racikan[0]->jenisracikan) ?? '' }} -->
                            {{ strtoupper($racikan[0]->keteranganpakai) ?? '' }}</b></span>
                    @else
                        @if ($racikan[0]->jenisracikan != null)
                            <span style="font-size: 8.5pt; max-width:100%;  word-wrap: break-word;white-space: nowrap"><b>
                                {{ str_replace('X', ' ', strtoupper($racikan[0]->aturanpakai)) ?? '' }},
                                {{ strtoupper(App\Traits\Valet::english($racikan[0]->jenisracikan)) ?? '' }}
                                {{ strtoupper($racikan[0]->keteranganpakai) ?? '' }}</b></span>
                        @else
                            <span style="font-size: 8.5pt; max-width:100%;  word-wrap: break-word;white-space: nowrap"><b>
                                {{ str_replace('X', ' ', strtoupper($racikan[0]->aturanpakai)) ?? '' }}
                                TIMES A DAY,
                                <!-- {{ strtoupper($racikan[0]->jenisracikan) ?? '' }} -->
                                {{ strtoupper($racikan[0]->keteranganpakai) ?? '' }}</b></span>
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="3" width="58%">
                    <span style="font-size: 9pt;font-weight: bold;">
                        @if ($kebangsaan == 1)
                         Pada :
                        @else 
                         In :
                         @endif
                        </span> <span 
                        style="font-size: 8.5pt;font-weight: bold;">
                        @if ($item->pagi != '')
                            #{{ $item->pagi ?? '' }}
                        @endif
                        @if ($item->siang != '')
                            #{{ $item->siang ?? '' }}
                        @endif
                        @if ($item->sore != '')
                            #{{ $item->sore ?? '' }}
                        @endif
                        @if ($item->malam != '')
                            #{{ $item->malam ?? '' }}
                        @endif
                    </span>
                </td>
            </tr>
        </table>

        <table style="margin-top: 40px;">
            <tr>
                <td>
                    <table style="margin-top: 1px;">
                        <tr>
                            <td colspan="3">
                                <div class="footer">
                                    <span style="font-size: 8pt;">SEMOGA LEKAS SEMBUH</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endforeach
@endif



    @if ($nonRacikan != '0')
        @foreach ($nonRacikan as $d)
            @php
                $kebangsaan = $d->objectkebangsaanfk;
            @endphp
            <table width="100%" class="receipt" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th style="text-align: center">
                            <span style="font-size: 8pt;text-transform:uppercase">{{ $profile->namalengkap }} </span>
                        </th>
                    </tr>
                    <tr>
                        <th style="text-align: center">
                            <span style="font-size: 8pt;font-weight:400">Jl. Raya Ciawi-Malangbong, Garut
                            </span>
                        </th>
                    </tr>
                </thead>
            </table>

            <table class="receipt" cellspacing="1" cellpadding="0"
                style="width:{{ $pageWidth }};background-color:#FFFFFF; border:0;">
                <tbody>
                    <tr>
                        <td>
                            <table width="100%">
                                <tr>
                                    <td width="60%"></td>
                                    <td width="40%">
                                        <span style="font-size: 9pt;font-weight: bold">Tanggal
                                            {{ date('d-m-Y') }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
    
            <table class="receipt" cellspacing="0" cellpadding="0"
                style="width:{{ $pageWidth }};background-color:#FFFFFF;border:0; padding-left: 10px;">
                <tbody>
                    <tr>
                        <td>
                            <table width="100%"> 
                                <!-- <tr>
                                    <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                                        <span style="font-size: 9pt;"> Nama  </span>
                                        <span style="font-size: 9pt; margin-left: 13%"> : </span>
                                        <font style="font-size: 8.5pt; white-space: nowrap">
                                            {{ $nonRacikan[0]->namapasien }}</font>
                                    </td>
                                </tr> -->

                                <tr>
                                        <td width="width:33%" style="font-size:12px"> Nama </td>
                                        <td style="width:1%"> : </td>
                                        <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width:66%; font-size:12px;">  
                                                {{ $nonRacikan[0]->namapasien }}
                                           
                                        </td>
                                </tr>


                                        <!-- <tr>
                                            <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                                                    <span style="font-size: 9pt;">Tgl. Lhr/RM </span>
                                                    <span style="font-size: 9pt; margin-left: 2%"> : </span>
                                                    <span style="font-size: 8.5pt;;">
                                                        {{ date('d-m-Y', strtotime($nonRacikan[0]->tgllahir ? $nonRacikan[0]->tgllahir : '')) }}
                                                        / {{ $nonRacikan[0]->nocm ?? '' }}
                                                    </span>
                                            </td>
                                        </tr> -->

                                <tr>
                                    <td> Tgl.Lhr/RM </td>
                                    <td> : </td>
                                    <td style="word-wrap: break-word; font-size:12px;">  
                                        {{ date('d-m-Y', strtotime($nonRacikan[0]->tgllahir ? $nonRacikan[0]->tgllahir : '')) }}
                                        / {{ $nonRacikan[0]->nocm ?? '' }}
                                    </td>
                                </tr>


                                        <!-- <tr>
                                            <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                                                <span style="font-size: 9pt;"> Alamat </span>
                                                <span style="font-size: 9pt; margin-left: 11%"> : </span>
                                                <span style="font-size: 9pt;">
                                                    {{ ucfirst($nonRacikan[0]->alamatlengkap) }}</span>
                                            </td>
                                        </tr> -->

                                <tr> 
                                    <td> Alamat  </td>
                                    <td> : </td>
                                    <td style="font-size:10px;"> {{ ucfirst($nonRacikan[0]->alamatlengkap) }} </td>

                                </tr>

                                        <!-- <tr>
                                            <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                                                <span style="font-size: 9pt; padding-right: 4.5%"> Nama Obat </span>
                                                <span style="font-size: 9pt; "> : </span>
                                                
                                                @if (strlen($d->namaproduk) > 30)
                                                    <span style="font-size: 9pt; max-width: 100%;"> 
                                                        {{ $d->namaproduk ?? '' }}
                                                    </span>
                                                @else
                                                    <span colspan="3" style="font-size: 9pt; max-width: 100%; ">
                                                        {{ $d->namaproduk ?? '' }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr> -->

                                
                                @if ($d->ruanganfk ==  '329')
                                <tr>
                                    <td> Nama Obat </td>
                                    <td> : </td>
                                    <td style="font-size:12px;"> 
                                        @if (strlen($d->namaproduk) > 30)
                                        <!-- <span style="font-size: 9pt; max-width: 100%;">  -->
                                        {{ $d->namaproduk ?? '' }} ({{ $d->jumlah ?? '' }}) 
                                        <!-- </span> -->
                                        @else
                                        <!-- <span colspan="3" style="font-size: 9pt; max-width: 100%; "> -->
                                        {{ $d->namaproduk ?? '' }} ({{ $d->jumlah ?? '' }}) 
                                        <!-- </span> -->
                                        @endif
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td> Nama Obat </td>
                                    <td> : </td>
                                    <td style="font-size:12px;"> 
                                        @if (strlen($d->namaproduk) > 30)
                                        <!-- <span style="font-size: 9pt; max-width: 100%;">  -->
                                        {{ $d->namaproduk ?? '' }} 
                                        <!-- </span> -->
                                        @else
                                        <!-- <span colspan="3" style="font-size: 9pt; max-width: 100%; "> -->
                                        {{ $d->namaproduk ?? '' }} 
                                        <!-- </span> -->
                                        @endif
                                    </td>
                                </tr>
                                @endif

                                

                                             <!-- <tr>
                                                <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                                                    <span style="font-size: 9pt;"> Ruangan </span>
                                                    <span style="font-size: 9pt; margin-left: 8.5%"> : </span>
                                                    <font style="font-size: 8.5pt; white-space: nowrap">
                                                        {{ $nonRacikan[0]->namaruangan }}</font>
                                                </td>
                                            </tr> -->

                                
                                
                                <tr> 
                                    <td> Jumlah </td>
                                    <td> : </td>
                                    <td style=" font-size:10px;">  {{ $d->jumlah }} </td>
                                </tr>            
                                <tr> 
                                    <td> Ruangan </td>
                                    <td> : </td>
                                    <td style=" font-size:10px;">  {{ $nonRacikan[0]->namaruangan }} </td>
                                </tr>


                                        <!-- <tr>
                                            <td width="100" >
                                                @if ($d->tglpemakaian && $d->tglpemakaian != '-')
                                                    <span style="font-size: 9pt;"> Batas Penggunaan </span>
                                                
                                                    <font style="font-size: 8.5pt; white-space: nowrap; font-weight: bold">
                                                        {{ date('Y-m-d', strtotime($d->tglpemakaian)) }}
                                                    </font>
                                                @else
                                                    <span style="font-size: 9pt;"> Tgl Expired </span>
                        
                                                    <font style="font-size: 8.5pt; white-space: nowrap; font-weight: bold">
                                                        {{ $d->tglkadaluarsa ? date('Y-m-d', strtotime($d->tglkadaluarsa)) : '-' }}
                                                    </font>
                                                @endif
                                            </td>
                                        </tr> -->

                                <tr>
                                    @if ($d->tglpemakaian && $d->tglpemakaian != '-')
                                        <td> Batas Penggunaan </td>
                                        <td> : </td>
                                        <td>  {{ date('Y-m-d', strtotime($d->tglpemakaian)) }} </td>
                                    @else
                                        <td> Exp </td>
                                        <td> : </td>
                                        <td>  {{ $d->tglkadaluarsa ? date('Y-m-d', strtotime($d->tglkadaluarsa)) : '-' }} </td>    
                                    
                                    @endif
                                </tr>
                                
                                <!-- <tr>
                                    <td width="100%" colspan = "3">
                                    @if ($kebangsaan == 1)
                                                <span style="font-size: 8.5pt; max-width: 100%; word-wrap: break-word; white-space: normal;"><b>
                                                        {{ strtoupper($d->aturanpakai) ?? '' }},
                                                        {{ strtoupper($d->satuanresep) ?? '-' }}<br>                                   
                                                        {{ strtoupper($d->keteranganpakai) ?? '' }}

                                                    </b></span>
                                                @else
                                                    <span style="font-size: 8.5pt; max-width: 100%; word-wrap: break-word; white-space: normal;"><b>
                                                            {{ str_replace('X', ' ', strtoupper($d->aturanpakai)) ?? '' }},
                                                            {{ strtoupper($d->satuanresep) ?? '-' }}<br>
                                                            {{ strtoupper($d->keteranganpakai) ?? '' }}</b></span>
                                            @endif
                                        </td>
                                    <td width="1%">
                                        <span style="font-size: 9pt;"></span>
                                    </td>
                                    <td width="30%">
                                    </td>
                                </tr> -->

                                <tr>
                                    <td colspan="3" style="font-size:10px; font-weight:bold">
                                            {{ strtoupper($d->aturanpakai) ?? '' }},
                                            {{ strtoupper($d->satuanresep) ?? '-' }}<br>                                   
                                            {{ strtoupper($d->keteranganpakai) ?? '' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align: left; white-space: nowrap;" colspan = "3">
                                        <span style="font-size: 9pt; font-weight: bold;">
                                        @if ($kebangsaan == 1)
                                        Pada :
                                        @else 
                                        In :
                                        @endif
                                        </span>
                                        <span style="font-size: 8.5pt; font-weight: bold;">
                                            @if ($d->pagi != '')
                                                #{{ $d->pagi ?? '' }}
                                            @endif
                                            @if ($d->siang != '')
                                                #{{ $d->siang ?? '' }}
                                            @endif
                                            @if ($d->sore != '')
                                                #{{ $d->sore ?? '' }}
                                            @endif
                                            @if ($d->malam != '')
                                                #{{ $d->malam ?? '' }}
                                            @endif
                                        </span>
                                    </td>
                                </tr> 
                    </tr>
                    <!-- <tr>
                        <td width="35%">
                            <span style="font-size: 9pt;"> Waktu</span>
                        </td>
                        <td width="5%">
                            <span style="font-size: 9pt;">:</span>
                        </td>
                        <td width="58%">
                            <span style="font-size: 9pt;"> {{ $d->satuanresep ?? '-' }}</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;"></span>
                        </td>
                        <td width="30%">
                            <span style="ffont-size: 9pt;;font-weight: bold">
                            </span>
                        </td>
                    </tr>  -->
                </table>
                    </td>
                    </tr>
                    <tr>
                        <td>
                        <table style="margin-top: 1px;">
                                <tr>
                                    <td colspan="3">
                                    <div class="footer">
                                        <span style="font-size: 8pt;">SEMOGA LEKAS SEMBUH</span>
                                    </div>
                                    </td>
                                </tr>
                        </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="page-break-before: always;"></div>
        @endforeach
    @endif

    

</body>

</html>

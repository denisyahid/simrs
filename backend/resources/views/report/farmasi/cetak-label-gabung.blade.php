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

    .footer {
        position: absolute;
        width: 100%; 
        bottom: 10px;
        text-align: center;
        background-color: white; 
        padding: 5px 0; 
        font-size: 14pt;
    }

    @page {
        size: auto;

    }

    @media print {
        table.receipt {
            width: 58mm
        }
    }

    /* fix for Chrome */
</style>


<body>
    @foreach ($dataReport as $key => $data)
        <!-- <table class="receipt"
            style="width:{{ $pageWidth }};background-color:#FFFFFF;padding:10px; border:0;margin-top:.3cm">
            <thead>
                <tr>
                    <th style="text-align: center">
                        <span style="font-size: 9pt;text-transform:uppercase">Instalasi Farmasi</span>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center">
                        <span style="font-size: 9pt;text-transform:uppercase">{{$profile->namalengkap}}</span>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center">
                        <span style="font-size: 10pt;font-weight:400">{{ $profile->alamatlengkap }}</span>
                    </th>
                </tr>
                <hr style="padding: 0px;margin:0px;">
            </thead>
            <tbody>
                <tr>
                    <table width="100%">
                        <tr>
                            <td width="8%">
                                <span style="font-size: 9pt;">TGL</span>
                            </td>
                            <td width="1%">
                                <span style="font-size: 9pt;">:</span>
                            </td>
                            <td width="20%">
                                <span
                                    style="font-size: 9pt;font-weight: bold">{{ date('Y-m-d h:m:s', strtotime($data->tglresep ?? '')) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="8%">
                                <span style="font-size: 9pt;"> Nama Pasien</span>
                            </td>
                            <td width="1%">
                                <span style="font-size: 9pt;">:</span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 11.5pt;font-weight: bold">
                                    {{ $data->namapasien ?? '' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="8%">
                                <span style="font-size: 9pt;">NO CM</span>
                            </td>
                            <td width="1%">
                                <span style="font-size: 9pt;">:</span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 9pt;font-weight: bold">
                                    {{ $data->nocm ?? '' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </tr>

                <tr>
                    <table width="100%">
                        <tr>
                            <th style="font-weight:400" width="20%">
                                <span style="font-size: 9pt;">Nama Obat</span>
                            </th>
                            <th style="font-weight:400" width="5%">
                                <span style="font-size: 9pt;">Jml</span>
                            </th>
                            <th style="font-weight:400" width="10%">
                                <span style="font-size: 9pt;">Tgl Kadaluarsa</span>
                            </th>
                        </tr>
                        <tr>
                            <td style="font-weight:bold" width="20%">
                                <span style="font-size: 9pt;font-weight:bold">{{ $data->namaproduk }}</span>
                            </td>
                            <td style="font-weight:bold;text-align:center" width="5%">
                                <span style="font-size: 9pt;font-weight:bold">{{ $data->jumlah }}</span>
                            </td>
                            <td style="font-weight:bold;text-align:center" width="10%">
                                <span style="font-size: 9pt;font-weight:bold">{{ $data->tglkadaluarsa ? date('Y-m-d', strtotime($data->tglkadaluarsa)) : '-' }}</span>
                            </td>
                            </tr>
                    </table>
                </tr>
                <tr>
                    <table width="100%">
                        <tr>
                            <th style="font-weight:400" width="20%">
                                <span style="font-size: 9pt;">Batas Penggunaan Obat</span>
                            </th>
                            <th style="font-weight:400" width="10%">
                                <span style="font-size: 9pt;">Satuan</span>
                            </th>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;text-align:center" width="20%">
                                <span style="font-size: 9pt;font-weight:bold">{{ $data->aturanpakai ?? '-' }}
                                </span>
                            </td>

                            <td style="font-weight:bold;text-align:center" width="10%">
                                    <span style="font-size: 9pt;font-weight:bold">{{ $data->satuanstandar ?? '-' }} ,</span>
                            </td>
                        </tr>
                    </table>
                </tr>
                <tr>
                    <table width="100%">
                        <tr>
                            <th colspan="2" style="text-align:center">
                                <span style="font-size: 9pt;">{{ $data->satuanresep ?? '-' }}</span>
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center">
                                <span style="font-size: 9pt">{{ $data->keteranganpakai ?? '-' }}</span>
                            </td>
                        </tr>
                    </table>
                </tr>
            </tbody>
        </table> -->
        @php
                $kebangsaan = $data->objectkebangsaanfk;
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
                            <span style="font-size: 8pt;font-weight:400">Jl. By Pass Ngurah Rai No. 548 Garut
                            </span>
                        </th>
                    </tr>
                </thead>
            </table>

            <table class="receipt" cellspacing="0" cellpadding="0"
                style="width:{{ $pageWidth }};background-color:#FFFFFF; border:0;">
                <tbody>
                    <tr>
                        <td>
                            <table width="100%" style="background-color: #FFFFFF">
                                <tr>
                                    <td width="60%"></td>
                                    <td width="40%">
                                    <span style="font-size: 9pt; font-weight: bold; position: absolute; left: 160px;">
                                            Tanggal
                                            {{ date('d-m-Y') }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="color:white;">
                <td>
                    <span>
                        buat space
                    </span>
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
                                <tr>

                                <!-- <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                                        <span style="font-size: 9pt;"> Nama  </span>
                                        <span style="font-size: 9pt; margin-left: 13%"> : </span>
                                        <font style="font-size: 8.5pt; white-space: nowrap">
                                            {{ $data->namapasien }}</font>
                                    </td>
                                </tr> -->

                                <tr>
                                        <td width="width:33%" style="font-size:12px"> Nama </td>
                                        <td style="width:1%"> : </td>
                                        <td style="word-wrap: break-word; width:66%; font-size:11px;">  
                                                {{ $data->namapasien }}
                                           
                                        </td>
                                </tr>

                                <tr>
                                    <td> Tgl. Lhr/ RM </td>
                                    <td> : </td>
                                    <td style="word-wrap: break-word; font-size:10px;">  
                                        {{ date('d-m-Y', strtotime($data->tgllahir ? $data->tgllahir : '')) }}
                                        / {{ $data->nocm ?? '' }}
                                    </td>
                                </tr>

                                <!-- <tr>
                                <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                                        <span style="font-size: 9pt;">Tgl. Lhr/RM </span>
                                        <span style="font-size: 9pt; margin-left: 2%"> : </span>
                                        <span style="font-size: 8.5pt;;">
                                            {{ date('d-m-Y', strtotime($data->tgllahir ? $data->tgllahir : '')) }}
                                            / {{ $data->nocm ?? '' }}
                                        </span>
                                    </td>
                                </tr> -->


                                <!-- <tr>
                                <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                                        <span style="font-size: 9pt;"> Alamat </span>
                                        <span style="font-size: 9pt; margin-left: 11%"> : </span>
                                        <span style="font-size: 9pt;">
                                            {{ ucfirst($data->alamatlengkap) }}</span>
                                    </td>
                                </tr> -->

                                <tr> 
                                    <td> Alamat  </td>
                                    <td> : </td>
                                    <td style="font-size:10px;"> {{ ucfirst($data->alamatlengkap) }} </td>

                                </tr>

                                @if ($data->ruanganfk == '329')
                                <tr>
                                    <td> Nama Obat </td>
                                    <td> : </td>
                                    <td style="font-size:12px;"> 
                                        @if (strlen($data->namaproduk) > 30)
                                        {{ $data->namaproduk ?? '' }} ({{ $data->jumlah ?? '' }})
                                        @else
                                        {{ $data->namaproduk ?? '' }} ({{ $data->jumlah ?? '' }})
                                        @endif
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td> Nama Obat </td>
                                    <td> : </td>
                                    <td style="font-size:12px;"> 
                                        @if (strlen($data->namaproduk) > 30)
                                        {{ $data->namaproduk ?? '' }} 
                                        @else
                                        {{ $data->namaproduk ?? '' }} 
                                        @endif
                                    </td>
                                </tr>
                                @endif        
                                
                                <tr> 
                                    <td> Ruangan </td>
                                    <td> : </td>
                                    <td style=" font-size:10px;">  {{ $data->namaruangan }} </td>
                                </tr>

                                <tr>
                                    @if ($data->tglpemakaian && $data->tglpemakaian != '-')
                                        <td> Batas Penggunaan </td>
                                        <td> : </td>
                                        <td style=" font-size:10px;">  {{ date('Y-m-d', strtotime($data->tglpemakaian)) }} </td>
                                    @else
                                        <td> Tgl Kadaluarsa </td>
                                        <td> : </td>
                                        <td style=" font-size:10px;"> {{ $data->tglkadaluarsa ? date('Y-m-d', strtotime($data->tglkadaluarsa)) : '-' }} </td>    
                                    
                                    @endif
                                </tr>
                                

                                <!-- <tr>
                                <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                                        <span style="font-size: 9pt;"> Ruangan </span>
                                        <span style="font-size: 9pt; margin-left: 6.5%"> : </span>
                                        <font style="font-size: 8.5pt; white-space: nowrap">
                                            {{ $data->namaruangan }}</font>
                                    </td>
                                </tr> -->
                                <!-- <tr>
                                <td colspan="3" style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                                        <span style="font-size: 9pt;"> Tgl Expired </span>
                                        <span style="font-size: 9pt; margin-left: 1%"> : </span>
                                        <span style="ffont-size: 9pt;;font-weight: bold">
                                            {{ $data->tglkadaluarsa ? date('Y-m-d', strtotime($data->tglkadaluarsa)) : '-' }}
                                        </span>
                                    </td>
                                </tr> -->
                                <!-- <tr>
                                <td width="100" >
                                        @if ($data->tglpemakaian && $data->tglpemakaian != '-')
                                            <span style="font-size: 9pt;"> Batas Penggunaan </span> -->
                                            <!-- <span style="font-size: 7pt;"> : </span> -->
                                            <!-- <font style="font-size: 8.5pt; white-space: nowrap; font-weight: bold">
                                                {{ date('Y-m-d', strtotime($data->tglpemakaian)) }}
                                            </font>
                                        @else
                                            <span style="font-size: 9pt;"> Tgl Expired </span> -->
                                            <!-- <span style="font-size: 7pt;"> : </span> -->
                                            <!-- <font style="font-size: 8.5pt; white-space: nowrap; font-weight: bold">
                                                {{ $data->tglkadaluarsa ? date('Y-m-d', strtotime($data->tglkadaluarsa)) : '-' }}
                                            </font>
                                        @endif
                                    </td>
                                </tr> -->
                                
                                <tr>
                                    <td colspan="3" style="font-size:10px; font-weight:bold">
                                            {{ strtoupper($data->aturanpakai) ?? '' }},
                                            {{ strtoupper($data->satuanresep) ?? '-' }}<br>                                   
                                            {{ strtoupper($data->keteranganpakai) ?? '' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align: left; white-space: nowrap;" colspan = "3">
                                        <span style="font-size: 9pt; font-weight: bold;">Pada :</span>
                                        <span style="font-size: 8.5pt; font-weight: bold;">
                                            @if ($data->pagi != '')
                                                #{{ $data->pagi ?? '' }}
                                            @endif
                                            @if ($data->siang != '')
                                                #{{ $data->siang ?? '' }}
                                            @endif
                                            @if ($data->sore != '')
                                                #{{ $data->sore ?? '' }}
                                            @endif
                                            @if ($data->malam != '')
                                                #{{ $data->malam ?? '' }}
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
                            <span style="font-size: 9pt;"> {{ $data->satuanresep ?? '-' }}</span>
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
    <!-- {{-- <div class="page-break" style="margin-top:20px"></div> --}} -->

</body>

</html>

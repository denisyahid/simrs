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
        font-family: Tahoma, Geneva, sans-serif !important;
        font-size: 11px;
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
    <table class="receipt"
        style="width:{{ $pageWidth }};background-color:#FFFFFF;padding:10px; border:0;margin-top:.3cm">
        <thead>
            <tr>
                <th style="text-align: center">
                    <span style="font-size: 11pt;text-transform:uppercase">Instalasi Farmasi</span>
                </th>
            </tr>
            <tr>
                <th style="text-align: center">
                    <span style="font-size: 11pt;text-transform:uppercase">{{$profile->namalengkap}}</span>
                </th>
            </tr>
            <tr>
                <th style="text-align: center">
                    <span style="font-size: 10pt;font-weight:400">{{$profile->alamatlengkap}}</span>
                </th>
            </tr>
            <hr style="padding: 0px;margin:0px;">
        </thead>
        <tbody>
            <tr>
                <table width="100%">
                    <tr>
                        <td width="8%">
                            <span style="font-size: 11pt;">TGL</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;">:</span>
                        </td>
                        <td width="20%">
                            <span
                                style="font-size: 11pt;font-weight: bold">{{ date('Y-m-d h:m:s', strtotime($dataReport[0]->tglresep ?? '')) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="8%">
                            <span style="font-size: 11pt;"> Nama Pasien</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 11.5pt;font-weight: bold">
                                {{ $dataReport[0]->namapasien ?? '' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td width="8%">
                            <span style="font-size: 11pt;">NO CM</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 11pt;font-weight: bold">
                                {{ $dataReport[0]->nocm ?? '' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </tr>

            <tr>
                <table width="100%">
                    <tr>
                        <th style="font-weight:400" width="20%">
                            <span style="font-size: 11pt;">Nama Obat</span>
                        </th>
                        <th style="font-weight:400" width="5%">
                            <span style="font-size: 11pt;">Jml</span>
                        </th>
                        <th style="font-weight:400" width="10%">
                            <span style="font-size: 11pt;">Tgl Kadaluarsa</span>
                        </th>
                    </tr>
                    @foreach ($dataReport as $key => $d)
                        <tr>
                            <td style="font-weight:bold" width="20%">
                                <span style="font-size: 11pt;font-weight:bold">{{ $d->namaproduk }}</span>
                            </td>
                            <td style="font-weight:bold;text-align:center" width="5%">
                                <span style="font-size: 11pt;font-weight:bold">{{ $d->jumlah }}</span>
                            </td>
                            <td style="font-weight:bold;text-align:center" width="10%">
                                <span
                                    style="font-size: 11pt;font-weight:bold">{{ $d->tglkadaluarsa ? date('Y-m-d', strtotime($d->tglkadaluarsa)) : '-' }}</span>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </tr>
            <tr>
                <table width="100%">
                    <tr>
                        <th style="font-weight:400" width="20%">
                            <span style="font-size: 11pt;">Batas Penggunaan Obat</span>
                        </th>
                        <th style="font-weight:400" width="10%">
                            <span style="font-size: 11pt;">Satuan</span>
                        </th>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;text-align:center" width="20%">
                            <span style="font-size: 11pt;font-weight:bold">{{ $dataReport[0]->aturanpakai ?? '-' }}
                            </span>
                        </td>
                        <td style="font-weight:bold;text-align:center" width="10%">
                            <span
                                style="font-size: 11pt;font-weight:bold">{{ $dataReport[0]->jenisracikan ?? '-' }}</span>
                        </td>
                    </tr>
                </table>
            </tr>
            <tr>
                <table width="100%">
                    <tr>
                        <th colspan="2" style="text-align:center">
                            <span style="font-size: 11pt">{{ $dataReport[0]->satuanresep ?? '-' }}</span>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center">
                            <span style="font-size: 11pt">{{ $dataReport[0]->keteranganpakai ?? '-' }}</span>
                        </td>
                    </tr>
                </table>
            </tr>
        </tbody>
    </table>
    {{-- <div class="page-break" style="margin-top:20px"></div> --}}

</body>

</html>

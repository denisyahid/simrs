<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Label Obat
    </title>
    @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), 'transmedika') !== false)
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('service/css/style.css') }}" rel="stylesheet">
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

    @media print {
        @page {
            size: auto;
            margin: 0;
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

    /* @media print { table.receipt { width: 100mm } } */
</style>


<body>
    @foreach ($dataReport as $d)
        <table class="receipt" style="width:{{ $pageWidth }}">
            <thead>
                <tr>
                    <th style="text-align: center">
                        <span style="text-transform:uppercase">Instalasi Farmasi</span>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center">
                        <span style="text-transform:uppercase">{{$profile->namalengkap}}</span>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center">
                        <span style="font-weight:400">{{$profile->alamatlengkap}}</span>
                    </th>
                </tr>
                <hr style="padding: 0px;margin:0px;">
            </thead>
            <tbody>
                <tr>
                    <table width="100%">
                        <tr>
                            <td width="15%">
                                TGL
                            </td>
                            <td width="1%">
                                :
                            </td>
                            <td width="20%">
                                {{ date('Y-m-d h:m:s', strtotime($d->tglstruk)) }}
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                Nama Pasien
                            </td>
                            <td width="1%">
                                :
                            </td>
                            <td width="20%">
                                <span style="font-weight: bold">{{ $d->namapasien }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                No cm
                            </td>
                            <td width="1%">
                                :
                            </td>
                            <td width="20%">
                                <span style="font-weight: bold">{{ $d->nocm }}</span>
                            </td>
                        </tr>
                    </table>
                </tr>

                <tr>
                    <table width="100%">
                        <tr>
                            <th style="font-weight:400" width="20%">
                                <span>Nama Obat</span>
                            </th>
                            <th style="font-weight:400" width="5%">
                                <span>Jml</span>
                            </th>
                            <th style="font-weight:400" width="10%">
                                <span>Tgl Kadaluarsa</span>
                            </th>
                        </tr>
                        <tr>
                            <td style="font-weight:bold" width="20%">
                                <span>{{ $d->namaproduk }}</span>
                            </td>
                            <td style="font-weight:bold;text-align:center" width="5%">
                                <span>{{ $d->qty }}</span>
                            </td>
                            <td style="font-weight:bold;text-align:center" width="10%">
                                <span>{{ $d->tglkadaluarsa ? date('Y-m-d', strtotime($d->tglkadaluarsa)) : '-' }}</span>
                            </td>
                        </tr>
                    </table>
                </tr>
                <tr>
                    <table width="100%">
                        <tr>
                            <th style="font-weight:400" width="20%">
                                <span>Batas Penggunaan Obat</span>
                            </th>
                            {{-- <th style="font-weight:400" width="10%">
                                <span>Bulan</span>
                            </th> --}}
                            <th style="font-weight:400" width="10%">
                                <span>Satuan</span>
                            </th>
                        </tr>
                        <tr>
                            <td style="font-size:15pt;font-weight:bold;text-align:center" width="20%">
                                <span>{{ $d->aturanpakai }}</span>
                            </td>
                            <td style="font-weight:bold;text-align:center" width="10%">
                                <span>{{ $d->satuan }}</span>
                            </td>
                        </tr>
                    </table>
                </tr>
                <tr>
                    <table width="100%">
                        <tr>
                            <th style="font-weight:500;text-align:center">
                                <span>{{ $d->satuanresep }}</span>
                            </th>
                            <th style="font-weight:500;text-align:center">
                                <span>{{ $d->keteranganpakai }}</span>
                            </th>
                        </tr>
                        <tr>
                            <th style="font-weight:500;text-align:center" colspan="2">
                                <span>{{ (!empty($d->pagi) ? $d->pagi . ',' : '') .
                                    (!empty($d->siang) ? $d->siang . ',' : '') .
                                    (!empty($d->sore) ? $d->sore . ',' : '') .
                                    (!empty($d->malam) ? $d->malam : '') }}</span>
                            </th>
                        </tr>
                        {{-- <tr>
                            <td style="font-weight:500;text-align:center">
                                <span>{{ $d->keteranganpakai }}</span>
                            </td>
                            <td style="font-weight:500;text-align:center">
                                <span>{{ $d->keteranganpakai }}</span>
                            </td>
                        </tr> --}}
                    </table>
                </tr>
            </tbody>
        </table>
        <div class="page-break" style="margin-top:20px"></div>
    @endforeach

</body>

</html>

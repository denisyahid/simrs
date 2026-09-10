<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Label
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
        font-family: Tahoma, Geneva, sans-serif !important;
        font-size: 11px;
    }

    @page {
        size:auto;
        
    }

    @media print { table.receipt { width: 58mm } } /* fix for Chrome */
</style>


<body >
    @foreach ($dataReport as $d)
         <table class="receipt" style="width:{{ $pageWidth }};background-color:#FFFFFF;padding:10px; border:0">
            <caption style="display: none"></caption>
            <tbody>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                <tr>
                    <td>
                        <table style="width:100%; border:0">
                            <caption style="display: none"></caption>
                            <tr style="display: none">
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <td rowspan="2">
                                    <p style="text-align:right">
                                        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                                            <img src="{{ 'img/logo-rs.png' }}" width="40px" border="0">
                                        @else
                                            <img src="{{ asset('img/logo-rs.png') }}" width="40px" border="0">
                                        @endif
                                    </p>
                                </td>
                                <td style="text-align:center">
                                    <span style="font-size: 11pt;font-weight: 600;letter-spacing: 2px;" color="#000000">
                                        {!! strtoupper($profile->namalengkap) !!}
                                    </span>
                                </td>
                                <td>
                                    <div style="width: 50px;">
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center">
                                    <span style="font-size: 10pt;" color="#000000"><b> Instalasi Farmasi
                                        </b></span>

                                </td>
                            </tr>
                        </table>
                        <hr class="baris2">
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="width:100%">
                            <caption style="display: none"></caption>
                            <tr style="display: none">
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <td colspan="2"><span style="font-size: 10pt;" color="#000000"><b>
                                            {!! $d->namapasien !!} </b> </span></td>
                                <td><span style="font-size: 10pt;"
                                        color="#000000"><b>{{ date('Y-m-d', strtotime($d->tglresep)) }}</b></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:40%"><span style="font-size: 10pt;"
                                        color="#000000"><b>{{ $d->nocm }} </b></span></td>
                                <td style="width:40%"><span style="font-size: 10pt;"
                                        color="#000000">{{ date('Y-m-d', strtotime($d->tgllahir)) }}</span></td>
                                <td style="width:40%"><span style="font-size: 10pt;" color="#000000">{{ $d->noresep }}
                                    </span></td>
                            </tr>
                            <tr height="30px">
                                <td style="text-align:center" colspan="3"><span style="font-size: 10pt;"
                                        color="#000000"><b>{{ $d->namaproduk . ' (' . $d->jumlah . ')' }}
                                        </b></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center" colspan="3"><span style="font-size: 10pt;"
                                        color="#000000"><b>
                                            {{ $d->aturanpakai . ' ' . ($d->satuanresep == null ? $d->satuanstandar : $d->satuanresep) }}
                                        </b></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center" colspan="3"><span style="font-size: 10pt;"
                                        color="#000000"><b>ED : </b>
                                        {{$d->tglkadaluarsa ?  date('Y-m-d', strtotime($d->tglkadaluarsa)) :'-'}}</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:center" colspan="3"><span style="font-size: 10pt;"
                                        color="#000000">
                                        <b>Waktu :
                                        </b>{{ (!empty($d->pagi) ? $d->pagi . ',' : '') .
                                            (!empty($d->siang) ? $d->siang . ',' : '') .
                                            (!empty($d->sore) ? $d->sore . ',' : '') .
                                            (!empty($d->malam) ? $d->malam : '') }}
                                    </span></td>
                            </tr>
                        </table>
                        <hr class="baris2">

                        <table style="width:100%">
                            <caption style="display: none"></caption>
                            <tr style="display: none">
                                <th scope="col"></th>
                            </tr>

                            <tr>
                                <td style="text-align:center" colspan="3">
                                    <span style="font-size: 10pt;" color="#000000"><b>Semoga Lekas Sembuh</b></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="page-break" style="margin-top:20px"></div>
    @endforeach

</body>

</html>

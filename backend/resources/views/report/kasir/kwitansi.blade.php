<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cetak Kwitansi</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        font {
            /* font-family: Arial, Helvetica, sans-serif; */
            font-family: Tahoma, "Trebuchet MS", sans-serif !important;
            color: #000000 !important;
            font-weight: bold !important;
        }

        .table-bordered tr td {
            border: 1px solid #444444;
        }

        .label-strong {
            text-align: center;
            font-size: 10pt;
        }

        .label-normal {
            text-align: center;
            font-size: 10pt;
            font-weight: normal;
        }

        .label-right {
            text-align: right;
            font-size: 10pt;
            font-weight: normal;
        }

        .label-left {
            text-align: left;
            font-size: 10pt;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th>
                <img src="{{ 'img/logo-rs.png' }}" width="50px">
            </th>
            <th width="90%">
                <table width="100%" style="left:-2rem;position:relative">
                    <tr>
                        <td class="label-strong">
                            <font>{{ strtoupper($profile->namapemerintahan) }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-strong">
                            <font>{{ strtoupper($profile->namalengkap) }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-normal">
                            <font>{{$profile->alamatlengkap}} Telp.{{ $profile->fixedphone }} Fax.{{ $profile->faksimile }}
                                Kode Pos {{ $profile->kodepos }}</font>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td class="label-strong">
                            <font>{{ strtoupper($profile->namakota) }}</font>
                        </td>
                    </tr> --}}
                </table>
            </th>
        </tr>
    </table>
    <hr style="margin: 0px">
    <table width="100%">
        <tr>
            <th class="label-normal">
                <font>Nomer Bukti : {{ $identitas[0]->nokwitansi }}</font>
            </th>
        </tr>
        <tr>
            <th class="label-strong" style="text-transform:uppercase">
                <font>Tanda Bukti Pembayaran</font>
            </th>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td class="label-normal" style="text-align: left !important" width="32%">
                <font>Telah menerima uang sebesar</font>
            </td>
            <td class="label-strong" style="text-align: left !important;font-weight:bold">
                <font style="font-size:18pt">Rp. {{-- {{ number_format((float) $identitas[0]->totaldibayar, 0) }} --}}
                    {{ number_format($identitas[0]->totaldibayar, 0, ',', '.')}}</font> 
            </td>
        </tr>
        <tr>
            <td class="label-normal" style="text-align: left !important">
                <font>dengan huruf</font>
            </td>
            <td class="label-strong" style="text-align: left;font-weight:bold ; margin-left:-30px;">
                <font>({{ strtoupper($terbilang) }})</font>
            </td>
        </tr>
        <tr>
            <td class="label-normal" style="text-align: left !important;padding-top:.5">
                <font>dari</font>
            </td>
        </tr>
    </table>

    <table width="90%" style="margin-top:-.5rem;margin-left:3rem;margin-right:3rem">
        <tr>
            <td class="label-left" width="25%">
                <font style="font-weight: bold">Nama</font>
            </td>
            <td width="3%" class="label-normal">
                <font style="font-weight: bold">:</font>
            </td>

            <td class="label-normal" style="text-align: left">
                <font>{{  $namaalias && $namaalias != '-' ? strtoupper($namaalias) : strtoupper($identitas[0]->namapasien) }}</font>
            </td>
        </tr>
        <tr>
            <td class="label-left">
                <font style="font-weight: bold">Alamat</font>
            </td>
            <td class="label-normal">
                <font style="font-weight: bold">:</font>
            </td>
            <td class="label-normal" style="text-align: left">
                <font>{{ $identitas[0]->alamatlengkap ? $identitas[0]->alamatlengkap : '-' }}</font>
            </td>
        </tr>
        <tr>
            <td style="padding-bottom:5px;text-align:left;vertical-align:top;font-size:10pt">
                <font>Sebagai Pembayaran</font>
            </td>
            <td style="padding-bottom:5px;text-align:center;vertical-align:top;font-size:10pt">
                <font style="font-weight: bold">:</font>
            </td>

            <td class="label-normal" style="text-align: left">
                @if ($identitas[0]->nocm != '-')
                    <font style=" border-bottom: 1px dotted rgba(0, 0, 0, .8)">
                        {{ $identitas[0]->keteranganlainnya }} an.{{ strtoupper($identitas[0]->namapasien) }} No.RM :
                        {{ $identitas[0]->nocm }} , Nama Ruangan : {{ $identitas[0]->namaruangan }}
                    </font>
                @else
                    <font style=" border-bottom: 1px dotted rgba(0, 0, 0, .8)">
                        {{ $identitas[0]->keteranganlainnya }} an.{{ strtoupper($identitas[0]->namapasien) }} ,Berupa
                        layanan :
                        @foreach ($detailLayanan as $item)
                            @if ($item->objectprodukfk == 60710)
                                {{ $item->produk }},
                            @else
                                {{ $item->namaproduk }},
                            @endif
                        @endforeach
                    </font>
                @endif

            </td>
        </tr>
    </table>

    <table width="90%"
        style="margin-left:3rem;margin-right:3rem; border-collapse:collapse;border:1px solid black;margin-top:.5rem">
        <tr>
            <th style="padding:3px;border: 1px solid black" class="label-normal" width="10%">
                <font>No</font>
            </th>
            <th style="padding:3px;border: 1px solid black" class="label-normal" width="30%">
                <font>No Registrasi</font>
            </th>
            <th style="padding:3px;border: 1px solid black" class="label-normal">
                <font>Jumlah</font>
            </th>
        </tr>
        <tr>
            <td class="label-normal" style="border: 1px solid black">
                <font>1</font>
            </td>
            <td class="label-normal" style="border: 1px solid black">
                <font>{{ strtoupper($identitas[0]->noregistrasi) }}</font>
            </td>
            <td class="label-right" style="border: 1px solid black">
                <font>Rp. {{-- {{ number_format((float) $identitas[0]->totaldibayar, 0) }} --}}  {{ number_format($identitas[0]->totaldibayar, 0, ',', '.')}}</font>
            </td>
        </tr>
    </table>

    <table width="90%" style="margin-left:3rem;margin-right:3rem;margin-top:.2rem">
        <tr>
            <td width="45%" class="label-right">
                <font>Tanggal Diterima Uang</font>
            </td>
            <td width="4%" class="label-normal">
                <font>:</font>
            </td>
            <td style="text-align:left !important">
                <font style="font-size:10pt"> {{ isset($identitas[0]->tglsbm) ? $identitas[0]->tglsbm  : '' }}</font>
                {{-- <font style="font-size:10pt"> {{ isset($identitas[0]->tglsbm) ? \Carbon\Carbon::parse($identitas[0]->tglsbm)->format('d-m-Y h:i:s')  : '' }}</font> --}}
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td class="label-normal" width="25%" rowfont="2">
                <font>Mengetahui</font>
            </td>
            <td class="label-normal">
            </td>
            <td class="label-normal" rowfont="2">
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top:-10px;">
        <tr>
            <td class="label-normal" width="25%" rowfont="2">
                <font>Bendahara Penerimaan</font>
            </td>
            <td class="label-normal">
                <font>Petugas Kasir</font>
            </td>
            <td class="label-normal" rowfont="2">
                <font>Pembayar Penyetor</font>
            </td>
        </tr>
    </table>

    <table width="100%" style="margin-top:.5rem">
        <tr>
            <td class="label-normal">
                <br>
            </td>
            <td class="label-normal">
                <br>
            </td>
            <td class="label-normal">
                <br>
            </td>
        </tr>
        <tr>
            <td class="label-normal" width="25%">
                <font style="font-style:bold; text-transform: uppercase">Ade Rohyani, SE</font>
            </td>
            <td class="label-normal" style="vertical-align: bottom">
                <font>{{ $namapegawai }}</font>
            </td>
            <td class="label-normal">
                <font style="border-bottom:1px solid black">
                    {{ strtoupper($identitas[0]->namapenangung ? $identitas[0]->namapenangung : $identitas[0]->namapasien) }}
                </font>
            </td>
        </tr>
    </table>

</body>

</html>

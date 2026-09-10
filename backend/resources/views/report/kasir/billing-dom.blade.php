<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/paper.css ">
    <link rel="stylesheet" href="css/table-v2.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Billing</title>
</head>
<style>
    body,
    td,
    th,
    span {
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

<body style="padding:25px">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <thead style="display: none"></thead>
        <tbody>
            <tr>
                <td rowspan="5">
                    <img src="img/logo-rs.png" width="80px" border="0" style="margin-top:-20px">
                </td>
                <td class="text-center">
                    <span style="font-size: 14pt;font-weight: 600;letter-spacing: 4px;color:#000000"><b>
                            {!! strtoupper($profile->namapemerintahan) !!}
                        </b></span>
                </td>
                <td rowspan="5">
                    <div style="width: 80px;">
                </td>
            </tr>

            <!-- <tr>
                <td class="text-center">
                    <span style="font-size: 14pt;font-weight: 600;letter-spacing: 2px;color:#000000"><b>
                            {!! strtoupper($profile->namalengkap) !!}
                        </b></span>
                </td>
            </tr> -->

            <tr>
                <td class="text-center" style="text-align: center">
                    <span style="font-size: 12pt;color:#000000">
                        {!! $profile->alamatlengkap !!}<br>

                        {{ $profile->fixedphone }}<br>

                        Email : <a href="#"> {!! $profile->alamatemail !!} </a>
                    </span>
                </td>
            </tr>

            



        </tbody>
    </table>

    <hr class="baris1" style="margin-top:-10px">
    <hr class="baris1" style="margin-top:-5px">

    <table width="100%">
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" style="border-bottom:2px solid black;">
                        <thead style="display: none"></thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <span class="text-judul" style="font-size: 12pt">RINCIAN BIAYA PELAYANAN</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <thead style="display: none"></thead>
                        <tbody>
                            <tr>
                                <td colspan="4" style="padding-bottom: 20px;">
                                    <span class="text-normal" style="font-size:8pt;">
                                        Print by {{ $res['user'] }} &nbsp;&nbsp;&nbsp;
                                        {{ date('d/m/Y H:i') }}
                                    </span>
                                </td>
                            </tr>
                            <tr style="font-size: 11pt;">

                                <td width="15%">
                                    <span class="text-normal">No. Registrasi</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->noregistrasi }}</span>
                                </td>

                                <td width="15%">
                                    <span class="text-normal">Unit</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namadepartemen }}</span>
                                </td>
                            </tr>
                            <tr style="font-size: 11pt;">

                                <td width="15%">
                                    <span class="text-normal">No. RM</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">: {{ $res['identitas']->nocm }}
                                    </span>
                                </td>


                                <td width="15%">
                                    <span class="text-normal">Ruang</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namaruangan }}</span>
                                </td>
                            </tr>
                            <tr style="font-size: 11pt;">

                                <td width="15%">
                                    <span class="text-normal">Nama Pasien</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namapasien . ' (' . $res['identitas']->jeniskelamin . ')' }}</span>
                                </td>

                                <td width="15%">
                                    <span class="text-normal">Tgl Masuk</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ date_format(date_create($res['identitas']->tglregistrasi), 'd/m/Y H:i') }}</span>
                                </td>

                                {{-- <td width="15%">
                                    <span class="text-normal">Kelas</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namakelas }}</span>
                                </td> --}}
                            </tr>
                            <tr style="font-size: 11pt;">

                                {{-- <td width="15%">
                                    <span class="text-normal">Tgl Masuk</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ date_format(date_create($res['identitas']->tglregistrasi), 'd/m/Y H:i') }}</span>
                                </td> --}}


                                <td width="15%">
                                    <span class="text-normal">DPJP</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namalengkap }}</span>
                                </td>
                                <td width="15%">
                                    <span class="text-normal">Tgl Pulang</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ date_format(date_create($res['identitas']->tglpulang), 'd/m/Y H:i') }}</span>
                                </td>
                            </tr>
                            <tr style="font-size: 11pt;">

                                {{-- <td width="15%">
                                    <span class="text-normal">Tgl Pulang</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ date_format(date_create($res['identitas']->tglpulang), 'd/m/Y H:i') }}</span>
                                </td> --}}

                                <td width="15%">
                                    <span class="text-normal">Tipe</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->kelompokpasien }}</span>
                                </td>
                                <td width="15%">
                                    <span class="text-normal">No SEP</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->nosep }}</span>
                                </td>

                            </tr>
                            <tr style="font-size: 11pt;">

                                {{-- <td width="15%">
                                    <span class="text-normal">No SEP</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->nosep }}</span>
                                </td> --}}


                                <td width="15%">
                                    <span class="text-normal">Penjamin</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namarekanan }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>


    <table width="100%" cellspacing="0" cellpadding="1" border="0">
        <thead style="display: none"></thead>
        <tbody style="font-size: 11pt;">
            <tr>
                <th class="th-class text-left">
                    <span class="text-normal">No</span>
                </th>
                <th class="th-class  text-center">
                    <span class="text-normal">Tanggal</span>
                </th>
                <th class="th-class text-left">
                    <span class="text-normal">Deskripsi</span>
                </th>
                {{-- <th class="th-class text-left">
                    <span class="text-normal">Kelas</span>
                </th> --}}
                {{-- <th class="th-class text-left">
                    <span class="text-normal">Dokter</span>
                </th> --}}
                <th class="th-class  text-center">
                    <span class="text-normal">Qty</span>
                </th>
                <th class="th-class  text-center">
                    <span class="text-normal">Tarif</span>
                </th>
                <th class="th-class  text-center">
                    <span class="text-normal">Diskon</span>
                </th>
                <th class="th-class  text-right">
                    <span class="text-normal">Sub Total</span>
                </th>
            </tr>
            @php
                $nomor = 1;
                $totaltagihan = 0;
                $totaldiskon = 0;
                $jumlahbill = 0;
                $totaldiklaim = 0;
            @endphp
            @foreach ($res['billing'] as $ruangan)
                <tr style="background: #d6d4d4;">
                    <td colspan="10">
                        <span class="text-biasa bold">
                            <b> {{ strtoupper($ruangan[0]->namaruangan) }}</b>
                        </span>
                    </td>
                </tr>
                @foreach ($ruangan->groupBy('jenisproduk') as $item)
                    <tr style="font-style:italic">
                        <td colspan="10">
                            <span class="text-biasa">
                                <b> JENIS:
                                    {{ strtoupper($item[0]->jenisproduk) }}</b>
                            </span>
                        </td>
                    </tr>
                    @php
                        $total = 0;
                        $diskon = 0;
                    @endphp
                    @foreach ($item as $data)
                        <tr>
                            <td class="text-top text-left">
                                <span class="text-biasa">{{ $nomor }}</span>
                            </td>
                            <td class="text-top text-center">
                                <span class="text-biasa">
                                    {{ date_format(date_create($data->tglpelayanan), 'd/m/Y') }}</span>
                            </td>
                            <td class="text-top text-left">
                                <span class="text-biasa">{{ $data->namaproduk }}</span>
                            </td>
                            {{-- <td class="text-top text-left">
                                <span class="text">{{ $data->namakelas }}</span>
                            </td> --}}
                            {{-- @if ($data->penulisresep === null)
                                <td class="text-top text-left">
                                    <span class="text">{{ $data->dokter }}</span>
                                </td>
                            @else
                                <td class="text-top text-left">
                                    <span class="text">{{ $data->penulisresep }}
                                    </span>
                                </td>
                            @endif --}}
                            @if (strpos($data->jumlah, '.') !== false)
                                <td class="text-top text-center">
                                    <span class="text-biasa">{{ number_format($data->jumlah, 0, '.', ',') }}</span>
                                </td>
                            @else
                                <td class="text-top text-center">
                                    <span class="text-biasa">{{ $data->jumlah }}</span>
                                </td>
                            @endif
                            <td class="text-top text-right">
                                <span class="text-biasa">
                                    {{ number_format($data->hargasatuan, 0, '.', ',') }}</span>
                            </td>
                            <td class="text-top text-center">
                                <span class="text-biasa">{{ number_format($data->diskon, 0, '.', ',') }}</span>
                            </td>
                            <td class="text-top text-right">
                                <span class="text-biasa">
                                    {{ number_format(ceil($data->total), 0, '.', ',') }}</span>
                            </td>
                        </tr>
                        @php
                            $nomor = $nomor + 1;
                            $total = $total + $data->total;
                            $diskon = $diskon + $data->diskon;
                        @endphp
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="10">
                            <span class="text-biasa" style="text-align: right;">
                                {{ number_format(ceil($total), 0, '.', ',') }}
                            </span>
                        </td>
                    </tr>
                    @php
                        $totaltagihan = $totaltagihan + $total;
                        $totaldiskon = $totaldiskon + $diskon;
                        $jumlahbill = $totaltagihan - $totaldiskon;
                    @endphp
                @endforeach
            @endforeach
        </tbody>
    </table>



    <table width="100%" cellspacing="0" cellpadding="0" style="border-top:2px solid black;">
        <thead style="display: none"></thead>
        <tbody style="font-size: 11pt;">
            <tr>
                <td width="50%"></td>
                <td width="20%" class="text-left">
                    <span class="text-115">TOTAL TAGIHAN</span>
                </td>
                <td class="text-center">
                    <span class="text-115">:</span>
                </td>
                <td class="text-right">
                    <span class="text-115">
                        {{ number_format(ceil($res['total']), 0, '.', ',') }}</span>
                </td>
            </tr>
            <tr>
                <td width="50%"></td>
                <td width="20%" class="text-left">
                    <span class="text-115">DEPOSIT - UANG MUKA</span>
                </td>
                <td class="text-center">
                    <span class="text-115">:</span>
                </td>
                <td class="text-right">
                    <span class="text-115 bold">
                        {{ number_format($res['deposit'], 0, '.', ',') }}</span>
                </td>
            </tr>


            <tr>
                <td width="50%"></td>
                <td width="20%" class="text-left">
                    <span class="text-115">TOTAL BAYAR </span>
                </td>
                <td class="text-center">
                    <span class="text-115">:</span>
                </td>
                <td class="text-right">
                    <span class="text-115">
                        {{ number_format($res['dibayar'], 0, '.', ',') }}</span>
                </td>
            </tr>
            <tr>
                <td width="50%"></td>
                <td width="20%" class="text-left">
                    <span class="text-115">{{$res['identitas']->kelompokpasien == 'Umum'  ? 'PIUTANG' : 'TOTAL DIKLAIM'}}</span>
                </td>
                <td class="text-center">
                    <span class="text-115">:</span>
                </td>
                <td class="text-right">
                    <span class="text-115">
                        {{ number_format($res['klaim'], 0, '.', ',') }}</span>
                </td>
            </tr>
            <tr>
                <td width="50%"></td>
                <td width="20%" class="text-left">
                    <span class="text-115">SISA HARUS BAYAR</span>
                </td>
                <td class="text-center">
                    <span class="text-115">:</span>
                </td>
                <td class="text-right">
                    <span class="text-115 bold">
                        {{ number_format(ceil($res['sisa']), 0, '.', ',') }}</span>
                </td>
            </tr>
            @if ($res['iurbayar'] != 0)
                <tr>
                    <td width="50%"></td>
                    <td width="20%" class="text-left">
                        <span class="text-115">IUR BAYAR</span>
                    </td>
                    <td class="text-center">
                        <span class="text-115">:</span>
                    </td>
                    <td class="text-right">
                        <span class="text-115 bold">
                            {{ number_format($res['iurbayar'], 0, '.', ',') }}</span>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tbody style="font-size: 11pt">
            <tr>
                <td>
                    <span class="text-biasa">TERBILANG :
                </td>
            </tr>
            <tr>
                <td>
                    <span class="text-biasa"><i>#
                            {{ strtoupper(App\Traits\Valet::static_terbilang($jumlahbill)) }}
                            #</i>
                </td>
            </tr>
        </tbody>
    </table>
    <table style="font-size: 11pt">
        @if ($res['ismultipenjamin'] == true)
            <tr>
                <td>
                    <span class="text-biasa">Multi Penjamin :
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0" border="1">
                        <tbody>
                            <tr class="garisatasbawah">
                                <th class="text-center"><span class="text-biasa">Penjamin</span>
                                </th>
                                <th class="text-center"><span class="text-biasa">Jumlah</span>
                                </th>
                            </tr>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($res['multipenjamin'] as $item)
                                @php
                                    $total = $total + $item->totalppenjamin;
                                @endphp
                                <tr>
                                    <td class="text-left"><span class="text-biasa">{{ $item->namarekanan }}</span>
                                    </td>
                                    <td class="text-right"><span
                                            class="text-biasa">{{ number_format($item->totalppenjamin, 0, '.', ',') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-right">
                                    <span class="text">
                                        <b>JUMLAH</b>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="text">
                                        {{ number_format($total, 0, '.', ',') }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        @endif
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" style="padding-top: 20px">
        <tbody style="font-size: 11pt">
            <tr>
                <td width="50%" class="text-center">
                    <span class="text-biasa" class="text-center"></span>
                </td>
                <td width="50%" class="text-center">
                    <span class="text-biasa">{!! $profile->namakota !!},
                        &nbsp;&nbsp;{{ date('d/m/Y H:i') }}</span>
                </td>
            </tr>
            <tr>
                <td width="50%" class="text-center"><span class="text-biasa">Penanggung
                        Biaya</span>
                </td>
                <td width="50%" class="text-center"><span class="text-biasa">Kasir</span>
                </td>
            </tr>
            <tr>
                <td valign="bottom" height="70" width="25%" class="text-center">
                    <span class="text-biasa">
                        {{ str_replace('( Laki-laki )', '', str_replace('( Perempuan )', '', $res['identitas']->namapasien)) }}
                    </span>
                </td>
                <td height="80" valign="bottom" height="100"width="25%" class="text-center">
                    <span class="text-biasa">-</span>
                </td>
            </tr>
        <tbody>
    </table>
</body>

</html>

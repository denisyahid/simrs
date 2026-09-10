<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Billing</title>
</head>
<body>
    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0">
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr>
                                <td class="text-center" style="text-align: center">
                                    <span style="font-size: 14pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                        {!! strtoupper($profile->namapemerintahan) !!}
                                    </span>
                                </td>
                            </tr>
                            <!-- <tr>
                                <td class="text-center" style="text-align: center">
                                    <span style="font-size: 16pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                        {!! strtoupper($profile->namalengkap) !!}

                                    </span>
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
                </td>
            </tr>
        </tbody>
    </table>

    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0">
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" style="border-bottom:2px solid black;">
                        <tr>
                            <td class="text-center" style="text-align:center; justify-content: center; font-weight: bold">
                                <span class="text-judul" style="text-align:center; justify-content: center; font-weight: bold">
                                    RINCIAN BIAYA
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <td style="padding-bottom: 20px;">
                                    <span class="text-normal">
                                        Print by {{ $res['user'] }} &nbsp;&nbsp;&nbsp;
                                        {{ date('d/m/Y H:i') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                
                                <td>
                                    <span class="text-normal">No. Registrasi</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ $res['identitas']->noregistrasi }}</span>
                                </td>
                
                                <td>
                                    <span class="text-normal">Unit</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namadepartemen }}</span>
                                </td>
                            </tr>
                            <tr>
                
                                <td>
                                    <span class="text-normal">No. RM / Umur</span>
                                </td>
                                <td>
                                    <span class="text-normal">: {{ $res['identitas']->nocm }} / {{ $res['identitas']->umur }}
                                    </span>
                                </td>
                
                
                                <td>
                                    <span class="text-normal">Ruang</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namaruangan }}</span>
                                </td>
                            </tr>
                            <tr>
                
                                <td>
                                    <span class="text-normal">Nama Pasien</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namapasien . ' (' . $res['identitas']->jeniskelamin . ')' }}</span>
                                </td>
                                <td>
                                    <span class="text-normal">Tgl Masuk</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ date_format(date_create($res['identitas']->tglregistrasi), 'd/m/Y H:i') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-normal">DPJP</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namalengkap }}</span>
                                </td>
                                <td>
                                    <span class="text-normal">Tgl Pulang</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ date_format(date_create($res['identitas']->tglpulang), 'd/m/Y H:i') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-normal">No SEP</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ $res['identitas']->nosep }}</span>
                                </td>
                
                
                
                                <td>
                                    <span class="text-normal">Tipe</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ $res['identitas']->kelompokpasien }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-normal">No Kartu</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ $res['identitas']->nobpjs }}</span>
                                </td>
                
                                <td>
                                    <span class="text-normal">Penjamin</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namarekanan }}</span>
                                </td>
                            </tr>
                        </thead>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0">
        <thead style="font-size: 12pt;">
            <tr>
                <th width="50%">
                    No
                </th>
                <th class="th-class  text-center">
                    Tanggal
                </th>
                <th>
                    <span class="text-normal-1">Jenis Biaya</span>
                </th>
                <th class="th-class  text-center">
                    <span class="text-normal-1">Qty</span>
                </th>
                <th class="th-class  text-center">
                    <span class="text-normal-1">Tarif</span>
                </th>
                <th class="th-class  text-center">
                    <span class="text-normal-1">Section</span>
                </th>
                <th class="th-class  text-center">
                    <span class="text-normal-1">Dokter</span>
                </th>
                <th class="th-class  text-right">
                    <span class="text-normal-1">Sub Total</span>
                </th>
            </tr>
        </thead>
        <tbody style="font-size: 12pt;">
            @php
                $nomor = 0;
                $totaltagihan = 0;
                $totaldiskon = 0;
                $jumlahbill = 0;
                $totaldiklaim = 0;
            @endphp
            @foreach ($res['billing'] as $tglkeys => $tgldata)
                <tr style="background-color: #d6d4d4; page-break-inside: avoid;">
                    <td colspan="10">
                        <span class="text-normal-1 bold">
                            <b> {{ $tglkeys }}</b>
                        </span>
                    </td>
                </tr>
                @php
                    $total = 0;
                    $diskon = 0;
                @endphp
                
                @foreach ($tgldata->groupBy('namaruangan') as $lytgl => $ruangan)
                    <tr>
                        <td colspan="10">
                            <span class="text-normal-1">
                                <b> {{ $lytgl }} </b>
                            </span>
                        </td>
                    </tr>
                    @foreach ($ruangan as $keyly => $data)
                        @php
                            $total = $total + $data->total;
                            $diskon = $diskon + $data->diskon;
                            $nomor = $nomor + 1;
                        @endphp
                        <tr>
                            <td class="text-top text-left">
                                <span class="text-normal-1">{{ $nomor }}</span>
                            </td>
                            <td class="text-top text-center">
                                <span class="text-normal-1">
                                    {{ date_format(date_create($data->tglpelayanan), 'd/m/Y') }}</span>
                            </td>
                            <td class="text-top text-left">
                                <span class="text-normal-1">{{ $data->namaproduk }}</span>
                            </td>
                            
                            <td class="text-top text-center">
                                <span class="text-normal-1">x{{ $data->jumlah }}</span>
                            </td>
                            <td class="text-top text-right">
                                <span class="text-normal-1">
                                    {{ number_format($data->hargasatuan, 0, '.', ',') }}</span>
                            </td>
                            <td class="text-top text-left">
                                <span class="text-normal-1">{{ $data->ruang_group }}</span>
                            </td>
                            <td class="text-top text-left">
                                <span class="text-normal-1">{{ $data->pemeriksa }}</span>
                            </td>
                            <td class="text-top text-right">
                                <span class="text-normal-1"> {{ number_format($data->total, 0, '.', ',') }}</span>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td class="text-right" style="text-align: right" colspan="10">
                        <span class="text-normal-1">
                            Sub Total : {{ number_format($total, 0, '.', ',') }}
                        </span>
                    </td>
                </tr>
                @php
                    $totaltagihan = $totaltagihan + $total;
                    $totaldiskon = $totaldiskon + $diskon;
                    $jumlahbill = $totaltagihan - $totaldiskon;
                @endphp
            @endforeach
        </tbody>
    </table>

    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0">
        <tr>
            <td style="padding-top:10px">
                <table width="100%" cellspacing="0" cellpadding="0" style="border-top:2px solid black;">
                    <tr>
                        <td class="text-left">
                            <span class="text-biasa">TOTAL TAGIHAN</span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa">
                                {{ number_format($res['total'], 0, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <span class="text-biasa">DEPOSIT - UANG MUKA</span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa bold">
                                {{ number_format($res['deposit'], 0, '.', ',') }}</span>
                        </td>
                    </tr>
                    @if ($res['pengembalian'] > 0)
                        <tr>
                            <td class="text-left">
                                <span class="text-biasa">PENGEMBALIAN DEPOSIT</span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa bold">
                                    {{ number_format($res['pengembalian'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                    @endif
    
                    <tr>
                        <td class="text-left">
                            <span class="text-biasa">TOTAL BAYAR </span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa">
                                {{ number_format($res['dibayar'], 0, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <span class="text-biasa">TOTAL DIKLAIM</span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa">
                                {{ number_format($res['klaim'], 0, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <span class="text-biasa">SISA HARUS BAYAR</span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa bold">
                                {{ number_format($res['sisa'], 0, '.', ',') }}</span>
                        </td>
                    </tr>
                    @if ($res['iurbayar'] != 0)
                        <tr>
                            <td class="text-left">
                                <span class="text-biasa">IUR BAYAR</span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa bold">
                                    {{ number_format($res['iurbayar'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <span class="text-biasa">Multi Penjamin : {{ $res['ismultipenjamin'] == false ? 'Tidak ada' : ''}}
            </td>
        </tr>
        @if ($res['ismultipenjamin'] == true)
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0" border="1" style="border: 1px solid black !important;">
                        <tr class="garisatasbawah" style="border: 1px solid black !important;">
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
                            <tr style="border: 1px solid black !important;">
                                <td class="text-left"><span class="text-biasa">{{ $item->namarekanan }}</span>
                                </td>
                                <td class="text-right"><span
                                        class="text-biasa">{{ number_format($item->totalppenjamin, 0, '.', ',') }}</span>
                                </td>
                            </tr>
                        @endforeach
                        <tr style="border: 1px solid black !important;">
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
                    </table>
                </td>
            </tr>
        @endif
        <tr>
            <td style="padding-top:20px">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <span class="text-biasa">TERBILANG :
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-biasa"><i>
                                    {{ strtoupper(App\Traits\Valet::static_terbilang($res['total'])) }} </i>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
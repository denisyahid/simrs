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
                                    <span class="text-normal">Tipe</span>
                                </td>
                                <td>
                                    <span class="text-normal">:
                                        {{ $res['identitas']->kelompokpasien }}</span>
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
                <th class="th-class text-left">
                    <span class="text-normal-1">No</span>
                </th>
                <th class="th-class  text-center">
                    <span class="text-normal-1">Tanggal</span>
                </th>
                <th class="th-class text-left">
                    <span class="text-normal-1">Jenis Biaya</span>
                </th>
                <th class="th-class  text-center">
                    <span class="text-normal-1">Qty</span>
                </th>
                <th class="th-class  text-center">
                    <span class="text-normal-1">Tarif</span>
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
            @foreach ($res['billing'] as $ruangan)
                <tr style="background-color: #d6d4d4; page-break-inside: avoid;">
                    <td colspan="10">
                        <span class="text-normal-1 bold">
                            <b> {{ strtoupper($ruangan[0]->namaruangan) }}</b>
                        </span>
                    </td>
                </tr>
                @foreach ($ruangan->groupBy('jenis') as $jen => $item)
                    @foreach ($item->groupBy('layanan_group') as $keyly => $lygroup)
                        @php
                            $total = 0;
                            $diskon = 0;
                        @endphp
                        <tr>
                            <td colspan="10">
                                <span class="text-normal-1">
                                    <b> {{ $keyly }} </b>
                                </span>
                            </td>
                        </tr>
                        @foreach ($lygroup as $data)
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
                                    <span class="text-normal-1">{{ (int)$data->jumlah }}</span>
                                </td>
                                <td class="text-top text-right">
                                    <span class="text-normal-1">
                                        {{ (int)$data->hargasatuan }}
                                        
                                    </span>
                                </td>
                                <td class="text-top text-right">
                                    <span class="text-normal-1"> {{ (int)$data->total }}</span>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right" style="text-align: right" colspan="10">
                                <span class="text-normal-1">
                                    {{ $total }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
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
                                {{ $res['total'] }}</span>
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
                                {{ (int)$res['deposit'] }}</span>
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
                                    {{ (int)$res['pengembalian'] }}</span>
                            </td>
                        </tr>
                    @endif
                    @if ($res['ismultipenjamin'] == true)
                        @foreach ($res['multipenjamin'] as $item)
                            <tr>
                                <td class="text-left">
                                    <span class="text-biasa">{{ $item->namarekanan }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-biasa">:</span>
                                </td>
                                <td class="text-right">
                                    <span class="text-biasa bold">
                                        {{ (int)$item->totalppenjamin }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
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
                                {{ (int)$res['dibayar'] }}</span>
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
                                {{ (int)$res['klaim'] }}</span>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
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
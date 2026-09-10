@extends('template.layout')
@section('title',  $dataReport['judul'] )
@section('page-style')
    <style>
    .class-2 {
        border-top:1px solid black;border-bottom:1px solid black;
    }
    .p1 {
  font-family: "Times New Roman", Times, serif;
}
    table tr td,
    font{
        font-size: 11pt;
    }
    </style>
@endsection
@section('content')
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" height="40px">
                        <font style="font-size: 16pt;font-weight: bold" color="#000000">{{ $dataReport['judul'] }}</font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td style="width: 20%">
                        <font>No. Registrasi / No. RM</font>
                    </td>
                    <td style="width: 25%">
                        <font>:
                            {{ $dataReport['header']->noregistrasi }}&nbsp;/&nbsp;{{ $dataReport['header']->nocm }}</font>
                    </td>
                    <td style="width: 10%"></td>
                    <td style="width: 10%">
                        <font>Unit Layanan</font>
                    </td>
                    <td style="width: 25%">
                        {{-- {{dd($dataReport['details'][0]->namaruangan)}} --}}
                        <font>: {{ $dataReport['details'][0]->namaruangan }}</font>
                    </td>
                </tr>
                <tr>
                    <td>
                        <font>Nama Pasien</font>
                    </td>
                    <td>
                        <font>: {{ $dataReport['header']->namapasien }}</font>
                    </td>
                    <td style="width: 10%"></td>
                    <td>
                        <font>Tipe Pasien</font>
                    </td>
                    <td>
                        <font>:
                            {{ $dataReport['header']->kelompokpasien }}&nbsp;/&nbsp;{{ $dataReport['header']->namapenjamin }}
                        </font>
                    </td>
                </tr>
                <tr style="vertical-align: top">
                    <td>
                        <font>Alamat</font>
                    </td>
                    <td colspan="2">
                        <font>: {{ $dataReport['header']->alamatlengkap }}</font>
                    </td>
                    <td>
                        <font>Tgl Registrasi</font>
                    </td>
                    <td>
                        <font>:
                            {{ $dataReport['header']->tglregistrasi }}&nbsp;
                        </font>
                    </td>
                </tr>
                <tr>
                    <td>
                        <font>Jenis Kelamin / Tgl Lahir</font>
                    </td>
                    <td>
                        <font>:
                            {{ $dataReport['header']->jk }}&nbsp;/&nbsp;{{ $dataReport['header']->tglkelahiran }}</font>
                    </td>
                    <td style="width: 10%"></td>
                    <td>
                        <font>Kamar / Kelas</font>
                    </td>
                    <td>
                        <font>:
                            {{ $dataReport['header']->namakamar }}&nbsp;/&nbsp;{{ $dataReport['header']->namapenjamin }}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td>
                        <font>Dokter DPJP</font>
                    </td>
                    <td>
                        <font>: {{ $dataReport['header']->dpjp }}</font>
                    </td>
                    <td style="width: 10%"></td>
                    <td>
                        <font>Ruangan Pengirim</font>
                    </td>
                    <td>
                        <font>: {{ $dataReport['header']->ruanganasal }}</font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:10px">
            <table width="100%" cellspacing="0" cellpadding="1" border="0">
                <tr>
                    <th  class="class-2 text-center">
                        <font>No</font>
                    </th>
                    <th  class="class-2 text-center">
                        <font>Tgl Pelayanan</font>
                    </th>
                    <th  class="class-2 text-center">
                        <font>Pelayanan</font>
                    </th>
                    <th  class="class-2 text-center">
                        <font>Dokter Pemeriksa</font>
                    </th>
                    <th  class="class-2 text-center">
                        <font>Qty</font>
                    </th>
                    <th  class="class-2 text-center">
                        <font>Tarif</font>
                    </th>
                    <th  class="class-2 text-center">
                        <font>Diskon</font>
                    </th>
                    <th  class="class-2 text-center">
                        <font>Jasa</font>
                    </th>
                    <th  class="class-2 text-center">
                        <font>Total</font>
                    </th>
                </tr>
                @php
                    $nomor = 1;
                    $totalharga = 0;
                    $totaldiskon = 0;
                    $totaljasa = 0;
                    $totalall = 0;
                @endphp
                @forelse ($dataReport['details'] as $item)
                    <tr>
                        <th class="text-top text-left">
                            <font class="p1">{{ $nomor }}</font>
                        </th>
                        <th class="text-top text-left">
                            <font>
                                {{ date_format(date_create($item->tglpelayanan), 'd/m/Y') }}</font>
                        </th>
                        <th class="text-top text-left">
                            <font>{{ $item->namaproduk }}</font>
                        </th>
                        <th class="text-top text-left">
                            <font>{{ $item->namadokter }}</font>
                        </th>
                        <th class="text-top text-left">
                            <font>{{ $item->jumlah }}</font>
                        </th>
                        <th class="text-top text-right">
                            <font>
                                {{ number_format($item->hargasatuan, 2, '.', ',') }}</font>
                        </th>
                        <th class="text-top text-right">
                            <font>
                                {{ number_format($item->diskon, 2, '.', ',') }}</font>
                        </th>
                        <th class="text-top text-right">
                            <font>{{ number_format($item->jasa, 2, '.', ',') }}
                            </font>
                        </th>
                        <th class="text-top text-right">
                            <font>{{ number_format($item->total, 2, '.', ',') }}
                            </font>
                        </th>
                    </tr>
                    @php
                        $nomor = $nomor + 1;
                        $totalharga = $totalharga + $item->hargasatuan;
                        $totaldiskon = $totaldiskon + $item->diskon;
                        $totaljasa = $totaljasa + $item->jasa;
                        $totalall = $totalall + $item->total;
                    @endphp
                @empty
                <tr>
                    <th class=" text-center" colspan="8">
                    Data Tidak ada
                    </th>
                </tr>
                @endforelse
                <tr>
                    <th class="class-2 text-left" colspan="8">
                        <font>Total</font>
                    </th>
                    <th  class="class-2 text-right" colfont="5">
                        <font>{{ number_format($totalall, 2, '.', ',') }}</font>
                    </th>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td colspan="2" align="right">
                        <font>{!! $profile->namakota !!},
                            &nbsp;&nbsp;{{ date('d/m/Y H:i') }}</font>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">Pasien</td>
                    <td class="text-center">Dokter</td>
                    <td class="text-center">Kasir</td>
                </tr>
                <tr>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center">{{ $dataReport['header']->namapasien }}</td>
                    <td class="text-center">_______________________</td>
                    <td class="text-center">_______________________</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <span style="font-size: 10pt; color:#000000">
                            Dicetak Oleh : {{ $dataReport['user'] }} &nbsp;&nbsp;&nbsp;
                            Diprint Tanggal : {{ date('d/m/Y H:i') }}
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

@endsection

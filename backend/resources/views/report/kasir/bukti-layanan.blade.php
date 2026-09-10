@extends('template.layout')
@section('title',  $dataReport['judul'] )
@section('page-style')
    <style>
    .class-2 {
        border-top:1px solid black;border-bottom:1px solid black;
    }
    .text-biasa{
        font-size: 11.5pt;
        font-weight: 500;
    }
    </style>
@endsection
@section('content')
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" height="80px">
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
                    <td>
                        <span class="text-biasa">No. Registrasi / No. RM</span>
                    </td>
                    <td>
                        <span class="text-biasa">:
                            {{ $dataReport['header']->noregistrasi }}&nbsp;/&nbsp;{{ $dataReport['header']->nocm }}</span>
                    </td>

                    <td>
                        <span class="text-biasa">Unit Layanan</span>
                    </td>
                    <td>
                        <span class="text-biasa">: {{ $dataReport['header']->ruanganperiksa }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text-biasa">Nama Pasien</span>
                    </td>
                    <td>
                        <span class="text-biasa">: {{ $dataReport['header']->namapasien }}</span>
                    </td>

                    <td>
                        <span class="text-biasa">Tipe Pasien</span>
                    </td>
                    <td>
                        <span class="text-biasa">:
                            {{ $dataReport['header']->kelompokpasien }}&nbsp;
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text-biasa">Alamat</span>
                    </td>
                    <td>
                        <span class="text-biasa">: {{ $dataReport['header']->alamatlengkap }}</span>
                    </td>

                    <td>
                        <span class="text-biasa">Tgl Registrasi</span>
                    </td>
                    <td>
                        <span class="text-biasa">:
                            {{ $dataReport['header']->tglregistrasi }}&nbsp;
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text-biasa">Jenis Kelamin / Tgl Lahir</span>
                    </td>
                    <td>
                        <span class="text-biasa">:
                            {{ $dataReport['header']->jk }}&nbsp;/&nbsp;{{ $dataReport['header']->tglkelahiran }}</span>
                    </td>

                    <td>
                        <span class="text-biasa">Kamar / Kelas</span>
                    </td>
                    <td>
                        <span class="text-biasa">:
                            {{ $dataReport['header']->namakamar }}&nbsp;/&nbsp;{{ $dataReport['header']->namakelas }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text-biasa">Dokter DPJP</span>
                    </td>
                    <td>
                        <span class="text-biasa">: {{ $dataReport['header']->dpjp }}</span>
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
                        <span class="text-biasa">No</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Tgl Pelayanan</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Pelayanan</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Dokter Pemeriksa</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Qty</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Tarif</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Diskon</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Jasa</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Total</span>
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
                    <tr style="padding:10px">
                        <th class="text-top text-left">
                            <span class="text-biasa">{{ $nomor }}</span>
                        </th>
                        <th class="text-top text-left">
                            <span class="text-biasa">
                                {{ date_format(date_create($item->tglpelayanan), 'd/m/Y') }}</span>
                        </th>
                        <th class="text-top text-left">
                            <span class="text-biasa">{{ $item->namaproduk }}</span>
                        </th>
                        <th class="text-top text-left">
                            <span class="text-biasa">{{ $item->namadokter }}</span>
                        </th>
                        <th class="text-top text-left">
                            <span class="text-biasa">{{ $item->jumlah }}</span>
                        </th>
                        <th class="text-top text-right">
                            <span class="text-biasa">
                                {{ number_format($item->hargasatuan, 2, '.', ',') }}</span>
                        </th>
                        <th class="text-top text-right">
                            <span class="text-biasa">
                                {{ number_format($item->diskon, 2, '.', ',') }}</span>
                        </th>
                        <th class="text-top text-right">
                            <span class="text-biasa">{{ number_format($item->jasa, 2, '.', ',') }}
                            </span>
                        </th>
                        <th class="text-top text-right">
                            <span class="text-biasa">{{ number_format($item->total, 2, '.', ',') }}
                            </span>
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
                        <span class="text-biasa">Total</span>
                    </th>
                    <th  class="class-2 text-right" colspan="5">
                        <span class="text-biasa">{{ number_format($totalall, 2, '.', ',') }}</fonspant>
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
                        <span class="text-biasa">{!! $profile->namakota !!},
                            {{-- &nbsp;&nbsp;{{ date('d/m/Y H:i') }} --}}
                            &nbsp;&nbsp;{{ date_format(date_create($item->tglpelayanan), 'd/m/Y H:i') }}
                        </span>
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
                        <span style="font-size: 11.5pt; color:#000000">
                            Dicetak Oleh : {{ $dataReport['user'] }} &nbsp;&nbsp;&nbsp;
                            Diprint Tanggal : {{ date('d/m/Y H:i') }}
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

@endsection

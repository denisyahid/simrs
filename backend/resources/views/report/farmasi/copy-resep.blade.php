@extends('template.layout')
@section('title', 'Cetak Resep')
@section('page-style')
    <style>
        .table-bd {
            border: 1px solid black;
            padding: 2px;
            padding-left: 5px;
        }
    </style>
@endsection
@section('content')
    <tr>
        <td style="padding-top:10px">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td align="center">
                        <font style="font-size: 15pt;font-weight: 600;text-decoration: underline;" color="#000000">
                            COPY RESEP
                        </font>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <font style="font-size: 11pt;" color="#000000">
                            Apoteker : {{ $apoteker->nama }}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <font style="font-size: 11pt;" color="#000000">
                            SIPA : {{ $apoteker->nosipa }}
                        </font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:10px">
            <table width="90%" cellspacing="0" cellpadding="0" border="0" class="garishalus2" align="center">
                <tr>
                    {{-- col 1 --}}
                    <td width="20%">
                        <font style="font-size: 11pt;" color="#000000">No. Resep</font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 11pt;" color="#000000">:</font>
                    </td>
                    <td width="35%">
                        <font style="font-size: 11pt;" color="#000000">{{ $dataReport['identitas']->noresep }}</font>
                    </td>

                    {{-- col 2 --}}
                    <td width="20%">
                        <font style="font-size: 11pt;" color="#000000">Tanggal Resep</font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 11pt;" color="#000000">:</font>
                    </td>
                    <td width="35%">
                        <font style="font-size: 11pt;" color="#000000">{{ $dataReport['identitas']->tglorder }}</font>
                    </td>
                </tr>
                <tr>
                    {{-- col 1 --}}
                    <td width="20%">
                        <font style="font-size: 11pt;" color="#000000">Nama Pasien</font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 11pt;" color="#000000">:</font>
                    </td>
                    <td width="35%">
                        <font style="font-size: 11pt;" color="#000000">{{ $dataReport['identitas']->namapasien }}</font>
                    </td>

                    {{-- col 2 --}}
                    <td width="20%">
                        <font style="font-size: 11pt;" color="#000000">Rekam Medik</font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 11pt;" color="#000000">:</font>
                    </td>
                    <td width="35%">
                        <font style="font-size: 11pt;" color="#000000">{{ $dataReport['identitas']->nocm }}</font>
                    </td>
                </tr>
                <tr>
                    {{-- col 1 --}}
                    <td width="20%">
                        <font style="font-size: 11pt;" color="#000000">Tanggal Lahir</font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 11pt;" color="#000000">:</font>
                    </td>
                    <td width="35%">
                        <font style="font-size: 11pt;" color="#000000">{{ $dataReport['identitas']->tgllahir }}</font>
                    </td>

                    {{-- col 2 --}}
                    <td width="20%">
                        <font style="font-size: 11pt;" color="#000000">Poli Ruangan</font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 11pt;" color="#000000">:</font>
                    </td>
                    <td width="35%">
                        <font style="font-size: 11pt;" color="#000000">{{ $dataReport['identitas']->namaruangan }}</font>
                    </td>
                </tr>
                <tr>
                    {{-- col 1 --}}
                    <td width="20%">
                        <font style="font-size: 11pt;" color="#000000">Umur</font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 11pt;" color="#000000">:</font>
                    </td>
                    <td width="35%">
                        <font style="font-size: 11pt;" color="#000000">{{ $dataReport['identitas']->umur }}</font>
                    </td>

                    {{-- col 2 --}}
                    <td width="20%">
                        <font style="font-size: 11pt;" color="#000000">Nama Dokter</font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 11pt;" color="#000000">:</font>
                    </td>
                    <td width="35%">
                        <font style="font-size: 11pt;" color="#000000">{{ $dataReport['identitas']->namalengkap }}</font>
                    </td>
                </tr>
                <tr>
                    {{-- col 1 --}}
                    <td width="20%">
                        <font style="font-size: 11pt;" color="#000000">Jenis Kelamin</font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 11pt;" color="#000000">:</font>
                    </td>
                    <td width="35%">
                        <font style="font-size: 11pt;" color="#000000">{{ $dataReport['identitas']->jeniskelamin }}
                        </font>
                    </td>

                    {{-- col 2 --}}
                    <td width="20%">
                        <font style="font-size: 11pt;" color="#000000">SIP Dokter</font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 11pt;" color="#000000">:</font>
                    </td>
                    <td width="35%">
                        <font style="font-size: 11pt;" color="#000000">{{ $dataReport['identitas']->nosip }}</font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px">
            <table width="90%" cellspacing="0" cellpadding="0" border="1" align="center"
                style="border:1px solid black">
                <tr class="garisatasbawah" class="table-bd">
                    <th class="table-bd" align="left">
                        <font style="font-size: 11pt;" color="#000000">R/</font>
                    </th>
                    <th class="table-bd" align="left">
                        <font style="font-size: 11pt;" color="#000000">Nama Obat</font>
                    </th>
                    <th class="table-bd" align="center">
                        <font style="font-size: 11pt;" color="#000000">Dosis</font>
                    </th>
                    <th class="table-bd" align="center">
                        <font style="font-size: 11pt;" color="#000000">Aturan Pakai</font>
                    </th>
                    <th class="table-bd" align="center">
                        <font style="font-size: 11pt;" color="#000000">Jumlah</font>
                    </th>
                    <th class="table-bd" align="left">
                        <font style="font-size: 11pt;" color="#000000">Iter</font>
                    </th>
                    <th class="table-bd" align="left">
                        <font style="font-size: 11pt;" color="#000000">Keterangan</font>
                    </th>
                </tr>
                @php
                    $total = 0;
                @endphp
                @foreach ($dataReport['details'] as $item)
                    @php
                        $total = $total + $item->jumlah;
                    @endphp
                    <tr class="table-bd">
                        <td align="left" class="table-bd">
                            <font style="font-size: 11pt;" color="#000000">{{ $item->rke }}</font>
                        </td>
                        <td class="table-bd" align="left">
                            <font style="font-size: 11pt;" color="#000000">{{ $item->namaproduk }}</font>
                        </td>
                        @php
                            if ($item->jeniskemasan == "Racikan") {
                                $dosis = $item->dosis;
                                $jumlahMakan = ($item->jumlah / $item->dosis) * $item->kekuatan;
                            } else {
                                $dosis =1;
                                $jumlahMakan =$item->jumlah;
                            }
                        @endphp
                        <td class="table-bd" align="center">
                            <font style="font-size: 11pt;" color="#000000">{{$jumlahMakan }}/{{ $dosis  }}/{{$item->kekuatan}}</font>
                        </td>
                        <td class="table-bd" align="center">
                            <font style="font-size: 11pt;" color="#000000">{{ $item->aturanpakai }}</font>
                        </td>
                        <td class="table-bd" align="center">
                            <font style="font-size: 11pt;" color="#000000">
                                {{ number_format($item->jumlah, 2, '.', ',') }}</font>
                        </td>
                        @if ($Iter != null)
                            @php
                                $iters = explode(',', $Iter);
                            @endphp
                            @foreach ($iters as $iter)
                                @if (explode('|', $iter)[0] == $item->objectprodukfk)
                                    <td align="left">
                                        <font style="font-size: 11pt;" color="#000000">{{ explode('|', $iter)[1] }}
                                        </font>
                                    </td>
                                    {{-- <td align="left">
                                        <font style="font-size: 11pt;" color="#000000">{{ explode('|', $iter)[2] }}
                                        </font>
                                    </td> --}}
                                    @continue
                                @endif
                            @endforeach
                        @else
                            <td class="table-bd" align="left">
                                <font style="font-size: 11pt;" color="#000000"></font>
                            </td>
                            {{-- <td class="table-bd" align="left">
                                <font style="font-size: 11pt;" color="#000000"></font>
                            </td> --}}
                        @endif
                        <td class="table-bd" align="center">
                            <font style="font-size: 11pt;" color="#000000">{{ $item->keteranganpakai }}</font>
                        </td>

                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:30px">
            <table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
                <tr>
                    <td width="50%"></td>
                    <td width="50%" align="center">
                        <font style="font-size: 11pt;" color="#000000">{!! $profile->namakota !!},
                            {{ App\Traits\Valet::getDateIndo(date('Y-m-d')) }}</font>
                    </td>
                </tr>
                <tr>
                    <td width="50%" height="100"></td>
                    <td width="50%" height="100"></td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%" align="center">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection

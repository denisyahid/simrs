@extends('template.layout')
@section('title', 'Laporan Penerimaan Kasir')
@section('page-style')
 <style>
    .border{
    }
    .border td{
        border-top: 2px  dashed;
        height: 20px;

    }
 </style>
@endsection
@section('content')
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0" style="border-bottom:2px solid black;">
                <tr>
                    <td class="text-center">
                        <span class="text-judul">Laporan Penerimaan Kasir</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding-top:10px">
            <table width="100%" cellspacing="0" cellpadding="1" border="0">
               

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
                            <span class="text-normal-1 bold">
                                <b> Kelompok Pasien : {{ strtoupper($ruangan[0]->kelompokpasien) }}</b>
                            </span>
                        </td>

                    </tr>
                    @foreach ($ruangan->groupBy('kasir') as $item)
                        <tr style="font-style:italic">
                            <td colspan="10">
                                <span class="text-normal-1">
                                    <b> Kasir: {{ strtoupper($item[0]->kasir) }}</b>
                                </span>
                            </td>
                        </tr>
                        @php
                            $total = 0;
                            $diskon = 0;
                        @endphp
                         <tr>
                            {{-- <th class="th-class text-left">
                                    <span class="text-normal-1">No</span>
                                </th> --}}
                            <th class="th-class text-left">
                                <span class="text-normal-1">Noregistrasi</span>
                            </th>
                            <th class="th-class  text-center">
                                <span class="text-normal-1">Tanggal Bayar</span>
                            </th>
                            <th class="th-class text-left">
                                <span class="text-normal-1">Nama Pasien</span>
                            </th>
        
                            <th class="th-class text-left">
                                <span class="text-normal-1">No.CM</span>
                            </th>
                            <th class="th-class  text-center">
                                <span class="text-normal-1">Ruangan</span>
                            </th>
                            <th class="th-class  text-center">
                                <span class="text-normal-1">Total Biaya</span>
                            </th>
                            <th class="th-class  text-center">
                                <span class="text-normal-1">Hutang</span>
                            </th>
                            <th class="th-class  text-center">
                                <span class="text-normal-1">Diskon</span>
                            </th>
                            <th class="th-class  text-right">
                                <span class="text-normal-1">Tunai</span>
                            </th>
                            <th class="th-class  text-right">
                                <span class="text-normal-1">Card/CC</span>
                            </th>
                        </tr>
                        @foreach ($item as $data)
                            <tr>
                                {{-- <td class="text-top text-left">
                                        <span class="text-normal-1">{{ $nomor }}</span>
                                    </td> --}}
                                <td class="text-top text-left">
                                    <span class="text-normal-1">{{ $data->noregistrasi }}</span>
                                </td>
                                <td class="text-top text-center">
                                    <span class="text-normal-1">
                                        {{ date_format(date_create($data->tglsbm), 'd/m/Y') }}</span>
                                </td>
                                @if ($data->namapasien === null)
                                    <td class="text-top text-left">
                                        <span class="text-normal-1">{{ $data->namapasien_klien }}</span>
                                    </td>
                                @else
                                    <td class="text-top text-left">
                                        <span class="text-normal-1">{{ $data->namapasien }} </span>
                                    </td>
                                @endif
                                <td class="text-top text-center">
                                    <span class="text-normal-1">{{ $data->namaruangan }}</span>
                                </td>
                                <td class="text-top text-center">
                                    <span class="text-normal-1">{{ $data->nocm }}</span>
                                </td>
                         
                                <td class="text-top text-right">
                                        <span class="text-normal-1"> {{ number_format($data->totalPenerimaan, 0, '.', ',') }}</span>
                                    </td>
                                <td class="text-top text-center">
                                    <span
                                        class="text-normal-1">{{ number_format($data->totalsisapiutang, 0, '.', ',') }}</span>
                                </td>
                                <td class="text-top text-center">
                                    <span class="text-normal-1">{{ number_format($data->totaldiskon, 0, '.', ',') }}</span>
                                </td>
                                <td class="text-top text-right">
                                    <span class="text-normal-1">
                                        {{ number_format($data->tunai, 0, '.', ',') }}</span>
                                </td>
                                <td class="text-top text-right">
                                    <span class="text-normal-1">
                                        {{ number_format($data->nontunai, 0, '.', ',') }}</span>
                                </td>
                                <td class="text-top text-right">
                                    <span class="text-normal-1">
                                      
                                </td>
                            </tr>
                            @endforeach
                            @php
                                    // $nomor = $nomor + 1;
                                    $total = $total + $data->totalPenerimaan;
                                    // $diskon = $diskon + $data->diskon;

                                @endphp
                                <tr class="border">
                                    <td class="text-right" colspan="2">
                                        <span class="text-normal-1">
                                           Sub Total Perkelompok Pasien 
                                        </span>
                                    </td>
                                    <td class="text-right" colspan="8">
                                        <span class="text-normal-1">
                                            {{ number_format($total, 0, '.', ',') }}
                                        </span>
                                    </td>
                                </tr>
                        {{-- 
                            @php
                                $totaltagihan = $totaltagihan + $total;
                                $totaldiskon = $totaldiskon + $diskon;
                                $jumlahbill = $totaltagihan - $totaldiskon;
                            @endphp --}}
                    @endforeach
                @endforeach
            </table>
        </td>
    </tr>
    <tr>

    </tr>
    {{-- <tr>
            <td style="padding-top:20px">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <span class="text-biasa">TERBILANG :
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-biasa"><i>#
                                    {{ strtoupper(App\Traits\Valet::static_terbilang( $res['total'])) }} #</i>
                        </td>
                    </tr>
                </table>
            </td>
        </tr> --}}

    <tr>

    </tr>

@endsection

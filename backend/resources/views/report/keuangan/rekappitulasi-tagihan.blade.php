@extends('template.layout')
@section('title', 'Cetak Rekappitulasi Tagihan')
@section('page-style')
 <style>
    .garisatasbawah th{
         border: 1px solid
    }
    .garisatasbawah td{
         border: 1px solid
    }
 </style>

@endsection
@section('content')
    <table class="bayangprint" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0"  width="100%"
        style="padding:25px">
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center" height="80px">
                                <font style="font-size: 14pt;" color="#000000">
                                    <b>REKAPITULASI TAGIHAN ASURANSI {!! $dataReport['datas'][0]->namarekanan !!}</b>
                                    <br />
                                    <span style="font-size:12pt;">
                                        PERIODE TANGGAL {!! strtoupper($dataReport['periode']) !!}
                                    </span>
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0"  align="center" bgcolor="#FFFFFF">
                        <tr class="garisatasbawah">
                            <th align="center">
                                <font style="font-size: 11pt;" color="#000000">NO</font>
                            </th>
                            <th align="center">
                                <font style="font-size: 11pt;" color="#000000">NAMA ASURANSI</font>
                            </th>
                            <th align="center">
                                <font style="font-size: 11pt;" color="#000000">NAMA PASIEN</font>
                            </th>
                            <th align="center">
                                <font style="font-size: 11pt;" color="#000000">NO KARTU PESERTA</font>
                            </th>
                            <th align="center">
                                <font style="font-size: 11pt;" color="#000000">TGL BEROBAT</font>
                            </th>
                            <th align="center">
                                <font style="font-size: 11pt;" color="#000000">BIAYA PENGOBATAN</font>
                            </th>
                        </tr>
                        @php
                            $nomor = 1;
                            $total = 0;
                            $totalAll = 0;
                        @endphp
                        @foreach ($dataReport['datas'] as $item)
                            <tr class="garisatasbawah">
                                <td align="center">
                                    <font style="font-size: 11pt;" color="#000000">{{ $nomor }}</font>
                                </td>
                                <td align="left">
                                    <font style="font-size: 11pt;" color="#000000">{{ $item->namarekanan }}</font>
                                </td>
                                <td align="left">
                                    <font style="font-size: 11pt;" color="#000000">{{ $item->namapasien }}</font>
                                </td>
                                <td align="right">
                                    <font style="font-size: 11pt;" color="#000000">{{ $item->nokepesertaan }}</font>
                                </td>
                                <td align="right">
                                    <font style="font-size: 11pt;" color="#000000">
                                        {{ date_format(date_create($item->tglregistrasi), 'd/m/Y') }}</font>
                                </td>
                                <td align="right">
                                    <font style="font-size: 11pt;" color="#000000">
                                        {{ number_format(weirdRounding($item->tarifklaim), 2, '.', ',') }}</font>
                                </td>
                            </tr>
                            @php
                                $nomor = $nomor + 1;
                                $total = $total + $item->tarifklaim;
                            @endphp
                        @endforeach
                        @php

                        @endphp
                        <tr>
                            <td align="right" colspan="5">
                                <font style="font-size: 10.7pt;font-weight:bold;" color="#000000">
                                    <b>TOTAL</b>
                                </font>
                            </td>
                            <td align="right">
                                <font style="font-size: 10.7pt;font-weight:bold;" color="#000000">
                                    @php
                                        function weirdRounding($num)
                                        {
                                            if ($num - floor($num) >= 0.5) {
                                                return ceil($num);
                                            } else {
                                                $str = (string) $num;
                                                $str2 = explode('.', $str);
                                                $str2[1] = '00';
                                                $str3 = implode('.', $str2);
                                                return $str3;
                                            }
                                        }
                                    @endphp
                                    {{ number_format(weirdRounding($total), 2, '.', ',') }}
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" align="right">
                                <font style="font-size: 11pt;" color="#000000"; align="center"></font>
                            </td>
                            <td width="50%" align="right" height="145px">
                                <font style="font-size: 11pt;" color="#000000">
                                    {!! $profile->namakota !!},{{ $dataReport['tanggal'] }}<br>Regards</font>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" align="right">
                                <font style="font-size: 11pt;" color="#000000"; align="center"></font>
                            </td>
                            <td width="50%" align="right">
                                <font style="font-size: 11pt;" color="#000000";>
                                    <u><b>Eriza Safitri Zain,S.E</b></u><br />
                                    Kepala Bagian Keuangan & SDM
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
        </tbody>
    </table>
@endsection

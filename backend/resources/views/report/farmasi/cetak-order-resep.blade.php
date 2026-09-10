@extends('template.layout')
@section('title', 'Order Resep')
@section('page-style')
    <style>
        .solid {
            background-color: white;
            border-top-style: solid;
            border-bottom-style: solid
        }
    </style>
@endsection
@section('content')

    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0" style="border-bottom:2px solid black;">
                <tr>
                    <td align="center">
                        <span style="font-size: 14pt;font-weight: bold">PERMINTAAN RESEP</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="6" style="padding-bottom: 20px;">
                        <span style="font-size: 12px;">
                            Print by {{ $dataReport['user'] }} &nbsp;&nbsp;&nbsp;
                            {{ date('d/m/Y H:i') }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <span style="font-size: 12px;">
                            No. Resep
                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">
                            {{ $dataReport['raw']->noresep }}
                        </span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 12px;">
                            Tanggal Resep
                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">
                            {{ $dataReport['raw']->tglorder }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <span style="font-size: 12px;">
                            Nama Pasien
                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">
                            {{ $dataReport['raw']->namapasien }}
                        </span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 12px;">
                            Rekam Medik
                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">
                            {{ $dataReport['raw']->nocm }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <span style="font-size: 12px;">
                            Tanggal Lahir
                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">
                            {{ $dataReport['raw']->tgllahir }}
                        </span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 12px;">
                            Poli/Ruangan
                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">
                            {{ $dataReport['raw']->namaruangan }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <span style="font-size: 12px;">
                            Umur
                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">
                            {{ $dataReport['raw']->umur }}
                        </span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 12px;">
                            Nama Dokter
                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">
                            {{ $dataReport['raw']->namalengkap }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <span style="font-size: 12px;">
                            Jenis Kelamin
                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">
                            {{ $dataReport['raw']->jeniskelamin }}
                        </span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 12px;">
                            Tipe Pasien
                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">
                            {{ $dataReport['raw']->kelompokpasien }} &nbsp; ({{ $dataReport['raw']->namarekanan }})
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <span style="font-size: 12px;">
                            Alergi
                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">

                        </span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 12px;">

                        </span>
                    </td>
                    <td width="5%">
                        <span style="font-size: 12px;">
                            :
                        </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 12px;">

                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:10px">
            <table width="100%" cellspacing="0" cellpadding="1" border="0">
                <tr style="border-bottom-style: solid;border-top-style: solid;border:1px ;background: #d6d4d4;">

                    <td class="solid">
                        <b>R Ke</b>
                    </td>
                    <td class="solid">
                        <b>Nama Obat</b>
                    </td>
                    <td class="solid">
                        <b>Dosis</b>
                    </td>
                    <td class="solid">
                        <b>Jumlah</b>
                    </td>
                    <td class="solid">
                        <b>Aturan Pakai</b>
                    </td>


                </tr>
                @php
                    $nomor = 1;
                    $totaltagihan = 0;
                    $totaldiskon = 0;
                    $jumlahbill = 0;
                    $totaldiklaim = 0;
                @endphp
                @foreach ($dataReport['detail']->groupBy('jenisracikan') as $item)
                    <tr>
                        <td colspan="6">
                            <span style="font-size: 13px;font-weight: bold;">{{ $item[0]->jenisracikan }}
                            </span>
                        </td>
                    </tr>
                    @foreach ($item as $dat)
                        <tr>
                            <td>
                                R.{{ $dat->rke }}
                            </td>
                            <td>
                                {{ $dat->namaproduk }}
                            </td>
                            @php
                                if ($dat->jenisracikan == "Racikan") {
                                    $dosis = $dat->dosis;
                                    $jumlahMakan = ($dat->jumalah / $dat->dosis) * $dat->kekuatan;
                                } else {
                                    $dosis =1;
                                    $jumlahMakan =$dat->jumlah;
                                }
                            @endphp
                            <td>
                               {{$jumlahMakan }}/{{ $dosis  }}/{{$dat->kekuatan}}
                            </td>
                            <td>
                                {{ $dat->jumlah }}
                            </td>
                            <td>
                                {{ $dat->aturanpakai }}
                            </td>



                        </tr>
                        @php
                            $nomor = $nomor + 1;
                        @endphp
                    @endforeach
                @endforeach
            </table>
        </td>
    </tr>

@endsection

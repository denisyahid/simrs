@extends('template.layout-lab')
@section('title', 'Cetak Lembar Rawat Inap')
@section('page-style')
    <style>
        .border-name {
            border: 1px solid #777777;
            padding: 7px 5px 5px 5px;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
        }

        /* .table tr.border-line {
            border-top: 1px solid #777777;
            border-left: 1px solid #777777;
            border-right: 1px solid #777777;
            border-bottom: 1px solid #777777;
        }

        table .bordered {
            border-top: 1px solid #777777;
            border-left: 1px solid #777777;
            border-right: 1px solid #777777;
            border-bottom: 1px solid #777777;
        } */

        .table-order {
            margin-top: 40px;
            border: 2px solid black;
            border-collapse: collapse;
            width: 100%;

        }

        .table-order td,
        th {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
@endsection
@section('content')
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0" style="border-bottom:2px solid black;">
                <tr>
                    <td style="text-align: center">
                        <span class="text-judul">ORDER PEMERIKASAAN {{$type}}</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <section style="margin-top: 5px"></section>
    {{-- <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 10px">
        <tr>
            <td style="text-align: center" width="80%">

            </td>
            <td style="text-align: center" width="20%">
                <div class="border-name">
                    No : {{ $datas[0]->noorder }}
                </div>
            </td>
        </tr>
    </table> --}}
    <tr style="margin-top: 10px;">
        <table width="100%" cellspacing="0" cellpadding="0">
            <td width="65%">
                <table class="table">
                    <tr width="100%" class="border-line">
                        <td width="60%">
                            <span style="font-size: 11pt;" color="#000000"> Nomer Id/MR</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="60%%">
                            <span style="font-size: 11pt;" color="#000000">{{ $datas[0]->nocm ?? '' }}</span>
                        </td>
                    </tr>
                    <tr width="100%" class="border-line">
                        <td width="60%">
                            <span style="font-size: 11pt;" color="#000000"> Nama Pasien</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="39%">
                            <span style="font-size: 11pt;" color="#000000">{{ $datas[0]->namapasien ?? '' }}</span>
                        </td>
                    </tr>
                    <tr width="100%" class="border-line">
                        <td width="60%">
                            <span style="font-size: 11pt;" color="#000000"> No</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="60%%">
                            <span style="font-size: 11pt;" color="#000000">{{ $datas[0]->noorder }}</span>
                        </td>
                    </tr>
                    <tr width="100%" class="border-line">
                        <td width="60%">
                            <span style="font-size: 11pt;" color="#000000">Ruang Asal</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="39%">
                            <span style="font-size: 11pt;" color="#000000">{{ $datas[0]->ruanganasal ?? '' }}</span>
                        </td>
                    </tr>
                    <tr width="100%" class="border-line">
                        <td width="60%">
                            <span style="font-size: 11pt;" color="#000000">No Telp</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="39%">
                            <span style="font-size: 11pt;" color="#000000">{{ $datas[0]->nohp ?? '' }}</span>
                        </td>
                    </tr>
                    <tr width="100%" class="border-line">
                        <td width="60%">
                            <span style="font-size: 11pt;" color="#000000">Tgl Lahir</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="39%">
                            <span style="font-size: 11pt;" color="#000000">{{ $datas[0]->tgllahir ?? '' }}</span>
                        </td>
                    </tr>
                    {{-- <tr width="100%" class="border-line">
                        <td width="60%">
                            <span style="font-size: 11pt;" color="#000000">Email</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="39%">
                            <span style="font-size: 11pt;" color="#000000">{{ $datas[0]->email ?? '' }}</span>
                        </td>
                    </tr> --}}
                    <tr width="100%" class="border-line">
                        <td width="60%">
                            <span style="font-size: 11pt;" color="#000000"> Alamat</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="39%">
                            <span style="font-size: 11pt;" color="#000000">{{ $datas[0]->alamatlengkap ?? '' }}</span>
                        </td>
                    </tr>
                </table>
                {{-- <div class="border-name" style="margin-top: 15px">
                    <table>
                        <tr width="100%" class="border-line">
                            <td width="70%">
                                <span style="font-size: 11pt;" color="#000000">Diagnosisi / Keterangan Klinik</span>
                            </td>
                            <td width="1%">
                                <span style="font-size: 11pt;" color="#000000">:</span>
                            </td>
                            <td width="29%">
                                <span style="font-size: 11pt;" color="#000000">-</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="border-name" style="margin-top: 15px">
                    <table>
                        <tr width="100%" class="border-line">
                            <td width="70%">
                                <span style="font-size: 11pt;" color="#000000">Pemeriksaan lainya</span>
                            </td>
                            <td width="1%">
                                <span style="font-size: 11pt;" color="#000000">:</span>
                            </td>
                            <td width="29%">
                                <span style="font-size: 11pt;" color="#000000">-</span>
                            </td>
                        </tr>
                    </table>
                </div> --}}
            </td>
            <td width="35%">
                <table class="bordered" width="100%">
                    <tr width="100%" class="border-line">
                        <td width="30%">
                            <span style="font-size: 11pt;" color="#000000">Dokter</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 11pt;" color="#000000">{{ $datas[0]->pengorder ?? '' }}</span>
                        </td>
                    </tr>
                    <tr width="100%" class="border-line">
                        <td width="30%">
                            <span style="font-size: 11pt;" color="#000000">Alamat</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 11pt;" color="#000000">{{ $data[0]->alamatdokter ?? '' }}</span>
                        </td>
                    </tr>
                    <tr width="100%" class="border-line">
                        <td width="30%">
                            <span style="font-size: 11pt;" color="#000000">Telepon</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 11pt;" color="#000000">{{ $datas[0]->tlpdokter ?? '' }}</span>
                        </td>
                    </tr>
                    <tr width="100%" class="border-line">
                        <td width="30%">
                            <span style="font-size: 11pt;" color="#000000">Tanggal</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 11pt;" color="#000000">{{ date('d-m-Y') }}</span>
                        </td>
                    </tr>
                </table>
                {{-- <table class="bordered" width="100%" style="margin-top: 30px">
                    <tr width="100%" class="border-line">
                        <td width="30%">
                            <span style="font-size: 11pt;" color="#000000">Penjamin</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 11pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 11pt;" color="#000000">{{ $datas[0]->penanggungjawab ?? '' }}</span>
                        </td>
                    </tr>
                </table> --}}
            </td>
        </table>
    </tr>
    <hr>
    <tr width-="70%">
        <table width="100%" class="table-order">
            <tr>
                <th style="text-align: center">No</th>
                <th style="text-align: center">LIST ORDER PEMERIKSAAN</th>
                <th style="text-align: center">Harga</th>
                <th style="text-align: center">Status</th>
            </tr>
            @foreach ($datas as $key => $d)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td>{{ $d->pemeriksaan }}</td>
                    <td style="text-align: center">{{ isset($d->hargasatuan) ? number_format($d->hargasatuan) : 'Belum diVerifikasi'}}</td>
                    <td style="text-align: center"><input type="checkbox"  /></td>
                </tr>
            @endforeach
        </table>
    </tr>
@endsection

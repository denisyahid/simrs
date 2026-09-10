@extends('template.layout')
@section('title', 'Cetak Resep')
@section('page-style')

@endsection
@section('content')

    <tr>
        <td>
            <table style="width:100%">
                <caption style="display: none"></caption>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                <tr>
                    <td style="text-align:center; height:20px">
                        <span style="font-size: 13pt;font-weight: bold" color="#000000">RESEP</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table style="width:100%">
                <caption style="display: none"></caption>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                <tr>
                    <td style="width:13%"><span style="font-size: 11pt;" color="#000000">No Resep</span>
                    </td>
                    <td style="width:45%"><span style="font-size: 11pt;" color="#000000">:
                            {{ $dataReport['datas']->noresep }}</span></td>
                    <td style="width:13%"><span style="font-size: 11pt;" color="#000000">Tgl. Resep</span>
                    </td>
                    <td style="width:35%"><span style="font-size: 11pt;" color="#000000">:
                            {{ $dataReport['datas']->tgl }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px">
            <table style="width:100%" class="baris1">
                <caption style="display: none"></caption>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                <tr>
                    <td style="width:13%"><span style="font-size: 11pt;" color="#000000">Nama Pasien</span>
                    </td>
                    <td style="width:45%"><span style="font-size: 11pt;" color="#000000">:
                            <b>{{ $dataReport['datas']->namapasienjk }}</b></span></td>
                    <td style="width:13%"><span style="font-size: 11pt;" color="#000000">Rekam Medik</span>
                    </td>
                    <td style="width:35%"><span style="font-size: 11pt;" color="#000000">:
                            <b>{{ $dataReport['datas']->nocm }}</b></span></td>
                </tr>
                <tr>
                    <td style="width:13%"><span style="font-size: 11pt;" color="#000000">Tanggal
                            Lahir</span>
                    </td>
                    <td style="width:45%"><span style="font-size: 11pt;" color="#000000">:
                            {{ $dataReport['datas']->tgllahir }}</span></td>
                    <td style="width:13%"><span style="font-size: 11pt;" color="#000000">Poli/Ruangan</span>
                    </td>
                    <td style="width:35%"><span style="font-size: 11pt;" color="#000000">:
                            {{ $dataReport['datas']->ruanganpasien }}</span></td>
                </tr>
                <tr>
                    <td style="width:13%"><span style="font-size: 11pt;" color="#000000">Usia</span></td>
                    <td style="width:45%"><span style="font-size: 11pt;" color="#000000">:
                            {{ $dataReport['datas']->umur }}</span></td>
                    <td style="width:13%"><span style="font-size: 11pt;" color="#000000">Nama
                            Dokter</span>
                    </td>
                    <td style="width:35%"><span style="font-size: 11pt;" color="#000000">:
                            {{ $dataReport['datas']->namalengkap }}</span></td>
                </tr>
                <tr>
                    <td style="width:13%"><span style="font-size: 11pt;" color="#000000">Jenis
                            Kelamin</span></td>
                    <td style="width:45%"><span style="font-size: 11pt;" color="#000000">:
                            {{ $dataReport['datas']->jk }}</span></td>
                    <td style="width:13%"><span style="font-size: 11pt;" color="#000000">SIP
                            Dokter</span>
                    </td>
                    <td style="width:35%"><span style="font-size: 11pt;" color="#000000">:
                            {{ $dataReport['datas']->nosip }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:10px">
            <table style="width:100%">
                <caption style="display: none"></caption>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                <tr>
                    <td style="width: 100%"><span style="font-size: 11pt;font-weight: bold;" color="#000000">R/</span></td>
                </tr>
            </table>
            <hr class="baris2">
        </td>
    </tr>

    <tr>
        <td style="padding:8px">
            <table style="width:100%">
                <caption style="display: none"></caption>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                <tr>
                    <td style="width:10%"><span style="font-size: 11pt;" color="#000000">&nbsp;</span>
                    </td>
                    <td style="width: 20%"><span style="font-size: 11pt;" color="#000000">Nama
                            Obat</span>
                    </td>
                    <td style="width:10%"><span style="font-size: 11pt;" color="#000000">Dosis</span>
                    </td>
                    <td style="width:35%"><span style="font-size: 11pt;" color="#000000">Aturan
                            Pakai</span>
                    </td>
                    <td style="width:10%"><span style="font-size: 11pt;" color="#000000">Jumlah</span>
                    </td>
                    <td style="width:10%"><span style="font-size: 11pt;" color="#000000">Harga</span>
                    </td>
                    <td style="width:10%"><span style="font-size: 11pt;" color="#000000">Diskon</span>
                    </td>
                    <td style="width:10%"><span style="font-size: 11pt;" color="#000000">Total</span>
                    </td>
                </tr>
            </table>
            <hr class="baris2">
        </td>
    </tr>
    <tr>
        <td style="padding:8px">
            <table style="width:100%">
                <caption style="display: none"></caption>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                @php
                    $totalharga = 0;
                    $totaltagihan = 0;
                    $totalDiskon = 0;
                    $detailByJenisKemasan = $dataReport['detail'];
                @endphp
                @foreach ($detailByJenisKemasan as $jenisKemasan => $dataReport['detail'])
                    <tr>
                        <td style="padding-bottom: 15px; width:13%"><span style="font-size: 11pt;" color="#000000">Jenis
                                Obat</span></td>
                        <td style="padding-bottom: 15px; width:82%"><span style="font-size: 11pt;" color="#000000">:
                                {{ $jenisKemasan }}</span></td>
                    </tr>
                    @foreach ($dataReport['detail'] as $det)
                        <tr>
                            <td colspan="6">
                                <table style="width:100%">
                                    <caption style="display: none"></caption>
                                    <tr style="display: none">
                                        <th scope="col"></th>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; width:10%"><span style="font-size: 11pt;"
                                                color="#000000"><span>R/
                                                    {{ $det->rke }}</span></span></td>
                                        <td style="width: 20%"><span style="font-size: 11pt;"
                                                color="#000000">{{ $det->namaprodukstandar }}</span></td>
                                        <td style="vertical-align: top;text-align: center; width:10%"><span
                                                style="font-size: 11pt;margin-left: 5px"
                                                color="#000000">{{ $det->dosis }}</span></td>
                                        <td style="vertical-align: top;text-align: center; width:15%"><span
                                                style="font-size: 11pt;" color="#000000">{{ $det->aturanpakai }}</span>
                                        </td>
                                        <td style="vertical-align: top;text-align: right; width:10%">
                                            <span style="font-size: 11pt;" color="#000000">
                                                {{ number_format($det->jumlah, 2, '.', ',') }}</span>
                                        </td>
                                        <td style="vertical-align: top;text-align: right; width: 10%">
                                            <span style="font-size: 11pt;" color="#000000">
                                                {{ number_format($det->hargasatuan, 2, '.', ',') }}</span>
                                        </td>
                                        <td style="vertical-align: top;text-align: right; width:10%">
                                            <span style="font-size: 11pt;" color="#000000">
                                                {{ number_format($det->diskon, 2, '.', ',') }}</span>
                                        </td>
                                        <td style="vertical-align: top;text-align: right; width:10%">
                                            <span style="font-size: 11pt;" color="#000000">
                                                {{ number_format($det->totalharga, 2, '.', ',') }}</span>
                                        </td>
                                        <td style="width:10%"><span style="font-size: 11pt;" color="#000000"></span></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        @php
                            $totaltagihan = $totaltagihan + $det->totalharga;
                            $totalDiskon = $totalDiskon + $det->diskon;
                            $totalharga = $totalharga + $det->hargasatuan;
                        @endphp
                    @endforeach
                @endforeach
            </table>
            <hr class="baris2">
        </td>
    </tr>
    <tr>
        <td>
            <table style="width:100%">
                <caption style="display: none"></caption>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                <tr>
                    <td style="width:70%"><span style="font-size: 11pt;" color="#000000"></span></td>
                    <td style="width:15%"><span style="font-size: 11pt;" color="#000000"> Harga </span></td>
                    <td style="width:25%;text-align:right"><span style="font-size: 11pt;" color="#000000"><b>
                            </b><b>{{ number_format($totalharga, 2, '.', ',') }}</b></span></td>
                </tr>
                <tr>
                    <td style="width:70%"><span style="font-size: 11pt;" color="#000000"></span></td>
                    <td style="width:15%"><span style="font-size: 11pt;" color="#000000">Diskon</span></td>
                    <td style="width:25%;text-align:right"><span style="font-size: 11pt;" color="#000000"><b> 
                           
                            </b><b>{{ number_format($totalDiskon, 2, '.', ',') }}</b></span></td>
                </tr>
                <tr>
                    <td style="width:70%"><span style="font-size: 11pt;" color="#000000"></span></td>
                    <td style="width:15%"><span style="font-size: 11pt;" color="#000000">Total</span></td>
                    <td style="width:25%;text-align:right"><span style="font-size: 11pt;" color="#000000"><b>
                            </b><b>{{ number_format($totaltagihan, 2, '.', ',') }}</b></span></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px">
            <table class="baris1" style="width:100%">
                <caption style="display: none"></caption>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                <tr style="height: 100px;vertical-align: top;">
                    <td style="text-align:center;border-right: 2px solid black;height:70px; width:33%"><span style="font-size: 11pt;"
                            color="#000000">Kasir</span></td>
                    <td style="text-align:center;border-right: 2px solid black; height:70px;width:33%"><span style="font-size: 11pt;"
                            color="#000000">Dokter</span></td>
                    <td style="text-align:center; width:33%;height:70px"><span style="font-size: 11pt;" color="#000000">Admin</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection

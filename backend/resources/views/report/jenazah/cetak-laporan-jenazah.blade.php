<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cetak Laporan Jenazah</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> --}}
    <style>
        :root {
            --font: Arial, Helvetica, sans-serif;
        }

        .table-conten,
        .th-conten,
        .td-conten {
            border: 1px solid black;
            font-size: 10pt;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>

<body>

    <table width="35%" cellspacing="0" cellpadding="0" style="margin-top: 5rem">
        <tr>
            <td>
                <span style="font-size: 9pt">RSUP FARMAWATI</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-size: 9pt">JL. RS FATMAWATI CILANDAK</span>
            </td>
        </tr>
    </table>

    <div style="margin-top:1rem;margin-bottom:1rem">
        <span style="font-weight:bold;font-size:10pt">Laporan Kunjungan Rawat Jalan Per Pasien</span>
        <table width="50%" cellspacing="0" cellpadding="0" style="margin-top:.8rem">
            <tr>
                <td width="15%">
                    <span style="font-size: 10pt;font-weight:bold">Cara Bayar</span>
                </td>
                <td width="2%">
                    <span style="font-size: 10pt;font-weight:bold">:</span>
                </td>
                <td>
                    <span style="font-size: 10pt;font-weight:bold;">{{$dataReport[0]->carabayar}}</span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 10pt;font-weight:bold">INSTALASI</span>
                </td>
                <td width="2%">
                    <span style="font-size: 10pt;font-weight:bold">:</span>
                </td>
                <td>
                    <span style="font-size: 10pt;font-weight:bold;text-transform: uppercase">{{ $departemen->namadepartemen }}</span>
                </td>
            </tr>
        </table>
    </div>

    <span style="text-transform: uppercase;font-weight:bold;font-size:10pt">Periode : {{ $periode }}</span>

    <table width="100%" cellspacing="0" cellpadding="0" style="margin-top:.8rem">
        <tr>
            <th class="th-conten">NO</th>
            <th class="th-conten">Cara Bayar</th>
            <th class="th-conten">Sep</th>
            <th class="th-conten">Nopen</th>
            <th class="th-conten">NORM</th>
            <th class="th-conten">Nama pasien</th>
            <th class="th-conten">Tanggal Daftar</th>
            <th class="th-conten">Tanggal Kunjungan</th>
            <th class="th-conten">Tanggal Tindakan</th>
            <th class="th-conten">Tanggal Hasil</th>
            <th class="th-conten">Akun Penginput</th>
            <th class="th-conten">Dokter Hasil</th>
            <th class="th-conten">Dokter Tindakan</th>
            <th class="th-conten">Nama Tindakan</th>
            <th class="th-conten">Tarif Tindakan</th>
            <th class="th-conten">Ruangan Pelayanan</th>
            <th class="th-conten">Ruangan Pendaftaran</th>
        </tr>
        @foreach ($dataReport as $data)
            <tr>
                <td class="td-conten">{{ $loop->iteration }}</td>
                <td class="td-conten">{{ $data->carabayar }}</td>
                <td class="td-conten">{{ $data->nosep }}</td>
                <td class="td-conten">{{ $data->noregistrasi }}</td>
                <td class="td-conten">{{ $data->nocm }}</td>
                <td class="td-conten">{{ $data->namapasien }}</td>
                <td class="td-conten">{{ $data->tglregistrasi }}</td>
                <td class="td-conten">{{ $data->tglorder }}</td>
                <td class="td-conten">{{ $data->tglpelayanan }}</td>
                <td class="td-conten"></td>
                <td class="td-conten"></td>
                <td class="td-conten"></td>
                <td class="td-conten">{{ $data->namalengkap }}</td>
                <td class="td-conten">{{ $data->namaproduk }}</td>
                <td class="td-conten">Rp. {{ number_format($data->hargasatuan, 0, ',', '.') }}</td>
                <td class="td-conten">{{ $data->ruangantujuan }}</td>
                <td class="td-conten">{{ $data->ruangantujuan }}</td>
            </tr>
        @endforeach
    </table>



    {{-- <table width="20%" cellspacing="0" cellpadding="0" style="margin-top: 5rem">
        <tr>
            <td>
                <span>RSUP FATMAWATI</span>
            </td>
        </tr>
        <tr>
            <td>
                <span>JL. RS FATMAWATI CILENDEK</span>
            </td>
        </tr>
    </table>
    <table width="30%" cellspacing="0" cellpadding="0" style="margin-top: 2rem">
        <tr>
            <th>
                <span>LAPORAN KUNJUNGAN RAWAT JALAN PER PASIEN</span>
            </th>
        </tr>
        <tr>
            <th>
                <span>CARA BAYAR : </span>
            </th>
        </tr>
        <tr>
            <th>
                <span>INSTALASI : </span>
            </th>
        </tr>
        <tr>
            <th>
                <span>UNIT : </span>
            </th>
        </tr>
        <tr>
            <th>
                <span>SUB UNIT : </span>
            </th>
        </tr>
    </table> --}}
    {{-- <tr>
                <td  colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>No CM</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>{{$dataReport[0]->nocm}}</span>
                            </td>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>No Pendaftaran</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>-</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>Nama Pasien</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>{{$dataReport[0]->namapasien_klien}}</span>
                            </td>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="25%">
                                <span>Tanggal Pendaftaran</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="20%">
                                <span>-</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>Tgl Lahir</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>{{$dataReport[0]->tgllahir}}</span>
                            </td>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="25%">
                                <span>Jenis Pasien</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="20%">
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>Alamat</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>-</span>
                            </td>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="25%">
                                <span>Penjamin</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="20%">
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>Ruangan</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>{{$dataReport[0]->namaruangan}}</span>
                            </td>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="25%">
                                <span>Dokter</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="20%">
                                <span>{{$dataReport[0]->namalengkap}}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="8"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="text-align: center" colspan="8">
                                <span style="font-size: 10pt;font-weight: 600;text-align: center" >NOTA / RESEP</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    No.Nama Barang
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Jenis\Satuan
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Asal Barang
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Jumlah
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                   Harga Satuan
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Total
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Plafon
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Dibayar Pasen
                                </span>
                            </td>
                        </tr>
                        @php
                            $totalharga = 0;
                            $totaltagihan = 0;
                            $totalDiskon = 0;
                        @endphp
                        @foreach ($dataReport as $key => $detail)
                        <tr>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    {{$loop->iteration}}.{{$detail->namaproduk}}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    {{$detail->detailjenisproduk}}\{{$detail->satuan}}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    -
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    {{$detail->qty}}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString($detail->hargasatuan) }}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString($detail->hargasatuan * $detail->qty) }}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString($detail->discount) }}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                   Rp.{{ \App\Traits\Valet::getMoneyFormatString(($detail->hargasatuan * $detail->qty) - $detail->discount) }}
                                </span>
                            </td>
                        </tr>
                        @php
                            $totaltagihan   = $totaltagihan +($detail->hargasatuan * $detail->qty) - $detail->discount;
                            $totalDiskon    = $detail->discount;
                            $totalharga     = $totalharga + ($detail->hargasatuan * $detail->qty);
                        @endphp
                        @endforeach
                        <tr>
                            <td colspan="8">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center;font-style: italic">
                                    Terbilang {{ \App\Traits\Valet::terbilang2($totaltagihan)}}
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="5"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="49%">
                                <table width="100%" cellspacing="0" cellpadding="0" class="table-bordered">
                                    <tr>
                                        <td style="text-align: center" colspan="4">
                                            <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                                Dikerjakan Oleh
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center">
                                            <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                                Entri Data
                                            </span>
                                        </td>
                                        <td style="text-align: center">
                                            <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                                Etiket Resep
                                            </span>
                                        </td>
                                        <td style="text-align: center">
                                            <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                                Mengemas
                                            </span>
                                        </td>
                                        <td style="text-align: center">
                                            <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                                Penyerahan
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="20"></td>
                                        <td height="20"></td>
                                        <td height="20"></td>
                                        <td height="20"></td>
                                    </tr>
                                </table>
                            </td>
                            <td width="2%">

                            </td>
                            <td width="49%">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="text-align: left" width="70%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                TOTAL
                                            </span>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                Rp.{{ number_format($totalharga, 2, '.', ',') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left" width="70%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                PLAFON
                                            </span>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                Rp.{{ number_format($totalDiskon, 2, '.', ',') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left" width="70%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                SUBSIDI
                                            </span>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                Rp.0
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left" width="70%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                SISA YANG HARUS DIBAYAR
                                            </span>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                Rp.{{ number_format($totaltagihan, 2, '.', ',') }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="10"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="1">
                        <tr>
                            <td width="60%">
                                <table style="border: 1px solid #444444" width="100%">
                                    <tr>
                                        <td colspan="3">
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                Keterangan:
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                Total Pemakaian Obat dan Alkes Samapai Dengan
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                {{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d-M-Y')}}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                {{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('H:i:s')}}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                               Rp.{{ \App\Traits\Valet::getMoneyFormatString($totaltagihan) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center;font-style: italic">
                                                Terbilang {{ \App\Traits\Valet::terbilang2($totaltagihan)}}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td with="2%"></td>
                            <td width="19%">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: left">
                                                Penerima
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="20"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">

                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                nama jelas
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="19%">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                Petugas
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="20"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">

                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                nama jelas
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr> --}}
    </table>
</body>

</html>

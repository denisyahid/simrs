@extends('template.layout')
{{-- @section('title',  $dataReport['judul'] ) --}}
@section('page-style')
    <style>
        table tr td{
            font-size: 12pt;
        }

        .header-font{
            font-size: 14pt;
        }
        .header-title{
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            text-decoration-thickness: 2pt;
        }
        .normal-font{
            font-size: 12pt;
        }

        .table-identitas{
            width: 90%;
            margin-left: 30px;
        }
    </style>
@endsection
@section('content')
<tr>
    <td>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" colspan="3">
                    {{-- <hr style="border:0.5px solid #000;margin-top:5px" /> --}}
                    <font class="header-font header-title" color="#000000">Hasil pengujian kesehatan</font>
                    {{-- <hr style="border:0.5px solid #000;margin-top:5px; width:100%" /> --}}
                </td>
            </tr>
            <tr>
                <td align="center">
                    <font class="header-font">
                        No: 812/{no_surat}/RHS/{{\Carbon\Carbon::parse(date("Y"))->isoFormat('Y')}}
                    </font>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <p class="normal-font">
                        Dokter Tim Penguji Kesehatan Kota Bandung yang ditetapkan berdasarkan Keputusan Menteri Kesehatan Republik Indonesia Nomor : HK.01/07/MENKES/4238/2021 tanggal 25 Maret 2021 dan anggota-anggotanya yang telah menjalankan tugas, dengan menerangkan bahwa:
                    </p>
                </td>
            </tr>
        </table>
        <table class="table-identitas">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>
                                Nama
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {NAMA}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                NIP
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {NIP/NIRP}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Jabatan
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {JABATAN}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Alamat
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {ALAMAT}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Permintaan
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {PERMINTAAN}
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table>
                        <tr>
                            <td>
                                Nama Kecil
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {NAMA}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                No. Karpeg
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {NIP/NIRP}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Gol. Ruang
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {JABATAN}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 200px">
                                Telah diperiksa dengan teliti atas surat nomor
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {ALAMAT}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Tanggal surat
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {PERMINTAAN}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    Dengan hasil pemeriksaan sebagai berikut :
                </td>
            </tr>
            <tr>
                <td>
                    <ol type="a">
                        <li><i>Memenuhi syarat untuk semua jenis pekerjaan pada umumnya</i></li>
                        <li><i>Memenuhi syarat untuk jenis pekerjaan tertenu</i></li>
                        <li><i>Dapat diterima dengan bersyarat untuk (a) atau (b) tersebut diatas</i></li>
                        <li><i>Untuk sementara belum memenuhi syarat kesehatan dan memerlukan pengobatan/perawatan dan uji kesehatan perlu diulang setelah pengobatan/perawatan atau ditolak untuk sementara</i></li>
                        <li><i>Tidak memenuhi syarat untuk menjalankan tugas sebagai Pegawai</i></li>
                    </ol>
                </td>
            </tr>
        </table>
        <table style="margin-top: 40px">
            <tr>
                <td style="width: 55%">
                </td>
                <td>
                    <table>
                        <tr>
                            <td align="center">{{$profile->namakota}}, {{\Carbon\Carbon::parse(date("Y"))->isoFormat('DD MMMM Y')}}</td>
                        </tr>
                        <tr>
                            <td align="center">Ketua Tim Penguji Kesehatan</td>
                        </tr>
                        <tr>
                            <td style="height: 40px"></td>
                        </tr>
                        <tr>
                            <td align="center"><b><u>dr. INDRAJI DWI MULYAWAN Sp.PD, M.Kes</u></b></td>
                        </tr>
                        <tr align="center">
                            <td>NIP. 1981101 2200902 1 003</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection

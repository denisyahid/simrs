@extends('template.layout-lab')
@section('title', 'Cetak Hasil Mikrobiolgi')
@section('page-style')
    <style>
        .table-parent {
            border: 2px solid #353434;
            border-collapse: collapse;
        }

        .none-top {
            border-top: none;
        }

        .table-bordered tr {}

        .table-bottom tr td {
            width: 33%;
        }

        h1 {
            font-size: 20px;
        }

        .h2 {
            font-size: 18px;
        }

        .h3 {
            font-size: 16px;
        }

        .h4 {
            font-size: 14px;
        }

        .h5 {
            font-size: 12px;
        }

        .h6 {
            font-size: 8px;
        }

        .bold {
            font-weight: 600
        }

        .italic {
            font-style: italic
        }

        * {
            font-family: initial
        }

        tr {
            justify-content: center;
            align-items: center;
            padding: 0;
            /* text-align: center; */
        }

        td {
            justify-content: center;
            align-items: center;
            padding: 0;
            font-size: 12pt;
            /* text-align: center; */
        }

        .left {
            justify-content: left !important;
            align-items: left !important;
            text-align: left !important;
        }

        span {
            font-size: 10px;
        }

        .border {
            border: 1px solid #353434;
        }

        .border tr {
            vertical-align: middle;
        }

        .border tr td {
            justify-content: left;
            text-align: left;
            text-align: left;
            padding: 5px 10px;
        }

        .border-a tbody tr {
            border-top: 1px solid #353434;
        }

        .border-b {
            border-bottom: 1px solid #353434;
        }
        .border{
            border: 1px solid black;
        }
        .fs{
            font-size: 10pt
        }
    </style>
@endsection

@section('content')
    <tr>
        <td style="padding-top:2px;">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="infopasien">
                <tbody>
                    <tr>
                        <td class="border" colspan="2" align="center" width="50%"><b>HASIL PEMERIKSAAN COVID-19</b></td>
                    </tr>
                    <tr>
                        <td class="border" align="center" width="50%"><b>Informasi Pasien</b></td>
                        <td class="border" align="center" width="50%"><b>Informasi Spesimen</b></td>
                    </tr>
                    <tr>
                        <td class="border" align="center" width="50%">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="fs">Nama</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->namapasien}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">Tanggal Lahir</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->tgllahir}}/{{$data->umur}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">Jenis Kelamin</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->jeniskelamin}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">No. RM</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->nocm}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">No. Identitas</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->noidentitas}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">No. Telp/HP</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->nohp}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">Alamat</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->alamatlengkap}}</td>
                                </tr>
                            </table>
                        </td>
                        <td class="border" align="center" width="50%">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="fs">Jenis Spesimen</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->jenisspesimen}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">Kode Spesimen</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->kodespesimen}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">Spesimen Ke-</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->spesimenke}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">Asal Spesimen</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->asalspesimen}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">Tgl & Jam Pengambilan</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->tglterimaspesimen}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">Tgl Diproses</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->tgldikerjakanspesimen}}</td>
                                </tr>
                                <tr>
                                    <td class="fs">Tgl Pelaporan</td>
                                    <td class="fs">:</td>
                                    <td class="fs">{{$data->tglkeluarhasil}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="border fs" colspan="2" width="50%"><br>Jenis Pemeriksaan&emsp;: {{$data->namaproduk}} <br> Hasil&emsp;&emsp;&emsp;&emsp;&emsp;: {{$data->hasilspesimen}} SARS-CoV2<br><br></td>
                    </tr>
                    <tr>
                        <td class="border fs" colspan="2" width="50%"><br>Komentar:<br><br>
                        1. SARS-CoV2 adalah virus penyebab COVID-19<br>
                        2. Hasil AG-RDT negatif berarti tidak terdeteksinya komponen protein virus SARS-CoV2 di atas amabang batas dari kit deteksi<br>
                        3. Hasil negatif belum dapat menyingkirkan penyakit COVID-19 yang disebabkan oleh virus tersebut, sehingga jika klinis masih mengarah COVID-19 dapat melakukan tes PCR<br>
                        4. Hubungi layanan kesehatan terdekat untuk informasi lebih lanjut dan patuhi protokol kesehatan<br><br></td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top: 20px">
            <table width="100%">
                <tr>
                    <td rowspan="5">
                        @if($pdf == true)
                        <img src="data:image/jpeg;base64,{{ $qrcode }}" width="120px" border="0">
                        @endif
                    </td>
                    <td width="50%"><b>Garut, {{\Carbon\Carbon::parse($data->tglkeluarhasil)->isoFormat('DD MMMM Y')}}<b></td>
                </tr>
                <tr>
                    <td width="50%"><b>Kepala Laboratorium Mikrobiologi Klinik</b></td>
                </tr>
                <tr>
                    <td height="80px"></td>
                </tr>
                <tr>
                    <td width="50%"><u>dr I Wy Agus Gede Manik S, M.Ked.Klin, Sp.MK</u></td>
                </tr>
                <tr>
                    <td width="50%">NIP. 198308142009021004</td>
                </tr>
            </table>
        </td>
    </tr>
@endsection

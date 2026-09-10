@extends('template.layout')
@section('title', 'Cetak Hasil Antigen')
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
            text-align: center;
        }

        td {
            justify-content: center;
            align-items: center;
            text-align: center;
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
            border-radius: 20px;
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
    </style>
@endsection

@section('content')
    <tr>
        <td style="padding-top:2px;">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="infopasien">
                <tbody>
                    <tr>
                        <th colspan="3">
                            <span class="h3 bold">HASIL PEMERIKSAAN LABORATORIUM</span>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <span class="italic">Laboratory Test Result</span>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <span class="h3 bold">TES AMPLIFIKASI ASAM NUKLEAT COVID-19</span>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <span class="italic">Nucleic Acid Amplification Test (NAAT) for COVID-19</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <span class="bold">NO. 14719/LMK/XII/2023</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="border">
                                <tr>
                                    <td width="24%">
                                        <span>Nama Pasien</span><br>
                                        <span class="italic h6">Patient Name</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span>Nama Pasien</span>
                                    </td>
                                    <td width="24%">
                                        <span>Jenis Kelamin</span><br>
                                        <span class="italic h6">Sex</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">-</td>
                                </tr>
                                <tr>
                                    <td width="24%">
                                        <span>Tanggal Lahir</span><br>
                                        <span class="italic h6">Date of Birth</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span>-</span>
                                    </td>
                                    <td width="24%">
                                        <span>Pengirirm</span><br>
                                        <span class="italic h6">Reffering Doctor/Unit</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">-</td>
                                </tr>
                                <tr>
                                    <td width="24%">
                                        <span>No. KTP/NIK</span><br>
                                        <span class="italic h6">Resident ID Number</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span>-</span>
                                    </td>
                                    <td width="24%">
                                        <span>Jenis Sampel</span><br>
                                        <span class="italic h6">sample Type</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">-</td>
                                </tr>
                                <tr>
                                    <td width="24%">
                                        <span>No. Passport</span><br>
                                        <span class="italic h6">Passport .NO</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span>-</span>
                                    </td>
                                    <td width="24%">
                                        <span>Tgl Terima Sample</span><br>
                                        <span class="italic h6">Sample Receiving Date</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">-</td>
                                </tr>
                                <tr>
                                    <td width="24%">
                                        <span>Alamat</span><br>
                                        <span class="italic h6">Address</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span>-</span>
                                    </td>
                                    <td width="24%">
                                        <span>Tgl Selesai Hasil</span><br>
                                        <span class="italic h6">Result/Print Out Date</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">-</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" height="20px"></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%"class="border-a">
                                <tbody>
                                    <tr>
                                        <td width="25%">
                                            <span>Kode Sampel</span><br>
                                            <span class="h6 italic">Sample Code</span>
                                        </td>
                                        <td width="25%">
                                            <span>Jenis Pemeriksaan</span>
                                            <span class="h6 italic">Type of Examination</span>
                                        </td>
                                        <td width="25%">
                                            <span class="h6">Hasil</span>
                                            <span class="h6 italic">Result</span>
                                        </td>
                                        <td width="25%">
                                            <span class="h6">Keterangan</span>
                                            <span class="h6 italic">Other Infromation/Explanation</span>
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td width="25%">
                                            <span>15250/Ag/XII/23</span>
                                        </td>
                                        <td width="25%">
                                            <span>Deteksi Antigen SARS CoV-2</span>
                                            <span class="h6 italic">Detection of SARS CoV-2 Antigen</span>
                                        </td>
                                        <td width="25%">
                                            <span>Negatif</span>
                                            <span class="h6 italic">Negative</span>
                                        </td>
                                        <td width="25%">
                                            <span>Rapid Chromatographic</span>
                                            <span class="h6 italic">Immunoassay</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td colspan="3" width="100%" class="left">
                                        <span class="h6 bold">*Deteksi RNA SARS CoV-2 / Detection of SARS CoV-2 RNA</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" height="20px"></td>
                    </tr>
                    <tr>
                        <td width="8%" class="left">
                            <span class="h6">Diperiksa oleh / Checked by</span>
                        </td>
                        <td width="2%">
                            <span class="h5">:</span>
                        </td>
                        <td width="90%" class="left">
                            <span class="h6">FARIS ‘ALAUDDIN, AMd.A.K</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="8%" class="left">
                            <span class="h6">Diverifikasi oleh / Verified by</span>
                        </td>
                        <td width="2%">
                            <span class="h5">:</span>
                        </td>
                        <td width="90%" class="left">
                            <span class="h6">FARIS ‘ALAUDDIN, AMd.A.K</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="8%" class="left">
                            <span class="h6">Disetujuhi oleh / Approval by</span>
                        </td>
                        <td width="2%">
                            <span class="h5">:</span>
                        </td>
                        <td width="90%" class="left">
                            <span class="h6">FARIS ‘ALAUDDIN, AMd.A.K</span>
                        </td>
                    </tr>
                    <tr>
                        <td height="80px">
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <br>
                            <img src="data:image/jpeg;base64,{{ $qrcode }}" width="120px" border="0">
                        </td>
                        <td width="50%" colspan="2">
                            <span class="h5 bold">Kepala Unit Pelayanan Instalasi</span><br>
                            <span class="h5 bold">Mikrobiologi Klinik Dan Patologi Anatomi</span><br>
                            <span class="italic">Head of Clinical Microbiology And Anantomic Pathology
                                Departement</span><br>
                             <span class="h5 bold">{{$profile->namalengkap}} - {{$profile->namakota}}</span><br>
                            <span class="italic">{{$profile->namalengkap}} Regional Hospital -  {{$profile->namakota}}</span><br>
                            <img style="margin: 10px" src="data:image/jpeg;base64,{{ $qrcode }}" width="30px"
                                border="0"><br>
                            <span class="h5 bold">dr. M. Erwin Indrakusuma, Sp.MK</span><br>
                            <span style="width: 100%"></span>
                            <span class="h5 bold">NIP. 19781214 200701 1 007</span><br>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td height="100px"></td>
    </tr>
    <tr style="border-bottom: 2px solid #000; width: 100%">
        <td>
        </td>
    </tr>
    <tr>
        <td class="left">
            <span class="bold h5">Untuk informasi Lebih lanjut dapat menghubungi Hotline Laboratorium Mikrobiologi :
                +6282111034003</span>
        </td>
    </tr>
    <tr>
        <td class="left">
            <span class="italic h6">Untuk informasi Lebih lanjut dapat menghubungi Hotline Laboratorium Mikrobiologi :
                +6282111034003</span>
        </td>
    </tr>
@endsection

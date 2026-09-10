<!DOCTYPE html>
<html>

<head>
    <title>TESTING</title>
    <style>
        body,
        table,
        td {
            font-family: 'Open Sans', sans-serif !important;
            border-collapse: collapse;
        }

        th {
            text-align: center !important;
            font-weight: bold;
            border: 1px solid black;
        }

        .bold {
            font-weight: bold !important
        }

        .font {
            font-size: 10px !important;
        }

        table {
            width: 100% !important
        }

        .border {
            border: 1px solid black !important;
        }

        .top {
            vertical-align: top !important;
        }

        .padding {
            padding: 3px;
        }

        .checked {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td colspan="6" class="border">
                <table style="border-collapse: collapse">
                    <tr>
                        <td style="text-align: center;padding: 10px;width: 15%">
                            <img src="{{ 'img/provinsi-rs.svg' }}" width="80px" height="80px"
                                style="display: block;">
                        </td>
                        <td style="text-align: center;width: 70%">
                            <span>
                                <b>PEMERINTAH PROVINSI BALI</b><br>
                                <b>RUMAH SAKIT UMUM DAERAH BALI MANDARA</b><br>
                                Jalan ByPass Ngurah Rai No.548 Sanur,Garut-Bali<br>
                                No.Telp : (0361) 4490566, E-mail : rsud.balimandara@gmail.com
                            </span>
                        </td>
                        <td style="text-align: center;padding: 10px;width: 15%">
                            <img src="{{ 'img/logo-rs.png' }}" width="80px" height="80px" style="display: block;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="6" class="border padding bold" style="font-size: 12px">
                FORMULIR PERMINTAAN DARAH / BLOOD TRANSFUSION ORDER FORM
            </td>
        </tr>
        <tr>
            <td class="border font padding" style="width: 16.5%">
                ID BDRS<br>
                BORS ID
            </td>
            <td class="border font padding" style="width: 16.5%">
                &nbsp;
            </td>
            <td class="border font padding" style="width: 16.5%">
                Tanggal & Jam Diterima<br>
                <i>Received Date & Time</i>
            </td>
            <td class="border font padding" style="width: 16.5%">
                &nbsp;
            </td>
            <td class="border font padding" style="width: 16.5%">
                Petugas Penerima <br>
                <i>Received By</i>
            </td>
            <td class="border font padding" style="width: 16.5%">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td class="border font padding top">
                Cara Bayar<br>
                <i>Payment</i>
            </td>
            <td class="border font padding">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                <label>
                    BPJS<br>
                    <i>Offical Health Insurance</i>
                </label>
            </td>
            <td class="border font padding">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                <label>
                    JKBM<br>
                    <i>Balinese Health Insurance</i>
                </label>
            </td>
            <td class="border font padding">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                <label>
                    IKS<br>
                    <i>Join Cooperation Insurance</i>
                </label>
            </td>
            <td class="border font padding">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                <label>
                    Umum<br>
                    <i>Public</i>
                </label>
            </td>
            <td class="border font padding">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                <label>
                    Lainnya<br>
                    <i>Other</i>
                </label>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                No. CM/RM<br>
                <i>Medical Record Name</i>
            </td>
            <td class="border font padding" colspan="2">
                <u>20.30.21</u>
            </td>
            <td class="border font padding top" rowspan="7" colspan="3">
                <b>PERHATIAN / ATTENTION</b><br>
                - Harap diisi dengan jelas, jika tidak lengkap akan dikembalikan<br>
                <i>Please fill in thoroughly, if not completed we will return to completion</i><br>
                - Beri tanda centang pada kotak (<input type="checkbox" />) yang dimaksud<br>
                <i>Give check mark for your option</i><br>
                - Setiap permintaan darah harus disertai contoh darah EDTA 3 ml<br>
                <i>every blood transfusion request must be have 3 ml EDTA blood</i><br>
                - Nama dan identitas pasien pada formulir dan contoh darahnya harus sama<br>
                <i>Name and other patient identity in form and blood sample must match</i><br>
                - Sebelum transfusi, cocokan etiket pada kantong darah dengan labelnya dan disertakan dengan identitas
                pasien yang akan ditransfusi, bila ada ketidakkecocokan segera kembalikan ke Bank Darah RSUD Bali
                Mandara<br>
                <i>Before transfusion, please check if patient identity and the blood label is match, if unmatch please
                    return to blood bank RSUD Bali Mandara</i><br>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Nama Pasien<br>
                <i>Patient Name</i>
            </td>
            <td class="border font padding">
                <u>Alif</u>
            </td>
            <td class="border font padding">
                <label>
                    Kelamin : <u>Perempuan</u><br>
                    <i>Sex</i>
                </label>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Tanggal Lahir<br>
                <i>Date of Birth</i>
            </td>
            <td class="border font padding">
                <u>11 April 2098</u>
            </td>
            <td class="border font padding">
                Umur : <u>98 Tahun</u><br>
                <i>Age</i>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Alamat Pasien<br>
                <i>Patient Address</i>
            </td>
            <td class="border font padding" colspan="2">
                <u>Jl. Kuningan</u>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Rumah Sakit<br>
                <i>Hospital</i>
            </td>
            <td class="border font padding" colspan="2">
                <u>RSUD Bali Mandara</u>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Bagian<br>
                <i>Departement/Division</i>
            </td>
            <td class="border font padding">
                <u>Rawat Jalan</u>
            </td>
            <td class="border font padding">
                Kelas : <u>VIP</u><br>
                <i>Class : <u>VIP</u></i>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Ruangan<br>
                <i>Ward</i>
            </td>
            <td class="border font padding">
                <u>Bank Darah</u>
            </td>
            <td class="border font padding">
                Kelas : <u>VIP</u><br>
                <i>Class : <u>VIP</u></i>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Dokter yang Meminta<br>
                <i>Doctor Rquest</i>
            </td>
            <td class="border font padding" colspan="2">
                <u>dr. Nisa Nabila</u>
            </td>
            <td class="border font padding" colspan="3" style="text-align: center">
                HARAP DIBERIKAN DARAH<br>
                KINDLY PROVIDE BLOOD
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Tanggal Permintaan<br>
                <i>Date of Requesting</i>
            </td>
            <td class="border font padding" colspan="2">
                <u>25 November 2076</u>
            </td>
            <td class="border font padding" colspan="2">
                Golongan Darah : <b>A / B / 0 / AB / ?</b><br>
                <i>Blood Type : </i>
            </td>
            <td class="border font padding" style="text-align: center">
                RHESUS : + / -
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Diagnosa<br>
                <i>Indication of Transfusion</i>
            </td>
            <td class="border font padding" colspan="2">
                <u>Warna darah merah</u>
            </td>
            <td class="border font padding" colspan="2">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                Darah Lengkap (Segar, Simpan)<br>
                <i>Whole Blood (Fresh Blood, Store Blood)</i>
            </td>
            <td class="border font padding" style="text-align: right">
                Kantong<br>
                <i>Bag</i>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Alasan Transfusi<br>
                <i>Reason of Transfusion</i>
            </td>
            <td class="border font padding" colspan="2">
                <u>Warna darah merah</u>
            </td>
            <td class="border font padding" colspan="2">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                Sel Darah Merah Pekat<br>
                <i>Packed Red Cell</i>
            </td>
            <td class="border font padding" style="text-align: right">
                Kantong<br>
                <i>Bag</i>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Kadar Hemoglobin<br>
                <i>Hemoglobin Concentration</i>
            </td>
            <td class="border font padding" colspan="2">
                <u>100%</u>
            </td>
            <td class="border font padding" colspan="2">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                Sel Darah Merah Dicuci<br>
                <i>Washed Red Cell</i>
            </td>
            <td class="border font padding" style="text-align: right">
                Kantong<br>
                <i>Bag</i>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                Reaksi Transfusi<br>
                <i>Reason of Transfusion</i>
            </td>
            <td class="border font padding">
                <u>
                    Ya/Tidak<br>
                    <i>Yes/No</i>
                </u>
            </td>
            <td class="border font padding">
                Gejala-gejala<br>
                <i>Sign & symptom</i>
            </td>
            <td class="border font padding" colspan="2">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                <i>Buffycoat</i>
            </td>
            <td class="border font padding" style="text-align: right">
                Kantong<br>
                <i>Bag</i>
            </td>
        </tr>
        <tr>
            <td class="border font padding top" rowspan="2">
                Khusus Wanita<br>
                <i>Female Patient Only</i>
            </td>
            <td class="border font padding" colspan="2">
                1. Jumlah kehamilan sebelumnya<br>
                <i>Preview of Pregnant</i>
            </td>
            <td class="border font padding" colspan="2">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                Trombosit Konsentrat<br>
                <i>Trombocyte Concentrate</i>
            </td>
            <td class="border font padding" style="text-align: right">
                Kantong<br>
                <i>Bag</i>
            </td>
        </tr>
        <tr>
            <td class="border font padding">
                2. Pernah Abortus?<br>
                <i>Had an abortion</i>
            </td>
            <td class="border font padding">
                Ya/Tidak<br>
                <i>Yes/No</i>
            </td>
            <td class="border font padding" colspan="2">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                Plasma Kaya Trombosit<br>
                <i>Trombocyte Concentrate</i>
            </td>
            <td class="border font padding" style="text-align: right">
                Kantong<br>
                <i>Bag</i>
            </td>
        </tr>
        <tr>
            <td class="border font padding top" rowspan="3">
                Jenis Permintaan Darah<br>
                <i>Bllod Order Priority</i>
            </td>
            <td class="border font padding" colspan="2" rowspan="3">
                <table>
                    <tr>
                        <td style="width: 50%">
                            <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                            Biasa<br>
                            <i>Reguler</i>
                        </td>
                        <td style="width: 50%">
                            <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                            Siap Pakai<br>
                            <i>Prepared when use</i>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                            Cadangan<br>
                            <i>For Reserve</i>
                        </td>
                        <td style="width: 50%">
                            <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                            Cito<br>
                            <i>Emergency</i>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="border font padding top" rowspan="3">
                <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                Plasma<br>
                <i>Plasma</i>
            </td>
            <td class="border font padding top">
                Plasma Cair / Liquid Plasma
            </td>
            <td class="border font padding" style="text-align: right">
                Kantong<br>
                <i>Bag</i>
            </td>
        </tr>
        <tr>
            <td class="border font padding top">
                Plasma Segar / Fresh Liquid
            </td>
            <td class="border font padding" style="text-align: right">
                Kantong<br>
                <i>Bag</i>
            </td>
        </tr>
        <tr>
            <td class="border font padding top">
                Plasma Segar Beku / Fresh Frozen Plasma
            </td>
            <td class="border font padding" style="text-align: right">
                Kantong<br>
                <i>Bag</i>
            </td>
        </tr>
        <tr>
            <td class="border font padding top" colspan="2">
            </td>
            <td class="border font padding top" colspan="2" style="text-align: center">
                Petugas yang mengambil Contoh Darah<br>
                <i>Nurse Taking The Blood Sample</i><br><br><br><br><br>
                Nama & Tanda tangan<br>
                <i>Name and sign</i>
            </td>
            <td class="border font padding top" colspan="2" style="text-align: center">
                Dokter yang Meminta<br>
                <i>Doctor Requesting</i><br><br><br><br><br>
                Nama, Tandan tangan, & Stampel RS<br>
                <i>Name, Sign, and Hospital Stamp</i>
            </td>
        </tr>
        <tr>
            <td class="border font padding top" colspan="6"
                style="font-size: 12px;font-weight: bold;text-align: center">
                DIISI OLEH PETUGAS BANK DARAH RSUD BALI MANDARA (FILLED BY BLOOD BANK STAFF RSUD BALI MANDARA)
            </td>
        </tr>
        <tr>
            <td class="border font padding top" colspan="2">
                Nama Pasien : <u>Alif</u><br>
                <i>Patient Name : </i>
            </td>
            <td class="border font padding top" colspan="2" style="text-align: center">
                Golongan Darah<br>
                <i>Blood Type</i>
            </td>
            <td class="border font padding top" colspan="2" rowspan="4">
                Catatan : <br>
                <i>Note : </i>
            </td>
        </tr>
        <tr>
            <td class="border font padding top" colspan="2">
                Tanggal Lahir : <br>
                <i>Date of Birth : </i>
            </td>
            <td class="border font padding top" style="text-align: center;vertical-align: middle">
                ABO
            </td>
            <td class="border font padding top" style="text-align: center;vertical-align: middle">
                Rhesus
            </td>
        </tr>
        <tr>
            <td class="border font padding top" colspan="2">
                No. Rekam Medis : <br>
                <i>Medical Record Number : </i>
            </td>
            <td class="border font padding top" rowspan="2">
                &nbsp;
            </td>
            <td class="border font padding top" rowspan="2">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td class="border font padding top" colspan="2">
                ID BDRS /
                <i>Blood Bank ID</i>
            </td>
        </tr>
        <tr>
            <td colspan="6" class="border font top">
                <table>
                    <tr>
                        <th rowspan="2">
                            No<br>
                            <i>No</i>
                        </th>
                        <th rowspan="2">
                            No. Reg<br>
                            <i>Reg Number</i>
                        </th>
                        <th rowspan="2">
                            Jenis Darah<br>
                            <i>Blood Component</i>
                        </th>
                        <th colspan="2">
                            Golongan Darah<br>
                            <i>Blood Type</i>
                        </th>
                        <th colspan="3">
                            Hasil Uji Cocok Serasi<br>
                            <i>Crossmatch Result</i>
                        </th>
                        <th colspan="3">
                            Analis Pemeriksa<br>
                            <i>Blood Bank Examiner</i>
                        </th>
                        <th colspan="2">
                            Keluar<br>
                            <i>Out</i>
                        </th>
                        <th colspan="2">
                            Petugas BDRS<br>
                            <i>Blood Bank Staff</i>
                        </th>
                        <th colspan="2">
                            Petugas Yang Mengambil
                        </th>
                    </tr>
                    <tr>
                        <th>ABO</th>
                        <th>Rhesus</th>
                        <th>Major</th>
                        <th>Minor</th>
                        <th>Auto Control</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Nama</th>
                        <th>Paraf</th>
                        <th>Nama</th>
                        <th>Paraf</th>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>

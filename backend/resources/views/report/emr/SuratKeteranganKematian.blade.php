@extends('template.layout-emr2')
@section('title')
    @if ($cekWargaNegaraWNA)
        Print Death Certificate
    @else
        Cetak Surat Keterangan Kematian
    @endif
@endsection
@section('page-style')
    <style>
        body,
        table,
        td {
            font-family: 'Open Sans', sans-serif !important;
        }

        .bold {
            font-weight: bold;
        }

        .checkbox {
            vertical-align: top;
            display: inline-block
        }

        .center {
            vertical-align: middle;
            text-align: center
        }

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }

        .border {
            border: 1px solid black;
        }

        .font {
            font-size: 8pt !important;
        }

        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: auto;
            page-break-after: avoid
        }

        .pd {
            padding: 3px;
        }

        .pd1 {
            padding: 1.5px;
        }
    </style>
@endsection

@php
    function convertToRegularDate($isoDateString)
    {
        $date = new DateTime($isoDateString);
        return $date->format('d-m-Y');
    }

    function convertToMakassarDate($isoDateString)
    {
        $date = new DateTime($isoDateString, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
        return $date->format('d-m-Y H:i:s');
    }

    function convertToRegularTime($isoDateString)
    {
        $date = new DateTime($isoDateString);
        $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
        return $date->format('H:i');
    }
@endphp

@section('content')
    @if ($cekWargaNegaraWNA)
        <tr>
            <td align="center">
                <div style="font-size: 15pt; font-weight: bold;padding:3px">
                    <u>DEATH CERTIFICATE</u><br>
                    <span style="font-size:13pt">NUMBER: {{ isset($data['nomor']) ? $data['nomor'] : '-' }}</span>
                </div>
            </td>
        </tr>
        <tr>
            <td class="font">
                <table>
                    <tr>
                        <td style="width: 33%" class="pd center">
                            <span>Hospital Code: {{ isset($data['kodeRS']) ? $data['kodeRS'] : '-' }}</span>
                        </td>
                        <td style="width: 33%" class="pd center">
                            <span>Registration Number:
                                {{ isset($data['noUrutPencatatan']) ? $data['noUrutPencatatan'] : '-' }}</span>
                        </td>
                        <td style="width: 33%" class="pd center">
                            <span>Medical Record Number: {{ isset($data['norm']) ? $data['norm'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="font pd" style="font-weight: bold;padding-left:5px">I. Deceased's Identity</td>
        </tr>
        <tr>
            <td class="font pd">
                <table style="margin-left: 10px">
                    <tr>
                        <td class="pd1">
                            1. Name: {{ isset($data['namaLengkap']) ? $data['namaLengkap'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            2. ID Number/Passport Number: {{ isset($data['NIK']) ? $data['NIK'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            3. Family Card Number: {{ isset($data['NKK']) ? $data['NKK'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            4. Gender: {{ isset($data['jenisKlm']) ? App\Traits\Valet::english($data['jenisKlm']) : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            5. Place/Date of Birth: {{ isset($data['tmptLhr']) ? $data['tmptLhr'] : '-' }} /
                            {{ isset($data['tglLahir']) ? convertToRegularDate($data['tglLahir']) : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            6. Religion: {{ isset($data['agama']) ? App\Traits\Valet::english($data['agama']) : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            7. Address: {{ isset($data['alamatTempTingl']) ? $data['alamatTempTingl'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            8. Nationality: {{ isset($data['kewarnegaraan']) ? $data['kewarnegaraan'] : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="font pd" style="font-weight: bold;padding-left:5px">II. Special Death Information</td>
        </tr>
        <tr>
            <td class="font pd">
                <table style="margin-left: 10px">
                    <tr>
                        <td class="pd1">
                            1. Place of Death:
                            {{ isset($data['tempatMeninggal']) ? $data['tempatMeninggal'] . ', ' : '-' }}
                            @if (isset($data['tempatMeninggal']))
                                @if ($data['tempatMeninggal'] == 'Ruang Rawat')
                                    Length of Stay:
                                    {{ isset($data['lamaDirawatHari_RR']) ? $data['lamaDirawatHari_RR'] : '-' }}
                                    Days,
                                    Hours: {{ isset($data['lamaDirawatJam_RR']) ? $data['lamaDirawatJam_RR'] : '-' }} Hours
                                @elseif ($data['tempatMeninggal'] == 'Instalasi Gawat Darurat')
                                    Length of Stay:
                                    {{ isset($data['lamaDirawatHari_IGD']) ? $data['lamaDirawatHari_IGD'] : '-' }}
                                    Days,
                                    Hours: {{ isset($data['lamaDirawatJam_IGD']) ? $data['lamaDirawatJam_IGD'] : '-' }}
                                    Hours
                                @elseif ($data['tempatMeninggal'] == 'Ruang Bersalin')
                                    {{ isset($data['bayiLahirMeninggal']) ? $data['bayiLahirMeninggal'] : '' }}
                                    {{ isset($data['ibuMelahirkanMeninggal']) ? $data['ibuMelahirkanMeninggal'] : '' }}
                                @elseif ($data['tempatMeninggal'] == 'Diterima di RS dalam keadaan meninggal')
                                    Arrived at the hospital dead
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            2. Time of Death:
                            {{ isset($data['waktuMeninggal']) ? convertToMakassarDate($data['waktuMeninggal']) : '-' }}
                            WIB
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            3. Estimated Time of Death:
                            {{ isset($data['perkiraanWaktuMeninggal']) ? convertToMakassarDate($data['perkiraanWaktuMeninggal']) : '-' }}
                            WIB
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            4. Examination Time:
                            {{ isset($data['waktuPemeriksaan']) ? convertToMakassarDate($data['waktuPemeriksaan']) : '-' }}
                            WIB
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="font pd" style="font-weight: bold;padding-left:5px">III. Cause of Death</td>
        </tr>
        <tr>
            <td class="font pd">
                <table style="margin-left: 10px">
                    <tr>
                        <td class="pd1" colspan="4">
                            Basis for Diagnosis (May include more than one)
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1" style="width: 25%">
                            <input type="checkbox" {{ isset($data['catatanMedis']) ? 'checked' : '' }} class="checkbox" />
                            <span>Medical Notes</span>
                        </td>
                        <td class="pd1" style="width: 25%">
                            <input type="checkbox" {{ isset($data['pemeriksaanLuarJenasah']) ? 'checked' : '' }}
                                class="checkbox" />
                            <span>External Body Examination</span>
                        </td>
                        <td class="pd1" style="width: 25%">
                            <input type="checkbox" {{ isset($data['otopsiForesik']) ? 'checked' : '' }} class="checkbox" />
                            <span>Forensic Autopsy</span>
                        </td>
                        <td class="pd1" style="width: 25%">
                            <input type="checkbox" {{ isset($data['otopsiKlinik']) ? 'checked' : '' }} class="checkbox" />
                            <span>Clinical Autopsy</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="font pd" style="font-weight: bold;padding-left:5px">IV. Cause of Death Category</td>
        </tr>
        <tr>
            <td class="font pd">
                <table style="margin-left: 10px">
                    <tr>
                        <td class="pd1" style="width: 33%">
                            <input type="checkbox" {{ isset($data['alamiah']) ? 'checked' : '' }} class="checkbox" />
                            <span>Natural</span>
                        </td>
                        <td class="pd1" style="width: 33%">
                            <input type="checkbox" {{ isset($data['tidakAlamiah']) ? 'checked' : '' }} class="checkbox" />
                            <span>Non-Natural</span>
                        </td>
                        <td class="pd1" style="width: 33%">
                            <input type="checkbox" {{ isset($data['tidakDD_KPK']) ? 'checked' : '' }} class="checkbox" />
                            <span>Indeterminate</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="font pd">
                <table>
                    <tr>
                        <td style="width: 70%"></td>
                        <td style="width: 30%;text-align:center">
                            <br><br>
                            Garut, {{ isset($data['tanggal']) ? convertToMakassarDate($data['tanggal']) : '-' }}<br>
                            @if (isset($data['dokterPemeriksa']['label']))
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $data['dokterPemeriksa']['label'] }}"
                                    style="padding:5px"><br>
                            @endif
                            {{ isset($data['dokterPemeriksa']) ? $data['dokterPemeriksa']['label'] : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @else
        <tr>
            <td align="center">
                <div style="font-size: 15pt; font-weight: bold;padding:3px">
                    <u>SURAT KETERANGAN KEMATIAN</u><br>
                    <span style="font-size:13pt">NOMOR : {{ isset($data['nomor']) ? $data['nomor'] : '-' }}</span>
                </div>
            </td>
        </tr>
        <tr>
            <td class="font">
                <table>
                    <tr>
                        <td style="width: 33%" class="pd center">
                            <span>Kode RS : {{ isset($data['kodeRS']) ? $data['kodeRS'] : '-' }}</span>
                        </td>
                        <td style="width: 33%" class="pd center">
                            <span>Nomor Urut Pencatatan :
                                {{ isset($data['noUrutPencatatan']) ? $data['noUrutPencatatan'] : '-' }}</span>
                        </td>
                        <td style="width: 33%" class="pd center">
                            <span>Nomor Rekam Medis : {{ isset($data['norm']) ? $data['norm'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="font pd" style="font-weight: bold;padding-left:5px">I. Identitas Jenazah</td>
        </tr>
        <tr>
            <td class="font pd">
                <table style="margin-left: 10px">
                    <tr>
                        <td class="pd1">
                            1. Nama : {{ isset($data['namaLengkap']) ? $data['namaLengkap'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            2. NIK/No Paspor : {{ isset($data['NIK']) ? $data['NIK'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            3. Nomor Kartu Keluarga : {{ isset($data['NKK']) ? $data['NKK'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            4. Jenis Kelamin : {{ isset($data['jenisKlm']) ? $data['jenisKlm'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            5. Tempat/Tgl Lahir : {{ isset($data['tmptLhr']) ? $data['tmptLhr'] : '-' }} /
                            {{ isset($data['tglLahir']) ? convertToRegularDate($data['tglLahir']) : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            6. Agama : {{ isset($data['agama']) ? $data['agama'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            7. Alamat : {{ isset($data['alamatTempTingl']) ? $data['alamatTempTingl'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            8. Kewarganegaraan : {{ isset($data['kewarnegaraan']) ? $data['kewarnegaraan'] : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="font pd" style="font-weight: bold;padding-left:5px">II. Keterangan Khusus Kematian</td>
        </tr>
        <tr>
            <td class="font pd">
                <table style="margin-left: 10px">
                    <tr>
                        <td class="pd1">
                            1. Tempat Meninggal :
                            {{ isset($data['tempatMeninggal']) ? $data['tempatMeninggal'] . ', ' : '-' }}
                            @if (isset($data['tempatMeninggal']))
                                @if ($data['tempatMeninggal'] == 'Ruang Rawat')
                                    Lama Dirawat :
                                    {{ isset($data['lamaDirawatHari_RR']) ? $data['lamaDirawatHari_RR'] : '-' }}
                                    Hari,
                                    Jam : {{ isset($data['lamaDirawatJam_RR']) ? $data['lamaDirawatJam_RR'] : '-' }} Jam
                                @elseif ($data['tempatMeninggal'] == 'Instalasi Gawat Darurat')
                                    Lama Dirawat :
                                    {{ isset($data['lamaDirawatHari_IGD']) ? $data['lamaDirawatHari_IGD'] : '-' }}
                                    Hari,
                                    Jam : {{ isset($data['lamaDirawatJam_IGD']) ? $data['lamaDirawatJam_IGD'] : '-' }} Jam
                                @elseif ($data['tempatMeninggal'] == 'Ruang Bersalin')
                                    {{ isset($data['bayiLahirMeninggal']) ? $data['bayiLahirMeninggal'] : '' }}
                                    {{ isset($data['ibuMelahirkanMeninggal']) ? $data['ibuMelahirkanMeninggal'] : '' }}
                                @elseif ($data['tempatMeninggal'] == 'Diterima di RS dalam keadaan meninggal')
                                    Diterima di RS dalam keadaan meninggal
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            2. Waktu Meninggal :
                            {{ isset($data['waktuMeninggal']) ? convertToMakassarDate($data['waktuMeninggal']) : '-' }}
                            WIB
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            3. Perkiraan Waktu Meninggal :
                            {{ isset($data['perkiraanWaktuMeninggal']) ? convertToMakassarDate($data['perkiraanWaktuMeninggal']) : '-' }}
                            WIB
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1">
                            4. Waktu Pemeriksaan :
                            {{ isset($data['waktuPemeriksaan']) ? convertToMakassarDate($data['waktuPemeriksaan']) : '-' }}
                            WIB
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="font pd" style="font-weight: bold;padding-left:5px">III. Penyebab Kematian</td>
        </tr>
        <tr>
            <td class="font pd">
                <table style="margin-left: 10px">
                    <tr>
                        <td class="pd1" colspan="4">
                            Dasar Diagnosis (Dapat lebih dari satu)
                        </td>
                    </tr>
                    <tr>
                        <td class="pd1" style="width: 25%">
                            <input type="checkbox" {{ isset($data['catatanMedis']) ? 'checked' : '' }}
                                class="checkbox" />
                            <span>Catatan Medis</span>
                        </td>
                        <td class="pd1" style="width: 25%">
                            <input type="checkbox" {{ isset($data['pemeriksaanLuarJenasah']) ? 'checked' : '' }}
                                class="checkbox" />
                            <span>Pemeriksaan Luar Jenasah</span>
                        </td>
                        <td class="pd1" style="width: 25%">
                            <input type="checkbox" {{ isset($data['otopsiForesik']) ? 'checked' : '' }}
                                class="checkbox" />
                            <span>Otopsi Foresik</span>
                        </td>
                        <td class="pd1" style="width: 25%">
                            <input type="checkbox" {{ isset($data['otopsiKlinik']) ? 'checked' : '' }}
                                class="checkbox" />
                            <span>Otopsi Klinik</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="font pd" style="font-weight: bold;padding-left:5px">IV. Kelompok Penyebab Kematian</td>
        </tr>
        <tr>
            <td class="font pd">
                <table style="margin-left: 10px">
                    <tr>
                        <td class="pd1" style="width: 33%">
                            <input type="checkbox" {{ isset($data['alamiah']) ? 'checked' : '' }} class="checkbox" />
                            <span>Alamiah</span>
                        </td>
                        <td class="pd1" style="width: 33%">
                            <input type="checkbox" {{ isset($data['tidakAlamiah']) ? 'checked' : '' }}
                                class="checkbox" />
                            <span>Tidak Alamiah</span>
                        </td>
                        <td class="pd1" style="width: 33%">
                            <input type="checkbox" {{ isset($data['tidakDD_KPK']) ? 'checked' : '' }} class="checkbox" />
                            <span>Tidak Dapat Ditentukan</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="font pd">
                <table>
                    <tr>
                        <td style="width: 70%"></td>
                        <td style="width: 30%;text-align:center">
                            <br><br>
                            Garut, {{ isset($data['tanggal']) ? convertToMakassarDate($data['tanggal']) : '-' }}<br>
                            @if (isset($data['dokterPemeriksa']['label']))
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $data['dokterPemeriksa']['label'] }}"
                                    style="padding:5px"><br>
                            @endif
                            {{ isset($data['dokterPemeriksa']) ? $data['dokterPemeriksa']['label'] : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif
@endsection

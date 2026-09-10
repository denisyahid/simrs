@extends('template.head-emr')
@section('title', 'formulir skrining igd')
@section('about', 'Formulir Skrining IGD')

@push('style')
    <style>
        .table {
            border: 1px !important;
            border-collapse: collapse !important;
        }

        .sub-table {
            width: 90%;
            margin-left: 2rem;
        }

        .table-totalPoin {
            width: 95%;
            margin-left: 15px;
            margin-top: 7px;
        }

        .space-keterangan {
            padding: 8px;
        }
        
        .label{
            position: relative;
            top: -3px;
        }

        .header{
            font-size: 10pt !important;
            padding: 5px;
        }
    </style>
@endpush

@section('content')

    <table style="border-collapse: collapse">
        <tr>
            <td>
                <span style="font-size: 10pt;" class="judulLabel">Anamnesa dan Riwayat (14 Hari SMRS)</span>
            </td>
        </tr>
        <tr height="27px">
            <td>
                <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">1. Demam</span>
            </td>
        </tr>
        <tr>
            <td>
                <table style="border-collapse: collapse" class="sub-table">
                    <tr>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['Demam']) && $data['Demam'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">a. Ya</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;">Poin = 1</span>
                        </td>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['Demam']) && $data['Demam'] == 0 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">b. Tidak</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;">Poin = 0</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr height="27px">
            <td>
                <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">2. Batuk/ Pilek / Nyeri
                    Tenggorokan</span>
            </td>
        </tr>
        <tr>
            <td>
                <table style="border-collapse: collapse " class="sub-table">
                    <tr>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['Batuk']) && $data['Batuk'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">a. Ya</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;">Poin = 1</span>
                        </td>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['Batuk']) && $data['Batuk'] == 0 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">b. Tidak</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;">Poin = 0</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr height="27px">
            <td>
                <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">3. Kontak erat/Kasus Konfirmasi</span>
            </td>
        </tr>
        <tr>
            <td>
                <table style="border-collapse: collapse" class="sub-table">
                    <tr>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['KontakErat']) && $data['KontakErat'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">a. Ya</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;">Poin = 1</span>
                        </td>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['KontakErat']) && $data['KontakErat'] == 0 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">b. Tidak</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;">Poin = 0</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr height="27px">
            <td>
                <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">4. Riwayat pemeriksaan Swab test
                    (PCR)</span>
            </td>
        </tr>
        <tr>
            <td>
                <table style="border-collapse: collapse" class="sub-table">
                    <tr>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['PernahPCR']) && $data['PernahPCR'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">a. Ya</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;">Poin = 1</span>
                        </td>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['PernahPCR']) && $data['PernahPCR'] == 0 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">b. Tidak</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;">Poin = 0</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table style="border-collapse: collapse" class="table-totalPoin">
                    <tr>
                        <td width="12%">
                            <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">Total Poin</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">:</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">{{isset($data['totalSkorAnamnesa']) ? $data['totalSkorAnamnesa'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table style="border-collapse: collapse">
        <tr>
            <td>
                <span style="font-size: 10pt;" class="judulLabel">Gejala Klinis</span>
            </td>
        </tr>
        <tr height="27px">
            <td>
                <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">1. Suhu 38°C</span>
            </td>
        </tr>
        <tr>
            <td>
                <table style="border-collapse: collapse" class="sub-table">
                    <tr>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['suhuTinggi']) && $data['suhuTinggi'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">a. Ya</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;">Poin = 1</span>
                        </td>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['suhuTinggi']) && $data['suhuTinggi'] == 0 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">b. Tidak</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;">Poin = 0</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr height="27px">
            <td>
                <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">2. Sesak Nafas</span>
            </td>
        </tr>
        <tr>
            <td>
                <table style="border-collapse: collapse" class="sub-table">
                    <tr>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['sesakNafas']) && $data['sesakNafas'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">a. Ya</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;">Poin = 1</span>
                        </td>
                        <td width="18%">
                            <input type="checkbox" {{isset($data['sesakNafas']) && $data['sesakNafas'] == 0 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" class="label">b. Tidak</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;">Poin = 0</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="border-collapse: collapse" class="table-totalPoin">
                    <tr>
                        <td width="12%">
                            <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">Total Poin</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">:</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;margin-left: 10px;" class="judulLabel">{{isset($data['totalSkorGejala']) ? $data['totalSkorGejala'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>

    <span style="font-size: 10pt;" class="judulLabel">Keterangan Skor</span>

    <table border="1px" style="border-collapse: collapse;margin-top: 6px;" width="100%">
        <thead>
            <tr>
                <th class="header">
                    <span>Pemeriksaan</span>
                </th>
                <th class="header">
                    <span>Kriteria</span>
                </th>
                <th class="header">
                    <span>Skor</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="5" class="space-keterangan">
                    <span>Anamnesa dan Riwayat (14 hari SMRS)</span>
                </td>
                <td class="space-keterangan">
                    <span>Demam</span>
                </td>
                <td class="space-keterangan">
                    <span>0 = Tidak Ada</span>
                </td>
            </tr>
            <tr>
                <td class="space-keterangan">
                    <span>Batuk/Pilek/Nyeri Tenggorokan</span>
                </td>
                <td class="space-keterangan">
                    <span>1 = Ada Salah Satu</span>
                </td class="space-keterangan">
            </tr>
            <tr>
                <td class="space-keterangan">
                    <span>Nyeri Otot</span>
                </td>
                <td class="space-keterangan">
                    <span>2 = Ada > 1</span>
                </td>
            </tr>
            <tr>
                <td class="space-keterangan">
                    <span>Kontak erat/Kasus konfirmasi</span>
                </td>
                <td class="space-keterangan">
                    <span>3 = Jika Kontak (+), Swab (+)</span>
                </td>
            </tr>
            <tr>
                <td class="space-keterangan">
                    <span>Riwayat Pemeriksaan Swab test (Pn Cr)</span>
                </td>
                <td class="space-keterangan">
                    <span></span>
                </td>
            </tr>

            <tr>
                <td rowspan="3" class="space-keterangan">
                    <span>Anamnesa dan Riwayat (14 hari SMRS)</span>
                </td>
                <td class="space-keterangan">
                    <span>Suhu 38°C</span>
                </td>
                <td class="space-keterangan">
                    <span>0 = Tidak Ada</span>
                </td>
            </tr>
            <tr>
                <td class="space-keterangan">
                    <span>Sesak Nafas</span>
                </td>
                <td class="space-keterangan">
                    <span>1 = Jika hipertermia</span>
                </td>
            </tr>
            <tr>
                <td class="space-keterangan"></td>
                <td class="space-keterangan">
                    <span>3 = Jika Sesak</span>
                </td>
            </tr>

        </tbody>

    </table>

    <br>
    <table border="1px" style="border-collapse: collapse" width="100%">
        <tbody>
            <tr>
                <td width="14%"; class="space-keterangan" rowspan="2" style="background: green">
                    <span style="font-weight: bold">SKOR = 2</span>
                </td>
                <td class="space-keterangan">
                    <span>1. Pasien/pengunjung boleh melanjutkan ke area pelayanan kesehatan di zona non COVID-19</span>
                </td>
            </tr>
            <tr>
                <td class="space-keterangan">
                    <span>2. Memakai masker, cuci tangan dengan sabun dan air mengalir serta penerapan jarak fisik >
                        1m</span>
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table border="1px" style="border-collapse: collapse" width="100%">
        <tbody>
            <tr>
                <td class="space-keterangan" rowspan="4" style="background: red">
                    <span style="font-weight: bold">SKOR = 1-3</span>
                </td>
                <td class="space-keterangan">
                    <span>1. Penunjung yang memiliki skor 1-3 diterapkan sistem penanganan pasien gejala COVID-19</span>
                </td>
            </tr>
            <tr>
                <td class="space-keterangan">
                    <span>2. Pasien diarahkan ke IGD khusus COVID-19 untuk evaluasi lebih lanjut</span>
                </td>
            </tr>
            <tr>
                <td class="space-keterangan">
                    <span>3. Pasien menunggu di ruang COVID-19</span>
                </td>
            </tr>
            <tr>
                <td class="space-keterangan">
                    <span>4. Memakai masker, cuci tangan dengan sabun dan air mengalir serta penerapan jarak fisik >
                        1m</span>
                </td>
            </tr>
        </tbody>
    </table>
@endsection

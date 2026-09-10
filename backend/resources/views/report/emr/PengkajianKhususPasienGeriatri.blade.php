@extends('template.head-emr')
@section('title', 'EMR - Pengkajian Khusus Pasien Geriatri')
@section('about', 'Pengkajian Khusus Pasien Geriatri')

@push('style')
    <style>
        .table {
            border-collapse: collapse;
        }

        .head {
            font-size: 14px;
            padding: 5px;
        }

        .number {
            text-align: center;
            font-weight: bold;
        }

        .field {
            padding: 7px;
        }

        .table,
        .row,
        .field,
        .head {
            border: 1.7px solid black;
        }

        .field-2 {
            padding: 0px;
            /* border: 1px solid black; */
        }

        .check {
            vertical-align: top;
        }

    </style>
@endpush

@section('content')

    <table class="table">
        <thead>
            <tr class="row">
                <th class="head" width="7%">No</th>
                <th class="head" width="30%">Parameter</th>
                <th class="head">Pengkajian</th>
                <th class="head" width="8%">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr class="row">
                <td class="field number">
                    <span>1</span>
                </td>
                <td class="field">
                    <span>Makan</span>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="field-2 check" width="1%">
                                <input type="checkbox"
                                    {{ isset($data['makan']) && $data['makan']['code'] == 'TM' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Tidak Mampu</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['makan']) && $data['makan']['code'] == 'MB' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Memerlukan bantuan seperti, mengoleskan
                                    mentega, atau memerlukan bentuk diet khusus</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['makan']) && $data['makan']['code'] == 'TB' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Mandiri / tanpa bantuan</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="number">
                                <span>0</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>5</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>10</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="row">
                <td class="field number">
                    <span>2</span>
                </td>
                <td class="field">
                    <span>Mandi</span>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="field-2 check" width="1%">
                                <input type="checkbox"
                                    {{ isset($data['mandi']) && $data['mandi']['code'] == 'TG' ? 'checked' : '' }} />
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Tergantung</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['mandi']) && $data['mandi']['code'] == 'MI' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Mandiri</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="number">
                                <span>0</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>5</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="row">
                <td class="field number">
                    <span>3</span>
                </td>
                <td class="field">
                    <span>Kerapihan / Penampilan</span>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="field-2 check" width="1%">
                                <input type="checkbox"
                                    {{ isset($data['kerapihan']) && $data['kerapihan']['code'] == 'MBP' ? 'checked' : '' }} />
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Memerlukan bantuan untuk menata penampilan
                                    diri</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['kerapihan']) && $data['kerapihan']['code'] == 'MI' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Mandiri (Mampu menyikat gigi, mengelap wajah,
                                    menata rambut, bercukur)</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="number">
                                <span>0</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>5</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="row">
                <td class="field number">
                    <span>4</span>
                </td>
                <td class="field">
                    <span>Berpakaian</span>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="field-2 check" width="1%">
                                <input type="checkbox"
                                    {{ isset($data['berpakaian']) && $data['berpakaian']['code'] == 'TM' ? 'checked' : '' }} />
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Tergantung / tidak mampu</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['berpakaian']) && $data['berpakaian']['code'] == 'MI' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Mandiri (mampu mengancingkan baju, menutup
                                    resleting)</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="number">
                                <span>0</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>5</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="row">
                <td class="field number">
                    <span>5</span>
                </td>
                <td class="field">
                    <span>Buang Air Besar</span>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="field-2 check" width="1%">
                                <input type="checkbox"
                                    {{ isset($data['BAB']) && $data['BAB']['code'] == 'IA' ? 'checked' : '' }} />
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Inkontinensia</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['BAB']) && $data['BAB']['code'] == 'KMK' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Kadang mengalami kesulitan</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['BAB']) && $data['BAB']['code'] == 'MI' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Mandiri</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="number">
                                <span>0</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>5</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>10</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="row">
                <td class="field number">
                    <span>6</span>
                </td>
                <td class="field">
                    <span>Buang Air Kecil</span>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="field-2 check" width="1%">
                                <input type="checkbox"
                                    {{ isset($data['BAK']) && $data['BAK']['code'] == 'IA' ? 'checked' : '' }} />
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Inkontinensia, harus dipasang kateter, tidak
                                    mampu mengontrol BAK secara mandiri</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['BAK']) && $data['BAK']['code'] == 'KMK' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Kadang mengalami kesulitan</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['BAK']) && $data['BAK']['code'] == 'MI' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Mandiri</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="number">
                                <span>0</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>5</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>10</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="row">
                <td class="field number">
                    <span>7</span>
                </td>
                <td class="field">
                    <span>Penggunaan kamar mandi / toilet</span>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="field-2 check" width="1%">
                                <input type="checkbox"
                                    {{ isset($data['toilet']) && $data['toilet']['code'] == 'TG' ? 'checked' : '' }} />
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Tergantung</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['toilet']) && $data['toilet']['code'] == 'PDP' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Perlu digantung tapi tidak tergantung
                                    penuh</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['toilet']) && $data['toilet']['code'] == 'MI' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Mandiri</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="number">
                                <span>0</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>5</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>10</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="row">
                <td class="field number">
                    <span>8</span>
                </td>
                <td class="field">
                    <span>Berpindah tempat (dari tempat tidur ke tempat duduk atau sebaliknya)</span>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="field-2 check" width="1%">
                                <input type="checkbox"
                                    {{ isset($data['berpindahTempat']) && $data['berpindahTempat']['code'] == 'TM' ? 'checked' : '' }} />
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Tidak mampu, mengalami gangguan
                                    keseimbangan</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['berpindahTempat']) && $data['berpindahTempat']['code'] == 'MB' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Memerlukan bantuan (perlu satu atau dua
                                    orang) untuk bisa duduk</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['berpindahTempat']) && $data['berpindahTempat']['code'] == 'MSB' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Memerlukan sedikit bantuan (hanya diarahkan
                                    secara verbal)</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="number">
                                <span>0</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>5</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>10</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="row">
                <td class="field number">
                    <span>9</span>
                </td>
                <td class="field">
                    <span>Mobilitas (berjalan pada permukaan yang rata)</span>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="field-2 check" width="1%">
                                <input type="checkbox"
                                    {{ isset($data['mobilitas']) && $data['mobilitas']['code'] == 'TMB' ? 'checked' : '' }} />
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Tidak mampu atau berjalan kurang dari 50
                                    meter</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['mobilitas']) && $data['mobilitas']['code'] == 'HBK' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Hanya bisa bergerak dengan kursi roda, lebih
                                    dari 50 meter</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['mobilitas']) && $data['mobilitas']['code'] == 'BDB' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Berjalan dengan bantuan lenih dari 50
                                    meter</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['mobilitas']) && $data['mobilitas']['code'] == 'MI' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Mandiri (meski menggunakan alat
                                    bantu)</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="number">
                                <span>0</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>5</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>10</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>15</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="row">
                <td class="field number">
                    <span>10</span>
                </td>
                <td class="field">
                    <span>Menaiki / menuruni tangga</span>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="field-2 check" width="1%">
                                <input type="checkbox"
                                    {{ isset($data['menaikiTangga']) && $data['menaikiTangga']['code'] == 'TM' ? 'checked' : '' }} />
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Tidak mampu</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['menaikiTangga']) && $data['menaikiTangga']['code'] == 'MB' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Memerlukan bantuan</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-2 check">
                                <input type="checkbox"
                                    {{ isset($data['menaikiTangga']) && $data['menaikiTangga']['code'] == 'MI' ? 'checked' : '' }} />
                            </td>
                            <td class="field-2">
                                <span style="font-size: 9pt;" color="#000000">Mandiri</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="field">
                    <table>
                        <tr>
                            <td class="number">
                                <span>0</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>5</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="number">
                                <span>10</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="row">
                <td class="field number" colspan="3">
                    <span>Jumlah Total</span>
                </td>
                <td class="field number">
                    <span>{{ isset($data['jumlahTotal']) ? $data['jumlahTotal'] : '-' }}</span>
                </td>
            </tr>

        </tbody>
    </table>

    <table style="padding-top:10px">
        <tr>
            <td>
                <span style="font-weight: bold;">KETERANGAN</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-weight: bold;">Total Nilai : <i style="margin-left: 10px">{{isset($data['ketPoin']) ? $data['ketPoin']['title'] : '-'}}</i> </span>
            </td>
        </tr>
    </table>
   
@endsection

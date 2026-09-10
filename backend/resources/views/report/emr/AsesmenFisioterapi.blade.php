<!DOCTYPE html>
<html>

<head>
    <title>Asesmen Fisioterapi</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse !important;
            width: 100%;
        }

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        td {
            vertical-align: top !important;
        }

        .bt {
            border-top: 1px solid black;
        }

        .btl {
            border-top: 1px dashed gray;
        }

        .sml {
            font-size: 8pt !important;
        }

        .mid {
            text-align: center !important;
        }

        .border {
            border: 1px solid black;
        }

        .font {
            font-size: 9pt !important;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td colspan="2">
                    <table class="border">
                        <td
                            style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;text-align: left">
                            RSUD MALANGBONG
                        </td>
                        <td
                            style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                            RM.9V/RAJAL/00
                        </td>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table>
                        <td width="10%" style="text-align: center;padding: 10px" class="border">
                            <img src="{{ 'img/logo-rs.png' }}" width="80px" height="80px" style="display: block;">
                        </td>
                        <th width="40%"
                            style="vertical-align: middle;text-align: center;font-weight: bold;font-size: large;padding:10px"
                            class="border">
                            ASESMEN FISIOTERAPI
                        </th>
                        <td width="40%" style="padding: 5px;font-size:10pt" class="border">
                            <table>
                                <tr>
                                    <td style="text-align:left;width: 40%">Nama</td>
                                    <td style="text-align:left;width: 10%;text-align: center">:</td>
                                    <td style="text-align:left;width: 50%">{{ $pasien['namapasien'] }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;width: 40%">Tanggal Lahir</td>
                                    <td style="text-align:left;width: 10%;text-align: center">:</td>
                                    <td style="text-align:left;width: 50%">{{ $pasien['tgllahir'] }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;width: 40%">Jenis Kelamin</td>
                                    <td style="text-align:left;width: 10%;text-align: center">:</td>
                                    <td style="text-align:left;width: 50%">{{ $pasien['jeniskelamin'] }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;width: 40%">No. Rekam Medis</td>
                                    <td style="text-align:left;width: 10%;text-align: center">:</td>
                                    <td style="text-align:left;width: 50%">{{ $pasien['nocm'] }}</td>
                                </tr>
                            </table>
                        </td>
                    </table>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">
                    <table class="border font">
                        <tr>
                            <td style="padding: 3px">
                                Rujukan :
                                @if (isset($data['rujukan']) && $data['rujukan'] == 'Ya, dari')
                                    @if (isset($data['rujukanDariRS']))
                                        {{ $data['rujukanDariRS'] }}
                                    @elseif (isset($data['rujukanDariPuskesmas']))
                                        {{ $data['rujukanDariPuskesmas'] }}
                                    @elseif (isset($data['rujukanDariDokter']))
                                        {{ $data['rujukanDariDokter'] }}
                                    @elseif (isset($data['rujukanDariLainnya']))
                                        {{ $data['rujukanDariLainnya'] }}
                                    @endif
                                @elseif (isset($data['rujukan']) && $data['rujukan'] == 'Tidak')
                                    @if (isset($data['rujukanTidak']) && $data['rujukanTidak'] == 'Datang Sendiri')
                                        {{ $data['rujukanTidak'] }}
                                    @elseif (isset($data['rujukanTidak']) && $data['rujukanTidak'] == 'Diantar')
                                        {{ $data['rujukanTidak'] }} {{ $data['rujukanTidakketerangan'] }}
                                    @endif
                                @endif
                                <br>
                                Riwayat Alergi : {{ isset($data['riwayatAlergi']) ? $data['riwayatAlergi'] : '-' }}
                            </td>
                        </tr>
                        <tr class="btl">
                            <td style="padding: 3px">
                                <i><b>Anamnesa</b></i><br>
                                <table>
                                    <tr>
                                        <td colspan="2">
                                            <b>Keluhan Utama</b> :
                                            {{ isset($data['keluhanUtama']) ? $data['keluhanUtama'] : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>Riwayat Penyakit Sekarang</b> :
                                            {{ isset($data['riwayatPenyakitSekarang']) ? $data['riwayatPenyakitSekarang'] : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>Riwayat Penyakit Dahulu dan Penyerta</b> :
                                            {{ isset($data['riwayatPenyakitDahulu']) ? $data['riwayatPenyakitDahulu'] : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <table>
                                                <tr>
                                                    <td style="width: 20%">
                                                        <input type="checkbox"
                                                            {{ isset($data['diabetes']) ? 'checked' : '' }} />
                                                        <span>Diabetes Militus</span>
                                                    </td>
                                                    <td style="width: 20%">
                                                        <input type="checkbox"
                                                            {{ isset($data['Jantung']) ? 'checked' : '' }} />
                                                        <span>Jantung</span>
                                                    </td>
                                                    <td style="width: 20%">
                                                        <input type="checkbox"
                                                            {{ isset($data['rhematoid']) ? 'checked' : '' }} />
                                                        <span>Rhematoid Artitis</span>
                                                    </td>
                                                    <td style="width: 20%">
                                                        <input type="checkbox"
                                                            {{ isset($data['hipertensi']) ? 'checked' : '' }} />
                                                        <span>Hypertensi</span>
                                                    </td>
                                                    <td style="width: 20%">
                                                        <input type="checkbox"
                                                            {{ isset($data['riwayat']) ? 'checked' : '' }} />
                                                        <span>Riwayat Alergi</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>Pemeriksaan Fisik</b> :
                                            {{ isset($data['pemeriksaanFisik']) ? $data['pemeriksaanFisik'] : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>Kemampuan Fungsional</b> :
                                            {{ isset($data['kemampuanFungsional']) ? $data['kemampuanFungsional'] : '-' }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="btl">
                            <td style="padding: 3px">
                                <table>
                                    <tr>
                                        <td style="width: 70%">
                                            <table>
                                                <tr>
                                                    <td colspan="5"><b>Skala Nyeri</b></td>
                                                </tr>
                                                <tr>
                                                    <td class="mid">
                                                        <img src="img/skalanyeri/1.png" width="30px" height="30px">
                                                    </td>
                                                    <td class="mid">
                                                        <img src="img/skalanyeri/2.png" width="30px" height="30px">
                                                    </td>
                                                    <td class="mid">
                                                        <img src="img/skalanyeri/3.png" width="30px" height="30px">
                                                    </td>
                                                    <td class="mid">
                                                        <img src="img/skalanyeri/4.png" width="30px" height="30px">
                                                    </td>
                                                    <td class="mid">
                                                        <img src="img/skalanyeri/5.png" width="30px" height="30px">
                                                    </td>
                                                    <td class="mid">
                                                        <img src="img/skalanyeri/6.png" width="30px" height="30px">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="mid sml">0<br>No Hurt</td>
                                                    <td class="mid sml">2<br>Hurts Little Bit</td>
                                                    <td class="mid sml">4<br>Hurts Little More</td>
                                                    <td class="mid sml">6<br>Hurts Even More</td>
                                                    <td class="mid sml">8<br>Hurts Whole Lot</td>
                                                    <td class="mid sml">10<br>Hurts Whorts</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="width: 30%">
                                            @php
                                                $sn = '';
                                                if (isset($data['skoringNyeri'])) {
                                                    switch ($data['skoringNyeri']) {
                                                        case '0':
                                                            $sn = '0 - 1 = Tidak Ada Nyeri';
                                                            break;
                                                        case '2':
                                                            $sn = '2 - 3 = Sedikit Nyeri';
                                                            break;
                                                        case '4':
                                                            $sn = '4 - 5 = Cukup Nyeri';
                                                            break;
                                                        case '6':
                                                            $sn = '6 - 7 = Lumayan Nyeri';
                                                            break;
                                                        case '8':
                                                            $sn = '8 - 9 = Sangat Nyeri';
                                                            break;
                                                        case '10':
                                                            $sn = '10 = Amat Sangat Nyeri';
                                                            break;
                                                        default:
                                                            $sn = '';
                                                            break;
                                                    }
                                                }
                                            @endphp
                                            <b>Skor Nyeri :</b><br> {{ $sn }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="btl">
                            <td style="padding: 3px">
                                <table>
                                    <tr>
                                        <td>
                                            <b>Resiko Jatuh :
                                            </b>{{ isset($data['resikoJatuh']) ? $data['resikoJatuh'] : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Pemeriksaan Khusus :
                                            </b>{{ isset($data['pemeriksaanKhusus']) ? $data['pemeriksaanKhusus'] : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Pengukuran Khusus :
                                            </b>{{ isset($data['pengukuranKhusus']) ? $data['pengukuranKhusus'] : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Data Penunjang :
                                            </b>{{ isset($data['dataPenunjang']) ? $data['dataPenunjang'] : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Diagnosis Fisioterapi :
                                            </b>{{ isset($data['diagnosisFisioterapi']) ? $data['diagnosisFisioterapi'] : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Intervensi :
                                            </b>{{ isset($data['intervensi']) ? $data['intervensi'] : '-' }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="btl">
                            <td style="padding: 3px;">
                                <table>
                                    <tr>
                                        <td style="width: 75%"></td>
                                        <td style="width: 25%" class="mid">
                                            Garut,
                                            {{ isset($data['tanggalTTD']) ? \Carbon\Carbon::parse($data['registrasi']['tglregistrasi'])->format('Y-m-d') : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="mid">
                                            @if (isset($data['TTDFisioterapi']))
                                                <img style="width: 100px;height: 100px;"
                                                    src="{{ $data['TTDFisioterapi'] }}">
                                            @else
                                                <br><br><br>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="mid">
                                            <br>
                                            @if (isset($data['pegawaiFisioterapi']) && is_array($data['pegawaiFisioterapi']) && array_key_exists('label', $data['pegawaiFisioterapi']))
                                                {{ $data['pegawaiFisioterapi']['label'] ?? '-' }}
                                            @else
                                                {{ $data['pegawaiFisioterapi'] ?? '-' }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>

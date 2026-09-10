<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    <style>
        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <table width="100%" cellspacing="0" cellpadding="0"
        style="border-bottom: 1px solid black;border-collapse: collapse; padding">
        <tr>
            <td style="text-align: center; padding: 15px;">
                <img src="{{ 'img/logo-cetakan-obgyn.png' }}" width="100px" height="100px" style="display: block;">
            </td>

            <td style="text-align: center;">
                <span style="font-weight: bold;">PEMERINTAH PROVINSI BALI </span><br>
                <span style="font-weight: bold;">DINAS KESEHATAN</span> <br>
                <span style="font-weight: bold;">RUMAH SAKIT UMUM DAERAH BALI MANDARA</span> <br>
                <span>Jalan ByPass Ngurah Rai No.548 Sanur,Garut-Bali</span>
                <span>No.Telp : (0361) 4490566, E-mail : rsud.balimandara@gmail.com</span>
            </td>
            <td style="text-align: center; padding: 15px;">
                <img src="{{ 'img/logo-rs.png' }}" width="100px" height="100px" style="display: block;">
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <tr>
            <td style="text-align: center;">
                <span style="font-weight: bold;">PEMERIKSAAN OBSTETRI</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;font-size: small">
        <tr>
            <td class="bold" style="border: 1px solid black; width: 25%; padding: 5px;">
                <span>No RM</span>
            </td>
            <td style="border: 1px solid black;width: 25%;padding: 5px;">
                <span>{{ isset($data['norm']) ? $data['norm'] : '-' }}</span>
            </td>
            <td style="font-weight:bold;border: 1px solid black;width: 25%;padding: 5px">
                <span>Tanggal / Jam Periksa</span>
            </td>
            <td style="border: 1px solid black;width: 25%;padding: 5px;">
                <span>
                    {{ isset($data['tglPembuatan'])
                        ? \Carbon\Carbon::parse($data['tglPembuatan'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')
                        : '-' }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="bold" style="border: 1px solid black;padding: 5px">
                <span>Nama Pasien</span>
            </td>
            <td style="border: 1px solid black; padding: 5px;">
                <span>{{ isset($data['namaPasien']) ? $data['namaPasien'] : '-' }}</span>
            </td>
            <td class="bold" style="border: 1px solid black;padding: 5px">
                <span>Dokter Pemeriksa</span>
            </td>
            <td style="border: 1px solid black; padding: 5px;">
                <span>
                    @if (!empty($data['dokterPemeriksa']['label']))
                        {{ $data['dokterPemeriksa']['label'] }}
                    @elseif (!empty($data['dokterPemeriksa']))
                        {{ $data['dokterPemeriksa'] }}
                    @else
                        -
                    @endif
                </span>
            </td>
        </tr>
        <tr>
            <td class="bold" style="border: 1px solid black;padding: 5px;">
                <span>Alamat Pasien</span>
            </td>
            <td style="border: 1px solid black; padding: 5px;">
                <span>{{ isset($data['pasien']['alamatlengkap']) ? $data['pasien']['alamatlengkap'] : '-' }}</span>
            </td>
            <td class="bold" style="border: 1px solid black;padding: 5px">
                <span>HPHT</span>
            </td>
            <td style="border: 1px solid black; padding: 5px;">
                <span>{{ isset($data['hpht']) ? $data['hpht'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td class="bold" style="border: 1px solid black; width: 15%;padding:5px">
                <span>Tanggal Lahir</span>
            </td>
            <td style="border: 1px solid black; padding: 5px;">
                <span>{{ isset($data['tanggalLahirPasien']) ? $data['tanggalLahirPasien'] : '-' }}
                    {{ isset($data['umur']) ? $data['umur'] : '-' }}</span>
            </td>
            <td class="bold" style="border: 1px solid black;padding: 5px;">
                <span>Diagnosa</span>
            </td>
            <td style="border: 1px solid black; padding: 5px;">
                <span>{{ isset($data['diagnosa']) ? $data['diagnosa'] : '-' }}</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <tr>
            <td style="padding: 5px;padding-top: 0px">
                <span style="font-weight: bold;">I. Kondisi Teknis : </span>
                <span>{{ isset($data['kondisiTeknisFetal']) ? $data['kondisiTeknisFetal'] : '-' }}</span>
                <span>Terbatas, karena {{ isset($data['karenaFetal']) ? $data['karenaFetal'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;padding-top: 0px">
                <span style="font-weight: bold;">II. Janin
                    :</span><span>{{ isset($data['janin']) ? $data['janin'] : '-' }}, </span>
                <span style="font-weight: bold;">Khorionisitas: </span>
                <span>{{ isset($data['khorionisitas']) ? $data['khorionisitas'] : '-' }}, </span>
                <span style="font-weight: bold;">DJJ: </span>
                <span>{{ isset($data['djj']) ? $data['djj'] : '-' }},{{ isset($data['ketDJJ']) ? $data['ketDJJ'] : '-' }}
                    x/menit, </span>
                <span style="font-weight: bold;">Fetal Movement: </span>
                <span>{{ isset($data['fetalmovement']) ? $data['fetalmovement'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;padding-top: 0px">
                <span style="font-weight: bold;">III. Biometri:</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;margin-top: 15px;font-size: small">
        <thead>
            <tr>
                <th style="border: 1px solid black; padding: 5px;">PENGUKURAN</th>
                <th style="border: 1px solid black; padding: 5px;">MM</th>
                <th style="border: 1px solid black; padding: 5px;">USIA KEHAMILAN</th>
                <th style="border: 1px solid black; padding: 5px;">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid black; padding: 5px;">Gestasional Sac</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['gestasionalsac']) ? $data['gestasionalsac'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['usiaGestasional']) ? $data['usiaGestasional'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['ketGestasional']) ? $data['ketGestasional'] : '-' }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 5px;">Crown-rump Length</td>
                <td style="border: 1px solid black; padding: 5px;">{{ isset($data['crown']) ? $data['crown'] : '-' }}
                </td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['usiaCrown']) ? $data['usiaCrown'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['ketCrown']) ? $data['ketCrown'] : '-' }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 5px;">Biparietal Diameter</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['biparietal']) ? $data['biparietal'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['usiaBiparietal']) ? $data['usiaBiparietal'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['ketBiparietal']) ? $data['ketBiparietal'] : '-' }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 5px;">Head Circumference</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['headcircum']) ? $data['headcircum'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['usiaHeadCircum']) ? $data['usiaHeadCircum'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 5px; vertical-align: top;" rowspan="3">Temuan abnormal
                    : {{ isset($data['Ketabdominalcircum']) ? $data['Ketabdominalcircum'] : '-' }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 5px;">Abdominal Circumference</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['abdominalcircum']) ? $data['abdominalcircum'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['usiaAbdominalcircum']) ? $data['usiaAbdominalcircum'] : '-' }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 5px;">Femoral Lenght</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['femoral']) ? $data['femoral'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 5px;">
                    {{ isset($data['usiaFemoral']) ? $data['usiaFemoral'] : '-' }}</td>
            </tr>
        </tbody>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;margin-top: 15px;">
        <tr>
            <td style="padding: 5px;padding-top: 0px">
                <span style="font-weight: bold;">IV. Plasenta : </span>
                <span>{{ isset($data['plasenta']) ? $data['plasenta'] : '-' }}, </span>
                <span>{{ isset($data['menutupi']) ? $data['menutupi'] : '-' }}, </span>
                <span>{{ isset($data['ukuranMenutupi']) ? $data['ukuranMenutupi'] : '-' }}</span>
                <span>{{ isset($data['maturasi']) ? $data['maturasi'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;padding-top: 0px">
                <span style="font-weight: bold;">V. Cairan Amnion : </span>
                <span>{{ isset($data['cairanaminion']) ? $data['cairanaminion'] : '-' }}, </span>
                <span style="font-weight: bold;">AFI : </span>
                <span>{{ isset($data['AFI']) ? $data['AFI'] : '-' }}, </span>
                <span style="font-weight: bold;">SDP : </span>
                <span>{{ isset($data['SDP']) ? $data['SDP'] : '-' }}</span> <br>
                <span>Temuan abnormal: {{ isset($data['temuanAbnormal']) ? $data['temuanAbnormal'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;padding-top: 0px">
                <span style="font-weight: bold;">VI. Kelainan Kongenital Mayor : </span>
                <span>{{ isset($data['kongenitalMayor']) ? $data['kongenitalMayor'] : '-' }}, </span>
                <br>
                <span>Temuan abnormal:
                    {{ isset($data['temuanAbnormalKongenital']) ? $data['temuanAbnormalKongenital'] : '-' }}</span>
            </td>
        </tr>

        <tr>
            <td style="padding: 5px;padding-top: 0px">
                <span style="font-weight: bold;">VII. Adneksa : </span>
                <span>{{ isset($data['adneksa']) ? $data['adneksa'] : '-' }}, </span>
                <br>
                <span>Temuan abnormal: {{ isset($data['fetalSaran']) ? $data['fetalSaran'] : '-' }}</span>
                <br>
                <br>
                <br>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
        <tr>
            <td width="60%"></td>
            <td style="text-align: center" width="40%">
                Garut,
                {{ isset($data['tglPembuatan'])
                    ? \Carbon\Carbon::parse($data['tglPembuatan'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')
                    : '-' }}
                {{-- {{ \Carbon\Carbon::parse($data['registrasi']['tglregistrasi'])->format('d F Y') }} --}}
            </td>
        </tr>
        <tr>
            <td width="60%"></td>
            <td style="text-align: center" width="40%">
                Dokter Pemeriksa
            </td>
        </tr>
        <tr>
            <td width="60%"></td>
            <td style="text-align: center" width="40%">
                <img src="data:image/png;base64, {!! $tte !!}" style="width: 100px;height:100px">
                <br>
            </td>
        </tr>
        <tr>
            <td width="60%"></td>
            <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                @if (!empty($data['dokterPemeriksa']['label']))
                    {{ $data['dokterPemeriksa']['label'] }}
                @elseif (!empty($data['dokterPemeriksa']))
                    {{ $data['dokterPemeriksa'] }}
                @else
                    -
                @endif
            </td>
        </tr>
        @if (isset($data['nip']->nip))
            <tr>
                <td width="60%"></td>
                <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                    NIP :
                    @if (isset($data['nip']->nip))
                        {{ $data['nip']->nip }}
                    @else
                        {{ '-' }}
                    @endif
                </td>
            </tr>
        @endif
    </table>
</body>

</html>

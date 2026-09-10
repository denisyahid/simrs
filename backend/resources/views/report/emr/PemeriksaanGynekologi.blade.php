<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    @yield('page-style')
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
                <span style="font-weight: bold;">PEMERIKSAAN GYNEKOLOGI</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px;">
                <span>No RM</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['norm']) ? $data['norm'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; padding: 10px;">
                <span>Tanggal / Jam Periksa</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>
                    {{ isset($data['tglPembuatan'])
                        ? \Carbon\Carbon::parse($data['tglPembuatan'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')
                        : '-' }}
                </span>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px; width: 20%;">
                <span>Nama Pasien</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['namaPasien']) ? $data['namaPasien'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; padding: 10px; width: 20%;">
                <span>Dokter Pemeriksa</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>
                    {{ isset($data['dokterPemeriksa'])
                        ? (is_array($data['dokterPemeriksa'])
                            ? $data['dokterPemeriksa']['label']
                            : $data['dokterPemeriksa'])
                        : '-' }}
                </span>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px;">
                <span>Alamat Pasien</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['pasien']['alamatlengkap']) ? $data['pasien']['alamatlengkap'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; padding: 10px;">
                <span>HPHT</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['hpht']) ? $data['hpht'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px;">
                <span>Tanggal Lahir</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['tanggalLahirPasien']) ? $data['tanggalLahirPasien'] : '-' }}
                    {{ isset($data['umur']) ? $data['umur'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; padding: 10px;">
                <span>Diagnosa</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['diagnosa']) ? $data['diagnosa'] : '-' }}</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">I. Kondisi Teknis : </span>
                <span>{{ isset($data['kondisiTeknis']) ? $data['kondisiTeknis'] : '-' }}, </span>
                <span style="font-weight: bold;">Karena: </span>
                <span>{{ isset($data['karena']) ? $data['karena'] : '-' }}, </span>
            </td>
        </tr>

        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">II. Vesica urinaria :</span>
                <span>{{ isset($data['vesicaUrinaria']) ? $data['vesicaUrinaria'] : '-' }}, </span>
            </td>
        </tr>

        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">III. Uterus : </span>
                <span>{{ isset($data['uterus']) ? $data['uterus'] : '-' }}, </span>
            </td>
        </tr>

        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">IV. Adnexa : </span>
                <span>{{ isset($data['adnexa']) ? $data['adnexa'] : '-' }}, </span>
            </td>
        </tr>

        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">V. Cairan Bebas: </span>
                <span>{{ isset($data['cairanBebas']) ? $data['cairanBebas'] : '-' }}, </span>
            </td>
        </tr>

        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">VI. Lain - Lain: </span>
                <span>{{ isset($data['obstetriLainnya']) ? $data['obstetriLainnya'] : '-' }}, </span>
            </td>
        </tr>

        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">Kesimpulan dan saran</span>
                <span>{{ isset($data['kesimpulansaran']) ? $data['kesimpulansaran'] : '-' }}, </span>
            </td>
        </tr>

    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <tr>
            <td width="60%"></td>
            <td style="text-align: center" width="40%">
                <br>
                Garut, {{ isset($data['tglPembuatan'])
                        ? \Carbon\Carbon::parse($data['tglPembuatan'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')
                        : '-' }}
                {{-- Garut, {{ \Carbon\Carbon::parse($data['registrasi']['tglregistrasi'])->format('d F Y') }} --}}
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
                <br>
                <img src="data:image/png;base64, {!! $tte !!}">
                <br><br>
            </td>
        </tr>
        <tr>
            <td width="60%"></td>
            <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                {{ isset($data['dokterPemeriksa'])
                    ? (is_array($data['dokterPemeriksa'])
                        ? $data['dokterPemeriksa']['label']
                        : $data['dokterPemeriksa'])
                    : '-' }}
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

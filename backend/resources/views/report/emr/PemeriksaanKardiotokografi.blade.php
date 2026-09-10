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
                <span style="font-weight: bold;">PEMERIKSAAN KARDIOTOKOGRAFI</span>
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
                {{-- <span>{{isset($data['dokterPemeriksa']) ? $data['dokterPemeriksa'] : '-' }}</span> --}}
                <span>
                    @if (isset($data['dokterPemeriksa']))
                        {{ is_array($data['dokterPemeriksa']) ? $data['dokterPemeriksa']['label'] : $data['dokterPemeriksa'] }}
                    @else
                        -
                    @endif
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
                <span><span style="font-style: bold;">1. TD Awal:
                    </span>{{ isset($data['tdawal']) ? $data['tdawal'] : '-' }} , <span style="font-style: bold;">TD
                        Menit ke 15</span> : {{ isset($data['tg15']) ? $data['tg15'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span><span style="font-style: bold;">2. Cara Pantau : </span>
                    {{ isset($data['carapantau']) ? $data['carapantau'] : '-' }} , <span
                        style="font-style: bold;">Kecepatan Kertas :
                    </span>{{ isset($data['kecepatankertas']) ? $data['kecepatankertas'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span><span style="font-style: bold;">3. Periksa Dalam :</span>
                    {{ isset($data['periksaDalam']) ? $data['periksaDalam'] : '-' }} , <span
                        style="font-style: bold;">Dengan Hasil :
                    </span>{{ isset($data['denganhasil']) ? $data['denganhasil'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span><span style="font-style: bold;">4. Diagnosis:</span>
                    {{ isset($data['diagnosis']) ? $data['diagnosis'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span><span style="font-style: bold;">5. Denyut Jantung Janin :
                    </span>{{ isset($data['denyutjantung']) ? $data['denyutjantung'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span><span style="font-style: bold;">6. Frekuensi Dasar :
                    </span>{{ isset($data['frekuensidasar']) ? $data['frekuensidasar'] : '-' }} dpm, </span>
                <span><span style="font-style: bold;">Variabilitas :</span>
                    {{ isset($data['variabilitas']) ? $data['variabilitas'] : '-' }},</span>
                <span><span style="font-style: bold;">Akselerasi :</span>
                    {{ isset($data['akselerasi']) ? $data['akselerasi'] : '-' }},</span>
                <span><span style="font-style: bold;">Deselerasi :
                    </span>{{ isset($data['deselerasi']) ? $data['deselerasi'] : '-' }}, </span>
                <span><span style="font-style: bold;">Jenisnya
                        :</span>{{ isset($data['jenisnya']) ? $data['jenisnya'] : '-' }}, </span>
                <span><span style="font-style: bold;">Beratnya :
                    </span>{{ isset($data['beratnya']) ? $data['beratnya'] : '-' }}, </span>
                <span><span style="font-style: bold;">Pola Disfungsi SSP :
                    </span>{{ isset($data['ssp']) ? $data['ssp'] : '-' }}, </span>
                <span><span style="font-style: bold;">Yaitu :
                    </span>{{ isset($data['yaitu']) ? $data['yaitu'] : '-' }}, </span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span><span style="font-style: bold;">6. Kontraksi Uterus / His :
                    </span>{{ isset($data['kontraksi']) ? $data['kontraksi'] : '-' }}, </span> <br>
                <span><span style="font-style: bold;">Frekuensi :
                    </span>{{ isset($data['frekuensi']) ? $data['frekuensi'] : '-' }}/10 menit,</span>
                <span><span style="font-style: bold;">Kekuatan :
                    </span>{{ isset($data['kekuatan']) ? $data['kekuatan'] : '-' }} mmHg,</span>
                <span><span style="font-style: bold;">Lamanya :
                    </span>{{ isset($data['lamanya']) ? $data['lamanya'] : '-' }} menit, </span>
                <span><span style="font-style: bold;">Relaksasi :
                    </span>{{ isset($data['relaksasi']) ? $data['relaksasi'] : '-' }}, </span>
                <span><span style="font-style: bold;">Konfigurasi :
                    </span>{{ isset($data['konfigurasi']) ? $data['konfigurasi'] : '-' }}, </span>
                <span><span style="font-style: bold;">Tonus Dasar :
                    </span>{{ isset($data['tumusdasar']) ? $data['tumusdasar'] : '-' }} mmHg</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span><span style="font-style: bold;">7. Gerak Janin :
                    </span>:{{ isset($data['gerakjanin']) ? $data['gerakjanin'] : '-' }} kali dalam,</span>
                <span><span style="font-style: bold;">dalam:
                    </span>{{ isset($data['lamagerak']) ? $data['lamagerak'] : '-' }} menit</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span><span style="font-style: bold;">8. Diagnosis KTG :</span>
                    {{ isset($data['diagnosisktg']) ? $data['diagnosisktg'] : '-' }} </span>
            </td style="padding: 5px;">
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span><span><span style="font-style: bold;">9. Saran
                            :</span>{{ isset($data['saran']) ? $data['saran'] : '-' }}</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <tr>
            <td width="60%"></td>
            <td style="text-align: center" width="40%">
                <br>
                Garut, {{ isset($data['tglPembuatan'])
                        ? \Carbon\Carbon::parse($data['tglPembuatan'])->setTimezone('Asia/Jakarta')->format('d-m-Y')
                        : '-' }}
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
                @if (isset($data['dokterPemeriksa']))
                    {{ is_array($data['dokterPemeriksa']) ? $data['dokterPemeriksa']['label'] : $data['dokterPemeriksa'] }}
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

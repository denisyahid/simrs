<?php
function formatDateIndonesian($date)
{
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return '-';
    }
    $day = date('d', $timestamp);
    $month = getIndonesianMonth(date('n', $timestamp));
    $year = date('Y', $timestamp);
    return "$day $month $year";
}
function getIndonesianMonth($monthNumber)
{
    $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];
    return $months[$monthNumber] ?? '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bukti Pelayanan Canggih</title>

</head>

<body onLoad="window.print()">
    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black;border-collapse: collapse;">
        <tr class="bg-cell" style="background-color: lightblue; box-shadow: inset 0 0 0 1000px lightblue;">
            <td style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;">
                RSUD BALI MANDARA
            </td>
            <td style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                @yield('kode')
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black">
                <table width="100%" style="border-collapse: collapse">
                    <tr style="border: ">
                        <td width="15%" style="text-align: center;padding: 10px">
                            <img src="{{ asset('img/provinsi-rs.svg') }}" width="80px" height="80px"
                                style="display: block;">
                        </td>
                        <td width="70%" style="text-align: center;">
                            <span>
                                <b>PEMERINTAH PROVINSI BALI</b><br>
                                <b>RUMAH SAKIT UMUM DAERAH BALI MANDARA</b><br>
                                Jalan ByPass Ngurah Rai No.548 Sanur,Garut-Bali<br>
                                No.Telp : (0361) 4490566, E-mail : rsud.balimandara@gmail.com
                            </span>
                        </td>
                        <td width="15%" style="text-align: center;padding: 10px">
                            <img src="{{ asset('img/logo-rs.png') }}" width="80px" height="80px"
                                style="display: block;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                    <table width="100%">
                        <tr>
                            <td width="100%" align="center">
                                <span style="font-size: 14pt; font-weight: bold;">Bukti Pelayanan Canggih</span>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <div>
                        <table width="100%">
                            <thead>
                                <tr>
                                    <td colspan="3" style="width:100%;">
                                        <span style="font-size: 11pt;" color="#000000">
                                            Saya yang bertanda-tangan dibawah ini :
                                        </span>
                                    </td>
                                </tr>
                                <tr style="text-align: start; width: 50%">
                                    <td style="width: 100px;">Nama</td>
                                    <td style="width: 10px;">:</td>
                                    <td style="font-size: 10pt;">{{ $data['CBDokter'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:25%"><span style="font-size: 11pt;"
                                            color="#000000">Spesialis</span></td>
                                    <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                                    <td style="width:74%"><span style="font-size: 11pt;"
                                            color="#000000">{{ $data['spesialisBertandaTangan'] }}</span></td>
                                </tr>
                                <tr>
                                    <td style="width:25%"><span style="font-size: 11pt;" color="#000000">Jabatan</span>
                                    </td>
                                    <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                                    <td style="width:74%"><span style="font-size: 11pt;" color="#000000">
                                            {{ $data['jabatanBertandaTangan'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr></tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="width:100%;">
                                        <span style="font-size: 11pt;" color="#000000">
                                            Memang Benar Telah Memberikan Pelayanan Canggih <span
                                                style="text-decoration: underline; font-weight: 600;">HEMODIALISA</span>
                                            Kepada :
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:25%"><span style="font-size: 11pt;" color="#000000">Nomor Rekam
                                            Medis:</span></td>
                                    <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                                    <td style="width:74%"><span style="font-size: 11pt;" color="#000000">
                                            {{ $data['norm'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:25%"><span style="font-size: 11pt;" color="#000000">Nama
                                            Pasien:</span></td>
                                    <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                                    <td style="width:74%"><span style="font-size: 11pt;" color="#000000">
                                            {{ $data['namaPasien'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:25%"><span style="font-size: 11pt;" color="#000000">Tanggal
                                            Lahir/Umur</span></td>
                                    <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                                    <td style="width:40%"><span style="font-size: 11pt;" color="#000000">
                                            <?php
                                            function hitungUmur($tanggalLahir)
                                            {
                                                $tglLahir = new DateTime($tanggalLahir);
                                                $sekarang = new DateTime();
                                                $umur = $sekarang->diff($tglLahir);
                                                return $umur->y . ' tahun, ' . $umur->m . ' bulan';
                                            }
                                            
                                            echo $data['pasien']['tgllahir'] . ' / ' . hitungUmur($data['pasien']['tgllahir']);
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:25%"><span style="font-size: 11pt;" color="#000000">Jenis
                                            Kelamin</span></td>
                                    <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span>
                                    </td>
                                    <td style="width:40%"><span style="font-size: 11pt;" color="#000000">
                                            {{ $data['pasien']['jeniskelamin'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:25%"><span style="font-size: 11pt;"
                                            color="#000000">Alamat</span></td>
                                    <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span>
                                    </td>
                                    <td style="width:40%"><span style="font-size: 11pt;" color="#000000">
                                            {{ $data['pasien']['alamatlengkap'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:25%"><span style="font-size: 11pt;"
                                            color="#000000">Diagnosa</span></td>
                                    <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span>
                                    </td>
                                    <td><span style="font-size: 11pt;" color="#000000">
                                            {{ $data['diagnosa'] }}
                                        </span>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width:25%"><span style="font-size: 11pt;" color="#000000"></span></td>
            <td style="width: 1%"><span style="font-size: 11pt;" color="#000000"></span></td>
            <td><span style="font-size: 11pt;" color="#000000">
                    Mulai HD: Pukul
                    <?php
                    $waktu = new DateTime($data['jamKunjungan']);
                    $waktuWITA = $waktu->setTimezone(new DateTimeZone('Asia/Jakarta'));
                    echo $waktuWITA->format('H:i');
                    ?>
                </span>
                WIB
            </td>
        </tr>
        <tr>
            <td style="width:25%"><span style="font-size: 11pt;" color="#000000"></span></td>
            <td style="width: 1%"><span style="font-size: 11pt;" color="#000000"></span></td>
            <td><span style="font-size: 11pt;" color="#000000">
                    Selesai HD: Pukul
                    <?php
                    $waktu = new DateTime($data['jamSelesai']);
                    $waktuWITA = $waktu->setTimezone(new DateTimeZone('Asia/Jakarta'));
                    echo $waktuWITA->format('H:i');
                    ?>
                </span>
                WIB
            </td>
        </tr>
        <tr>
            <td colspan="3" style="width:100%;">
                <span style="font-size: 11pt;" color="#000000">
                    Demikian surat ini kami sampaikan untuk dapat dipergunakan sebagaimana mestinya.
                </span>
            </td>
        </tr>
        </thead>
    </table>
    <div style="display: flex;">
        <table style="width: 50%;">
        </table>
        <table style="width: 50%;">
            <thead>
                <tr>
                    <td>
                        Garut, {{ date('d-m-Y', strtotime($data['tanggal'])) }}
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    <div style="display: flex;">
        <table style="width: 50%;">
            <thead>
                <tr>
                    <td style="text-align: center; display: none !important">
                        <span style="font-size: 11pt;" color="#000000">
                            Pasien Keluarga / Pasien
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; display: none !important">
                        <img src="{{ $data['TTDpasien'] }}" alt="" srcset="">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; display: none !important">
                        <span style="font-size: 11pt;" color="#000000">
                            {{ $data['pasien']['namapasien'] }}
                        </span>
                    </td>
                </tr>
            </thead>

        </table>
        <table>
            <thead>
                <tr>
                    <td style="text-align: center">
                        <span style="font-size: 11pt;" color="#000000">
                            Dokter Yang Merawat
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        <img src="data:image/png;base64, {!! $qrcode !!}">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        <span style="font-size: 11pt;" color="#000000">
                            {{ $data['CBDokter'] }}
                        </span>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    </div>
    </table>
    </td>
    </tr>
    </table>
</body>

</html>

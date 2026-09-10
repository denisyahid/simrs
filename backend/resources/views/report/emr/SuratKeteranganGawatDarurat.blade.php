<!DOCTYPE html>
<html>

<head>
    <title>Surat Keterangan Gawat Darurat</title>
    @yield('page-style')
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0"
        style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr colspan="3">
            <td style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;">
                RSUD BALI MANDARA
            </td>
            <td colspan="2"
                style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                RM.5/SK/00
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0"
        style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr>
            <td style="border: 1px solid black; width: 10%;">
                <img src="{{ 'img/logo-rs.png' }}" width="100px" height="100px" style="display: block;">
            </td>

            <td style="border: 1px solid black; font-weight: bold; text-align: center; width: 60%;">
                <h3>PEMERINTAH PROVINSI BALI <br>RSUD BALI MANDARA <br>Jalan By Pass Ngurah Rai No. 548, Garut - Bali
                </h3>
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-collapse: collapse">
        <tr>
            <td style=" font-weight: bold; text-align: center; width: 60%;">
                SURAT KETERANGAN GAWAT DARURAT
            </td>
        </tr>

        <tr>
            <td style="padding-left: 15px; padding-top: 10px;">
                Yang bertanda tangan dibawah ini:
            </td>
        </tr>
        <tr>
            <td style="padding-left: 35px;">
                @if (!isset($data['DDDokter']['label']))
                    <p>Nama : {{ isset($data['DDDokter']) ? $data['DDDokter'] : '-' }}</p>
                @else
                    <p>Nama : {{ isset($data['DDDokter']['label']) ? $data['DDDokter']['label'] : '-' }}</p>
                @endif
                
                <p>Jabatan : {{ isset($data['jabatanBertandaTangan']) ? $data['jabatanBertandaTangan'] : '-' }}</p>
            </td>
        </tr>

        <tr>
            <td style="padding-left: 15px; padding-top: 10px;">
                Yang bertanda tangan dibawah ini:
            </td>
        </tr>
        <tr>
            <td style="padding-left: 35px;">
                <p>Nama : {{ isset($data['namaPasien']) ? $data['namaPasien'] : '-' }}</p>
                <p>Tanggal Lahir/Umur
                    <?php
                    function hitungUmur($tanggalLahir, $tanggalReferensi = null)
                    {
                        if (!$tanggalLahir) {
                            return " ";
                        }

                        $tglLahir = new DateTime($tanggalLahir);
                        $tglReferensi = $tanggalReferensi ? new DateTime($tanggalReferensi) : new DateTime();
                        $umur = $tglReferensi->diff($tglLahir);
                        return $umur->y . 'thn ' . $umur->m . 'bln ' . $umur->d . 'hr';
                    }

                    $tglLahir = isset($data['tgllahir']) ? $data['tgllahir']->tgllahir : '';
                    $tglRawatInap = isset($data['tanggal']) ? $data['tanggal'] : null;

                    $umur = isset($data['tgllahir']) ? hitungUmur($data['tgllahir']->tgllahir, $tglRawatInap) : '';

                    echo ': ' . $tglLahir . ' / ' . $umur;
                    ?>
                </p>

                <p>Jenis Kelamin: {{ isset($data['jeniskelamin']) ? $data['jeniskelamin'] : '-' }}</p>
                <p>No Rekam Medis : {{ isset($data['norm']) ? $data['norm'] : '-' }}</p>
                <p>Diagnosa : {{ isset($data['diagnosa']) ? $data['diagnosa'] : '-' }}</p>
            </td>
        </tr>
        <tr>
            <p style="padding-left: 15px;">Memang benar telah mendapat penanganan <span style="font-weight: bold">GAWAT
                    DARURAT (EMERGENCY)</span> dan <span
                    style="font-weight: bold">{{ isset($data['tindakan']) ? $data['tindakan'] : '-' }}</span> untuk
                {{ isset($data['untuk']) && $data['untuk'] != '' ? $data['untuk'] : 'Rawat Inap' }}.
                Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
            </p>
        </tr>
        @php
            use Carbon\Carbon;
        @endphp
        <tr>
            <td style="text-align: center;">
                <p>{{ isset($data['tanggal']) ? Carbon::parse($data['tanggal'])->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') : '-' }}</p>
                <img src="data:image/png;base64, {!! $tte !!}">
                @if (!isset($data['DDDokter']['label']))
                    <p>{{ isset($data['DDDokter']) ? $data['DDDokter'] : '-' }}</p>
                @else
                    <p>{{ isset($data['DDDokter']['label']) ? $data['DDDokter']['label'] : '-' }}</p>
                @endif

                @if (isset($data['nip']->nip))
                NIP : {{ $data['nip']->nip }}
                @endif
            </td>
        </tr>
    </table>

</body>

</html>

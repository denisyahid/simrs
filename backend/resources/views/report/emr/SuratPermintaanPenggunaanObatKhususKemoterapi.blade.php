<!DOCTYPE html>
<html>

<head>
    <title>Surat Penggunaan Obat Khusus Kemoterapi</title>
    <style>
        html,
        body {
            page-break-inside: avoid !important;
            font-family: Arial, Helvetica, sans-serif;
        }

        .kesimpulan-wrapper {
            width: 100%;
            border-collapse: collapse !important;
            page-break-inside: avoid !important;
        }

        .checkbox-wrapper {
            white-space: nowrap
        }

        .checkbox {
            vertical-align: top;
            display: inline-block
        }

        .fnt {
            font-size: 11px;
            margin-top: 0px;
            padding-top: 0px;
        }
    </style>
</head>

@php
    function convertToMakassarTime($isoDateString)
    {
        $date = new DateTime($isoDateString, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
        return $date->format('d-m-Y H:i');
    }

    function convertToRegularDate($isoDateString)
    {
        $date = new DateTime($isoDateString);
        return $date->format('d-m-Y');
    }

    function convertToRegularTime($isoDateString)
    {
        $date = new DateTime($isoDateString);
        return $date->format('H:i');
    }
@endphp

<body>
    <table class="table" width="100%" cellspacing="0" cellpadding="0"
        style="border-bottom: 1px solid black;border-collapse: collapse; padding; page-break-inside: avoid !important;">
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

    <table width="100%" class="fnt">
        <tr>
            <td style="font-weight: bold;">I. Keterangan Pasien</td>
        </tr>
    </table>

    <table width="100%" class="fnt" style="margin-left: 10px;border-collapse: collapse">
        <thead>
            <tr style="text-align: start;">
                <td style="width:18%;font-weight: bold;">Nomor Rekam Medis</td>
                <td style="width:32%;">: {!! isset($data['norm']) ? nl2br(e($data['norm'])) : '-' !!}</td>
                <td style="width:18%;font-weight: bold;">Nama Pasien</td>
                <td style="width:32%;">: {!! isset($data['namaPasien']) ? nl2br(e($data['namaPasien'])) : '-' !!}</td>
            </tr>
            <tr style="text-align: start;">
                <td style="font-weight: bold;width:18%;">Tanggal Lahir</td>
                <td style="width:32%;">: {!! isset($data['tanggalLahirPasien']) ? convertToRegularDate($data['tanggalLahirPasien']) : '-' !!}</td>

                <td style="width:18%;;font-weight: bold;">Jenis Kelamin</td>
                <td style="width:32%;">: {!! isset($data['jeniskelamin']) ? nl2br(e($data['jeniskelamin'])) : '-' !!}</td>
            </tr>
        </thead>
    </table>

    <table width="100%" class="fnt" style="margin-left: 10px;border-collapse: collapse">
        <tr style="text-align: start;">
            <td style="width: 10%; font-weight: bold;">Cara Bayar</td>
            <td style="width: 90%" colspan="5">: {{ isset($data['caraBayar']) ? $data['caraBayar'] : '-' }}</td>
            {{-- <td style="width: 15%">:
                <input type="checkbox" class="checkbox"
                    {{ isset($data['caraBayar']) && $data['caraBayar'] == 'JKN' ? 'checked' : '' }}>
                <label class="checkbox-label">JKN</label>
            </td>
            <td style="width: 15%">
                <input type="checkbox" class="checkbox"
                    {{ isset($data['caraBayar']) && $data['caraBayar'] == 'IKS' ? 'checked' : '' }}>
                <label class="checkbox-label">IKS</label>
            </td>
            <td style="width: 15%">
                <input type="checkbox" class="checkbox"
                    {{ isset($data['caraBayar']) && $data['caraBayar'] == 'Umum' ? 'checked' : '' }}>
                <label class="checkbox-label">Umum</label>
            </td>
            <td style="width: 15%">
                <input type="checkbox" class="checkbox"
                    {{ isset($data['caraBayar']) && $data['caraBayar'] == 'WNA' ? 'checked' : '' }}>
                <label class="checkbox-label">WNA</label>
            </td>
            <td style="width: 15%">
                <input type="checkbox" class="checkbox"
                    {{ isset($data['caraBayar']) && $data['caraBayar'] == 'Lainnya' ? 'checked' : '' }}>
                <label class="checkbox-label">Lainnya</label>
                <span>: {{ isset($data['caraBayarLainnya']) ? $data['caraBayarLainnya'] : '' }}</span>
            </td> --}}
        </tr>
        <tr style="text-align: start;">
            <td style="font-weight: bold;">Diagnosis</td>
            <td colspan="5">: {!! isset($data['diagnosis']) ? $data['diagnosis'] : '-' !!}</td>
        </tr>
    </table>

    <table width="100%" class="fnt" style="margin-left: 10px;border-collapse: collapse">
        <tr>
            <td style="width: 25%; font-weight: bold;">Tekanan Darah</td>
            <td style="width: 25%">: {!! isset($data['tekananDarahObgyn']) ? nl2br(e($data['tekananDarahObgyn'])) : '-' !!} mmHg</td>

            <td style="width: 25%; font-weight: bold;">Nadi</td>
            <td style="width: 25%">: {!! isset($data['nadiObgyn']) ? nl2br(e($data['nadiObgyn'])) : '-' !!} x/mnt</td>

            <td style="width: 25%; font-weight: bold;">Suhu</td>
            <td style="width: 25%;">: {!! isset($data['celciusObgyn']) ? nl2br(e($data['celciusObgyn'])) : '-' !!} °C</td>

            <td style="width: 25%; font-weight: bold;">Respirasi</td>
            <td style="width: 25%;">: {!! isset($data['rr']) ? nl2br(e($data['rr'])) : '-' !!} %</td>
        </tr>
        <tr>
            <td style="width: 20%;font-weight: bold" colspan="2">Perfomance Status</td>
            <td style="width: 80%" colspan="6">: {!! isset($data['perfomanceStatus']) ? nl2br(e($data['perfomanceStatus'])) : '-' !!}</td>
        </tr>

        {{-- <tr>
            <td style="width: 18%; font-weight: bold;">Suhu</td>
            <td style="width: 32%;">: {!! isset($data['celciusObgyn']) ? nl2br(e($data['celciusObgyn'])) : '-' !!}</td>

            <td style="width: 18%; font-weight: bold;">RR</td>
            <td style="width: 32%;">: {!! isset($data['rr']) ? nl2br(e($data['rr'])) : '-' !!}</td>
        </tr> --}}
    </table>

    <table width="100%" class="fnt" style="margin-top: 10px;">
            {{-- <tr>
                <td style="width: 200px; font-weight: bold;">Perfomance Status</td>
                <td>: {!! isset($data['perfomanceStatus']) ? nl2br(e($data['perfomanceStatus'])) : '-' !!}</td>
            </tr> --}}
        <tr>
            <td>A. Hasil PA</td>
        </tr>
    </table>

    <table width="100%" class="fnt" style="margin-left: 20px;">
        <tr>
            <td style="width: 200px; font-weight: bold;">Biopsi PA</td>
            <td style="width: 10px;">:</td>
            <td>{!! isset($data['biopsi']) ? nl2br(e($data['biopsi'])) : '-' !!}</td>
        </tr>

        <tr>
            <td style="width: 200px; font-weight: bold;">IHC dan Hormonal</td>
            <td style="width: 10px;">:</td>
            <td>{!! isset($data['ihc']) ? nl2br(e($data['ihc'])) : '-' !!}</td>
        </tr>
    </table>

    <table width="100%" class="fnt" style="margin-top: 10px;">
        <tr>
            <td>B. Hasil Laboratorium (dilampirkan)</td>
        </tr>
    </table>

    <table width="100%" class="fnt" style="margin-left: 20px;">
        <tr>
            <td style="width: 10px; font-weight: bold;">-</td>
            <td>DL</td>
        </tr>

        <tr>
            <td style="width: 10px; font-weight: bold;">-</td>
            <td>Kimia Darah (SGOT, SGPT, Albumin, Globulin, Bilirubin Total, Bilirubin Direk, BUN, Serum
                Creatinin, Uric Acid, LDH, UL, Elektrolit</td>
        </tr>

        <tr>
            <td style="width: 10px; font-weight: bold;">-</td>
            <td>HbS Ag, Anti HCV, Anti HIV</td>
        </tr>
    </table>

    <table width="100%" class="fnt" style="margin-top: 10px;">
        <tr>
            <td>C. Foto Rontgen/CT-Scan/USG</td>
        </tr>
    </table>

    <table width="100%" class="fnt" style="margin-top: 10px;">
        <tr>
            <td style="width: 200px; font-weight: bold;">Lain-Lain</td>
            <td style="width: 10px;">:</td>
            <td>{!! isset($data['lainDataPenunjang']) ? nl2br(e($data['lainDataPenunjang'])) : '-' !!}</td>
        </tr>
    </table>

    <table width="100%" class="fnt">
        <thead>
            <tr>
                <td style="font-weight: bold;">II. Obat Khusus Yang Akan Diberikan</td>
            </tr>
        </thead>
    </table>

    <table width="100%" class="fnt" style="margin-left: 20px;">
        <thead>
            <tr style="text-align: start;">
                <td style="width: 200px; font-weight: bold;">Nama Obat oral</td>
                <td style="width: 10px;">:</td>
                <td>{!! isset($data['namaobatOral']) ? nl2br(e($data['namaobatOral'])) : '-' !!}</td>
            </tr>

            <tr style="text-align: start;">
                <td style="width: 200px; font-weight: bold;">Dosis obat</td>
                <td style="width: 10px;">:</td>
                <td>{!! isset($data['dosisObat']) ? nl2br(e($data['dosisObat'])) : '-' !!}</td>
            </tr>

            <tr style="text-align: start;">
                <td style="width: 200px; font-weight: bold;">Lama Pemberian</td>
                <td style="width: 10px;">:</td>
                <td>{!! isset($data['lamaPemberian']) ? nl2br(e($data['lamaPemberian'])) : '-' !!}</td>
            </tr>
        </thead>
    </table>

    <table width="100%" class="fnt" style="margin-left: 20px;">
        <tr style="text-align: start;">
            <td style="width: 200px; font-weight: bold;">Obat terapi sistemik intravena, subcutan di tulis di halaman
                berikutnya.</td>
        </tr>
    </table>

    <table width="100%" class="fnt">
        <thead>
            <tr>
                <td style="font-weight: bold;">III. Alasan Pemberian :</td>
            </tr>
            <tr>
                <td>{!! isset($data['alasanPemberian']) ? nl2br(e($data['alasanPemberian'])) : '-' !!}</td>
            </tr>
        </thead>
    </table>
    <table width="100%" class="fnt">
        <thead>
            <tr>
                <td style="font-weight: bold;">Garut,
                    {{ isset($data['tanggal'])
                        ? \Carbon\Carbon::parse($data['tanggal'])->setTimezone('Asia/Jakarta')->format('d-m-Y')
                        : '-' }}
                </td>
            </tr>
        </thead>

    </table>

    {{-- {{dd($data['nip'])}} --}}

    <table width="100%" class="fnt" style="text-align: center;">
        <thead style="vertical-align: top;">
            <tr style="width: 50%;">
                <td style="font-weight: bold;">
                    <div><span>Dokter Penanggung Jawab Pasien,</span></div>
                    <img src="data:image/png;base64, {!! $tte !!}">

                </td>
                <td style="font-weight: bold;">
                    <div><span>Konsultan Hemato Onkologi Medik,</span></div>
                    @isset($data['konsultan']['label'])
                    <img src="data:image/png;base64, {!! $qrcode2 !!}">
                    @endisset
                </td>
            </tr>
            <tr style="width: 50%;">
                <td style="width: 50%;">
                    <span>{{ isset($data['dokterRawat'])
                        ? (is_array($data['dokterRawat'])
                            ? $data['dokterRawat']['label']
                            : $data['dokterRawat'])
                        : '-' }}
                    </span><br>

                    {{-- <span style="font-weight: bold;">
                        NIP :
                        @if (isset($data['nip']->nip))
                            {{ $data['nip']->nip }}
                        @else
                            {{ '-' }}
                        @endif
                    </span> --}}
                </td>

                <td style="width: 50%;">
                    <span>
                        {{ isset($data['konsultan'])
                            ? (is_array($data['konsultan'])
                                ? $data['konsultan']['label']
                                : $data['konsultan'])
                            : '-' }}
                        <br>
                    </span>
                    {{-- <span style="font-weight: bold;">
                        NIP :
                        @if (isset($data['nip']->nip))
                            {{ $data['nip']->nip }}
                        @else
                            {{ '-' }}
                        @endif
                    </span> --}}
                </td>
            </tr>
        </thead>

    </table>

    <table width="100%" class="fnt" style="text-align: center;">
        <thead>
            <tr style="width: 50%;">
                <td style="font-weight: bold;">
                    <div><span style="white-space: pre-line;">Mengetahui,
                        Wakil Direktur Pelayanan
                        RSUD Bali Mandara</span></div>
                        @isset($data['wakilDirektur']['label'])
                        <img src="data:image/png;base64, {!! $qrcode3 !!}">
                        @endisset
                </td>
            </tr>
            <tr style="width: 50%;">
                <td style="width: 50%;">
                    <span>
                        {{ isset($data['wakilDirektur'])
                            ? (is_array($data['wakilDirektur'])
                                ? $data['wakilDirektur']['label']
                                : $data['wakilDirektur'])
                            : '-' }}
                    </span> <br>
                    {{-- <span style="font-weight: bold;">
                        NIP :
                        @if (isset($data['nip']->nip))
                            {{ $data['nip']->nip }}
                        @else
                            {{ '-' }}
                        @endif
                    </span> --}}
                </td>
            </tr>
        </thead>

    </table>

</body>

</html>

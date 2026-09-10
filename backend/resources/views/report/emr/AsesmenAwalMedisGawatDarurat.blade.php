@extends('template.layout-emr')
@section('title', 'Asesmen Awal Medis Gawat Darurat')
@section('kode', 'RM.9AG/RAJAL/00')
@section('page-style')
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        .bold {
            font-weight: bold;
        }

        @page {
            margin: 5mm;
            size: A4;
        }

        table {
            border-collapse: collapse !important;
            width: 100%;
        }

        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: auto;
            page-break-after: avoid
        }

        .pd td {
            padding: 3px;
            text-align: center;
            font-size: 10pt;
        }

        .fnt {
            font-size: 9pt;
        }

        .fnt th {
            border: 1px solid black;
            border-bottom: none;
        }

        .fnt td {
            vertical-align: top;
            padding: 3px;
        }

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        .mid {
            text-align: center !important;
        }

        .border {
            border: 1px solid black;
        }

        .font {
            font-size: 10pt !important;
        }
    </style>
@endsection

@php
    function convertToWITA($utcTime) {
        if (empty($utcTime)) {
            return '-';
        }

        try {
            $date = new DateTime($utcTime, new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
            return $date->format('H:i');
        } catch (Exception $e) {
            return '-';
        }
    }
    function UTCtoID($utcTime) {
        if (empty($utcTime)) {
            return '-';
        }
        try {
            $date = new DateTime($utcTime, new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
            return $date->format('d-m-Y');
        } catch (Exception $e) {
            return '-';
        }
    }
    function isConvertedToRegularDate($date) {
        // Contoh: Cek format tanggal yang sudah dikonversi
        return preg_match('/\d{2}-\d{2}-\d{4}/', $date);
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

    function formatKU($data)
    {
        switch ($data) {
            case 1:
                return 'Baik';
                break;
            case 2:
                return 'Sedang';
                break;
            case 3:
                return 'Buruk';
                break;
            default:
                break;
        }
    }

    function formatAllo($data)
    {
        switch ($data) {
            case 1:
                return 'Suami/Istri';
                break;
            case 2:
                return 'Orang Tua';
                break;
            case 3:
                return 'Anak';
                break;
            case 4:
                return 'Pasien';
                break;
            case 5:
                return 'Lainnya';
                break;
            default:
                break;
        }
    }
@endphp

@section('content')
    <tr>
        <td>
            <table class="pd">
                <tr>
                    <td colspan="3" style="border-bottom:1px solid black">
                        <b>Status Pasien</b> : <span
                            style="color: {{ isset($data['Parameter_GawatDarurat']) && $data['Parameter_GawatDarurat'] == 'Gawat Darurat' ? 'red' : 'green' }}">{{ isset($data['Parameter_GawatDarurat']) ? $data['Parameter_GawatDarurat'] : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%;border-right: 1px solid black">
                        <b>Tanggal</b>
                        {{-- {{ isset($data['DtanggalForm']) ? ': ' . convertToRegularDate($data['DtanggalForm']) : '-' }} --}}
                        {{-- {{ isset($data['DtanggalForm']) ? ': ' . UTCtoID($data['DtanggalForm']) : '-' }} --}}
                        {{ isset($data['DtanggalForm'])
                            ? (isConvertedToRegularDate($data['DtanggalForm'])
                                ? ': ' . convertToRegularDate($data['DtanggalForm'])
                                : ': ' . UTCtoID($data['DtanggalForm']))
                            : '-'
                        }}

                    </td>
                    {{-- <td style="width: 33%;border-right: 1px solid black">
                        <b>Jam Kedatangan</b>
                        {{ isset($data['HjamKedatangan']) ? ': ' . convertToRegularTime($data['HjamKedatangan']) : '-' }}
                        WIB
                    </td>
                    <td style="width: 33%;">
                        <b>Jam Triage</b> {{ isset($data['HjamAW']) ? ': ' . convertToRegularTime($data['HjamAW']) : '-' }}
                        WIB
                    </td> --}}
                    <td style="width: 33%;border-right: 1px solid black">
                        <b>Jam Kedatangan</b>
                        {{ isset($data['HjamKedatangan']) ? ': ' . convertToWITA($data['HjamKedatangan']) : '-' }}
                        WIB
                    </td>
                    <td style="width: 33%;">
                        <b>Jam Triage</b> {{ isset($data['HjamAW']) ? ': ' . convertToWITA($data['HjamAW']) : '-' }}
                        WIB
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black">
            <table class="fnt">
                <tr>
                    <td>
                        <b>Alloanamnesis</b> : {{ isset($data['Select_Allo']) ? formatAllo($data['Select_Allo']) : '-' }}
                        {{ isset($data['TBLainnya_Allo']) ? ', ' . $data['TBLainnya_Allo'] : '' }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black">
            <table class="fnt">
                <tr>
                    <td style="font-size: 9.5pt" colspan="2">
                        <b>Anamnesis</b>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%">
                        <span>Keluhan Utama : </span>{{ isset($data['TAKeluhanUtama']) ? $data['TAKeluhanUtama'] : '-' }}
                    </td>
                    <td style="width: 50%">
                        <span>Riwayat Penyakit Dahulu :
                        </span>{{ isset($data['TARiwayatPenyakitDahulu']) ? $data['TARiwayatPenyakitDahulu'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%">
                        <span>Riwayat Penggunaan Obat :
                        </span>{{ isset($data['TARiwayatPenggunaanObat']) ? $data['TARiwayatPenggunaanObat'] : '-' }}
                    </td>
                    <td style="width: 50%">
                        <span>Riwayat Vaksin :
                        </span>{{ isset($data['TARiwayatVaksin']) ? $data['TARiwayatVaksin'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="width: 100%">
                        <span>Riwayat Penyakit Sekarang :
                        </span>{{ isset($data['TARPS']) ? $data['TARPS'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span>MOI : </span>{{ isset($data['TAMOI']) ? $data['TAMOI'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span>Riwayat Alergi : </span>{{ isset($data['isalergi']) ? $data['isalergi'] : '-' }}
                        {{ isset($data['alergi_tidak_diketahui']) ? ', ' . $data['alergi_tidak_diketahui'] : '' }}
                    </td>
                </tr>
                @if (isset($data['isalergi']) && $data['isalergi'] == 'YA')
                    <tr>
                        <td colspan="2">
                            <span>Jenis Alergi : </span>
                            {{ isset($data['CBAlergiObat']) ? $data['CBAlergiObat'] . ', ' . $data['TBAlergiObat'] : '' }}
                            {{ isset($data['CBAlergiMakanan']) ? $data['CBAlergiMakanan'] . ', ' . $data['TBAlergiMakanan'] : '' }}
                            {{ isset($data['CBAlergiLainnya']) ? $data['CBAlergiLainnya'] . ', ' . $data['TBAlergiLainnya'] : '' }}
                        </td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black">
            <table class="fnt">
                <tr>
                    <td style="font-size: 9.5pt" colspan="4">
                        <b>Pemeriksaan Fisik</b>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9.5pt" colspan="4">
                        <b><i>A. Tanda-tanda Vital</i></b>
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%">
                        Keadaan Umum : {{ isset($data['keadaanumum']) ? formatKU($data['keadaanumum']) : '-' }}
                    </td>
                    <td style="width: 25%">
                        GCS : E {{ isset($data['TBeGCS']) ? $data['TBeGCS'] : '-' }} V
                        {{ isset($data['TBvGCS']) ? $data['TBvGCS'] : '-' }} M
                        {{ isset($data['TBmGCS']) ? $data['TBmGCS'] : '-' }}</span>
                    </td>
                    <td style="width: 25%">
                        Tekanan Darah : {{ isset($data['TBtekananDarahTTV']) ? $data['TBtekananDarahTTV'] : '-' }} mmHg
                    </td>
                    <td style="width: 25%">
                        Nadi : {{ isset($data['TBNadiTTV']) ? $data['TBNadiTTV'] : '-' }} x/mnt
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%">
                        Respirasi : {{ isset($data['TBRespirasiTTV']) ? $data['TBRespirasiTTV'] : '-' }} mmHg
                    </td>
                    <td style="width: 25%">
                        Suhu : {{ isset($data['TBcelciusTTV']) ? $data['TBcelciusTTV'] : '-' }} °C
                    </td>
                    <td style="width: 50%" colspan="2">
                        SaO2 : {{ isset($data['TBnsao2TTV']) ? $data['TBnsao2TTV'] : '-' }} %<br>
                        Keterangan : {{ isset($data['keteranganSAO2']) ? $data['keteranganSAO2'] : '-' }}<br>
                        Device :
                        @if (isset($data['device']))
                            NC : {{ isset($d['NC']) ? $d['NC'] : '-' }} I/m, SM : {{ isset($d['SM']) ? $d['SM'] : '-' }} I/m, NRM : {{ isset($d['NRM']) ? $d['NRM'] : '-' }} I/m, CPAP : {{ isset($d['cpap']) ? $d['cpap'] : '-' }} Venti : {{ isset($d['venti']) ? $d['venti'] : '-' }}
                        @else
                            <span>-</span>
                        @endif
                    </td>
                </tr>
                @if (isset($data['device']))
                    <tr>
                        <td colspan="4">
                            <table>
                                <td style="width:20%">
                                    NC : {{ isset($data['NC']) ? $data['NC'] : '-' }} I/m
                                </td>
                                <td style="width:20%">
                                    SM : {{ isset($data['SM']) ? $data['SM'] : '-' }} I/m
                                </td>
                                <td style="width:20%">
                                    NRM : {{ isset($data['NRM']) ? $data['NRM'] : '-' }} I/m
                                </td>
                                <td style="width:20%">
                                    CPAP : {{ isset($data['cpap']) ? $data['cpap'] : '-' }}
                                </td>
                                <td style="width:20%">
                                    VENTI : {{ isset($data['venti']) ? $data['venti'] : '-' }}
                                </td>
                            </table>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td style="font-size: 9.5pt" colspan="4">
                        <b><i>B. Status Generalis</i></b>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Kepala : {{ isset($data['TAKepalaSG']) ? $data['TAKepalaSG'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Mata :
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;">
                        Anemis : {{ isset($data['TBAnemisMata']) ? $data['TBAnemisMata'] : '-' }}
                    </td>
                    <td style="width: 25%;">
                        Ikterus : {{ isset($data['TBIkterusMata']) ? $data['TBIkterusMata'] : '-' }}
                    </td>
                    <td style="width: 25%;">
                        Refleks Pupil : {{ isset($data['TBRefleksPupilMata']) ? $data['TBRefleksPupilMata'] : '-' }}
                    </td>
                    <td style="width: 25%;">
                        Oedema Palpebrae :
                        {{ isset($data['TBOedemaPalpebraeMata']) ? $data['TBOedemaPalpebraeMata'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        THT :
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;">
                        Tonsil : {{ isset($data['TBTonsilTHT']) ? $data['TBTonsilTHT'] : '-' }}
                    </td>
                    <td style="width: 25%;">
                        Pharing : {{ isset($data['TBPharingTHT']) ? $data['TBPharingTHT'] : '-' }}
                    </td>
                    <td style="width: 25%;">
                        Telinga : {{ isset($data['TBTelingaTHT']) ? $data['TBTelingaTHT'] : '-' }}
                    </td>
                    <td style="width: 25%;">
                        Hidung : {{ isset($data['TBHidungTHT']) ? $data['TBHidungTHT'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;">
                        Bibir : {{ isset($data['TBBibirTHT']) ? $data['TBBibirTHT'] : '-' }}
                    </td>
                    <td style="width: 25%;" colspan="3">
                        Lain-lain : {{ isset($data['TBLainnyaTHT']) ? $data['TBLainnyaTHT'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Leher :
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;">
                        JVP : {{ isset($data['TBJVPLeher']) ? $data['TBJVPLeher'] : '-' }}
                    </td>
                    <td style="width: 25%;">
                        Pembesaran Kelenjar :
                        {{ isset($data['TBPembesaranKelenjarLeher']) ? $data['TBPembesaranKelenjarLeher'] : '-' }}
                    </td>
                    <td style="width: 25%;" colspan="2">
                        Kaku Duduk : {{ isset($data['CBKakuKudukLeher']) ? $data['CBKakuKudukLeher'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Thoraks : {{ isset($data['CBSimetrisThoraks']) ? $data['CBSimetrisThoraks'] : '' }}
                        {{ isset($data['CBAsimetrisThoraks']) ? $data['CBAsimetrisThoraks'] : '' }}
                        {{ isset($data['TBSimetrisORAsimetrisThoraks']) ? ', ' . $data['TBSimetrisORAsimetrisThoraks'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Retraksi : {{ isset($data['TBRetraksiThoraks']) ? $data['TBRetraksiThoraks'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Cor :
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;">
                        S1,S2 : {{ isset($data['TBS1S2Cor']) ? $data['TBS1S2Cor'] : '-' }}
                        {{ isset($data['CBRegulerCor']) ? ', ' . $data['CBRegulerCor'] : '' }}
                        {{ isset($data['CBIregulerCor']) ? ', ' . $data['CBIregulerCor'] : '' }}
                    </td>
                    <td style="width: 25%;">
                        Murmur : {{ isset($data['TBMurmurCor']) ? $data['TBMurmurCor'] : '-' }}
                    </td>
                    <td style="width: 25%;" colspan="2">
                        Lain-lain : {{ isset($data['TBLainLainCor']) ? $data['TBLainLainCor'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Pulmo :
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;">
                        Ronchi : {{ isset($data['TBRonchiPulmo']) ? $data['TBRonchiPulmo'] : '-' }}
                    </td>
                    <td style="width: 25%;">
                        Wheezing : {{ isset($data['TBWheezingPulmo']) ? $data['TBWheezingPulmo'] : '-' }}
                    </td>
                    <td style="width: 25%;">
                        Vesikuler : {{ isset($data['TBVesikulerPulmo']) ? $data['TBVesikulerPulmo'] : '-' }}
                    </td>
                    <td style="width: 25%;">
                        Lain-lain : {{ isset($data['TBLainnyaPulmo']) ? $data['TBLainnyaPulmo'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Abdomen : {{ isset($data['CBSouffleAbdomen']) ? $data['CBSouffleAbdomen'] : '' }}
                        {{ isset($data['CBDistensiAbdomen']) ? ', ' . $data['CBDistensiAbdomen'] : '' }}
                        {{ isset($data['CBMeteorismusAbdomen']) ? ', ' . $data['CBMeteorismusAbdomen'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Peristaltik : {{ isset($data['CBNormalPeristaltik']) ? $data['CBNormalPeristaltik'] : '' }}
                        {{ isset($data['CBMeningkatPeristaltik']) ? $data['CBMeningkatPeristaltik'] : '' }}
                        {{ isset($data['CBMenurunPeristaltik']) ? $data['CBMenurunPeristaltik'] : '' }}
                        {{ isset($data['CBAscitesPeristaltik']) ? $data['CBAscitesPeristaltik'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;">
                        Nyeri tekan lokasi :
                        {{ isset($data['TBNyeriTekanLokasiPeristaltik']) ? $data['TBNyeriTekanLokasiPeristaltik'] : '-' }}
                    </td>
                    <td style="width: 25%;">
                        Hepar : {{ isset($data['TBHeparPeristaltik']) ? $data['TBHeparPeristaltik'] : '-' }}
                    </td>
                    <td style="width: 25%;" colspan="2">
                        Lien : {{ isset($data['TBLienPeristaltik']) ? $data['TBLienPeristaltik'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Extremitas : {{ isset($data['CBHangatExtremitas']) ? $data['CBHangatExtremitas'] : '' }}
                        {{ isset($data['CBDinginExtremitas']) ? $data['CBDinginExtremitas'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;">
                        Odema : {{ isset($data['TBOdemaExtremitas']) ? $data['TBOdemaExtremitas'] : '-' }}
                    </td>
                    <td style="width: 25%;" colspan="3">
                        Lain-lain : {{ isset($data['TBLainlainExtremitas']) ? $data['TBLainlainExtremitas'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Lain-lain : {{ isset($data['TBLainlainSG']) ? $data['TBLainlainSG'] : '-' }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black">
            <table class="fnt">
                <tr>
                    <th style="text-align: center">Status Lokalis</th>
                </tr>
                <tr>
                    <th>
                        <div
                            style="position: relative; width: 700px; height: 460px; background-image: url('img/outline-human-body.jpg'); background-size: cover;">
                            <img src="{{ isset($data['GambarTubuh']) ? $data['GambarTubuh'] : '' }}" height="460"
                                width="700">
                        </div>
                        <span
                            style="font-weight: lighter">{{ isset($data['TAStatusLokalis']) ? $data['TAStatusLokalis'] : '-' }}</span>
                    </th>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black;font-size:9pt;padding:3px">
            Resume Pemeriksaan Penunjang : {{ isset($data['TArpp']) ? $data['TArpp'] : '-' }}
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black;font-size:9pt;padding:3px">
            Diagnosis : {{ isset($data['TADiagnosis']) ? $data['TADiagnosis'] : '-' }}
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black;text-align: center">
            <span style="font-size: 9pt;padding:3px;font-weight:bold">Rencana Kerja Dokter (Plan Of Care)</span>
            <table class="fnt">
                <tr>
                    <th style="width: 33%;border-bottom: 1px solid black;background-color: cornflowerblue">Daftar Masalah
                    </th>
                    <th style="width: 33%;border-bottom: 1px solid black;background-color: cornflowerblue">Rencana
                        Intervensi</th>
                    <th style="width: 33%;border-bottom: 1px solid black;background-color: cornflowerblue">Target<br>
                        (Kondisi Yang Diharapkan)</th>
                </tr>
                @if (isset($data['details']))
                    @foreach ($data['details'] as $d)
                        <tr>
                            <td style="width: 33%;border-right:1px solid black">
                                {{ isset($d['TAdaftarMasalah']) ? $d['TAdaftarMasalah'] : '-' }}</td>
                            <td style="width: 33%;border-right:1px solid black">
                                {{ isset($d['TArencanaIntervensi']) ? $d['TArencanaIntervensi'] : '-' }}</td>
                            <td style="width: 33%;">{{ isset($d['TAtarget']) ? $d['TAtarget'] : '-' }}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black;font-size:9pt;padding:3px">
            Intruksi : {{ isset($data['TAInstruksi']) ? $data['TAInstruksi'] : '-' }}
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black;font-size:9pt;padding:3px">
            <span style="font-weight: bold">Kondisi Keluar RS</span>
            <table class="fnt">
                <tr>
                    <td colspan="4">
                        Riwayat Keluar RS : {{ isset($data['riwayatkeluar']) ? $data['riwayatkeluar'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Status Keluar RS :
                        @if (isset($data['statuskeluar']))
                            @switch($data['statuskeluar'])
                                @case('Belum Keluar RS')
                                    Belum Keluar RS
                                @break

                                @case('Diijinkan Pulang')
                                    Diijinkan Pulang, <br> Perlu Kontrol: {{ isset($data['perlukontrol']) ? $data['perlukontrol'] : '-' }}
                                @break

                                @case('Pulang Paksa')
                                    Pulang Paksa,{{ isset($data['perlukontrol']) ? $data['perlukontrol'] : '-' }}
                                @break

                                @case('Dirujuk')
                                    Dirujuk :
                                    {{ isset($data['tujuan_skrs']) && isset($data['alasan_skrs']) ? 'Tujuan : ' . $data['tujuan_skrs'] . ', ' . 'Alasan : ' . $data['alasan_skrs'] : '-' }}
                                @break

                                @default
                            @endswitch
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black;font-size:9pt;padding:3px" align="center">
            {{-- Garut, {{ isset($data['DtanggalForm']) ? convertToRegularDate($data['DtanggalForm']) : '-' }} --}}
            Garut, {{ isset($data['DtanggalForm'])
                ? (isConvertedToRegularDate($data['DtanggalForm'])
                    ? ': ' . convertToRegularDate($data['DtanggalForm'])
                    : ': ' . UTCtoID($data['DtanggalForm']))
                : '-'
            }}
            <br><img style="width: 140px;height: 140px;" src="data:image/png;base64, {!! $qrcode !!}"><br>
            {{ $data['registrasi']['dokter'] }}
        </td>
    </tr>
@endsection

<?php // {{ isset($data['']) ? $data[''] : '-' }}
?>

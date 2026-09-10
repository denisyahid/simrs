@extends('template.layout-emr-kop-surat')
@section('title')
    @if ($cekWargaNegaraWNA)
        Print Physical Medicine and Rehabilitation Form
    @else
        Cetak Formulir Kedokteran Fisik dan Rehabilitasi
    @endif
@endsection
@section('kode', 'RW.1W/SK/00')
@section('page-style')
    <style>
        body,
        table,
        td {
            font-family: 'Open Sans', sans-serif !important;
        }

        .center {
            vertical-align: middle;
            text-align: center
        }

        .border {
            border: 1px solid black;
        }

        .table {
            width: 100%;
            border-collapse: collapse
        }

        .font {
            font-size: 10pt
        }

        .subJudul {
            vertical-align: middle;
            width: 33%;
            padding-left: 15px
        }

        .isi {
            width: 67%;
            vertical-align: middle;
        }
    </style>
@endsection

@php
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

@section('content')
    @if ($cekWargaNegaraWNA == true)
        <tr>
            <td align="center">
                <div style="font-size: 13pt; font-weight: bold;padding:8px">
                    PHYSICAL MEDICINE AND REHABILITATION FORM<br>
                    <span style="font-size:13pt">NUMBER : </span>
                </div>
            </td>
        </tr>
    @else
        <tr>
            <td align="center">
                <div style="font-size: 13pt; font-weight: bold;padding:8px">
                    FORMULIR RAWAT JALAN<br>
                    LAYANAN KEDOKTERAN FISIK DAN REHABILITASI
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="padding: 7px" class="font">
                    <table class="table">
                        <tr>
                            <td colspan="2">I. Diisi oleh Pasien/Peserta</td>
                        </tr>
                        <tr>
                            <td class="subJudul">No. Rekam Medis</td>
                            <td class="isi">
                                : {{ isset($data['TBNomorRM']) ? $data['TBNomorRM'] : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Nama Pasien</td>
                            <td class="isi">
                                : {{ isset($data['TBNamaPasien']) ? $data['TBNamaPasien'] : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Tanggal Lahir</td>
                            <td class="isi">
                                : {{ isset($data['DTanggalLahir']) ? convertToRegularDate($data['DTanggalLahir']) : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Alamat</td>
                            <td class="isi">
                                : {{ isset($data['TAAlamatPasien']) ? $data['TAAlamatPasien'] : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">No.Telp/Hp</td>
                            <td class="isi">
                                : {{ isset($data['TBNomorTeleponPasien']) ? $data['TBNomorTeleponPasien'] : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul" style="vertical-align: top">Hubungan dengan tertanggung</td>
                            <td class="isi">
                                <table class="table">
                                    <tr>
                                        <td style="width: 25%">
                                            <input type="checkbox"
                                                {{ isset($data['MHubunganDenganTertanggung']) && $data['MHubunganDenganTertanggung'] == 1 ? 'checked' : '' }} />
                                            <span style="font-size: 9pt;">Pasien Sendiri</span>
                                        </td>
                                        <td style="width: 25%">
                                            <input type="checkbox"
                                                {{ isset($data['MHubunganDenganTertanggung']) && $data['MHubunganDenganTertanggung'] == 2 ? 'checked' : '' }} />
                                            <span style="font-size: 9pt;">Suami/Istri</span>
                                        </td>
                                        <td style="width: 25%">
                                            <input type="checkbox"
                                                {{ isset($data['MHubunganDenganTertanggung']) && $data['MHubunganDenganTertanggung'] == 3 ? 'checked' : '' }} />
                                            <span style="font-size: 9pt;">Anak</span>
                                        </td>
                                        <td style="width: 25%">
                                            <input type="checkbox"
                                                {{ isset($data['MHubunganDenganTertanggung']) && $data['MHubunganDenganTertanggung'] == 4 ? 'checked' : '' }} />
                                            <span style="font-size: 9pt;">Orang tua</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <input type="checkbox"
                                                {{ isset($data['MHubunganDenganTertanggung']) && $data['MHubunganDenganTertanggung'] == 5 ? 'checked' : '' }} />
                                            <span style="font-size: 9pt;">Lainnya :
                                                <u>{{ isset($data['TBLainlainHDT']) ? $data['TBLainlainHDT'] : '-' }}</u></span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="padding: 7px" class="font">
                    <table class="table">
                        <tr>
                            <td colspan="2">II. Diisi oleh Dokter Sp.KFR</td>
                        </tr>
                        <tr>
                            <td class="subJudul">Tanggal Pelayanan</td>
                            <td class="isi">
                                :
                                {{ isset($data['DTanggalPelayanan']) ? convertToRegularDate($data['DTanggalPelayanan']) : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Anamnesa</td>
                            <td class="isi">
                                : {{ isset($data['TAAnamnesa']) ? $data['TAAnamnesa'] : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Pemeriksaan Fisik dan Uji Fungsi</td>
                            <td class="isi">
                                :
                                {{ isset($data['TAPemeriksaanFisikDanUjiFungsi']) ? $data['TAPemeriksaanFisikDanUjiFungsi'] : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Diagnosis Medis (ICD 10)</td>
                            <td class="isi">
                                : {{ isset($data['TBDiagnosisMedis']) ? $data['TBDiagnosisMedis'] : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Diagnosis Fungsi (ICD 10)</td>
                            <td class="isi">
                                : {{ isset($data['TBDiagnosisFungsi']) ? $data['TBDiagnosisFungsi'] : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Pemeriksaan Penunjang</td>
                            <td class="isi">
                                : {{ isset($data['TAPemeriksaanPenunjang']) ? $data['TAPemeriksaanPenunjang'] : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Tata Laksana KFR (ICD9CM)</td>
                            <td class="isi">
                                : {{ isset($data['TATataLaksanaKFR']) ? $data['TATataLaksanaKFR'] : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Anjuran</td>
                            <td class="isi">
                                : {{-- : {{ isset($data['TBAnjuran']) ? $data['TBAnjuran'] : '-' }} --}}
                                @if (isset($data['anjuranPerminggu']))
                                    {{ $data['anjuranPerminggu'] }}
                                @elseif (isset($data['anjuranProgram']))
                                    {{ $data['anjuranProgram'] }}
                                @elseif (isset($data['anjuranLain']))
                                    {{ $data['textLainnya1'] }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Evaluasi</td>
                            <td class="isi">
                                : {{-- {{ isset($data['TBEvaluasi']) ? $data['TBEvaluasi'] : '-' }} --}}
                                @if (isset($data['evaluasiMinggu']))
                                    {{ $data['evaluasiMinggu'] }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul">Suspek Penyakit Akibat Kerja</td>
                            <td class="isi">
                                : <input type="checkbox"
                                    {{ isset($data['CBSuspekPenyakit']) && $data['CBSuspekPenyakit'] == 'Ya' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;">Ya
                                    (<u>{{ isset($data['TBSuspekPenyakit']) ? $data['TBSuspekPenyakit'] : '-' }}</u>)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="subJudul"></td>
                            <td class="isi">
                                &nbsp; <input type="checkbox"
                                    {{ isset($data['CBSuspekPenyakit']) && $data['CBSuspekPenyakit'] == 'Tidak' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;">Tidak</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding: 7px;padding-top: 10px">
                <table class="table">
                    <tr>
                        <td class="font" style="width: 50%;text-align: center;vertical-align: top">
                            <p>Tanda tangan pasien</p>
                            <br>
                            <img src="data:image/jpeg;base64,{{ $qrcode2 }}" width="60px" border="0" style="margin-top: 0px; margin-bottom: 20px;">
                            <br>
                            {{ isset($data['TBNamaPasien']) ? $data['TBNamaPasien'] : '-' }}
                        </td>
                        <td class="font" style="width: 50%;text-align: center;vertical-align: top">
                            {{ date('j-F-Y', strtotime($data['registrasi']['tglregistrasi'])) }}
                            <br><b>Dokter Pemeriksa</b>
                            <br>
                            <img src="data:image/png;base64, {!! $tte !!}" style="width: 100px;height: 100px">
                            <br>
                            @if (is_array($data['DDDokter']) && array_key_exists('label', $data['DDDokter']))
                                {{ $data['DDDokter']['label'] ?? '-' }}
                            @else
                                {{ $data['DDDokter'] ?? '-' }}
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif
@endsection

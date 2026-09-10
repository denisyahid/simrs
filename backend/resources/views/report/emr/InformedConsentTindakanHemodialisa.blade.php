<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMR - Formulir Persetujuan Tindakan Hemodialisa  </title>
    <style>
        @media print {
            td.merah {
                background-color: #d54242 !important;
                -webkit-print-color-adjust: exact;
            }

            td.kuning {
                background-color: #c5d542 !important;
                -webkit-print-color-adjust: exact;
            }

            td.hijau {
                background-color: #42d55b !important;
                -webkit-print-color-adjust: exact;
            }

            td.hitam {
                background-color: #000000 !important;
                -webkit-print-color-adjust: exact;
            }
        }

        body {
            page-break-inside: avoid !important;
        }

        /* Ensure Page 5 starts on a new page */
        .page-break {
            page-break-before: always;
        }

        /*@media print {*/
        /*    body {margin:0}*/
        /*}*/

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        input[type=checkbox] {
            margin-bottom: -5px;
        }
        .double-border {

            border: 4px solid #000;

        }

        .double-border:before {

            border: 4px solid #fff;

        }

        .box {
            border: 2px solid black;
            /*border-radius: 6px;*/
        }

        .mt-5 {
            margin-top: 5px;
        }

        .garis6 td {
            padding: 3px;
        }

        .padding-y {
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .padding-x {
            padding-right: 6px;
            padding-left: 6px;
        }

        .vt {
            vertical-align: top;
        }

        .ws {
            white-space: pre-line;
        }

        .vc {
            vertical-align: center;
        }

        .nowrap {
            white-space: nowrap; /* Prevents small fields from wrapping */
        }

        .wrap {
            word-wrap: break-word;
            white-space: normal;
        }

        .bold {
            font-weight: bold;
        }

        .f-s-15 {
            font-size: 12px;
        }

        .half {
            width: 50%;
        }

        .top-height {
            height: 50px;
            vertical-align: text-top;
            width: 15%;
        }

        .logo {
            font-family: DejaVu Sans !important;
        }

        .text-top {
            vertical-align: text-top;
        }

        .kotak {
            width: 50px;
            height: 20px;
        }

        .merah {
            background-color: #d54242 !important;
        }

        .kuning {
            background-color: #c5d542 !important;
        }

        .hijau {
            background-color: #42d55b !important;
        }

        .hitam {
            background-color: #000000 !important;
        }

        .bmerah {
            border: thin solid #d54242;
        }

        .bkuning {
            border: thin solid #c5d542;
        }

        .bhijau {
            border: thin solid #42d55b;
        }

        .bhitam {
            border: thin solid #000000;
        }

        .border-lr {
            border-collapse: collapse;
        }

        .border-lr td {
            border: thin solid #000;
        }

        .border-doang {
            border-collapse: collapse;
            border: thin solid #000;
            border-top: none;
        }

        .border-doang td {
            padding: 5px;
        }

        .bg-gray {
            background-color: #DCDCDC;
        }

        .bg-blue {
            background-color: #91CEDE;
        }

        .tc {
            text-align: center;
        }
        .tr {
            text-align: right;
        }
        .tl {
            text-align: left;
        }

        .no-border {
            border-top: none;
            border-bottom: none;
        }

        .no-border-bottom {
            border-bottom: none;
        }

        .no-border-top {
            border-top: none;
        }

        .font {
            font-size: 8pt;
        }

        .font-2 {
            font-size: 8pt;
        }

        .font-3 {
            font-size: 6pt;
        }

    </style>

    @stack('style')

</head>

<body class="A4" style="font-family:DejaVu Sans, sans-serif;;height: auto" onload="window.print()">
    <section class="sheet padding-10mm" style="font-family:DejaVu Sans, sans-serif;;height: auto;overflow: hidden;">
        @php
            // $tglv_pemindahan = isset($data['tanggal']) ? date('d-m-Y H:i', strtotime($data['tanggal'])) : "";
            // $tglv_prosedur = isset($data['TanggalProsedur']) ? date('d-m-Y h:i', strtotime($data['TanggalProsedur'])) : "";
            // $tglv_observasiTerakhir = isset($data['observasiTerakhir']) ? date('h:i', strtotime($data['observasiTerakhir'])) : "";
            // $tglv_pemasanganKateter = isset($data['tanggalPemasangan']) ? date('d-m-Y', strtotime($data['tanggalPemasangan'])) : "";
            // $tglv_pemasanganLP = isset($data['tglPemasangan_LP']) ? date('d-m-Y', strtotime($data['tglPemasangan_LP'])) : "";
            // $tglv_kunjungan = isset($data['tanggalKunjunganPasien']) ? date('d-m-Y H:i', strtotime($data['tanggalKunjunganPasien'])) : "";
        @endphp
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <thead>
                <tr>
                    <td width="100%" style="text-align:right" colspan=2>
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr class="bg-blue">
                                <td>
                                    <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                    <td width="50%" style="font-size: 14px; text-align: right;">RM.2/CONSENT/01</td>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <!-- <td width="60%"></td> -->
                </tr>
            </thead>
            <tr>
                <td colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td width="10%" style="padding: 5px; border-right: 1px solid black;">
                                <img src="{{ 'img/logo-rs.png' }}" style="width: 60px;">
                            </td>
                            <td width="90%" style="text-align: center; border-right: 1px solid black;">
                                <b>
                                    <span style="font-size: 18px; white-space: pre-line;">PERSETUJUAN TINDAKAN KEDOKTERAN
                                </b>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="1">
                        <tr>
                            <td class="tc bold" style="font-size: 14px;">
                                PEMBERIAN INFORMASI
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="1">
                        <tr>
                            <td width="30%" class="vc">
                                <div><span>Dokter Pelaksana TIndakan</span></div>
                            </td>
                            <td width="70%" class="vc">
                                {{ isset($data['dokterTindakan']['label']) ? $data['dokterTindakan']['label'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="1">
                        <tr>
                            <td width="30%" class="vc">
                                <div><span>Pemberi Informasi</span></div>
                            </td>
                            <td width="70%" class="vc">
                                {{ isset($data['pemberiInformasi']['label']) ? $data['pemberiInformasi']['label'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="1">
                        <tr>
                            <td width="30%" class="vc">
                                <div><span>Penerima Informasi / Pemberi Persetujuan*</span></div>
                            </td>
                            <td width="70%" class="vc">
                                {{ isset($data['bahasaPasien']) ? $data['bahasaPasien'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="1">
                        <tr>
                            <td width="5%" class="tc">
                                NO
                            </td>
                            <td width="25%" class="vc tc">
                                <div>
                                    <span>
                                        JENIS INFORMASI
                                    </span>
                                </div>
                            </td>
                            <td width="65%" class="vc tc">
                                <div>
                                    <span>
                                        ISI INFORMASI
                                    </span>
                                </div>
                            </td>
                            <td width="15%" class="vc tc">
                                <div>
                                    <span>
                                        Tanda (<input type="checkbox" checked/>)
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @php
                            $daftarInformasi = [
                                [
                                    "no" => 1,
                                    "Kriteria" => "Diagnosis (WD & DD)",
                                    "keterangan" => [
                                        [
                                            "model" => "isiDiagnosa",
                                            "type"  => "textArea"
                                        ]
                                    ],
                                    "pilihan" => [
                                        [
                                            "label" => "",
                                            "model" => "AdaDiagnosa"
                                        ]
                                    ],
                                ],
                                [
                                    "no" => 2,
                                    "Kriteria" => "Dasar Diagnosis",
                                    "keterangan" => [
                                        [
                                            "model" => "isiDasar",
                                            "type"  => "textArea"
                                        ]
                                    ],
                                    "pilihan" => [
                                        [
                                            "label" => "",
                                            "model" => "adaDasarDiagnosa"
                                        ]
                                    ],
                                ],
                                [
                                    "no" => 3,
                                    "Kriteria" => "Tindakan Kedokteran",
                                    "keterangan" => [
                                        [
                                            "model" => "isiMedis",
                                            "type"  => "textArea"
                                        ]
                                    ],
                                    "pilihan" => [
                                        [
                                            "label" => "",
                                            "model" => "adaMedis"
                                        ]
                                    ],
                                ],
                                [
                                    "no" => 4,
                                    "Kriteria" => "Indikasi Tindakan",
                                    "keterangan" => [
                                        [
                                            "model" => "isiIndikasi",
                                            "type"  => "textArea"
                                        ]
                                    ],
                                    "pilihan" => [
                                        [
                                            "label" => "",
                                            "model" => "AdaIndikasi"
                                        ]
                                    ],
                                ],
                                [
                                    "no" => 5,
                                    "Kriteria" => "Tata Cara",
                                    "keterangan" => [
                                        [
                                            "model" => "isiTatacara",
                                            "type"  => "textArea"
                                        ]
                                    ],
                                    "pilihan" => [
                                        [
                                            "label" => "",
                                            "model" => "adaTataCara"
                                        ]
                                    ],
                                ],
                                [
                                    "no" => 6,
                                    "Kriteria" => "Tujuan",
                                    "keterangan" => [
                                        [
                                            "model" => "isiTujuan",
                                            "type"  => "textArea"
                                        ]
                                    ],
                                    "pilihan" => [
                                        [
                                            "label" => "",
                                            "model" => "AdaTujuan"
                                        ]
                                    ],
                                ],
                                [
                                    "no" => 7,
                                    "Kriteria" => "Risiko",
                                    "keterangan" => [
                                        [
                                            "model" => "isiRisiko",
                                            "type"  => "textArea"
                                        ]
                                    ],
                                    "pilihan" => [
                                        [
                                            "label" => "",
                                            "model" => "adaRisiko"
                                        ]
                                    ],
                                ],
                                [
                                    "no" => 8,
                                    "Kriteria" => "Komplikasi",
                                    "keterangan" => [
                                        [
                                            "model" => "isiKomplikasi",
                                            "type"  => "textArea"
                                        ]
                                    ],
                                    "pilihan" => [
                                        [
                                            "label" => "",
                                            "model" => "adaKomplikasi"
                                        ]
                                    ],
                                ],
                                [
                                    "no" => 9,
                                    "Kriteria" => "Prognosis",
                                    "keterangan" => [
                                        [
                                            "model" => "isiPrognosis",
                                            "type"  => "textArea"
                                        ]
                                    ],
                                    "pilihan" => [
                                        [
                                            "label" => "",
                                            "model" => "adaPrognosis"
                                        ]
                                    ],
                                ],
                                [
                                    "no" => 10,
                                    "Kriteria" => "Alternatif & Risiko",
                                    "keterangan" => [
                                        [
                                            "model" => "isiAlternatif",
                                            "type"  => "textArea"
                                        ]
                                    ],
                                    "pilihan" => [
                                        [
                                            "label" => "",
                                            "model" => "adaAlternatif"
                                        ]
                                    ],
                                ],
                                [
                                    "no" => '',
                                    "Kriteria" => "Lain - Lain",
                                    "keterangan" => [
                                        [
                                            "model" => "isiLainnya",
                                            "type"  => "textArea"
                                        ]
                                    ],
                                    "pilihan" => [
                                        [
                                            "label" => "",
                                            "model" => "adaLainnya"
                                        ]
                                    ],
                                ],
                            ];
                        @endphp
                        @foreach ($daftarInformasi as $item)
                            <tr>
                                <td>
                                    {{ $item['no'] }}
                                </td>
                                <td>
                                    {!! $item['Kriteria'] !!}
                                </td>
                                <td>
                                    @foreach ($item['keterangan'] as $ket)
                                        {{ isset($data[$ket['model']]) ? $data[$ket['model']] : '' }}
                                    @endforeach
                                </td>
                                <td class="tc">
                                    @foreach ($item['pilihan'] as $pil)
                                        <input type="checkbox"
                                            {{ isset($data[$pil['model']]) ? 'checked' : '' }} />
                                        <span>{{ $pil['label'] }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="3">
                                    <span style="white-space: pre-line;">Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar dan
                                        jelas dan memberikan kesempatan untuk bertanya dan/atau berdiskusi</span>
                                </td>
                                <td class="vt tc">
                                    <div>
                                        <span>Tanda Tangan</span>
                                    </div>
                                    <div>
                                        @isset($data['namaPegawai']['label'])
                                            <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(50)->generate($data['namaPegawai']['label'] ?? '')) }}"
                                        @endisset
                                    </div>
                                    <div>
                                        <span style="font-size: 9px;">
                                            {{ isset($data['namaPegawai']['label']) ? $data['namaPegawai']['label'] : '' }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span style="white-space: pre-line;">Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas yang
                                        saya beri tanda/paraf di kolom kanannya, dan telah memahaminya</span>
                                </td>
                                <td class="vc tc">
                                    <div>
                                        <span style="white-space: pre-line;">Tanda tangan</span>
                                    </div>
                                    <div>
                                        @if (isset($data['tandaTanganWali1']))
                                            <img width="50px" src="{!! $data['tandaTanganWali1'] !!}" alt="TTDWali1">
                                            <br />
                                            <span style="font-size: 9px;">{{ isset($data['namaTTD']) ? $data['namaTTD'] : '' }}</span>
                                        @elseif(isset($data['tanganTanganWali2']))
                                            <img width="50px" src="{!! $data['tanganTanganWali2'] !!}" alt="TTDWali2">
                                            <br />
                                            <span style="font-size: 9px;">{{ isset($data['namaWali']) ? $data['namaWali'] : '' }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="tl">
                                    <span style="white-space: pre-line; font-size: 9px;"><b><i>*Bila Pasien tidak kompeten atau tidak mau menerima informasi, maka penerima informasi adalah Wali atau Keluarga terdekat</i></b></span>
                                </td>
                            </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td class="tc">
                                <span>
                                    <b>PERSETUJUAN TINDAKAN KEDOKTERAN</b>
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border ">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td class="tl">
                                <div>
                                    <span>Yang bertandatangan di bawah ini, Saya, Nama</span> <span>{{ isset($data['nama']) ? $data['nama'] : '' }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tl">
                                <div>
                                    <span>, Umur </span> <span>{{ isset($data['tanggalLahirWali']) ? \Carbon\Carbon::parse($data['tanggalLahirWali'])->age : '    ' }} Tahun, </span> <span>{{ isset($data['jenisKelamin']) ? $data['jenisKelamin'] : '' }} </span> <span>, Alamat {{ isset($data['alamat']) ? $data['alamat'] : '' }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tl">
                                <div>
                                    <span>dengan ini menyatakan Persetujuan untuk dilakukannya tindakan </span> <span>{{ isset($data['tindakan']) ? $data['tindakan'] : '' }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tl">
                                <div>
                                    <span>terhadap Saya / {{ isset($data['hubunganPenjamin']['label']) ? $data['hubunganPenjamin']['label'] : '         ' }}</span> <span>Saya* Bernama {{ isset($data['namaPasien']) ? $data['namaPasien'] : '           ' }},</span> <span>Umur </span> <span>{{ isset($data['tanggalLahirPasien']) ? \Carbon\Carbon::parse($data['tanggalLahirPasien'])->age : '    ' }} Tahun, </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tl">
                                <div>
                                    <span>{{ isset($data['jenisKelaminPasien']) ? $data['jenisKelaminPasien'] : '          ' }}*,</span> <span>Alamat {{ isset($data['alamatPasien']) ? $data['alamatPasien'] : '' }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tl">
                                <div>
                                    <span style="white-space: pre-line">Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada
                                        saya, termasuk risiko dan komplikasi yang mungkin timbul.</span>
                                </div>
                                <div>
                                    <span style="white-space: pre-line">Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan
                                        kedokteran bukanlah keniscayaan, melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa.</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="page-break-after: always;">
                <td colspan="2" class="no-border" style="padding-top: 2px;">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td colspan="4" style="font-size: 10px;">
                                <span>Garut, Tanggal, {{ \Carbon\Carbon::now()->format('Y-m-d') }}, Pukul {{ \Carbon\Carbon::now()->format('H:i') }} WIB
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tc vt" colspan="2" width="50%" style="font-size: 10px;">
                                <div>
                                    <span style="white-space: pre-line;">Yang Menyatakan*
                                        Pasien/Keluarga Pasien</span>
                                </div>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td width="50%" class="tc">
                                            <div>
                                                @isset($data['tandaTanganDokter'])
                                                <img width="40px" src="{!! $data['tandaTanganDokter'] !!}" alt="Pasien">
                                                @endisset
                                            </div>
                                            <div>
                                                <span style="white-space: pre-line;">Pasien :
                                                    {{ isset($data['namaPasien']) ? $data['namaPasien'] : '' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td width="50%" class="tc">
                                            <div>
                                                @isset($data['tandaTanganSaksi2'])
                                                <img width="40px" src="{!! $data['tandaTanganSaksi2'] !!}" alt="Saksi2">
                                                @endisset
                                            </div>
                                            <div>
                                                <span style="white-space: pre-line;">Keluarga :
                                                    {{ isset($data['namaSaksi2']) ? $data['namaSaksi2'] : '' }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                            <td class="tc vt" width="20%">
                                <div>
                                    <span style="white-space: pre-line;">Perawat</span>
                                </div>
                                <div>
                                    @isset($data['namaPerawat']['label'])
                                            <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(50)->generate($data['namaPerawat']['label'] ?? '')) }}"
                                    @endisset
                                </div>
                                <div>
                                    <span>
                                        {{ isset($data['namaPerawat']['label']) ? $data['namaPerawat']['label'] : '' }}
                                    </span>
                                </div>
                            </td>
                            <td class="tc vt" width="30%">
                                <div>
                                    <span style="white-space: pre-line;">Dokter Penanggungjawab
                                        Pelayanan (DPJP)</span>
                                </div>
                                <div>
                                    @isset($data['dokterRawat']['label'])
                                            <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(50)->generate($data['dokterRawat']['label'] ?? '')) }}"
                                    @endisset
                                </div>
                                <div>
                                    <span>
                                        {{ isset($data['dokterRawat']['label']) ? $data['dokterRawat']['label'] : '' }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td class="tc vc">
                                <span>PERSETUJUAN TINDAKAN HEMODIALISIS</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span class="tl vc" style="white-space: pre-line;">Saya Memahami perlunya dan manfaat tindakan Hemodialisis yang telah dijelaskan seperti di atas kepada saya, terhadap resiko
                                    dan komplikasi yang mungkin timbul.</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="tl vc" style="white-space: pre-line;">Saya juga menyadari oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan kedokteran bukanlah
                                    keniscayaan, melainkan sangat tergantung kepada ijin Tuhan Yang Maha Esa.</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="tl vc" style="white-space: pre-line;">Dengan ini saya menyatakan setuju untuk dilakukannya tindakan Hemodialisis</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="1">
                        <tr>
                            <td class="vc tc" rowspan="2">
                                Tgl/Jam
                            </td>
                            <td class="vc tc" rowspan="2">
                                Yang Menyatakan Persetujuan
                            </td>
                            <td class="vc tc" colspan="2">
                                TANDA TANGAN
                            </td>
                        </tr>
                        <tr>
                            <td class="vc tc">
                                Pihak Pasien
                            </td>
                            <td class="vc tc">
                                Pihak Rumah Sakit
                            </td>
                        </tr>
                    @foreach ($data['formSaksi'] as $item)
                        <tr>
                            <td class="tc">
                                {{ isset($item['tanggalSaksi']) ? date('d-m-Y H:i', strtotime($item['tanggalSaksi'])) : '' }}
                            </td>
                            <td class="tc">
                                {{ isset($item['saksi1Persetujuan1']) ? $item['saksi1Persetujuan1'] : '' }}
                            </td>
                            <td class="tc">
                                @isset($item['ttdSaksi1Persetujuan'])
                                    <img width="100px;" src="{!! $item['ttdSaksi1Persetujuan'] !!}" alt="Saksi 1 Pasien">
                                @endisset
                            </td>
                            <td class="tc">
                                @isset($item['saksi2Persetujuan']['label'])
                                    <img width="70px;" src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(50)->generate($item['saksi2Persetujuan']['label'] ?? '')) }}">
                                @endisset
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>

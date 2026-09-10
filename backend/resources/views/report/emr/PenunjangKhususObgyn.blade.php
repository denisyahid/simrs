<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Formulir Kriteria Trombolisis</title>
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

        .page-break {
            page-break-before: always;
        }

        @page {
            size: A4;
        }

        /*@media print {*/
        /*    body {margin:0}*/
        /*}*/
        .double-border {

            border: 4px solid #000;

        }

        .double-border:before {

            border: 4px solid #fff;

        }

        font {
            margin-left: 5px;
        }

        .box {
            border: 2px solid black;
            /*border-radius: 6px;*/
        }

        .garis6 td {
            padding: 3px;
        }

        .bold {
            font-weight: bold;
        }

        .f-s-15 {
            font-size: 12px;
        }

        .top-height {
            height: 50px;
            vertical-align: text-top;
            width: 15%;
        }

        .bg-blue {
            background-color: #00A1E9;
        }

        .bg-gray {
            background-color: #D9D9D9;
        }

        .text-top {
            vertical-align: text-top;
        }

        .text-center {
            text-align: center;
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

        .bg-head {
            background-color: #D5DCE4;
        }

        .image-container-4 {
            position: relative;
            display: flex;
            justify-content: center;
            justify-items: center;
            width: 250px;
            height: 260px;
        }

        .image-top,
        .image-bottom,
        .image-top-3 {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .image-bottom {
            z-index: 1;
        }

        .image-top {
            z-index: 2;
            transform: translateX(2px), translateY(-2px);
        }
    </style>

    @stack('style')

</head>

<body class="A4" style="font-family:Tahoma;height: auto" onload="window.print()">
    @php
        use Carbon\Carbon;
    @endphp
    <section class="sheet padding-10mm" style="font-family:Tahoma;height: auto;">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td colspan="3" style="text-align: center; border-bottom: none;">
                    <img src="{{ 'img/kop-surat.jpg' }}" alt="kop" width="600px">
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; border-top: none; border-bottom: none;">
                    <span style="white-space: pre-line; vertical-align: top;">
                        PENUNJANG KHUSUS OBGYN (KOLPOSKOPY)
                    </span>
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; border-top: none;">
                    <br>
                </td>
            </tr>
        </table>

        <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse">
            <tr>
                <td colspan="2">
                    <div style="padding:7px">
                        <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="30%">Status HPV (High Risk)</td>
                                    <td width="70%">: {{ isset($data['StatusHPV']) ? $data['StatusHPV'] : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="30%">Hasil Sitologi (tanggal)</td>
                                    <td width="70%">:
                                        {{ isset($data['HasilSitologi']) ? Carbon::parse($data['HasilSitologi'])->translatedFormat('d F Y') : '-' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="padding:7px">
                        <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="30%">Hasil biopsy :</td>
                                    <td width="70%">: {{ isset($data['Hasilbiopsy']) ? $data['Hasilbiopsy'] : '-' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="padding:7px">
                        <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="30%">Hasil Pemeriksaan :</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="padding:7px">
                        <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="100%">
                                        <table width="100%" style="border-collapse: collapse; border: none;">
                                            @foreach (array_chunk($fotopendukung->toArray(), 3) as $groupIndex => $group)
                                                @if ($groupIndex > 0 && $groupIndex % 2 == 0)
                                                    <tr>
                                                        <td colspan="3" style="border: none;">
                                                            <div style="page-break-after: always;"></div>
                                                        </td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    @foreach ($group as $d)
                                                        @php
                                                            $url = storage_path('app/public/' . $d['file']);
                                                        @endphp
                                                        <td
                                                            style="border: none; border-top: 1px solid black; padding: 10px; text-align: center;">
                                                            <span
                                                                style="font-weight: bold;">{{ $d['nama'] }}</span><br>
                                                            <img src="{{ $url }}"
                                                                style="max-width: 100%; max-height: 300px; height: auto; display: block; margin: 5px auto;">
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="padding:7px">
                        <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="35%">Vulva (Normal/Kelainan):</td>
                                    <td colspan="2" width="70%">{{ isset($data['Vulva']) ? $data['Vulva'] : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="35%">Vagina (Normal/Kelainan):</td>
                                    <td width="70%">{{ isset($data['Vagina']) ? $data['Vagina'] : '-' }}
                                    </td>
                                    <td colspan="2">
                                        <div class="image-container-4">
                                            <img src="{{ $data['Kolposkopy'] }}" width="260px" height="250px"
                                                alt="Gambar" class="image-top">
                                            <img src="{{ 'img/kolposkopy.png' }}" width="260px" height="250px"
                                                class="image-bottom">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Asesmen Kolposkopi:</td>
                                    <td width="70%" colspan="2">
                                        {{ isset($data['AsesmenKolposkopi']) ? $data['AsesmenKolposkopi'] : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Adekuat:</td>
                                    <td width="70%">
                                        @if (isset($data['Adekuat']))
                                            <span>
                                                {{ $data['Adekuat'] == 'Ya' ? 'Ya' : '' }}<s>{{ $data['Adekuat'] == 'Tidak' ? 'Ya' : '' }}</s>/
                                                {{ $data['Adekuat'] == 'Tidak' ? 'Tidak' : '' }}<s>{{ $data['Adekuat'] == 'Ya' ? 'Tidak' : '' }}</s>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="35%">Sambungan skuamokolumnar:</td>
                                    <td width="70%">
                                        @if (isset($data['skuamokolumnar']))
                                            <span>
                                                {{ $data['skuamokolumnar'] == 'Tampak' ? 'Tampak' : '' }}<s>{{ $data['skuamokolumnar'] == 'Tidak' ? 'Tampak' : '' }}</s>/
                                                {{ $data['skuamokolumnar'] == 'Tidak' ? 'Tidak' : '' }}<s>{{ $data['skuamokolumnar'] == 'Tampak' ? 'Tidak' : '' }}</s>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Zona transformasi :</td>
                                    <td width="70%" colspan="2">
                                        @if (isset($data['Zonatransfor']))
                                            <span>
                                                {{ $data['Zonatransfor'] == 'Tipe I' ? 'Tipe I' : '' }}<s>{{ $data['Zonatransfor'] != 'Tipe I' ? 'Tipe I' : '' }}</s>/
                                                {{ $data['Zonatransfor'] == 'Tipe II' ? 'Tipe II' : '' }}<s>{{ $data['Zonatransfor'] != 'Tipe II' ? 'Tipe II' : '' }}</s>/
                                                {{ $data['Zonatransfor'] == 'Tipe III' ? 'Tipe III' : '' }}<s>{{ $data['Zonatransfor'] != 'Tipe III' ? 'Tipe III' : '' }}</s>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>

                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
                {{-- <td>
                    <div style="padding:7px">
                        <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                            <table width="30%" style="border-spacing: 0px;" >
                                <tr>
                                    <td>
                                        <div class="image-container-4">
                                            <img src="{{ $data['Kolposkopy'] }}" width="260px" height="250px" alt="Gambar" class="image-top">
                                            <img src="{{ 'img/kolposkopy.png' }}" width="260px" height="250px" class="image-bottom">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td> --}}
            </tr>
            <tr>
                <td colspan="2">
                    <div style="padding:7px">
                        <span><b>Gambaran kolposkopi normal :</b></span>
                        <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="30%">Epitel skuamosa original</td>
                                    <td width="70%">:
                                        @if (isset($data['skuamosaoriginal']))
                                            <span>
                                                {{ $data['skuamosaoriginal'] == 'imatur' ? 'imatur' : '' }}<s>{{ $data['skuamosaoriginal'] != 'imatur' ? 'imatur' : '' }}</s>/
                                                {{ $data['skuamosaoriginal'] == 'matur' ? 'matur' : '' }}<s>{{ $data['skuamosaoriginal'] != 'matur' ? 'matur' : '' }}</s>/
                                                {{ $data['skuamosaoriginal'] == 'atrofi' ? 'atrofi' : '' }}<s>{{ $data['skuamosaoriginal'] != 'atrofi' ? 'atrofi' : '' }}</s>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Epitel Kolumnar </td>
                                    <td width="70%">: ectopy -/+
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Epitel skuamosa metaplastic</td>
                                    <td width="70%">:
                                        @if (isset($data['skuamosametaplastic']))
                                            <span>
                                                {{ $data['skuamosametaplastic'] == 'kista naboti' ? 'kista naboti' : '' }}<s>{{ $data['skuamosametaplastic'] != 'kista naboti' ? 'imatur' : '' }}</s>/
                                                {{ $data['skuamosametaplastic'] == 'crypt (gland) opening' ? 'crypt (gland) opening' : '' }}<s>{{ $data['skuamosametaplastic'] != 'crypt (gland) opening' ? 'crypt (gland) opening' : '' }}</s>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Gambaran desidua Kehamilan </td>
                                    <td width="70%">:
                                        @if (isset($data['desidua']))
                                            <span>
                                                {{ $data['desidua'] == 'Ya' ? 'Ya' : '' }}<s>{{ $data['desidua'] != 'Ya' ? 'Ya' : '' }}</s>
                                                {{ $data['desidua'] == 'Tidak' ? 'Tidak' : '' }}<s>{{ $data['desidua'] != 'Tidak' ? 'Tidak' : '' }}</s>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="page-break-after: always">
                    <div style="padding:7px">
                        <span><b>Kolposkopi abnormal :</b></span>
                        <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="30%">Lokasi lesi</td>
                                    <td width="70%">:
                                        @if (isset($data['lokasilesi']))
                                            <span>
                                                {{ $data['lokasilesi'] == 'dalam TZ' ? 'dalam TZ' : '' }}<s>{{ $data['lokasilesi'] != 'dalam TZ' ? 'dalam TZ' : '' }}</s>/
                                                {{ $data['lokasilesi'] == 'Luar TZ' ? 'Luar TZ' : '' }}<s>{{ $data['lokasilesi'] != 'Luar TZ' ? 'Luar TZ' : '' }}</s>/
                                                {{ $data['lokasilesi'] == 'Dalam luar TZ' ? 'Dalam luar TZ' : '' }}<s>{{ $data['lokasilesi'] != 'Dalam luar TZ' ? 'Dalam luar TZ' : '' }}</s>/
                                                {{ $data['lokasilesi'] == 'Pada polip' ? 'Pada polip' : '' }}<s>{{ $data['lokasilesi'] != 'Pada polip' ? 'Pada polip' : '' }}</s>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Lokasi arah jam </td>
                                    <td width="70%">:
                                        {{ isset($data['lokasiArahjam']) ? $data['lokasiArahjam'] : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Jumlah kuadran</td>
                                    <td width="70%">:
                                        @if (isset($data['jumlahkuadran']))
                                            <span>
                                                {{ $data['jumlahkuadran'] == '1' ? '1' : '' }}<s>{{ $data['jumlahkuadran'] != '1' ? '1' : '' }}</s>/
                                                {{ $data['jumlahkuadran'] == '2' ? '2' : '' }}<s>{{ $data['jumlahkuadran'] != '2' ? '2' : '' }}</s>/
                                                {{ $data['jumlahkuadran'] == '3' ? '3' : '' }}<s>{{ $data['jumlahkuadran'] != '3' ? '3' : '' }}</s>/
                                                {{ $data['jumlahkuadran'] == '4' ? '4' : '' }}<s>{{ $data['jumlahkuadran'] != '4' ? '4' : '' }}</s>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">% serviks </td>
                                    <td width="70%">: {{ isset($data['serviks']) ? $data['serviks'] : '-' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="padding:7px">
                        <span><b>Temuan :</b></span>
                        <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" style="vertical-align: middle;"
                                            {{ isset($data['temuangradei']) && $data['temuangradei'] == 'GradeI' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt; color: #000000; vertical-align: middle;">Grade
                                            I</span>
                                    </td>
                                    <td width="80%">:
                                        <span>
                                            @if (isset($data['thinacetowhiteepithelium']) && $data['thinacetowhiteepithelium'] == 'thin acetowhite epithelium')
                                                thin acetowhite epithelium
                                            @else
                                                <s>thin acetowhite epithelium</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['irregularborder']) && $data['irregularborder'] == 'irregular border')
                                                irregular border
                                            @else
                                                <s>irregular border</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['geographicborder']) && $data['geographicborder'] == 'geographic border')
                                                geographic border
                                            @else
                                                <s>geographic border</s>
                                            @endif
                                        </span> /

                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%"></td>
                                    <td width="80%">
                                        <span>
                                            @if (isset($data['finemosaic']) && $data['finemosaic'] == 'fine mosaic')
                                                fine mosaic
                                            @else
                                                <s>fine mosaic</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['finepunctuation']) && $data['finepunctuation'] == 'fine punctuation')
                                                fine punctuation
                                            @else
                                                <s>fine punctuation</s>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" style="vertical-align: middle;"
                                            {{ isset($data['temuangradeii']) && $data['temuangradeii'] == 'GradeII' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Grade
                                            II</span>
                                    </td>
                                    <td width="80%">:
                                        <span>
                                            @if (isset($data['Denseacetowhiteepithelium']) && $data['Denseacetowhiteepithelium'] == 'Dense acetowhite epithelium')
                                                Dense acetowhite epithelium
                                            @else
                                                <s>Dense acetowhite epithelium</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['distinctmargin']) && $data['distinctmargin'] == 'distinct margin')
                                                distinct margin
                                            @else
                                                <s>distinct margin</s>
                                            @endif
                                        </span> /
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%"></td>
                                    <td width="80%">
                                        <span>
                                            @if (isset($data['rapidappearanceofacetowhite']) &&
                                                    $data['rapidappearanceofacetowhite'] == 'rapid appearance of acetowhite')
                                                rapid appearance of acetowhite
                                            @else
                                                <s>rapid appearance of acetowhite</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['cuffedcryptglandopening']) && $data['cuffedcryptglandopening'] == 'cuffed crypt (gland) opening')
                                                cuffed crypt (gland) opening
                                            @else
                                                <s>cuffed crypt (gland) opening</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['coarsemosaic']) && $data['coarsemosaic'] == 'coarse mosaic')
                                                coarse mosaic
                                            @else
                                                <s>coarse mosaic</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['coarsepunctuation']) && $data['coarsepunctuation'] == 'coarse punctuation')
                                                coarse punctuation
                                            @else
                                                <s>coarse punctuation</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['innerbordersign']) && $data['innerbordersign'] == 'inner border sign')
                                                inner border sign
                                            @else
                                                <s>inner border sign</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['Pubdgesign']) && $data['Pubdgesign'] == 'Pubdge sign')
                                                Pubdge sign
                                            @else
                                                <s>Pubdge sign</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['Ragsign']) && $data['Ragsign'] == 'Rag sign')
                                                Rag sign
                                            @else
                                                <s>Rag sign</s>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" style="vertical-align: middle;"
                                            {{ isset($data['temuanCurigalesiinvasive']) && $data['temuanCurigalesiinvasive'] == 'Curigalesiinvasive' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Curiga
                                            lesi invasive</span>
                                    </td>
                                    <td width="80%">:
                                        <span>
                                            @if (isset($data['Atypicalvessels']) && $data['Atypicalvessels'] == 'Atypical vessels')
                                                Atypical vessels
                                            @else
                                                <s>Atypical vessels</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['garglevessles']) && $data['garglevessles'] == 'gargle vessles')
                                                gargle vessles
                                            @else
                                                <s>gargle vessles</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['irregularsurface']) && $data['irregularsurface'] == 'irregular surface')
                                                irregular surface
                                            @else
                                                <s>irregular surface</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['exophyticlesions']) && $data['exophyticlesions'] == 'exophytic lesions')
                                                exophytic lesions
                                            @else
                                                <s>exophytic lesions</s>
                                            @endif
                                        </span> /
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%"></td>
                                    <td width="80%">
                                        <span>
                                            @if (isset($data['necrosis']) && $data['necrosis'] == 'necrosis')
                                                necrosis
                                            @else
                                                <s>necrosis</s>
                                            @endif
                                        </span>/
                                        <span>
                                            @if (isset($data['ulceration']) && $data['ulceration'] == 'ulceration')
                                                ulceration
                                            @else
                                                <s>ulceration</s>
                                            @endif
                                        </span>/
                                        <span>
                                            @if (isset($data['tumor']) && $data['tumor'] == 'tumor')
                                                tumor
                                            @else
                                                <s>tumor</s>
                                            @endif
                                        </span>/
                                        <span>
                                            @if (isset($data['grossneoplasm']) && $data['grossneoplasm'] == 'gross neoplasm')
                                                gross neoplasm
                                            @else
                                                <s>gross neoplasm</s>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" style="vertical-align: middle;"
                                            {{ isset($data['temuanNonspesifik']) && $data['temuanNonspesifik'] == 'Nonspesifik' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Non
                                            spesifik</span>
                                    </td>
                                    <td width="80%">:
                                        <span>
                                            @if (isset($data['Leukoplakia']) && $data['Leukoplakia'] == 'Leukoplakia')
                                                Leukoplakia
                                            @else
                                                <s>Leukoplakia</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['erosi']) && $data['erosi'] == 'erosi')
                                                erosi
                                            @else
                                                <s>erosi</s>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" style="vertical-align: middle;"
                                            {{ isset($data['temuanLainlain']) && $data['temuanLainlain'] == 'Lainlain' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;"
                                            color="#000000">Lain-lain</span>
                                    </td>
                                    <td width="80%">:
                                        <span>
                                            @if (isset($data['CongenitalTZ']) && $data['CongenitalTZ'] == 'Congenital TZ')
                                                Congenital TZ
                                            @else
                                                <s>Congenital TZ</s>
                                            @endif
                                        </span> /
                                        <span>
                                            @if (isset($data['Condyloma']) && $data['Condyloma'] == 'Condyloma')
                                                Condyloma
                                            @else
                                                <s>Condyloma</s>
                                            @endif
                                        </span>/
                                        <span>
                                            @if (isset($data['Polypectocervical']) && $data['Polypectocervical'] == 'Polyp (ectocervical)')
                                                Polyp (ectocervical)
                                            @else
                                                <s>Polyp (ectocervical)</s>
                                            @endif
                                        </span>/
                                        <span>
                                            @if (isset($data['kylammation']) && $data['kylammation'] == 'kylammation')
                                                kylammation
                                            @else
                                                <s>kylammation</s>
                                            @endif
                                        </span>/
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                    </td>
                                    <td width="80%">
                                        <span>
                                            @if (isset($data['Stenosis']) && $data['Stenosis'] == 'Stenosis')
                                                Stenosis
                                            @else
                                                <s>Stenosis</s>
                                            @endif
                                        </span>/
                                        <span>
                                            @if (isset($data['CongenitalAnomaly']) && $data['CongenitalAnomaly'] == 'Congenital Anomaly')
                                                Congenital Anomaly
                                            @else
                                                <s>Congenital Anomaly</s>
                                            @endif
                                        </span>/
                                        <span>
                                            @if (isset($data['Endometriosis']) && $data['Endometriosis'] == 'Endometriosis')
                                                Endometriosis
                                            @else
                                                <s>Endometriosis</s>
                                            @endif
                                        </span>/
                                        <span>
                                            @if (isset($data['Polupendocervical']) && $data['Polupendocervical'] == 'Polup (endocervical)')
                                                Polup (endocervical)
                                            @else
                                                <s>Polup (endocervical)</s>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="padding:7px">
                        <div style="margin-left: 40px;margin-top: 10px;margin-bottom: 10px">
                            <table class="text-center" width="100%" style="border-spacing: 0px;" border="1">
                                <tr>
                                    <td width="20%"><b>Skor Swede</b></td>
                                    <td width="20%"><b>0</b></td>
                                    <td width="20%"><b>1</b></td>
                                    <td width="20%"><b>2</b></td>
                                    <td width="20%"><b>Skor</b></td>
                                </tr>
                                <tr>
                                    <td width="20%">Acetouptake</td>
                                    <td width="20%">Tidak ada/transparan</td>
                                    <td width="20%">Putih susu</td>
                                    <td width="20%">Putih padat</td>
                                    <td width="20%">{{ isset($data['skor1']) ? $data['skor1'] : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="20%">Batas</td>
                                    <td width="20%">Tidak ada/menyebar</td>
                                    <td width="20%">Tajam irregular, satelit</td>
                                    <td width="20%">Berbatas tegas dan jelas</td>
                                    <td width="20%">{{ isset($data['skor2']) ? $data['skor2'] : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="20%">Pembuluh darah</td>
                                    <td width="20%">Regular, halus</td>
                                    <td width="20%">Tidak tampak</td>
                                    <td width="20%">atipikal</td>
                                    <td width="20%">{{ isset($data['skor3']) ? $data['skor3'] : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="20%">Ukuran lesi</td>
                                    <td width="20%">&lt;5mm</td>
                                    <td width="20%">5-15 mm/2 kuadran</td>
                                    <td width="20%">&gt;15 mm, 3-4 kuadran/endocervical</td>
                                    <td width="20%">{{ isset($data['skor4']) ? $data['skor4'] : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="20%">lodine uptake</td>
                                    <td width="20%">Coklat</td>
                                    <td width="20%">Kekuningan</td>
                                    <td width="20%">Kuning</td>
                                    <td width="20%">{{ isset($data['skor5']) ? $data['skor5'] : '-' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" width="20%">Total</td>
                                    <td width="20%">{{ isset($data['totalskor']) ? $data['totalskor'] : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="padding:7px">
                        <div style="margin-left: 40px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="20%"><b>Diagnosis</b></td>
                                    <td width="80%">:
                                        @if (isset($data['diagnosis']))
                                            <span>
                                                {{ $data['diagnosis'] == 'Zona transformasi tipe I' ? 'Zona transformasi tipe I' : '' }}<s>{{ $data['diagnosis'] != 'Zona transformasi tipe I' ? 'Zona transformasi tipe I' : '' }}</s>/
                                                {{ $data['diagnosis'] == 'Zona transformasi tipe II' ? 'Zona transformasi tipe II' : '' }}<s>{{ $data['diagnosis'] != 'Zona transformasi tipe II' ? 'Zona transformasi tipe II' : '' }}</s>/
                                                {{ $data['diagnosis'] == 'Zona transformasi tipe III' ? 'Zona transformasi tipe III' : '' }}<s>{{ $data['diagnosis'] != 'Zona transformasi tipe III' ? 'Zona transformasi tipe III' : '' }}</s>/
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%"></td>
                                    <td width="80%">
                                        @if (isset($data['diagnosis']))
                                            <span>
                                                {{ $data['diagnosis'] == 'Normal' ? 'Normal' : '' }}<s>{{ $data['diagnosis'] != 'Normal' ? 'Normal' : '' }}</s>/
                                                {{ $data['diagnosis'] == 'LSIL' ? 'LSIL' : '' }}<s>{{ $data['diagnosis'] != 'LSIL' ? 'LSIL' : '' }}</s>/
                                                {{ $data['diagnosis'] == 'HSIL' ? 'HSIL' : '' }}<s>{{ $data['diagnosis'] != 'HSIL' ? 'HSIL' : '' }}</s>/
                                                {{ $data['diagnosis'] == 'Kanker' ? 'Kanker' : '' }}<s>{{ $data['diagnosis'] != 'Kanker' ? 'Kanker' : '' }}</s>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="padding:7px">
                        <div style="margin-left: 40px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="20%"><b>Manajemen</b></td>
                                    <td width="80%">: {{ isset($data['Manajemen']) ? $data['Manajemen'] : '-' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="padding:7px">
                        <div style="margin-left: 40px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="20%"><b>Komentar</b></td>
                                    <td width="80%">: {{ isset($data['Komentar']) ? $data['Komentar'] : '-' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="padding:7px">
                        <div style="margin-left: 40px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" cellspacing="0" cellpadding="0">

                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="60%"></td>
                                        <td class="tc vt" width="50%"
                                            style="{{ empty($data['DDDokter']) ? 'padding-bottom: 2em;' : '' }};text-align:center;">
                                            <div>Garut,
                                                {{ Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</div>
                                            <br>
                                            <!-- Qrcode ceritanya -->
                                            @if (isset($data['DDDokter']['label']))
                                                <img src="data:image/png;base64, {!! $qrcode !!}">
                                            @endif
                                            <br>
                                            <span>{{ isset($data['DDDokter']['label']) ? $data['DDDokter']['label'] : ' ' }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>

        </table>


        {{-- <hr style="border:2px solid #000;margin-bottom:0px">
      <hr style="border:0.5px solid #000;margin-top:2px">
      <hr style="border:0.5px solid #000;margin-top:2px"> --}}
    </section>
</body>

</html>

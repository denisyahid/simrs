<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Informasi dan Edukasi Terintegrasi</title>

    <!-- <link rel="stylesheet" href="{{ asset('css/paper.css') }} "> -->
    <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>

        .table-ex {
            /* border: 1px solid black; */
            border-collapse: collapse !important;
            width: 100%;
        }

        /* table, td, th, tr {
            vertical-align: middle;
        } */

        html,
        body {
            font-family: sans-serif;
            font-weight: normal;
            page-break-inside: avoid !important;
        }

        .table {
            width: 100%;
            border-collapse: collapse !important;
            border: 1px solid #000;
            background-color: transparent;
            page-break-inside: avoid !important;
        }

        .table thead td,
        .table thead th {
            border-bottom-width: 2px;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table-borderless tbody+tbody,
        .table-borderless td,
        .table-borderless th,
        .table-borderless thead th {
            border: 0 !important;
            padding: 5px 0 !important;
            page-break-inside: avoid;
        }

        .table td,
        .table th {
            border: 1px solid #000;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #000;
        }

        th {
            text-align: inherit;
        }

        i {
            font-size: 9pt;
        }


        .table-sub {
            padding: 5px;
            border: 1px black;
            border-collapse: collapse;
            width: 100%;
        }

        .th-sub {
            /* padding:3px; */
            text-align: center;
            border: 1px solid black;
        }

        /* th,
    td {
        padding: 2px;
        padding-top: 0px;
        text-align: left;
    } */

        .d-flex {
            display: flex;
        }

        .ml-auto {
            margin-left: auto !important;
        }

        .mr-auto {
            margin-right: auto !important;
        }

        .th-primary {
            background-color: #2997d6 !important;
        }

        .fordots {
            display: flex;
        }

        .fordots .first,
        .fordots .end {
            flex: 1 0 auto;
        }

        .fordots .dots {
            flex: 0 1 auto;
            margin: 0 5px;
            overflow: hidden;
        }

        .dots::before {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: clip;
            content:
                "...................."
                "...................."
                "...................."
                "...................."
        }

        .text-center {
            text-align: center !important;
        }

        .table.table-nested {
            border: none !important;
        }

        .table.table-nested tr,
        .table.table-nested td,
        .table.table-nested th {
            padding: 0;
            border: 0;
            border-bottom: 1px solid black;
            text-align: center;
            /* border-right: 1px solid black; */
        }

        .table.table-nested tr:last-child td,
        .table.table-nested tr:last-child th,
        .table.table-nested tr:last-child {
            padding: 0;
            border: 0;
            border-bottom: 1px solid black;
            /* border-right: 1px solid black; */
        }

        .table.table-nested-sec {
            border: none !important;
        }

        .table.table-nested-sec tr,
        .table.table-nested-sec td,
        .table.table-nested-sec th {
            padding: 0;
            border: 0;
            border-bottom: 1px solid black;
            text-align: left;
            padding-left: 2px;
            font-weight: normal;
        }

        .paraf {
            font-size: 12px;
            vertical-align: middle !important;
        }
        .text-small {
            font-size: 9px;
        }
        tr.p-0>td {
            padding: 0.3rem;
        }

        tbody.break>tr:last-child {
            page-break-after: avoid;
            page-break-before: avoid;
            page-break-inside: avoid;
        }
        /* thead { display: table-header-group; } */
        /* tbody { display: table-header-group; } */
        .table.table-nested-sec tr:last-child td,
        .table.table-nested-sec tr:last-child th,
        .table.table-nested-sec tr:last-child {
            padding: 0;
            border: 0;
            padding-left: 2px;
            border-bottom: 1px solid black;
            border-right: 1px solid black;
        }
    </style>
</head>

<body>
    <table class="table" style="page-break-inside: avoid !important;">
        <thead>
            <tr>
                <th colspan="12" class="th-primary">
                    <span>RSUD BALI MANDARA</span>
                    <span class="ml-auto" style="font-weight: bold; float: right">RM.1A/RLD/01</span>
                </th>
            </tr>
        </thead>
        <tr>
            <th colspan="2" style="text-align: center; vertical-align: center">
                <img alt="" src="{{ asset('img/logo-rs.png') }}" border="0" style="width: 70px; text-align:center vertical-align: center" 
                    alt="">
            </th>
            <th colspan="6" style="text-align: center; margin-top: auto !important;">
                <div style="text-align: center !important;">
                    <p>
                        CATATAN INFORMASI DAN EDUKASI TERINTEGRASI <br>
                        <i>INTEGRATED INFORMATION AND EDUCATION</i>
                    </p>
                </div>
            </th>
            <th colspan="4">
                <table class="table-borderless" cellspacing="0" cellpadding="0" border="0" width="90%" align="center"
                    style="page-break-inside: auto;">
                    <tr>
                        <td width="30%">
                            Nama
                        </td>
                        <td width="1%">
                            :
                        </td>
                        <td width="75%">
                            &nbsp;..........................................................
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">
                            Tgl.Lahir
                        </td>
                        <td width="1%">
                            :
                        </td>
                        <td width="75%">
                            &nbsp;.............................................. Lk / Pr
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">
                            No.RM
                        </td>
                        <td width="1%">
                            :
                        </td>
                        <td width="75%">
                            &nbsp;..........................................................
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
        <tr>
            <td colspan="6">
                <div class="text-center">
                    <b>
                        <span>Metode Edukasi</span> <br>
                        <i>(Education Method)</i>
                    </b>
                </div>
            </td>
            <th colspan="6">
                <div class="text-center">
                    <b>
                        <span>Respon</span> <br>
                        <i>(Response)</i>
                    </b>
                </div>
            </th>
        </tr>
        <tr>
            <td style="vertical-align:middle; padding: 5px" class="paraf" colspan="3">
                <span>
                    Diskusi <i style="font-size: 10px">(Discussion)</i>
                </span>
            </td>
            <td style="vertical-align:middle; padding: 5px" class="paraf" colspan="3">
                <span>
                    Audio Visual
                </span>
            </td>
            <td style="vertical-align:middle; padding: 5px" colspan="6">
                1.
                <span style="padding-left: 5px">
                    Tidak respon sama sekali (tidak ada antusiasme dan keinginan belajar) <br>
                    <i style="padding-left:20px; font-size: 10px">(No Response (No Enthusiasm and Willingness to Learn)</i>
                </span>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:middle; padding: 5px" class="paraf" colspan="3">
                <span>
                    Demonstrasi <i style="font-size: 10px">(Demo)</i>
                </span>
            </td>
            <td style="vertical-align:middle; padding: 5px" class="paraf" colspan="3">
                <span>
                    Lembar Balik <i style="font-size: 10px">(Flip Sheet)</i>
                </span>
            </td>
            <td style="vertical-align:middle; padding: 5px" colspan="6">
                2.
                <span style="padding-left: 5px">
                    Tidak paham (ingin belajar tapi kesulitan mengerti) <br>
                    <i style="padding-left:20px; font-size: 10px">(Not Understand (Want to Learn but have difficulty to understand)</i>
                </span>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:middle; padding: 5px" class="paraf" colspan="3">
                <span>
                    Ceramah <i style="font-size: 10px">(Lecture)</i>
                </span>
            </td>
            <td style="vertical-align:middle; padding: 5px" class="paraf" colspan="3">
                <span>
                    Booklet
                </span>
            </td>
            <td style="vertical-align:middle; padding: 5px" colspan="6">
                3.
                <span style="padding-left: 5px">
                    Paham hal yang diajarkan tapi tidak bisa menjelaskan sendiri <br>
                    <i style="padding-left:20px; font-size: 10px">(Understand things that are thought but cannot explain it well)</i>
                </span>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:middle; padding: 5px" class="paraf" colspan="3">
                <span>
                    Praktek Langsung <i style="font-size: 10px">(Direct Practice)</i>
                </span>
            </td>
            <td style="vertical-align:middle; padding: 5px" class="paraf" colspan="3">
                <span>
                    Leaflet
                </span>
            </td>
            <td style="vertical-align:middle; padding: 5px" colspan="6">
                4.
                <span style="padding-left: 5px">
                    Dapat menjelaskan apa yang telah diajarkan tapi harus dibantu educator <br>
                    <i style="padding-left:20px; font-size: 10px">(Able to explain the things which are taught but have to help by educator)</i>
                </span>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:middle; padding: 5px" class="paraf" colspan="6">
            </td>
            <td style="vertical-align:middle; padding: 5px" colspan="6">
                5.
                <span style="padding-left: 5px">
                    Dapat menjelaskan apa yang telah diajarkan tapi tanpa dibantu <br>
                    <i style="padding-left:20px; font-size: 10px">(Able to explain the things which are taught without helps)</i>
                </span>
            </td>
        </tr>
        {{-- <tr style="border: none !important;">
            <td colspan="6" style="padding: 0; border: none;">
                <table class="table table-nested" style="width: 100%;">
                    <!-- In case data will be dynamicly generated, delete style nth:last-child -->

                    @php
                        $dataChunk = array_chunk($data['resmetode']['metode'], 2, true);
                    @endphp
                    @foreach ($dataChunk as $index => $chunks)
                        @php
                            $styleLeft = 'border: 0; border-bottom: 1px solid black;';
                            if($loop->last && (count($data['resmetode']['metode']) - 2) < count($data['resmetode']['response']) && (count($data['resmetode']['metode']) - 2) != count($data['resmetode']['response']) ) {
                                $styleLeft = 'border: 0;';
                            } 
                        @endphp
                        <tr style="{{$styleLeft}}">
                            @foreach ($chunks as $i => $c)
                                <td style="{{$styleLeft}}vertical-align:middle;" class="paraf">
                                    {!! $c !!}
                                </td>
                            @endforeach
                            @if($loop->last)
                                <td style="{{$styleLeft}}"></td>
                            @endif
                        </tr>
                    @endforeach

                </table>
            </td>
            <th colspan="6" style="padding:0!important;">
                <table class="table table-nested-sec" style="width: 100%; border: none !important;">
                    @foreach ($data['resmetode']['response'] as $k => $item)
                        @php
                            $styleRight = ''; 
                            if($loop->last && count($data['resmetode']['response']) < (count($data['resmetode']['metode']) - 2)) {
                                $styleRight = 'border-bottom: 0;'; 
                            }elseif($loop->last && count($data['resmetode']['response']) > (count($data['resmetode']['metode']) - 2)) {
                                $styleRight = 'border-bottom: 1px solid black;'; 
                            }
                        @endphp
                        <tr style="{{$styleRight}}">
                            <td style="{{$styleRight}}" class="paraf">{{ $item }}</td>
                        </tr>
                    @endforeach
                </table>
            </th>
        </tr> --}}
        <tbody class="break">
            <tr>
                <td class="text-center paraf" width="8%">
                    Tgl & Jam <br>
                    <i class="text-small">(Date & Time)</i>
                </td>
                <td class="text-center paraf" colspan="6" width="100%">
                    Materi Informasi dan Edukasi <br>
                    <i class="text-small">(Information and Education Lists)</i>
                </td>
                <td class="text-center paraf">
                    Paraf & Nama Pasien / Keluarga (hubungan dengan Pasien) <br>
                    <i class="text-small">(Signature and Full
                        Name; Relation with
                        Patients)</i>
                </td>
                <td class="text-center paraf" width="10%">
                    Tempat <br>
                    <i class="text-small">(Ward)</i>
                </td>
                <td class="text-center paraf">
                    Metode Edukasi / Durasi <br>
                    <i class="text-small">(Duration and
                        Education
                        Method)</i>
                </td>
                <td class="text-center paraf" width="1%">
                    Respon <br>
                    <i class="text-small">(Response)</i>
                </td>
                <td class="text-center paraf" width="5%">
                    Nama Paraf Educator dan Profesi <br>
                    <i class="text-small">(Signature
                        Full Name
                        Educator
                        and
                        Profession)</i>
                </td>
            </tr>
            @for ($i = 0; $i < 10; $i++)
                <tr class="p-0">
                    <td class="text-center paraf" width="8%" style="border-top: none; border-bottom: none;">
                        a
                    </td>
                    <td class="text-center paraf" colspan="6" width="100%" style="border-top: none; border-bottom: none;">
                        b
                    </td>
                    <td class="text-center paraf" style="border-top: none; border-bottom: none;">
                        c
                    </td>
                    <td class="text-center paraf" width="10%" style="border-top: none; border-bottom: none;">
                        d
                    </td>
                    <td class="text-center paraf" style="border-top: none; border-bottom: none;">
                        e
                    </td>
                    <td class="text-center paraf" width="1%" style="border-top: none; border-bottom: none;">
                        f
                    </td>
                    <td class="text-center paraf" width="5%" style="border-top: none; border-bottom: none;">
                        g
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
</body>
</html>

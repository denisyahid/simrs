<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expertise Radiologi</title>
    <style>
        /* General styles for table */
        .infopasien tbody tr td {
            vertical-align: top;
            padding: 5px;
            font-size: 8pt;
        }

        .infopasien tbody tr td:first-child,
        .infopasien tbody tr td:nth-child(4) {
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        tr {
            page-break-inside: auto;
            page-break-after: auto;
        }

        /* Ensure signature appears on each page */
        .signature img {
            max-width: 200px;
            height: 100px;
            margin-top: 20px; /* Adjusted margin for the image */
        }

        .signature span {
            font-size: 10pt;
        }

        /* Header and Signature on each page */
        @media print {
            /* Keep the header and patient info at the top of each page */
            .header, .infopasien {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                width: 100%;
                margin-bottom: 30px;
                border: none; /* remove table borders when printed */
                page-break-before: always;
            }

            .header table {
                margin-top: 0px;
            }

            /* Ensure the header is always at the top */
            .header {
                top: 0;
                left: 0;
                right: 0;
                width: 100%;
                position: fixed;
                padding: 10px;
                text-align: center;
            }

            /* Page breaks for content after the header */
            .page-break {
                page-break-before: always;
            }

            /* Ensure the signature is at the bottom of the page */
            .signature {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                align-content: flex-end;
                opacity: 1;
            }

            /* Add spacing at the bottom to ensure space for signature */
            body {
                margin-bottom: 150px; /* Ensure there's enough space at the bottom for the signature */
            }
        }
        .background{
            background: url({{ 'img/logo-rs.png' }}) no-repeat fixed center;
            /* -webkit-backface-visibility: hidden;  */
            background-size: 700px 800px;
            /* padding-bottom:160rem; */
            /* background-repeat: no-repeat; */
            background-position: center;
            opacity: 0.10;
        }
        .expertise{
            text-align: left; margin-left: 7px; font-size: 12px;
            opacity: 0;
        }

        /* Additional margin for printing (to avoid overlapping content) */
        @media print {
            body {
                margin-bottom: 50px; /* Ensure there's space at the bottom for the signature */
            }
        }
        /* .new-page {
            page-break-before: always;
        } */
        .new-page {
            page-break-before: always;
            break-before: page; /* modern syntax */
        }

        .new-page:first-child {
            page-break-before: auto !important;
            break-before: auto !important;
        }
    </style>
</head>
<body>

    <!-- Patient Info Table (Header) -->
    {{-- @php
        dd($dataBrid)
    @endphp --}}

    @forelse ($dataBrid as $e)
        <div class="new-page">
            <table cellspacing="0" width="100%" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:10px;padding-top:0px">
                <tbody width="100%">
                    <tr>
                        <td width="100%">
                            <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 5px; margin-bottom: -10px;">
                                <tr>
                                    <td style="text-align:center" width="10%" rowspan="3">
                                        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                                            <img src="{{ 'img/logo-rs.png' }}" width="80px" border="0" style="margin-top: -20px;">
                                        @else
                                            <img src="{{ asset('img/logo-rs.png') }}" width="80px" border="0" style="margin-top: -20px;">
                                        @endif
                                    </td>
                                    <td class="text-center" style="text-align: center;">
                                        <span style="font-size: 30pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                            {{ strtoupper($profile->namalengkap) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="text-align: center">
                                        <span style="font-size: 11pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                            {{$profile->alamatlengkap}} <br> Telp.{{$profile->fixedphone}} email : rsud.balimandara@gmail.com
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr>
        </div>

        <!-- Patient Information Table -->
        <table class="infopasien" cellspacing="0" cellpadding="0" border="0">
            <tbody>
                <tr>
                    <td width="17%"><strong>No RM</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="34%">{{ $e['nocm'] }}</td>
                    <td width="15%"><strong>Tanggal Imaging/Jam Imaging</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td width="34%">{{ isset($e['tglorder']) ? $e['tglorder'] : now() }}</td>
                    <!-- <td width="34%">{{ \Carbon\Carbon::parse($e['tgl_jam_expertise'] ?? now())->translatedFormat('d F Y H:i')  }}</td> -->
                </tr>
                <tr>
                    <td><strong>Nama</strong></td>
                    <td><strong>:</strong></td>
                    <td>{{ $e['namapasien'] }}</td>
                    <td><strong>Dokter Pengirim</strong></td>
                    <td><strong>:</strong></td>
                    <td>{{ $e['perujuk'] }}</td>
                </tr>
                <tr>
                    <td><strong>Tgl Lahir / Umur</strong></td>
                    <td><strong>:</strong></td>
                    <td>{{ $e['tgllahir'] }} / {{$e['umur']}}</td>
                    <td><strong>Ruangan Peng Order</strong></td>
                    <td><strong>:</strong></td>
                    <td>{{ $e['namaruangan'] }}</td>
                </tr>
                <tr>
                    <td><strong>Kelamin</strong></td>
                    <td><strong>:</strong></td>
                    <td>{{ $e['jeniskelamin'] }}</td>
                    <td><strong>Alamat</strong></td>
                    <td><strong>:</strong></td>
                    <td>{{ isset($e['alamatmanual']) ? $e['alamatmanual'] : $e['alamatlengkap'] }}</td>
                </tr>
            </tbody>
        </table>

        <hr>
        <div class="background">
            <div class="expertise">
                <div>
                    <p>{!! nl2br(e(str_replace('~', '<br/>', $e['expertise_text_finding'] ?? ''))) !!}</p>
                    <p>{!! nl2br(e(str_replace('~', '<br/>', $e['expertise_text_conclusion'] ?? ''))) !!}</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="signature" style="text-align: center; margin-right: 10px;">
            <span style="display: block; margin-bottom: 10px;">Tanda Tangan Pemeriksa,</span>
            <img src="data:image/png;base64, {!! $e['ttde'] !!}" alt="Signature"><br>
            <span><strong>{{ $e['dokterrad'] }}</strong></span><br>
            <i>{{ $e['dokterradnosip'] ?? '' }}</i>
        </div>
    @empty
        <tr>
            <td colspan="5" align="center">Data tidak tersedia</td>
        </tr>
    @endforelse


</body>
</html>

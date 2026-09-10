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
    </style>
</head>
<body>

    <!-- Patient Info Table (Header) -->
    <div class="header">
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
                <td width="34%">{{ $raw->nocm }}</td>
                <td width="15%"><strong>Tanggal Imaging</strong></td>
                <td width="1%"><strong>:</strong></td>
                <td width="34%">{{ App\Traits\Valet::getDateIndo(isset($raw->tglorder) ? $raw->tglorder : now()) }}</td>
            </tr>
            <tr>
                <td><strong>Nama</strong></td>
                <td><strong>:</strong></td>
                <td>{{ $raw->namapasien }}</td>
                <td><strong>Dokter Pengirim</strong></td>
                <td><strong>:</strong></td>
                <td>{{ $raw->perujuk }}</td>
            </tr>
            <tr>
                <td><strong>Tgl Lahir / Umur</strong></td>
                <td><strong>:</strong></td>
                <td>{{ $raw->tgllahir }} / {{ $raw->umur }}</td>
                <td><strong>Ruangan Peng Order</strong></td>
                <td><strong>:</strong></td>
                <td>{{ $raw->namaruangan }}</td>
            </tr>
            <tr>
                <td><strong>Kelamin</strong></td>
                <td><strong>:</strong></td>
                <td>{{ $raw->jeniskelamin }}</td>
                <td><strong>Alamat</strong></td>
                <td><strong>:</strong></td>
                <td>{{ isset($raw->alamatmanual) ? $raw->alamatmanual : $raw->alamatlengkap }}</td>
            </tr>
        </tbody>
    </table>

    <hr>
    <div class="background">
        <div class="expertise">
            @if(isset($dataBrid) && $dataBrid->count() > 0)
                @foreach($dataBrid as $e)
                    <div>
                        <p>{!! nl2br(e(str_replace('~', '<br/>', $e->expertise_text_finding ?? ''))) !!}</p>
                        <p>{!! nl2br(e(str_replace('~', '<br/>', $e->expertise_text_conclusion ?? ''))) !!}</p>
                    </div>
                    <div class="page-break"></div> 
                @endforeach
            @else
                <div>
                    <p>{!! nl2br($processedTextfinding) !!}</p>
                    <p>{!! nl2br($processedTextconclusion) !!}</p>
                </div>
            @endif
        </div>
    </div>
    <hr>
    <div class="signature" style="text-align: center; margin-right: 10px;">
        <span style="display: block; margin-bottom: 10px;">Tanda Tangan Pemeriksa,</span>
        <img src="data:image/png;base64, {!! $ttde !!}" alt="Signature"><br>
        <span><strong>{{ $dataBridDokter->dokterrad }}</strong></span><br>
        <i>{{ $dataBridDokter->dokterradnosip ?? '' }}</i>
    </div>


</body>
</html>

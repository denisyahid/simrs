<!DOCTYPE html>
<html>
@php
    $profile = App\Http\Controllers\Controller::static_profile();
@endphp

<head>
    <title>Cetak Hasil Lab</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
    <style>
        :root {
            --font: Arial, Helvetica, sans-serif;
        }

        body,
        td,
        th,
        span,
        p {
            font-family: Tahoma, Geneva, sans-serif !important;
            font-size: 11px;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 5px;
            /* page-break-after: always; */
        }

        .content {
            /* width: 100%; */
            padding-bottom: 20mm;
        }

        @media print {
            header:after {
                content: "Header Text";
                display: block;
                text-align: center;
            }

            footer:after {
                content: "Footer Text";
                display: block;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <main align="center">
        <div class="content" align="center" style="">
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="100%">
                <tbody>
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
                                    <td class="text-center">
                                        <span style="font-size: 30pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                            {{ strtoupper($profile->namapemerintah) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 11pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                            {{$profile->alamatlengkap}} <br> {{$profile->fixedphone}} <br> email : rsud.balimandara@gmail.com
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <hr class="baris2">
                        </td>
                    </tr>
                </tbody>
            </table>
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="100%">
                <thead style="display: table-header-group;">
                    <tr>
                        <th colspan="5" align="right" style="font-size: 10pt; font-weight: normal;">
                            <div>
                                <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="100%">
                                    <tbody>
                                        <tr>
                                            <td align="center" style="padding-bottom: 2mm;" colspan="4">
                                                <font style="font-size: 12pt;font-weight: bold;" color="#000000">HASIL
                                                    PEMERIKSAAN LABORATORIUM</font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 10px; padding-bottom:7px;" width="22%">
                                                <font style="font-size: 9pt" color="#000000">Nama Pasien</font>
                                            </td>
                                            <td style="padding-bottom:7px;" width="27%">
                                                <font style="font-size: 9pt" color="#000000">:
                                                    {{ $dataReport['header']->namapasien ?? '' }}
                                                </font>
                                            </td>
                                            <td style="padding-bottom:7px;" width="25%">
                                                <font style="font-size: 9pt" color="#000000">No Lab</font>
                                            </td>
                                            <td style="padding-right: 10px;  padding-bottom:7px;" width="26%">
                                                <font style="font-size: 9pt;  word-wrap: break-word;"
                                                    color="#000000">:
                                                    {{ isset($dataRegis->noorder) ? $dataRegis->noorder:'-' }}</font>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="padding-left: 10px; padding-bottom:7px;">
                                                <font style="font-size: 9pt" color="#000000">No. RM</font>
                                            </td>
                                            <td style="padding-bottom:7px;">
                                                <font style="font-size: 9pt" color="#000000">:
                                                    {{ $dataReport['header']->nocm ?? '' }}</font>
                                            </td>
                                             <td style="padding-bottom:7px;">
                                                <font style="font-size: 9pt" color="#000000">Tgl. Pengambilan Sample
                                                </font>
                                            </td>
                                            <td style="padding-right: 10px;  padding-bottom:7px;">
                                                <font style="font-size: 9pt;  color="#000000">:
                                                    {{ isset($dataRegis->tglpengambilan) ? $dataRegis->tglpengambilan:$dataRegis->updated_at }}
                                                </font>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 10px; padding-bottom:7px;">
                                                <font style="font-size: 9pt" color="#000000">Tgl. Lahir / Umur</font>
                                            </td>
                                            <td style="padding-bottom:7px;">
                                                <font style="font-size: 9pt" color="#000000">:
                                                    {{ $dataReport['header']->tglkelahiran }} /
                                                    {{ \Carbon\Carbon::parse($dataReport['header']->tglkelahiran)->age }}
                                                </font>
                                            </td>
                                            <td style="padding-bottom:7px;">
                                                <font style="font-size: 9pt" color="#000000">Tgl. Hasil Selesai</font>
                                            </td>
                                            <td style="padding-right: 10px;  padding-bottom:7px;">
                                                <font style="font-size: 9pt;  color="#000000">:
                                                    {{ $dataReport['details'][0]->tglhasil ?? date('Y-m-d H:i:s') }}
                                                </font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 10px; padding-bottom:7px;">
                                                <font style="font-size: 9pt" color="#000000">Jenis Kelamin</font>
                                            </td>
                                            <td style="padding-bottom:7px;">
                                                <font style="font-size: 9pt" color="#000000">:
                                                    {{ $dataReport['header']->jk == 'P' ? 'Perempuan' : 'Laki-Laki'  }}</font>
                                            </td>
                                            <td style="padding-bottom:7px;">
                                                <font style="font-size: 9pt" color="#000000">Asal / Ruangan</font>
                                            </td>
                                            <td style="padding-right: 10px;  padding-bottom:7px;">
                                                <font style="font-size: 9pt;  color="#000000">:
                                                    {{ isset($dataRegis->ruanganasal ) ? $dataRegis->ruanganasal : '-'  }}
                                                </font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 10px;padding-right: 10px; padding-bottom:7px;">
                                                <font style="font-size: 9pt" color="#000000">Alamat</font>
                                            </td>
                                            <td style="padding-bottom:7px;" rowspan="2">
                                                <font style="font-size: 9pt" color="#000000">@php
                                                    $alamat = $dataReport['header']->alamatlengkap;
                                                    $alamatBaru = wordwrap($alamat, 50, '<br />', true);
                                                    echo ': ' . $alamatBaru;
                                                @endphp</font>
                                            </td>
                                            <td style="padding-bottom:7px;">
                                                <font style="font-size: 9pt" color="#000000">Dokter Pengirim</font>
                                            </td>
                                            <td style="padding-right: 10px;  padding-bottom:7px;" rowspan="2">
                                                <font style="font-size: 9pt;  color="#000000">:
                                                    {{ isset($dataRegis->dokterpemeriksa ) ? $dataRegis->dokterpemeriksa : '-'  }}
                                                    {{-- $dataReport['details'][0]->clinician ? $dataReport['details'][0]->clinician : '-'  --}}
                                                </font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 10px; padding-bottom:7px;">&nbsp;</td>
                                            <td style="padding-bottom:7px;">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th align="left"
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;padding-left: 3px;">
                            PEMERIKSAAN</th>
                        <th
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;">
                            HASIL</th>
                        <th
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;">
                            SATUAN</th>
                        <th
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;">
                            NILAI NORMAL</th>
                        <th
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;">
                            METODE</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataReport['details'] as $data)
                        @php
                            if ($data->nilaitext) {
                                $nilai = explode(' ~ ', $data->nilaitext);
                                $hasil = '';
                                $min = $nilai[0];
                                if (isset($nilai[1])) {
                                    $max = $nilai[1];
                                    $hasil = $data->hasil < $min ? ($hasil = 'Low') : ($hasil = '');
                                    $hasil = $data->hasil > $max ? ($hasil = 'High') : ($hasil = '');
                                }
                            }
                            $analis = '';
                            if ($analis == '' && isset($data->analis)) {
                                $analis = isset($data->analis) ? $data->analis : '';
                            }
                        @endphp
                        <tr>
                            <td colspan="5" style="text-align:left; padding-left: 3px;">{{-- $data->test_group ? $data->test_group :'' --}}</td>
                        </tr>
                        <tr>
                            <td
                                style="padding-left: {{ $data->hasil ? '20px' : '3px' }}; font-weight: {{ $data->hasil ? 'none' : 'bold' }};border-top: none;">
                                &nbsp; {{ $data->hasil ? ' - ' : '' }}{{ $data->detailpemeriksaan }}</td>
                            <td style="text-align:center;">
                                <table cellspacing="0" cellpadding="0" border="0" style="width: 100%">
                                    <tr style="padding: 0">
                                        <td style="padding: 0; width:10%"></td>
                                        @if ($data->flag == 'H')
                                            <td style="padding: 0; color: red; font-weight: bold;" align="left">
                                                {{ $data->hasil }}</td>
                                        @elseif($data->flag == 'L')
                                            <td style="padding: 0; color: #0000ff; font-weight: bold;" align="left">
                                                {{ $data->hasil }}</td>
                                        @else
                                            <td style="padding: 0; font-weight: bold;" align="left">
                                                {{ $data->hasil }}</td>
                                        @endif
                                        @if ($data->flag == 'H')
                                            <td style="padding: 0; color: red; font-weight: bold; padding-right: 5px"
                                                align="right">{{ $data->flag }}</td>
                                        @elseif($data->flag == 'L')
                                            <td style="padding: 0; color: #0000ff; font-weight: bold; padding-right: 5px"
                                                align="right">{{ $data->flag }}</td>
                                        @else
                                            <td style="padding: 0; font-weight: bold; padding-right: 5px"
                                                align="right">
                                                {{ $data->flag }}</td>
                                        @endif
                                    </tr>
                                </table>
                            </td>
                            <td style="text-align:center;">
                                {{ $data->satuanstandar }}</td>
                            <td style="text-align:center;">
                                {{ $data->nilaitext }}</td>
                            <td style="text-align:center;">
                                {{ $data->metode }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <hr>
        <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="100%">
            {{-- <tr>
                <td style="text-align: center;vertical-align: middle" width="20%">
                    <img src="data:image/png;base64, {!! $tte !!}" width="100" height="100"><br>
                    <u>{{ isset($dataRegis->dokterpemeriksa) ? $dataRegis->dokterpemeriksa : '-'  }}</u><br>
                    <u></u><br>
                    Penanggung Jawab
                </td>
            </tr> --}}
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td style="30%"></td>
                            <td style="text-align:center">
                                <font style="font-size: 9pt;" color="#000000" ><u>Pemeriksa</u></font>
                            </td>
                            <td style="text-align:center">
                                <font style="font-size: 9pt;" color="#000000" ><u>Penanggung Jawab</u></font>
                            </td>
                        </tr>
                        <br>
                        <tr>
                            <td style="30%"></td>
                            <td style="text-align: center;vertical-align: middle" >
                                <img src="data:image/png;base64, {!! $ttepegawaiverif !!}" width="80" height="90"><br><br>
                                <u>{{ isset($dataRegis->namapegawaiverif) ? $dataRegis->namapegawaiverif : '-'  }}</u><br>
                            </td>
                            {{-- <td style="text-align:center"> --}}
                                {{-- <font style="font-size: 9pt;font-weight: 600" color="#000000" ><u>{{ $dataReport['details'][0]->pegawaiverifikator ?? "" }}</u></font> --}}
                                {{-- <font style="font-size: 9pt;font-weight: 600" color="#000000" ><u>{{ isset($dataRegis->namapegawaiverif) ? $dataRegis->namapegawaiverif : '-' }}</u></font> --}}
                            {{-- </td> --}}
                            {{-- <td style="text-align:center;">
                                <img src="data:image/png;base64, {!! $tte !!}" width="100" height="100" style="">
                                <font style="font-size: 9pt;font-weight: 600" color="#000000" ><u>{{ isset($dataRegis->dokterpemeriksa ) ? $dataRegis->dokterpemeriksa : '-'  }}</u></font>
                            </td> --}}
                            <td style="text-align: center;vertical-align: middle" >
                                <img src="data:image/png;base64, {!! $ttedokterverif !!}" width="80" height="90"><br><br>
                                <u>{{ isset($dataRegis->dokterpemeriksa) ? $dataRegis->dokterpemeriksa : '-'  }}</u><br>
                            </td>
                        </tr>
                        <tr>
                            <td style="30%"></td>
                            <td style="text-align:center">
                                <br>
                                <font style="font-size: 9pt;font-weight: 600" color="#000000" >NIP: {{ isset($dataRegis->nipverifikator ) ? $dataRegis->nipverifikator : '-' }}</font>
                            </td>
                            <td style="text-align:center;">
                                <br>
                                <font style="font-size: 9pt;font-weight: 600" color="#000000" >NIP : {{ isset($dataRegis->nippegawaiverifikator ) ? $dataRegis->nippegawaiverifikator : '-'  }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom:30px;">
                                <font style="font-size: 8pt;font-weight: 600" color="#000000" ></font>
                            </td>
                            <td style="padding-bottom:30px;"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>

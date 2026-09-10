<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cetak Laporan Jenazah</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> --}}
    <style>
        span {
            font-family: sans-serif;
        }
    </style>
</head>

<body>

    <table style="" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th width="90%">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <th style="" width="5%">
                            <img src="{{ 'img/logo-rs.png' }}" width="60px">
                        </th>
                        <th style="" width="70%">
                            <table width="100%">
                                <tr>
                                    <td style="text-align:left;padding:0px;margin:0px">
                                        <span style="text-transform: uppercase;font-size:9pt">RSUP FATMAWATI</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;padding:0px;margin:0px">
                                        <span style="text-transform: uppercase;font-size:9pt;vertical-align:top">JL. RS. FATMAWATI - CILANDAK</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;padding:0px;margin:0px">
                                        <span style="text-transform: uppercase;font-size:9pt;vertical-align:top">JAKARTA SELATAN</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;padding:0px;margin:0px">
                                        <span style="text-transform: uppercase;font-size:9pt;vertical-align:top">TELP. (021) - 7501524 & 7660552 (HUNTING)</span>
                                    </td>
                                </tr>
                            </table>
                        </th>
                    </tr>
                </table>
            </th>
            <th width="10%">
                <table width="100%" cellspacing="0" cellpadding="0" style="position:relative;top:-2.1rem">
                    <tr>
                        <th style="">
                            <span style="text-transform: uppercase;font-size:8pt;font-weight:normal;">AM.01.001.R0</span>
                        </th>
                    </tr>
                </table>
            </th>
        </tr>
    </table>

    <table style="margin-top:5px" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th>
                <span style="text-transform: uppercase;font-size:10pt">LAPORAN PENGGUNAAN AMBULANCE</span>
            </th>
        </tr>
        <tr>
            <th>
                <span style="text-transform: uppercase;font-size:10pt">AMBULANCE</span>
            </th>
        </tr>
        <tr>
            <th>
                <span style="text-transform: uppercase;font-size:10pt">{{date('d/m/Y',strtotime($tglAwal))}} s.d. {{date('d/m/Y',strtotime($tglAkhir))}}</span>
            </th>
        </tr>
        <tr>

        </tr>
    </table>

    <table style="margin-top:.6rem;" width="100%" cellspacing="0" cellpadding="0" >
        <tr>
            <td style="text-align:right" width="89%">
                <span style="font-size:9pt;font-weight:bold;">Tgl. Cetak</span>
            </td>
            <td style="text-align:right" width="2%">
                <span style="text-transform: uppercase;font-size:9pt;font-weight:bold;">:</span>
            </td>
            <td style="text-align:center">
                <span style="text-transform: uppercase;font-size:9pt;font-weight:bold;">{{date('d/m/Y h:i')}}</span>
            </td>
        </tr>
    </table>

    <table style="border:1px solid black;margin-top:.3rem" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th style="border:1px solid black;padding:4px" width="3%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">no</span>
            </th>
            <th style="border:1px solid black;padding:4px" width="15%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">nama pasien</span>
            </th>
            <th style="border:1px solid black;padding:4px" width="1%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">T g l</span>
            </th>
            <th style="border:1px solid black;padding:4px" width="8%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">NO.RM</span>
            </th>
            <th style="border:1px solid black;padding:4px;" width="10%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">Nomor pendaftaran</span>
            </th>
            <th style="border:1px solid black;padding:4px" width="10%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">No suart jalan amb</span>
            </th>
            <th style="border:1px solid black;padding:4px" width="10%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">Ruang</span>
            </th>
            <th style="border:1px solid black;padding:4px" width="10%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">Nama Perawat</span>
            </th>
            <th style="border:1px solid black;padding:4px" width="10%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">Nama Pengemudi</span>
            </th>
            <th style="border:1px solid black;padding:4px" width="15%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">Tujuan</span>
            </th>
            <th style="border:1px solid black;" width="10%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">Zona</span>
            </th>
            <th style="border:1px solid black;padding:4px" width="10%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">Biaya</span>
            </th>
            <th style="border:1px solid black;padding:4px">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">Bayar</span>
            </th>
            <th style="border:1px solid black;padding:4px">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">Piutang Dinas</span>
            </th>
            <th style="border:1px solid black;padding:4px">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">Bebas</span>
            </th>
            <th style="border:1px solid black;padding:4px" width="10%">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">No. Polisi</span>
            </th>
            <th style="border:1px solid black;padding:4px">
                 <span style="text-transform:uppercase;font-size:8pt;font-weight:bold;">Keperluan</span>
            </th>
        </tr>
        
         @foreach ($dataReport as $data)
        <tr>
            <td style="border:1px solid black;text-align:center;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{$loop->iteration}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{$data->namapasien}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{date('d',strtotime($data->tglorder))}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{$data->nocm}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{$data->noregistrasi}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{$data->nosurat}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{$data->namaruangan}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                <span style="font-size:7.5pt;text-transform:uppercase;">{{$data->namaperawat}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{$data->pengemudi}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{$data->tujuan}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt;">{{$data->zona}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{number_format($data->biaya, 0, ',', '.')}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{$data->statusbayar}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">-</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">-</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{$data->nopolisi}}</span>
            </td>
            <td style="border:1px solid black;text-align:left;padding:5px">
                 <span style="text-transform:uppercase;font-size:7.5pt">{{$data->keperluan}}</span>
            </td>
        </tr>
        @endforeach
    </table>
</body>

</html>

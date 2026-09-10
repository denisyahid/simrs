<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetakan Resume Medis</title>

    <style>
        @page {
            padding: 0;
        }

        small {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        .block {
            display: block;
        }

        .salinan {
            width: auto;
            border: 1px solid black;
            padding: 1px 17px;
            margin-bottom: .6rem;
        }

        .tebal {
            font-weight: bold
        }

        .inner-table,
        .inner-th,
        .inner-td {
            border: 1px solid black;
        }

        .inner-td {
            padding: 3px;
        }

        .normal {
            font-weight: normal;
            font-family: Arial;
        }

        .label-top {
            vertical-align: top;
        }

        .medium {
            font-size: 10.5pt;
        }

        hr {
            border: 0.5px solid black;
            margin: 1px;
        }

        .styled-pre {
            font-weight: normal;
            font-family: Arial;
            font-size: 10.5;
            color: black;
            display: unset;
        }
    </style>
</head>

<body>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse;border-bottom:none">
        <thead>
            <tr>
                <th width="60%" style="border: 1px solid black">
                    <table style="width:100%;border-collapse: collapse">
                        <tr>
                            <td width="20%" style="text-align: right">
                                <img src="img/logo-rs.png" style="width: 50px; padding-left:5px">
                            </td>
                            <td>
                                <table style="border-collapse: collapse;" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style=" text-align: center;padding:0px">
                                            <small style="text-transform:uppercase;font-size:9pt;display: block;"
                                                class="normal">{{$profile->reportdisplay}}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <small style="text-transform:uppercase;font-size:9pt;display: block;"
                                                class="normal">Dinas Kesehatan
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <small style="text-transform:uppercase;font-size:9pt;font-weight:bold;"
                                                class="normal">Rumah Sakit Bali Mandara</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <small style="font-size:9pt;" class="normal">{{$profile->alamatlengkap}} . Tlp.{{$profile->fixedphone}}, ${{$profile->faksimile}}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <small style="font-size:9pt;" class="normal">Email : {{$profile->alamatemail}}</small>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </th>
                <th width="40%" rowspan="3"
                    style="border-top: 1px solid black;vertical-align:baseline;position:relative;">
                    <table style="margin-top:3px; padding:0px;position:relative;top:1.5%" width="100%">
                        <tr>
                            <td style="width:35%;">
                                <span class="medium normal">Nomor RM</span>
                            </td>
                            <td style="width:2%;">
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ $identitas['nocm'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-top">
                                <span class="medium normal">Nama</span>
                            </td>
                            <td class="label-top">
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ $identitas['namapasien'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="medium normal">Tanggal Lahir</span>
                            </td>
                            <td>
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ date('d/m/Y', strtotime($identitas['tgllahir'])) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="medium normal">Jenis Kelamin</span>
                            </td>
                            <td>
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ $identitas['jeniskelamin'] }}</span>
                            </td>
                        </tr>
                        {{-- {{dd($data['pasien'])}} --}}
                        <tr>
                            <td>
                                <span class="medium normal">Ruangan</span>
                            </td>
                            <td>
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ $identitas['namaruangan'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="medium normal">Status</span>
                            </td>
                            <td>
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ $data['pasien']['kelompokpasien'] }}</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th style="border: 1px solid black;text-align: center;border-bottom:none;position:relative;">
                    <span class=tebal
                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size:15pt;text-transform:uppercase;position:relative;top:-4px">Permohonan Permintaan Alat Khusus Untuk Acara Operasi</span>
                </th>
            </tr>
        </thead>
    </table>

    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top:-50px">
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="normal" style="font-size:10.5pt;display:block">Tanggal Rencana Operasi :</span>
            </td>
            <td style="border: 1px solid black;text-align: left;padding-left:4px" width="35%">
                <span class="normal">{{isset($data['tglRencanaOperasi']) ? date('d-m-Y h:m:s', strtotime($data['tglRencanaOperasi'])) : ''}}</span>

            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="normal" style="font-size:10.5pt;display:block">Kamar Operasi :</span>
            </td>
            <td style="border: 1px solid black;text-align: left;padding-left:4px" width="35%">
                <span class="normal">{{isset($data['kamarOperasi']) ? $data['kamarOperasi'] : ''}}</span>

            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="normal" style="font-size:10.5pt;display:block">Diagnosa Medis :</span>
            </td>
            <td style="border: 1px solid black;text-align: left;padding-left:4px" width="35%">
                <span class="normal">{{isset($data['diagnosaMedis']) ? $data['diagnosaMedis'] : ''}}</span>

            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="normal" style="font-size:10.5pt;display:block">Rencana Tindakan :</span>
            </td>
            <td style="border: 1px solid black;text-align: left;padding-left:4px" width="35%">
                <span class="normal">{{isset($data['rencanaTindakan']) ? $data['rencanaTindakan'] : ''}}</span>

            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="normal" style="font-size:10.5pt;display:block">Rencana Anestesi :</span>
            </td>
            <td style="border: 1px solid black;text-align: left;padding-left:4px" width="35%">
                <span class="normal">{{isset($data['rencanaAnastesi']) ? $data['rencanaAnastesi'] : ''}}</span>

            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="normal" style="font-size:10.5pt;display:block">Jenis Operasi :</span>
            </td>
            <td style="border: 1px solid black;text-align: left;padding-left:4px" width="35%">
                <span class="normal">{{isset($data['jenisOperasi']) ? $data['jenisOperasi'] : ''}}</span>

            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="normal" style="font-size:10.5pt;display:block">Operator :</span>
            </td>
            @if(isset($data['operator']) && isset($data['operator']['label']))
                <td style="border: 1px solid black;text-align: left;padding-left:4px" width="35%">
                    <span class="normal">{{ $data['operator']['label'] }}</span>
                </td>
            @elseif(isset($data['operator']) && !isset($data['operator']['label']))
                <td style="border: 1px solid black;text-align: left;padding-left:4px" width="35%">
                    <span class="normal">{{ $data['operator'] }}</span>
                </td>
            @else
                <td style="border: 1px solid black;text-align: left;padding-left:4px" width="35%">
                    <span class="normal">-</span>
                </td>
            @endif
        </tr>
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="normal" style="font-size:10.5pt;display:block">Peralatan Khusus Yang Dipesan :</span>
            </td>
            <td style="border: 1px solid black;text-align: left;padding-left:4px" width="35%">
                <span class="normal">{{isset($data['peralatanKhususYangDipesan']) ? $data['peralatanKhususYangDipesan'] : ''}}</span>

            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="normal" style="font-size:10.5pt;display:block">Implan yang Dipesan :</span>
            </td>
            <td style="border: 1px solid black;text-align: left;padding-left:4px" width="35%">
                <span class="normal">{{isset($data['implanYangDipesan']) ? $data['implanYangDipesan'] : ''}}</span>

            </td>
        </tr>
    </table>

    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top:-50px">
        <tr>
            <td style="border: 1px solid black;text-align: left;padding-left:4px; text-align: center" width="35%">
                <br>
                <span class="normal">Pemohon</span> <br>
                <img src="{{ $data['TTDPemohon'] }}" alt="" srcset=""> <br>
                <span class="normal">{{isset($data['pemohon']) ? $data['pemohon']['label'] : ''}}</span>
            </td>
            <td style="border: 1px solid black;text-align: left;padding-left:4px; text-align: center" width="35%">
                <br>
                <span class="normal">Satelit Farmasi IBSA</span> <br>
                <img src="{{ $data['TTDDepoFarmasi'] }}" alt="" srcset=""> <br>
                <span class="normal">{{isset($data['depoFarmasi']) ? $data['depoFarmasi']['label'] : ''}}</span>
            </td>
            <td style="border: 1px solid black;text-align: left;padding-left:4px; text-align: center" width="35%">
                <span>Garut, {{isset($data['created_at']) ? date('d-m-Y ', strtotime($data['created_at'])) : ''}}</span> <br>
                <span class="normal">Kepala Ruangan</span> <br>
                <img src="{{ $data['TTDKepalaRuangan'] }}" alt="" srcset=""> <br>
                <span class="normal">{{isset($data['kepalaRuangan']) ? $data['kepalaRuangan']['label'] : ''}}</span>
            </td>
        </tr>
    </table>
</body>

</html>

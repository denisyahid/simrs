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

    <table style="border: 1px solid black;width:100%;border-collapse: collapse;">
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
                                                class="normal">{{$profile->namapemerintahan}}</small>
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
                                                class="normal">{{$profile->namalengkap}}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <small style="font-size:9pt;" class="normal">{{$profile->alamatlengkap}} . Tlp.{{$profile->fixedphone}}, Fax.${{$profile->faksimile}}</small>
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
                                <span class="medium tebal">{{$pasien->nocm}}</span>
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
                                <span class="medium tebal">{{$pasien->namapasien}}</span>
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
                                <span class="medium tebal">{{ date('d/m/Y', strtotime($pasien->tgllahir)) }}</span>
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
                                <span class="medium tebal">{{$pasien->jeniskelamin}}</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th style="border: 1px solid black;text-align: center;border-bottom:none;position:relative;">
                    <span class=tebal
                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size:15pt;text-transform:uppercase;position:relative;">Laporan Insiden Internal</span>
                </th>
            </tr>
        </thead>
    </table>

    <table width=100% 
        <tr>
            <td width="17%">
                <span class="normal medium">Tanggal & waktu Insiden</span>
            </td>
            <td width="2%">
                 <span class="normal medium">:</span>
            </td>
            <td width="50%">
                 <span class="normal medium">{{ date('d/m/Y H:i:s', strtotime($data->tglinsiden)) }}</span>
            </td>
        </tr>
        <tr>
            <td width="17%">
                  <span class="normal medium">Keselamatan</span>
            </td>
            <td width="2%">
                 <span class="normal medium">:</span>
            </td>
            <td width="50%">
                <span class="normal medium">{{$data->keselamatan}}</span>
            </td>
        </tr>
        <tr>
            <td width="17%">
                <span class="normal medium">Jenis Keselamatan</span>
            </td>
            <td width="2%">
                <span class="normal medium">:</span>
            </td>
            <td width="50%">
                <span class="normal medium">{{$data->jeniskeselamatan}}</span>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:top" width="17%">
             <span class="normal medium">Insiden</span>
            </td>
            <td style="vertical-align:top" width="2%">
             <span class="normal medium">:</span>
            </td>
            <td width="50%">
                <span class="normal medium">{{$data->insiden ? $data->insiden : ''}}</span>
            </td>
        </tr>
    </table>

    <table width=100% style="margin-top:5px">
        <tr>
            <td style="vertical-align:top">
                <span class="normal medium">Jenis Insiden</span>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->jenisinsiden) && $data->jenisinsiden == '1' ? 'checked' : '' }}  />
                <span class="normal medium" color="#000000">Sentinel</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->jenisinsiden) && $data->jenisinsiden == '2' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Kejadian Tidak Diharapkan (KTD)</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->jenisinsiden) && $data->jenisinsiden == '3' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Kejadian Tidak Cidera (KTC)</span>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->jenisinsiden) && $data->jenisinsiden == '4' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Kejadian Nyaris Cidera (KNC)</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->jenisinsiden) && $data->jenisinsiden == '5' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Kondisi Potensial Cidera ( KPC)</span>
            </td>
        </tr>
    </table>

    <table width=100% style="margin-top:5px">
        <tr>
            <td style="vertical-align:top">
                <span class="normal medium">Orang Pertama Yang Melaporkan Insiden</span>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" checked style="vertical-align:bottom" {{ isset($data->pelaporinsiden) && $data->pelaporinsiden == '1' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Karyawan : Dokter / Perawat / Petugas Lainnya</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->pelaporinsiden) && $data->pelaporinsiden == '2' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Keluarga / Pendamping Pasien</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom"  {{ isset($data->pelaporinsiden) && $data->pelaporinsiden == '3' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Pasien</span>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" style="vertical-align:bottom"  {{ isset($data->pelaporinsiden) && $data->pelaporinsiden == '4' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Pengunjung</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom"  {{ isset($data->pelaporinsiden) && $data->pelaporinsiden == '5' ? 'checked' : '' }}/>
                <span class="normal medium" color="#000000">Lain-lain</span>
            </td>
        </tr>
    </table>
    <table width=100% style="margin-top:5px">
        <tr>
            <td style="vertical-align:top" width="17%">
               <span class="normal medium">Lokasi Insiden</span>
            </td>
            <td width="2%">
                <span class="normal medium"> : </span>
            </td>
            <td width="50%">
                <span class="normal medium">{{isset($data->tempatinsiden) ? $data->tempatinsiden : ''}}</span>
            </td>
        </tr>
    </table>
    <table width=100% style="margin-top:5px">
        <tr>
            <td style="vertical-align:top">
                <span class="normal medium">Insiden Menyangkut Pasien</span>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->insidenterjadi) && $data->insidenterjadi == '1' ? 'checked' : '' }} />
                <span style="font-size: 9pt;"class="normal medium" color="#000000">Pasien Rawat Inap</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->insidenterjadi) && $data->insidenterjadi == '2' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Pasien Rawat Jalan</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->insidenterjadi) && $data->insidenterjadi == '3' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Pasien IGD</span>
            </td>
            <td width=20%>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->insidenterjadi) && $data->insidenterjadi == '4' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Lain-lain</span>
            </td>
        </tr>
    </table>

    <table width=100% style="margin-top:5px">
        <tr>
            <td style="vertical-align:top">
                <span class="normal medium">Insiden terjadi pada pasien ( jiwa dan sub spesialisasnya)</span>
            </td>
        </tr>
    </table>
    <table width=100%>
        <tr>
            <td>
                <input type="checkbox" {{ isset($data->jiwa) && $data->jiwa == '1' ? 'checked' : '' }}/>
                <span class="normal-medium" style="vertical-align:top" color="#000000">Anak Remaja</span>
            </td>
            <td>
                <input type="checkbox" {{ isset($data->jiwa) && $data->jiwa == '2' ? 'checked' : '' }} />
                <span class="normal-medium" style="vertical-align:top" color="#000000">Napza</span>
            </td>
            <td>
                <input type="checkbox" {{ isset($data->jiwa) && $data->jiwa == '3' ? 'checked' : '' }}/>
                <span class="normal-medium" style="vertical-align:top" color="#000000">Dewasa</span>
            </td>
            <td width=20%>
                <input type="checkbox" {{ isset($data->jiwa) && $data->jiwa == '4' ? 'checked' : '' }} />
                <span class="normal-medium" style="vertical-align:top" color="#000000">Lansia</span>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" {{ isset($data->jiwa) && $data->jiwa == '5' ? 'checked' : '' }} />
                <span class="normal-medium" style="vertical-align:top" color="#000000">GMO</span>
            </td>
            <td>
                <input type="checkbox" {{ isset($data->jiwa) && $data->jiwa == '6' ? 'checked' : '' }}/>
                <span class="normal-medium" style="vertical-align:top" color="#000000">ELektromedik</span>
            </td>
            <td>
                <input type="checkbox" {{ isset($data->jiwa) && $data->jiwa == '7' ? 'checked' : '' }} />
                <span class="normal-medium" style="vertical-align:top" color="#000000">Lain-lain</span>
            </td>
        </tr>
    </table>
    <table width=100% style=";margin-top:5px">
        <tr>
            <td style=";vertical-align:top" width="17%">
                <span class="normal medium">Unit Kerja Penyebab</span>
            </td>
            <td width="2%">
                <span class="normal medium"> : </span>
            </td>
            <td width="50%">
                <span class="normal medium">{{isset($data->unitterkait) ? $data->unitterkait : ''}}</span>
            </td>
        </tr>
    </table>

    <table width=100% style="margin-top:5px">
        <tr>
            <td style="vertical-align:top">
                <span class="normal medium">Akibat Insiden Terhadap Pasien</span>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->akibatinsiden) && $data->akibatinsiden == '1' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Kematian</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->akibatinsiden) && $data->akibatinsiden == '3' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Cedera Reversibel / Cedera Sedang</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->akibatinsiden) && $data->akibatinsiden == '2' ? 'checked' : '' }}/>
                <span class="normal medium" color="#000000">Cedera Irreversibe / Cedera Berat</span>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->akibatinsiden) && $data->akibatinsiden == '4' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Cedera Ringan</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->akibatinsiden) && $data->akibatinsiden == '5' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Tidak Ada Cedera</span>
            </td>
        </tr>
    </table>

    <table width=100% style="margin-top:5px">
        <tr>
            <td style="vertical-align:top">
                <span class="normal medium">Tindakan yang Dilakukan Segera Setelah Kejadian, dan hasilnya : </span>
            </td>
        </tr>
        <tr>
            <td><span class="normal medium">{{isset($data->penanganan) ? $data->penanganan : ''}}</span></td>
        </tr>
    </table>
    <table width=100% style="margin-top:5px">
        <tr>
            <td style="vertical-align:top">
                <span class="normal medium">Apakah Kejadian yang sama pernah terjadi di Unit Kerja Lain ? </span>
            </td>
        </tr>
    </table>
    <table width=100%>
        <tr>
            <td>
                <input type="checkbox" {{ isset($data->kejadiansama) && $data->kejadiansama == '1' ? 'checked' : '' }} />
                <span class="normal medium" style="vertical-align:top" color="#000000">Ya</span>
            </td>
            <td width=60%>
                <input type="checkbox" {{ isset($data->kejadiansama) && $data->kejadiansama == '2' ? 'checked' : '' }} />
                <span class="normal medium" style="vertical-align:top" color="#000000">Tidak</span>
            </td>
        </tr>
    </table>

    <table width=100% style="margin-top:5px">
        <tr>
            <td style="vertical-align:top">
                <span class="normal medium">Tindakan dilakukan oleh</span>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->dilakukanoleh) && $data->dilakukanoleh == '1' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Tim : Terdiri</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->dilakukanoleh) && $data->dilakukanoleh == '2' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Dokter</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->dilakukanoleh) && $data->dilakukanoleh == '4' ? 'checked' : '' }} />
                <span class="normal medium" color="#000000">Perawat</span>
            </td>
            <td>
                <input type="checkbox" style="vertical-align:bottom" {{ isset($data->dilakukanoleh) && $data->dilakukanoleh == '3' ? 'checked' : '' }}/>
                <span class="normal medium" color="#000000">Petugas Lainnya</span>
            </td>
        </tr>
    </table>
    <table width=100% style="margin-top:5px">
        <tr>
            <td style="vertical-align:top">
                <span class="normal medium"> Kapan & Langkah apa yang telah diambil pada Unit kerja tersebut untuk mencegah terulangnya kejadiannya yang sama : </span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="normal medium">{{isset($data->langkahpenanganan) ? $data->langkahpenanganan  : ''}}</span>
            </td>
        </tr>
    </table>

    <table width=100% style="margin-top:5px">
        <tr>
            <td width=50%>
                <table width=100%>
                    <tr>
                        <td> <span class="normal medium">Pembuat Laporan : </span></td>
                    </tr>
                    <tr>
                        <td> <span class="normal medium">{{isset($data->pembuatlaporan) ? $data->pembuatlaporan : ''}}</span> </td>
                    </tr>
                </table>
            </td>
            <td width=50%>
                <table width=100%>
                    <tr>
                        <td> <span class="normal medium">Tanggal Laporan : </span> </td>
                    </tr>
                    <tr>
                        <td> <span class="normal medium">{{ isset($data->tgllapor) ? date('d/m/Y', strtotime($data->tgllapor)) : ''}}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width=100% style="margin-top:5px">
        <tr>
            <td width=50%>
                <table width=100% >
                    <tr>
                        <td> <span class="normal medium">Penerima Laporan (Ka.RU/Ka.Ins) : </span> </td>
                    </tr>
                    <tr>
                        <td> <span class="normal medium">{{isset($data->penerimalaporan) ? $data->penerimalaporan : ''}}</span> </td>
                    </tr>
                </table>
            </td>
            <td width=50%>
                <table width=100% >
                    <tr>
                        <td> <span class="normal medium"> Tanggal Terima : </span></td>
                    </tr>
                    <tr>
                        <td> <span class="normal medium">{{ isset($data->tglterima) ? date('d/m/Y', strtotime($data->tglterima)) : ''}}</span> </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
     <table width=100% style="margin-top:5px">
        <tr>
            <td style="vertical-align:top">
                <span class="normal medium">Grading Risiko Kejadian (Diisi oleh atasan pelapor)</span>
            </td>
        </tr>
    </table>
    <table width=100%>
        <tr>
            <td>
                <input type="checkbox" {{ isset($data->grading) && $data->grading == '1' ? 'checked' : '' }} />
                <span class="normal medium" style="vertical-align:top" color="#000000">Biru</span>
            </td>
            <td>
                <input type="checkbox" {{ isset($data->grading) && $data->grading == '2' ? 'checked' : '' }}  />
                <span class="normal medium" style="vertical-align:top" color="#000000">Hijau</span>
            </td>
            <td>
                <input type="checkbox" {{ isset($data->grading) && $data->grading == '3' ? 'checked' : '' }} />
                <span class="normal medium" style="vertical-align:top" color="#000000">Kuning</span>
            </td>
            <td>
                <input type="checkbox"  {{ isset($data->grading) && $data->grading == '4' ? 'checked' : '' }} />
                <span class="normal medium" style="vertical-align:top" color="#000000">Merah</span>
            </td>
        </tr>
    </table> 
   


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cetak Resep Obat</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        :root {
            font-family: 'Arial Narrow';
        }

        font {
            font-family: 'Arial Narrow';
        }

        @font-face {
            font-family: 'Arial Narrow';
        }

        .table-bordered tr td {
            border: 1px solid #444444;
        }

        .label-strong {
            text-align: center;
            font-size: 13.4pt;
        }

        .label-normal {
            font-weight: 400;
            text-align: left;
            font-size: 13.4pt;
        }

        .label-right {
            text-align: right;
            font-size: 13.4pt;
            font-weight: normal;
        }

        .label-left {
            text-align: left;
            font-size: 13.4pt;
            font-weight: normal;
        }

        body {
            font-family: 'Arial Narrow';
            width: 50%;
        }
    </style>
</head>

<body style="margin-left: -40px; padding-left: 5px;">
    <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: -30px;">
        <tr>
            <th>
                <img src="{{ 'img/logo-rs.png' }}" width="50px">
            </th>
            <th width="90%">
                <table width="100%" style="position:relative">
                    <tr>
                        <td class="label-strong" style="text-align:center">
                            <font style="font-size: 8pt;">{{ strtoupper($profile->namalengkap) }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-normal" style="text-align:center">
                            <font style="font-size: 8pt;">Jl. Bypass Ngurah Rai No.548, Kota Garut, Bali <br> e-Mail: {{ $profile->alamatemail }} &nbsp; No. Telp: (0361) 4490566 </font>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>

    <hr style="margin: 0px">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th width="90%">
                <table width="100%" style="position:relative">
                    <tr>
                        <td width="20%" style="text-align:center"> {{$dataReport['data']->jenis}}{{$dataReport['data']->noantri}} </td>
                        <td width="60%" class="label-strong" style="text-align:center; font-size: 9pt;">
                            RESEP OBAT
                        </td>
                            @if($dataReport['data']->isbpl == 1)
                            {
                                <td width="20%" style="border:1px solid black; text-align:center">
                                    BPL
                                </td>
                            }
                            @else 
                            <td width="20%">
                                    
                            </td>
                            @endif
                    </tr>
                </table>
            </th>
        </tr>
    </table>

    <hr style="margin: 0px">

    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse;">
        <tr>
            <td colspan="2" height="5"></td>
        </tr>

        <tr>
            <td colspan="2">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                    <td class="label-normal" width="12%"  style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            Tgl/Jam
                        </td>
                        <td class="label-normal" width="2%"  style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :
                        </td>
                        <td class="label-normal" width="30%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->tglresep}}
                        </td>
                        <td class="label-normal" width="18%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            Dokter
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :&nbsp;
                        </td>
                        <td class="label-normal" width="30%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->namalengkap}}
                        </td>
                    </tr>
                    <tr>
                        <td class="label-normal" width="12%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            No. Resep
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :
                        </td>
                        <td class="label-normal" width="32%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->noresep}}
                        </td>
                        <td class="label-normal" width="18%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            No SIP
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :&nbsp;
                        </td>
                        <td class="label-normal" width="30%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->nosip}}
                        </td>
                    </tr>
                    <tr>
                        <td class="label-normal" width="12%" height="10px" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            Kamar/Poli
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :
                        </td>
                        <td class="label-normal" width="30%" height="10px" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->ruanganpengorder}}
                        </td>
                        <td class="label-normal" width="18%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            Jaminan
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :&nbsp;
                        </td>
                        <td class="label-normal" width="30%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->kelopokpasien}}
                        </td>
                        
                    </tr>
                    <tr>
                        <td class="label-normal" width="12%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            NRM/Tgl.L
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :
                        </td>
                        <td class="label-normal" width="30%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->nocm}} / {{ date('d-m-Y', strtotime($dataReport['data']->tgllahir ?? '')) }}
                        </td>
                        <td class="label-normal" width="18%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            Alergi
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :&nbsp;
                        </td>
                        <td class="label-normal" width="30%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->alergiobat}}
                        </td>
                    </tr>
                    <tr>
                        <td class="label-normal" width="12%"  style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            Nama
                        </td>
                        <td class="label-normal" width="2%"  style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :
                        </td>
                        <td class="label-normal" width="30%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->namapasienjk}}
                        </td>
                        <td class="label-normal" width="18%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            TB
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :
                        </td>
                        <td class="label-normal" width="30%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['tb']}} CM
                        </td>
                    </tr>
                    <tr>
                        <td class="label-normal" width="12%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            Alamat
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :
                        </td>
                        <td class="label-normal" width="32%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->alamat}}
                        </td>
                        <td class="label-normal" width="18%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            BB
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :&nbsp;
                        </td>
                        <td class="label-normal" width="30%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                        {{$dataReport['bb']}}&nbsp; KG
                        </td>
                    </tr>
                    <tr>
                        <td class="label-normal" width="12%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            Phone
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :
                        </td>
                        <td class="label-normal" width="32%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->noteleponfaks}}
                        </td>
                        <td class="label-normal" width="18%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            Umur
                        </td>
                        <td class="label-normal" width="2%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            :&nbsp;
                        </td>
                        <td class="label-normal" width="30%" style="vertical-align: top; font-size:8pt; padding: 0; margin: 0; line-height: 1.9;">
                            {{$dataReport['data']->umur}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        @foreach ($dataReport['detail'] as $jenisracikannya)
        <hr style="margin: 0px" width="200%">
        <table width="200%" cellspacing="0" cellpadding="0">
            <tr>
                <th width="100%">
                    <table width="100%" style="position:relative">
                        <tr>
                            <td class="label-strong" style="text-align:center; font-size: 9pt;">
                                @if($jenisracikannya->first()->jenisracikan == 'Non-Racikan')
                                Non Racikan
                                @else
                                Racikan : {{$jenisracikannya->first()->racikan}} {{ $jenisracikannya->first()->jenisracikan }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>
        
        <hr style="margin: 0px" width="200%">

        <tr>
            <td colspan="2" height="5"></td>
        </tr>

        <tr>
            <td colspan="2">
                <table width="100%" cellspacing="0" cellpadding="0">
                    @php
                    $totalharga = 0;
                    $totaltagihan = 0;
                    $totalDiskon = 0;
                    @endphp

                    @foreach ($jenisracikannya as $detailproduk)
                    <tr>
                        <td width="85%">
                            <font style="font-size: 7pt; text-align: left;">
                                {{$detailproduk->namaprodukstandar}}
                            </font>
                        </td>
                        <td width="5%" style="text-align: center; vertical-align: top;">
                            <font style="font-size: 7pt; text-align: center">
                                No.
                            </font>
                        </td>
                        <td width="10%" style="text-align: right; vertical-align: top;">
                            <font style="font-size: 7pt; text-align: right">
                                {{ number_format($detailproduk->jumlah, 2, '.', '.') }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <font style="font-size: 7pt; text-align: center">
                                {{$detailproduk->aturanpakai1}} {{$detailproduk->satuanstandar}}
                            </font>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach

                    @php
                        $totalharga += $jenisracikannya->sum('totalharga');
                    @endphp

                    <tr>
                        <td style="padding-top:5px"></td>
                    </tr>
                </table>
            </td>
        </tr>
        @endforeach


        <tr>
            <td colspan="3" style="border-top: 1px solid #444444;padding: 3px 0 3px 0;border-bottom: 1px solid #444444;text-align: right;">
                <font style="font-size: 8pt;text-align: right;">
                    Total = Rp.{{ number_format($dataReport['totalharga'], 2, ',', '.') }}
                </font>
            </td>
        </tr>
    
    <tr>
        <td colspan="2" height="10"></td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <font style="font-size: 8pt;">Yang Menyerahkan,</font>
        </td>
        <td style="text-align: center;">
            <font style="font-size: 8pt;">Yang Menerima,</font>
        </td>
    </tr>
    <tr>
        <td colspan="2" height="30"></td>
    </tr>
    <tr>
        <td style="text-align: center">
            <font style="font-size: 8pt;">(.................................)</font>
        </td>
        <td style="text-align: center">
            <font style="font-size: 8pt;">(.................................)</font>
        </td>
    </tr>
    <tr>
        <td>
            <font style="font-size: 8pt;">Jam :</font>
        </td>
    <tr>

    </tr>
    <tr>
        <td colspan="2" height="2"></td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%">
                        <table width="100%" cellspacing="0" cellpadding="0" class="table-bordered">
                            <tr>
                                <td style="text-align: center" rowspan="2">
                                    <font style="font-size: 8pt;text-align: center">Cetak Rx</font>
                                </td>
                                <td style="text-align: center" colspan="2">
                                    <font style="font-size: 8pt;text-align: center">Penyiapan</font>
                                </td>
                                <td style="text-align: center" colspan="2">
                                    <font style="font-size: 8pt;text-align: center">Etiket</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center">Jam</font>
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center">Paraf</font>
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center">Jam</font>
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center">Paraf</font>
                                </td>
                            </tr>
                            <tr>
                                <td height="20" style="text-align: center;">
                                    <font style="font-size: 8pt;text-align: center">{{date('H:i')}}</font>
                                </td>
                                <td height="20"></td>
                                <td height="20"></td>
                                <td height="20"></td>
                                <td height="20"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" cellspacing="0" cellpadding="1">
                <tr>
                    <td width="40%">
                        <table width="100%" cellspacing="0" cellpadding="0" class="table-bordered" style="margin-top:3px";>>
                            <tr>    
                                <td style="text-align: center; height:30px;" colspan="2">
                                    <font style="font-size: 8pt;text-align: center; vertical-align:middle">Pengkajian Resep <br></font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Tepat Pasien
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center;">
                                        @if($dataReport['data']->tepatpasien == 1)
                                        <font style="font-weight:bold">Y</font> /  <s>T</s>
                                        @else 
                                        <s>Y</s> / <font style="font-weight:bold">T</font>
                                        @endif
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Tepat Obat
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center;">
                                        @if($dataReport['data']->tepatobat == 1)
                                        <font style="font-weight:bold">Y</font> /  <s>T</s>
                                        @else 
                                        <s>Y</s> / <font style="font-weight:bold">T</font>
                                        @endif
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Campuran Obat Stabil
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center;">
                                        @if($dataReport['data']->campuranobat == 1)
                                        <font style="font-weight:bold">Y</font> /  <s>T</s>
                                        @else 
                                        <s>Y</s> / <font style="font-weight:bold">T</font>
                                        @endif
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Tepat Dosis / Kekuatan / Frekuensi</font>
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center;">
                                        @if($dataReport['data']->tepatdosis == 1)
                                        <font style="font-weight:bold">Y</font> /  <s>T</s>
                                        @else 
                                        <s>Y</s> / <font style="font-weight:bold">T</font>
                                        @endif
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Tepat Rute Pemberian</font>
                                </td>
                                <td style="text-align: center">
                                     <font style="font-size: 8pt;text-align: center;">
                                        @if($dataReport['data']->tepatrute == 1)
                                        <font style="font-weight:bold">Y</font> /  <s>T</s>
                                        @else 
                                        <s>Y</s> / <font style="font-weight:bold">T</font>
                                        @endif
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Duplikasi Obat</font>
                                </td>
                                <td style="text-align: center">
                                     <font style="font-size: 8pt;text-align: center;">
                                        @if($dataReport['data']->duplikasiobat == 1)
                                        <font style="font-weight:bold">Y</font> /  <s>T</s>
                                        @else 
                                        <s>Y</s> / <font style="font-weight:bold">T</font>
                                        @endif
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Interaksi Obat</font>
                                </td>
                                <td style="text-align: center">
                                     <font style="font-size: 8pt;text-align: center;">
                                        @if($dataReport['data']->interaksiobat == 1)
                                        <font style="font-weight:bold">Y</font> /  <s>T</s>
                                        @else 
                                        <s>Y</s> / <font style="font-weight:bold">T</font>
                                        @endif
                                    </font>
                                </td>
                            </tr>
                            <tr>
                            <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                <span style="font-size: 8pt; display: block; text-align: left;">
                                    Jenis Obat <span style="font-family: DejaVu Sans;">&ge;</span> 5
                                </span>
                            </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center;">
                                        @if($dataReport['data']->jenisobatl5 == 1)
                                        <font style="font-weight:bold">Y</font> /  <s>T</s>
                                        @else 
                                        <s>Y</s> / <font style="font-weight:bold">T</font>
                                        @endif
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Kontraindikasi
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center;">
                                        @if($dataReport['data']->kontraindikasi == 1)
                                        <font style="font-weight:bold">Y</font> /  <s>T</s>
                                        @else 
                                        <s>Y</s> / <font style="font-weight:bold">T</font>
                                        @endif
                                    </font>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="30%">
                        <table width="100%" cellspacing="0" cellpadding="0" class="table-bordered" style="margin-bottom:70px";>
                            <tr>
                                <td style="justify-content: center;text-align: center" colspan="2">
                                    <font style="font-size: 8pt;text-align: center">Telaah Obat <br>(Diisi Sebelum Penyerahan)</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Tepat Obat
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center;font-weight:bold">Y  /  T</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Tepat Pasien
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center;font-weight:bold">Y  /  T</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Tepat Jumlah & Dosis
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center;font-weight:bold">Y  /  T</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Tepat Rute
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center;font-weight:bold">Y  /  T</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="justify-content: center; align-items: center; font-size: 8pt;">
                                    Tepat Waktu & Frekuensi Pemberian
                                </td>
                                <td style="text-align: center">
                                    <font style="font-size: 8pt;text-align: center;font-weight:bold">Y  /  T</font>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
</body>

</html>
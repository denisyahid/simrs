<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cetak Resep Obat</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        :root {
            font-family: 'Arial Narrow';
        }
        font{
            font-family: 'Arial Narrow';
        }
        @font-face {
            font-family: 'Arial Narrow';
        }
        .table-bordered tr td{
            border: 1px solid #444444;
        }
        .label-strong {
            text-align: center;
            font-size: 13.4pt;
        }

        .label-normal {
            font-weight:400;
            text-align:left;
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
                <table width="100%"  style="position:relative">
                    <tr>
                        <td class="label-strong" style="text-align:center">
                            <font style="font-size: 8pt;">{{ strtoupper($profile->namalengkap) }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-normal" style="text-align:center">
                            <font style="font-size: 8pt;">Jl. Bypass Ngurah Rai No.548, Kota Garut, Bali <br> {{ $profile->alamatemail }}</font>
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
                <table width="100%"  style="position:relative">
                    <tr>
                        <td class="label-strong" style="text-align:center; font-size: 9pt;">
                            <font>PEMAKAIAN OBAT PASIEN</font>
                        </td>
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
                <td  colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="label-normal" width="10%">
                                <font style="font-size:8pt">Tgl/Jam</font>
                            </td>
                            <td class="label-normal" width="3%">
                                <font style="font-size:8pt">:</font>
                            </td>
                            <td class="label-normal" width="87%">
                                <font style="font-size:8pt; line-height: 10x;">{{ date('d-m-Y', strtotime($dataReport[0]->tglstruk ?? '')) }} / {{ date('H:m:s', strtotime($dataReport[0]->tglstruk ?? '')) }}</font>
                            </td>
                          </tr>
                        <tr>
                            <td class="label-normal" width="20%">
                                <font style="font-size:8pt">No. Resep</font>
                            </td>
                            <td class="label-normal" width="3%">
                                <font style="font-size:8pt">:</font>
                            </td>
                            <td class="label-normal" width="32%">
                                <font style="font-size:8pt">{{$dataReport[0]->nostruk}}</font>
                            </td>
                        </tr>

                        <tr>
                            <td class="label-normal" width="25%">
                                <font style="font-size:8pt">Tipe</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:8pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:8pt">{{$dataReport[0]->namarekanan}} / {{$dataReport[0]->kebangsaan}}</font>
                            </td>
                        </tr>

                        <tr>
                            <td class="label-normal" width="25%">
                                <font style="font-size:8pt">Nama </font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:8pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:8pt">{{$dataReport[0]->namapasien}}</font>
                            </td>
                        </tr>

                        <tr>
                            <td class="label-normal" width="25%">
                                <font style="font-size:8pt">Phone </font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:8pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:8pt">{{$dataReport[0]->noteleponfaks}}</font>
                            </td>
                        </tr>

                        <tr>
                            <td class="label-normal" width="25%">
                                <font style="font-size:8pt">Alamat </font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:8pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:8pt">{{$dataReport[0]->namatempattujuan}}</font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>
    <br>

    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width:11%; text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0; border-top-style: dashed; border-bottom-style: dashed;">
                <font style="font-size: 8pt;font-weight: 400;text-align: center" >
                    Qty
                </font>
            </td>
            
            <td style="width:35%; text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0; border-top-style: dashed; border-bottom-style: dashed;">
                <font style="font-size: 8pt;font-weight: 400;text-align: center" >
                    Nama Obat
                </font>
            </td>
            
            <td style="width:27%;text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0; border-top-style: dashed; border-bottom-style: dashed;">
                <font style="font-size: 8pt;font-weight: 400;text-align: right" >
                    Harga Satuan
                </font>
            </td>
            
            <td style="width:27%;text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0; border-top-style: dashed; border-bottom-style: dashed;">
                <font style="font-size: 8pt;font-weight: 400;text-align: right" >
                    Total Harga
                </font>
            </td>
        </tr>
    
        @php
        $totalharga = 0;
        $totaltagihan = 0;
        $totalDiskon = 0;
        @endphp
        @foreach ($dataReport  as $key => $detail)
        
        <tr>
            <td style="text-align: center;">
                <font style="font-size: 8pt;font-weight: 400;text-align: center" >
                    {{$detail->qty}}
                </font>
            </td>
            
            <td style="text-align: left;padding-top:3px" width="25%">
                <font style="font-size: 8pt;font-weight: 400;text-align: left" >
                    {{$detail->namaproduk}}
                </font>
                
            </td>
                <td style="text-align: right;" >
                <font style="font-size: 8pt;font-weight: 400;text-align: right" >
                    Rp.{{ \App\Traits\Valet::getMoneyFormatString($detail->hargasatuan) }}
                </font>
            </td>
            
            <td style="text-align: right;">
                <font style="font-size: 8pt;font-weight: 400;text-align: right" >
                Rp.{{ \App\Traits\Valet::getMoneyFormatString(($detail->hargasatuan * $detail->qty) - $detail->discount) }}
            </font>
            </td>
        </tr>

        @php
        $totaltagihan   = $totaltagihan +($detail->hargasatuan * $detail->qty) - $detail->discount;
        $totalDiskon    = $detail->discount;
        $totalharga     = $totalharga + ($detail->hargasatuan * $detail->qty);
        @endphp
        @endforeach
        <tr>
            <td style="padding-top:5px"></td>
        </tr>
        
        <tr>
            <td colspan="4" style="border-top: 1px solid #444444;padding: 3px 0 3px 0; border-top-style: dashed;"></td>
        </tr>
    </table>
                
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td style="text-align: right" width="70%">
                <font style="font-size: 10pt;font-weight: 600;text-align: right" >
                    Total = &emsp;&emsp; 
                </font>
            </td>
            
            <td style="text-align: right" width="30%">
                <font style="font-size: 10pt;font-weight: 600;text-align: left" >
                    Rp.{{ number_format($totalharga, 2, '.', ',') }}
                </font>
            </td>
        </tr>
    </table>
    </body>
</html>

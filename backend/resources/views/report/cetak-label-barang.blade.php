<html>
<head>
    <title>
        Cetak Label Barang
    </title>
@if(stripos(\Request::url(), 'localhost') !== FALSE)
    <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    <link  rel="stylesheet" href="{{ asset('css/style.css') }}">
@else
    <link rel="stylesheet" href="{{ asset('service/css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('service/css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('service/css/tabel.css') }}">
    <link  rel="stylesheet" href="{{ asset('service/css/style.css') }}">
@endif
</head>
<style type="text/css" media="print">
    @media print
    {
        @page
        {
            size: A5 148mm 210mm; 
            margin-left: 4.8px;
            margin-top: 22px;
            
        }
        footer {
            display: none
        }
        header {
            display: none
        }
        body {
            -webkit-print-color-adjust: exact !important;
        }
    }
</style>
<style>
        * {
            box-sizing: border-box;
        }
        tr td {
            padding:2px 4px 2px 4px;
        }
       
        body{
            font-family: Tahoma, Geneva, sans-serif;
        }
        

    .box {
        float: left;
    
    }
</style>
<body style="background-color: #CCCCCC;margin: 0" onLoad="window.print()" >
    
    @foreach($dataReport['datas'] as $det)
   
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" class='box'>
            <tbody>
                <tr>
                    <td style="text-align: left">
                     
                        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="padding: 5px;box-sizing: border-box;width: 245px;height: 122px;" >
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">Nama Barang</font>
                                </td>
                                <td style="width: 1%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">{{$det->namaproduk}}</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">Spesifikasi</font>
                                </td>
                                <td style="width: 1%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">{{$det->spesifikasi}}</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">SN</font>
                                </td>
                                <td style="width: 1%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">{{$det->noseri}}</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">Unit</font>
                                </td>
                                <td style="width: 1%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">{{$det->ruangancurrent}}</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">Tgl Distribusi</font>
                                </td>
                                <td style="width: 1%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">{{$det->tgldistribusi}}</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">Tgl Pembelian</font>
                                </td>
                                <td style="width: 1%;">
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 10px;text-align: left;" face="Tahoma">{{$det->tglpembelian}}</font>
                                </td>
                            </tr>
                           
                         
                        </table>
                    </td>
                    <!-- <td>
                        <p>
                            @if(stripos(\Request::url(), 'localhost') !== FALSE)
                                <img src="{{ asset('img/logo_rs.png') }}" width="60px" border="0">
                            @else
                                <img src="{{ asset('service/img/logo_rs.png') }}" width="60px" border="0">
                            @endif
                        </p>
                        <p style="margin-top: -21px">
                            <font style="text-transform: capitalize;font-size: 8px;font-weight: 600;letter-spacing: 1px;" color="#000000" >
                                {!! $profile->namalengkap !!}                                    
                            </font>
                        </p>
                        
                    </td> -->
                </tr>
            </tbody>
        </table>

    @endforeach

    <!-- <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" class='box' >
            <tbody>
                <tr>
                    <td style="padding: 30px;text-align: left">
                     
                        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border: 1px solid #c9b6b6;padding: 5px;box-sizing: border-box;width: 285px;height: 142px;" >
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">Nama Barang</font>
                                </td>
                                <td>
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">Spesifikasi</font>
                                </td>
                                <td>
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">SN</font>
                                </td>
                                <td>
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">Unit</font>
                                </td>
                                <td>
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">Tgl Pembelian</font>
                                </td>
                                <td>
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">Tgl Distribusi</font>
                                </td>
                                <td>
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 11px;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                         
                        </table>
                    </td>
                </tr>
            </tbody>
        </table> -->

    <!-- <div >
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
                <tr>
                    <td style="padding: 30px;text-align: left">
                     
                        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="365" style="border: 1px solid #c9b6b6;padding: 5px;box-sizing: border-box;" >
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Nama Barang</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Spesifikasi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">SN</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Unit</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Pembelian</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Distribusi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                         
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div >
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
                <tr>
                    <td style="padding: 30px;text-align: left">
                     
                        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="365" style="border: 1px solid #c9b6b6;padding: 5px;box-sizing: border-box;" >
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Nama Barang</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Spesifikasi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">SN</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Unit</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Pembelian</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Distribusi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                         
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div >
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
                <tr>
                    <td style="padding: 30px;text-align: left">
                     
                        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="365" style="border: 1px solid #c9b6b6;padding: 5px;box-sizing: border-box;" >
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Nama Barang</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Spesifikasi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">SN</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Unit</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Pembelian</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Distribusi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                         
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div >
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
                <tr>
                    <td style="padding: 30px;text-align: left">
                     
                        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="365" style="border: 1px solid #c9b6b6;padding: 5px;box-sizing: border-box;" >
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Nama Barang</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Spesifikasi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">SN</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Unit</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Pembelian</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Distribusi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                         
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div >
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
                <tr>
                    <td style="padding: 30px;text-align: left">
                     
                        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="365" style="border: 1px solid #c9b6b6;padding: 5px;box-sizing: border-box;" >
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Nama Barang</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Spesifikasi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">SN</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Unit</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Pembelian</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Distribusi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                         
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div >
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
                <tr>
                    <td style="padding: 30px;text-align: left">
                     
                        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="365" style="border: 1px solid #c9b6b6;padding: 5px;box-sizing: border-box;" >
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Nama Barang</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Spesifikasi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">SN</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Unit</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Pembelian</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Distribusi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                         
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div >
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
                <tr>
                    <td style="padding: 30px;text-align: left">
                     
                        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="365" style="border: 1px solid #c9b6b6;padding: 5px;box-sizing: border-box;" >
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Nama Barang</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Spesifikasi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">SN</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Unit</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Pembelian</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Distribusi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                         
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div >
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
                <tr>
                    <td style="padding: 30px;text-align: left">
                     
                        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="365" style="border: 1px solid #c9b6b6;padding: 5px;box-sizing: border-box;" >
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Nama Barang</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Spesifikasi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">SN</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Unit</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Pembelian</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Distribusi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                         
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div >
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
                <tr>
                    <td style="padding: 30px;text-align: left">
                     
                        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="365" style="border: 1px solid #c9b6b6;padding: 5px;box-sizing: border-box;" >
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Nama Barang</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Spesifikasi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">SN</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Unit</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Pembelian</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">Tgl Distribusi</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">:</font>
                                </td>
                                <td>
                                    <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                                </td>
                            </tr>
                         
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div> -->





</body>
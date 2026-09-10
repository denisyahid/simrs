<html>
<head>
    <title>
        Kwitansi Obat 23
    </title>

</head>
<style>
    @page { margin: 0px; }
    body { 
        margin: 0px;
        font-family: Tahoma, "Trebuchet MS", sans-serif;
    }
    @page {
        size: A4;
    }

    tr td {
        padding:2px 4px 2px 4px;
    }
    .baris1 {
       border: 0.01em solid #000000;
    }
    .baris2 {
       border: 1px solid #000000;
    }
</style>

<body class="A4" style="font-family:Tahoma;height: auto">
<div align="center">
    <table class="bayangprint" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="100%" style="padding:25px">
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td rowspan="5" >
                                <p align="right">
                                   
                                        <img src="img/logo-rs.png" width="80px" border="0">
                              
                                </p>
                            </td>
                            <td align="center">
                            </td>
                            <!-- <td rowspan="5">
                                <div style="width: 80px;">
                            </td> -->
                        </tr>
                        <tr>
                            <td align="left">
                                <font style="text-transform: capitalize;font-size: 16pt;font-weight: 600;letter-spacing: 1px;" color="#000000" >
                                    {!! $profile->namalengkap !!}                                    
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <font style="font-size: 12pt;" color="#000000" >
                                    {!! $profile->alamatlengkap  !!}                                   
                                    <!-- {{ $profile->fixedphone . "/" . $profile->faksimile }} -->
                                </font>
                            </td>
                        </tr>

                       <tr>
                            <td align="right">
                                <font style="font-size: 12pt;" color="#000000" >
                                    FARMASI                              
                                </font>
                            </td>
                       </tr>

                        <!-- <tr>
                            <td align="center">
                                <font style="font-size: 12pt;" color="#000000" >
                                    Email : <a href="#"> {!! $profile->alamatemail !!} </a> 
                                    Website : <a href="#"> {!! $profile->website !!} </a> 
                                </font>
                            </td>
                        </tr> -->
                    </table>
                    <!-- <hr class="baris1"> -->
                    <hr class="baris2">
                </td>
            </tr>
            
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center" height="60px">
                                <font style="font-size: 16pt;font-weight: bold" color="#000000" >KWITANSI RESEP 23</font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="13%"><font style="font-size: 11pt;" color="#000000">No Resep</font></td>
                            <td width="45%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->noresep }}/23</font></td>
                            <td width="13%"><font style="font-size: 11pt;" color="#000000">Tgl. Resep</font></td>
                            <td width="35%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->tgl }}</font></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" class="baris1" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="13%"><font style="font-size: 11pt;" color="#000000">Nama Pasien</font></td>
                            <td width="45%"><font style="font-size: 11pt;" color="#000000">: <b>{{ $dataReport['datas']->namapasienjk }}</b></font></td>
                            <td width="13%"><font style="font-size: 11pt;" color="#000000">Rekam Medik</font></td>
                            <td width="35%"><font style="font-size: 11pt;" color="#000000">: <b>{{ $dataReport['datas']->nocm }}</b></font></td>
                        </tr>
                        <tr>
                            <td width="13%"><font style="font-size: 11pt;" color="#000000">Tanggal Lahir</font></td>
                            <td width="45%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->tgllahir }}</font></td>
                            <td width="13%"><font style="font-size: 11pt;" color="#000000">Poli/Ruangan</font></td>
                            <td width="35%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->ruanganpasien }}</font></td>
                        </tr>
                        <tr>
                            <td width="13%"><font style="font-size: 11pt;" color="#000000">Usia</font></td>
                            <td width="45%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->umur }}</font></td>
                            <td width="13%"><font style="font-size: 11pt;" color="#000000">Nama Dokter</font></td>
                            <td width="35%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->namalengkap }}</font></td>
                        </tr>
                        <tr>
                            <td width="13%"><font style="font-size: 11pt;" color="#000000">Jenis Kelamin</font></td>
                            <td width="45%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->jk }}</font></td>
                            <td width="13%"><font style="font-size: 11pt;" color="#000000">SIP Dokter</font></td>
                            <td width="35%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->nosip }}</font></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:10px">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="100%"><font style="font-size: 11pt;font-weight: bold;" color="#000000">R/</font></td>
                        </tr>
                    </table>
                    <hr class="baris2">
                </td>
            </tr>
   
            <tr>
                <td style="padding:8px">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="10%"><font style="font-size: 11pt;" color="#000000">&nbsp;</font></td>
                            <td width="20%"><font style="font-size: 11pt;" color="#000000">Nama Obat</font></td>
                            <td width="10%"><font style="font-size: 11pt;" color="#000000">Dosis</font></td>
                            <td width="15%"><font style="font-size: 11pt;" color="#000000">Aturan Pakai</font></td>
                            <td width="10%"><font style="font-size: 11pt;" color="#000000">Jumlah</font></td>
                            <td width="15%"><font style="font-size: 11pt;" color="#000000">Tarif</font></td>
                            <td width="10%"><font style="font-size: 11pt;" color="#000000">Diskon</font></td>
                            <td width="15%"><font style="font-size: 11pt;" color="#000000">Total</font></td>
                        </tr>
                    </table>
                    <hr class="baris2">
                </td>
            </tr>
            <tr>
                <td style="padding:8px">
                    <table width="100%" cellspacing="0" cellpadding="0">
                    @php 
                        $detailByJenisKemasan = $dataReport['detail'];
                        $totaltagihan = 0;
                    @endphp
                    @foreach($detailByJenisKemasan as $jenisKemasan => $dataReport['detail'] )
                        <tr>
                            <td width="9%" style="padding-bottom: 15px;"><font style="font-size: 11pt;" color="#000000">Jenis Obat</font></td>
                            <td width="82%" style="padding-bottom: 15px;"><font style="font-size: 11pt;" color="#000000">: {{ $jenisKemasan }}</font></td>
                        </tr>
                        @foreach($dataReport['detail'] as $det)
                        <tr>
                            <td colspan="6">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="10%" style="vertical-align: top;"><font style="font-size: 11pt;" color="#000000"><span>R/ {{ $det->rke }}</span></font></td>
                                    <td width="20%"><font style="font-size: 11pt;" color="#000000">{{ $det->namaprodukstandar }}</font></td>
                                    <td width="10%" style="vertical-align: top;"><font style="font-size: 11pt;margin-left: 5px" color="#000000">{{ $det->dosis }}</font></td>
                                    <td width="15%" style="vertical-align: top;"><font style="font-size: 11pt;" color="#000000">{{ $det->aturanpakai }}</font></td>
                                    <td width="10%" style="vertical-align: top;"><font style="font-size: 11pt;" color="#000000">{{ number_format($det->jumlah, 2, '.', ',') }}</font></td>

                                    <td width="15%" style="vertical-align: top;"><font style="font-size: 11pt;" color="#000000">{{ number_format($det->hargasatuan, 2, '.', ',') }}</font></td>
                                    <td width="10%" style="vertical-align: top;"><font style="font-size: 11pt;" color="#000000">{{ number_format($det->hargadiscount, 2, '.', ',') }}</font></td>
                                    <td width="15%" style="vertical-align: top;"><font style="font-size: 11pt;" color="#000000">{{ number_format($det->totalbiaya, 2, '.', ',') }}</font></td>

                                    </tr>
                                </table>
                            </td>
                        </tr>
                        @php
                           $totaltagihan = $totaltagihan + $det->totalbiaya;
                        @endphp
                        @endforeach
                      
                    @endforeach
                    </table>
                    <hr class="baris2">
                </td>
            </tr>

            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%"><font style="font-size: 11pt;" color="#000000"></font></td>
                            <td width="60%"><font style="font-size: 11pt;" color="#000000"></font></td>
                            <td width="8%"><font style="font-size: 11pt;margin-left:60px" color="#000000"><b>Total</b></font></td>
                            <td width="30%"><font style="font-size: 11pt;" color="#000000">: <b>{{ number_format($totaltagihan, 2, '.', ',') }}</b></font></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="padding-top:20px">
                    <table width="100%" class="baris1" cellspacing="0" cellpadding="0">
                        <tr style="vertical-align: top;">
                            <td width="33%" style="text-align:center;height: 100px;border-right: 2px solid black;"><font style="font-size: 11pt;" color="#000000">Kasir</font></td>
                            <td width="33%" style="text-align:center;height: 100px;border-right: 2px solid black;"><font style="font-size: 11pt;" color="#000000">Dokter</font></td>
                            <td width="33%" style="text-align:center;height: 100px;"><font style="font-size: 11pt;" color="#000000">Admin</font></td>
                        </tr>
                    </table>
                </td>
            </tr>

          

        </tbody>
    </table>
</div>
</body>
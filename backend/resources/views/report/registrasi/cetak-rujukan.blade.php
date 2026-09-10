<html>

<head>
    <title>
        Cetak Rujukan
    </title>

</head>
<style>
    @page {
        margin: 0px;
    }

    body {
        margin: 0px;
        font-family: Tahoma, "Trebuchet MS", sans-serif;
    }

    table tr {
        padding: 0;
        margin: 0;
        margin-top: -10px;
    }

    table td {
        padding: 0;
        margin-top: -10px;
    }
</style>

<!-- onLoad="window.print()" -->

<body>
    <div style="text-align:left  ;padding:30px 30px 0px 30px;">
        <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#FFFFFF">
            <tr>
                <td>
                    <img src="img/logo_bpjs.png" alt="" style="width: 180px;">
                </td>
                <td>
                    <span>SURAT RUJUKAN </span>
                    <br>
                    <span>{{ $dataReport['datas']['namappkRumahSakit'] }}</span>

                </td>
                <td>
                    <span>No.</span>&nbsp;<span>{{ $dataReport['datas']['noRujukan'] }}</span>
                    <br>
                    <span style="font-size: 9pt;" >Tgl.</span>&nbsp;<span style="font-size: 9pt;" >{{  date('d-M-Y',strtotime( $dataReport['datas']['tglRujukan'])) }}</span>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="25%" class="judulLabel" style="padding-top: 10px;">
                                <span style="font-size: 9pt;" color="#000000">Kepada Yth</span>
                            </td>
                            <td width="1%" class="judulLabel" style="padding-top: 10px;">
                                <span style="font-size: 9pt;" color="#000000">&nbsp;</span>
                            </td>
                            <td width="69%" style="padding-top: 10px;">
                                <span style="font-size: 9pt;"
                                    color="#000000">{{ $dataReport['datas']['poliTujuan'] }}</span>
                                  
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="25%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">&nbsp;</span>
                            </td>
                            <td width="1%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">&nbsp;</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 9pt;" color="#000000"><span>
                                    </span><span>{{ $dataReport['datas']['namaPpkDirujuk'] }}</span></span>
                            </td>
                          
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="100%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">Mohon Pemeriksaan dan Penanganan Lebih
                                    Lanjut :</span>
                            </td>
                            <td width="100%" class="judulLabel" style="text-align: center">
                                <span style="font-size: 9pt;" color="#000000">== {{ $dataReport['datas']['tiperujukan']  }} == </span>
                 
                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="25%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">No.Kartu</span>
                            </td>
                            <td width="1%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">:</span>
                            </td>
                            <td width="34%">
                                <span style="font-size: 9pt;"
                                    color="#000000"><span>{{ $dataReport['datas']['noKartu'] }}</span></span>
                            </td>
                            <td width="35%" style="text-align: left">
                               &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span style="font-size: 9pt;" color="#000000">{{ $dataReport['datas']['jnsPelayanan']  == 1 ?'Rawat Inap':'Rawat Jalan' }}</span>
                            </td>
                            
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="25%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">Nama Peserta</span>
                            </td>
                            <td width="1%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">:</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 9pt;"
                                    color="#000000"><span>{{ $dataReport['datas']['nama'] }}</span></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="25%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">Tgl.Lahir</span>
                            </td>
                            <td width="1%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">:</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 9pt;"
                                    color="#000000"><span>{{ $dataReport['datas']['tglLahir'] }}</span></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="25%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">Diagnosa</span>
                            </td>
                            <td width="1%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">:</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 9pt;"
                                    color="#000000"><span>{{ $dataReport['datas']['diagnosa'] }}</span></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="25%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">Keterangan</span>
                            </td>
                            <td width="1%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">:</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 9pt;"
                                    color="#000000"><span>{{ $dataReport['datas']['catatan'] }}</span></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="100%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">Demikian atas bantuannya,diucapkan
                                    banyak terima kasih.</span>
                                  
                            </td>
                           
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="100%" class="judulLabel">
                              
                                    <span style="font-size: 7pt;" color="#000000">* Rujukan Berlaku Sampai Dengan {{ date('d-M-Y', strtotime( $dataReport['datas']['tglRujukan']. ' + 90 days'))  }}</span>
                                    <br>
                                    <span style="font-size: 7pt;" color="#000000">* Tgl.Rencana Berkunjung {{  date('d-M-Y',strtotime( $dataReport['datas']['tglRencanaKunjungan'])) }}</span>
                            </td>
                           
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="70%" class="judulLabel" style="float: bottom;bottom:0">
                                <br />
                                <br />
                              
                             
                                <span style="font-size: 6pt;" color="#000000">Tgl Cetak
                                    {{ date('d-m-Y H:i:s') }}</span>
                            </td>
                            <td width="30%" class="judulLabel" style="text-align: center">
                                <span style="font-size: 9pt;" color="#000000">Mengetahui,</span>
                                <br />
                                <br />
                                <br />
                                <hr>
                               

                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
</body>

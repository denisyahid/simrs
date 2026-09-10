<html>

<head>
    <title>
        Cetak Rencana Kontrol
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
                    <span>{{ $dataReport['datas']['jnsKontrol'] == 1?'SURAT RENCANA INAP':'SURAT RENCANA KONTROL'}} </span>
                    <br>
                    <span>{{ $dataReport['datas']['namappkRumahSakit'] }}</span>

                </td>
                <td>
                    <span>No.</span>&nbsp;<span>{{ $dataReport['datas']['nosuratkontrol'] }}</span>
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
                                    color="#000000">{{ $dataReport['datas']['namaDokter'] }}</span>
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
                                <span style="font-size: 9pt;" color="#000000"><span>Sp./Sub.
                                    </span>&nbsp;<span>{{ $dataReport['datas']['namaPoliTujuan'] }}</span></span>
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
                            <td width="69%">
                                <span style="font-size: 9pt;"
                                    color="#000000"><span>{{ $dataReport['datas']['noka'] }}</span></span>
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
                                    color="#000000"><span>{{ $dataReport['datas']['tgllahir'] }}</span></span>
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
                                    color="#000000"><span>{{ $dataReport['datas']['dxawal'] }}</span></span>
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
                                <span style="font-size: 9pt;" color="#000000">Inap</span>
                            </td>
                            <td width="1%" class="judulLabel">
                                <span style="font-size: 9pt;" color="#000000">:</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 9pt;"
                                    color="#000000"><span>{{ $dataReport['datas']['tglrencanakontrol'] }}</span></span>
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
                            <td width="70%" class="judulLabel" style="float: bottom;bottom:0">
                                <br />
                                <br />
                                <br />
                                <br/>
                                <span style="font-size: 6pt;" color="#000000">Tgl Cetak
                                    {{ date('d-m-Y H:i:s') }}</span>
                            </td>
                            <td width="30%" class="judulLabel" style="text-align: center">
                                <span style="font-size: 9pt;" color="#000000">Mengetahui DPJP,</span>
                                <br>
                                @if ($dataReport['ttdimg'])
                                    <div style="height:140px">
                                        <img src="{{ $dataReport['ttdimg'] }}" width="170" height="140"
                                            alt="TTD" />
                                    </div> <br>
                                @else
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $dataReport['datas']['namaDokter'] }}"
                                        alt="" style="margin-top:10px">
                                    <br />
                                @endif


                                <span style="font-size: 9pt;"
                                    color="#000000">{{ $dataReport['datas']['namaDokter'] }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
</body>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Report
    </title>
</head>

<body>
    <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td class="label-strong" style="text-align:center;">
                <font style="font-weight: normal; font-size: 16pt; color: black;">BUKTI RESERVASI</font><br>
                <font style="font-size: 16pt; color: black; font-weight: bold;">RUMAH SAKIT UMUM DAERAH BALI MANDARA</font>
            </td>
        </tr>
    </table>
    <table cellspacing="0" cellpadding="0" style="margin-top: 50px;" width="100%">
        <tr>
            <td>
                <table cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <td width="30%">
                                    <span style="font-size: 12pt; font-family: sans-serif;">Kode Reservasi</span>
                                </td>
                                <td colspan="2" width="70%">
                                    <span style="font-size: 12pt; font-family: sans-serif;">: {{ $data[0]->noreservasi }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">
                                    <span style="font-size: 12pt; font-family: sans-serif;">No RM / Tgl Lahir</span>
                                </td>
                                <td colspan="2" width="70%">
                                    <span style="font-size: 12pt; font-family: sans-serif;">: {{ $data[0]->nocm }} / {{ date('d-m-Y', strtotime($data[0]->tgllahir)) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span style="font-size: 12pt; font-family: sans-serif;">Tanggal Reservasi</span>
                                </td>
                                <td colspan="2">
                                    <span style="font-size: 12pt; font-family: sans-serif;">: {{ date('d-m-Y', strtotime($data[0]->tanggalreservasi)) }} {{ $data[0]->jamreservasi }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span style="font-size: 12pt; font-family: sans-serif;">No Antrian</span>
                                </td>
                                <td colspan="2">
                                    <span style="font-size: 12pt; font-family: sans-serif;">: {{ $data[0]->noantrianpoli }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" height="15"></td>
                            </tr>
                            <tr>
                                <td colspan="3" align="right">
                                    <table bgcolor="#FFFFFF" width="145" height="40" style="border: 2px solid black; margin-left: 195px;">
                                        <tr>
                                            <td colspan="3" align="center">
                                            <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text={{ $data[0]->noreservasi}}&scale=1&scaleY=1&scaleX=2" style="height: 35px; width: 140px;"><br/>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">
                                    <span style="font-size: 12pt; font-family: sans-serif; font-weight: bold;">{{ $data[0]->namaruangan }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">
                                    <span style="font-size: 12pt; font-family: sans-serif; font-weight: bold;">{{ $data[0]->dokter }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">
                                    <span style="font-size: 12pt; font-family: sans-serif; font-weight: bold;">{{ $data[0]->kelompokpasien }}</span>
                                </td>
                            </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
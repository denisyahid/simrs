<html>
<head>
    <title>
        Report
    </title>
    <style>

        body{
            font-family: Arial, Tahoma, sans-serif
        }
        </style>

</head>
<body style="margin: 0">
    <div align="left">
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="width: 300px">
            <tbody>
            <tr>
                <td style="text-align: left">
                    <font style="font-size: 11pt;" color="#000000" face="Tahoma"><b>Loket Pendaftaran</b></font><br>
                    <font style="font-size: 11pt;" color="#000000" face="Tahoma">{{$dataReport['namaprofile']}}</font><br>
                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">{{$dataReport['alamat']}}</font><br>
                    <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0">
                        <tr>
                            <td  style="text-align: left">
                                <font style="font-size: 8pt;text-align: left;" face="Tahoma">&nbsp;</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 16pt;text-align: left;" face="Tahoma">Nomor Antrian</font>
                            </td>
                        </tr>
                        <tr>
                            <td height="40" style="text-align: center">
                                <font style="font-size: 65pt;text-align: left;" face="Tahoma"><b>{{$dataReport['noantrian']}}</b></font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">{{$dataReport['jmlantrian']}}</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;font-weight:bold" face="sans-serif">{{ strtoupper($dataReport['namaruangan']) }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td height="15" style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">{{ strtoupper($dataReport['namapasien']) }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td height="15" style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">{{$dataReport['nobpjs']}}</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 10pt;text-align: left;" face="Tahoma">{{ date_format(date_create($dataReport['tanggal']), "d/m/Y H:i:s") }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: left">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 12t;text-align: center;" face="Tahoma">
                                Datanglah sesuai dengan jadwal kontrol<br>
                                Pastikan Tepat Tanggal dan Tepat Jam
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td height="25" style="text-align: center">
                                <font style="font-size: 14pt;text-align: left;" face="Tahoma"><b>{{$dataReport['jenis']}}</b></font>
                            </td>
                        </tr>
                        @if ($dataReport['jenis'] == "BPJS")
                            <tr>
                                <td  style="text-align: center">
                                    <font style="font-size: 12t;text-align: center;" face="Tahoma">
                                    Pastikan rujukan BPJS masih BERLAKU
                                    </font>
                                </td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    </body>
</html>

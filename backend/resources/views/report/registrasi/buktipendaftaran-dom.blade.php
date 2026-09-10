<html lang="en">

<head>
    <title>
        Report
    </title>

</head>

<style>

body{
    font-family: Arial, Helvetica, sans-serif
}
</style>

<body style="margin: 0;padding:0">
  
        <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="180" style="margin-top: -30px; margin-left: -30px">
            <tbody>
                <tr>
                    <td style="padding: -px;text-align: left">
                        <table width="100%" cellspacing="0" cellpadding="0" >
                            <tr>
                                <th>
                                    <img src="{{ 'img/logo-rs.png' }}" width="50px">
                                </th>
                                <th width="90%">
                                    <table width="100%"  style="position:relative">
                                        <tr>
                                            <td class="label-strong" style="text-align:center; margin-left: 14px; font-size: 11pt;">
                                                <font>BUKTI PENDAFTARAN <br> {{ $dataReport['namaprofile'] }}</font>
                                            </td>
                                        </tr>
                                    </table>
                                </th>
                            </tr>
                        </table>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
                            @php
                                $noregistrasi = $dataReport['noregistrasi'];
                            @endphp
                            <tr>
                                <td height="10" colspan="2">
                            </tr>
                            <tr>
                                <td height="5" colspan="2">
                                
                                    <img src='https://bwipjs-api.metafloor.com/?bcid=code39&text={{ $noregistrasi }}&scale=1&scaleY=1&scaleX=2'
                                    style=" height: 40px;width: 200px;-webkit-user-select: none;cursor:pointer"/>
                                    <span style="font-size: 8pt;" color="#000000">{{ $noregistrasi }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="50%">
                                    <span style="font-size: 8pt;" color="#000000">{{ date('l, j F, Y', strtotime($dataReport['tglregistrasi'])) }}</span>
                                </td>
                                <td height="5" width="50%" align="right">
                                    <span style="font-size: 8pt;" color="#000000">{{ $dataReport['jamregistrasi'] }}</span>
                                </td>
                            </tr>
                            @php
                                $norm = $dataReport['norm'];
                            @endphp
                            <tr>
                                <td height="5" colspan="2">
                                    <br>
                                    <img src='https://bwipjs-api.metafloor.com/?bcid=code39&text={{ $norm }}&scale=1&scaleY=1&scaleX=2'
                                    style=" height: 45px;width: 170px;-webkit-user-select: none;cursor:pointer"/><br>
                                    <span style="font-size: 8pt;" color="#000000">{{ $norm }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2">
                                    <span style="font-size: 7pt;" color="#000000">
                                        {{ strtoupper($dataReport['namapasien']) }} - <span style="font-size: 8px"> {{ @$dataReport['kebangsaan'] }}</span> 
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2">
                                    <span style="font-size: 8pt;" color="#000000">{{ $dataReport['umur'] }}</span>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
                            <tr>
                                <td height="5" colspan="2" align="center">
                                    <span style="font-size: 11pt;" color="#000000"><b>{{ $dataReport['noantrian'] }}</b></span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2" align="center">
                                    <span style="font-size: 8pt;" color="#000000">
                                        <b>{{ $dataReport['namaruangan'] }}</b>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2" align="center">
                                    <span style="font-size: 8pt;" color="#000000">{{ $dataReport['namadokter'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="10" colspan="2">
                            </tr>
                            <tr>
                                <td height="5" colspan="2" align="center">
                                    <span style="font-size: 8pt;" color="#000000">{{ $dataReport['kelompokpasien'] }}</span>
                                </td>
                            </tr>
                        </table>
                       
                    </td>
                </tr>
            </tbody>
        </table>

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Report
    </title>
</head>

<style>
    body{
        background-color: #FFFFFF;
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
        page-break-after: avoid;
    }
</style>


<body>
    <table cellspacing="0" cellpadding="0" width="450px; background-color: #FFFFFF; height: 100px; margin-top: -50px; margin-left: -50px; padding-top: 20px; padding-left: 20px; page-break-inside: avoid; page-break-after: avoid;">
        <tr>
            <td>
                <table
                    cellspacing="0" cellpadding="0">
                    <tr>
                        <th align="left">
                            <img src="{{ 'img/logo-rs.png' }}" width="50px" height="50px">
                        </th>
                        <th>
                            <table width="100%"  style="position:relative;">
                                <tr>
                                    <td class="label-strong" style="text-align:left; padding-left: -20px;">
                                        <font style="font-weight: normal; color: black;">RUMAH SAKIT UMUM DAERAH</font><br>
                                        <font style="font-size: 14pt; color: black;">BALI MANDARA</font>
                                    </td>
                                </tr>
                            </table>
                        </th>
                    </tr>
                        <tr style="display: none">
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <td colspan="3" height="15"></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span style="font-size: 14pt;font-weight: 800;"
                                        face="Tahoma">{{ $dataReport[0]->nocm }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                @if(strlen($dataReport[0]->namapasien) <= 32)
                                    <font style="font-size: 12pt;"
                                        face="Tahoma"><b>{{ $dataReport[0]->namapasien }}</b>
                                    </font>
                                @else
                                    <font style="font-size: 10pt;"
                                        face="Tahoma"><b>{{ $dataReport[0]->namapasien }}</b>
                                    </font>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" height="15"></td>
                            </tr>
                            <tr>
                                <td colspan="3" align="right">
                                    <table bgcolor="#FFFFFF" width="110" height="30" style="border: 2px solid black; margin-left: 215px;">
                                        <tr>
                                            <td colspan="3" align="center">
                                            <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text={{ $dataReport[0]->barcode}}&scale=1&scaleY=1&scaleX=2" style="height: 25px; width: 100px;"><br/>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <font style="font-size: 12pt;"
                                        face="Tahoma"><b>List Rujukan Berlaku:</b>
                                    </font>
                                </td>
                            </tr>
                            @foreach($rujukan as $r)
                            <tr>
                                <td colspan="3">
                                    @if(date('d-M-Y', strtotime( $r->tglKunjungan. ' + 90 days')) >= date('d-M-Y'))
                                    <font style="font-size: 10pt;"
                                        face="Tahoma">{{ $r->noKunjungan }} - {{ $r->poliRujukan->nama }} - Exp: {{ date('d-M-Y', strtotime( $r->tglKunjungan. ' + 90 days')) }}
                                    </font>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @foreach($rujukan2 as $r2)
                            <tr>
                                <td colspan="3">
                                    @if(date('d-M-Y', strtotime( $r2->tglKunjungan. ' + 90 days')) >= date('d-M-Y'))
                                    <font style="font-size: 10pt;"
                                        face="Tahoma">{{ $r2->noKunjungan }} - {{ $r2->poliRujukan->nama }} - Exp: {{ date('d-M-Y', strtotime( $r2->tglKunjungan. ' + 90 days')) }}
                                    </font>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
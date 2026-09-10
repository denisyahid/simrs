<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Report
    </title>

    <link href="css/style.css" rel="stylesheet">

    <link href="https://fonts.cdnfonts.com/css/3-of-9-barcode" rel="stylesheet">
</head>
<style type="text/css" media="print">
    @media print {
        @page {
            size: landscape;
            margin: 0;
            /* size: portrait; */
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

    tr td {
        /*padding:2px 4px 2px 4px;*/
        
    }

    .borderss {
        border-bottom: 1px solid black;
    }

    body {
        font-family: Tahoma bold;
    }
</style>
{{-- --}}
@if(isset($res['pdf']) && $res['pdf'] == true)
<body style="margin: 0">
@else
    <body style="background-color: #FFFFFF;margin: 0" onLoad="window.print()">
@endif
    <div style="text-align:left; margin-top: 25px; margin-left: 10px;">
        @if(isset($res['pdf']) && $res['pdf'] == true)
           <table style="background-color:#FFFFFF; padding-top:0; width:400px; border:0px">
            @else
            <table style="background-color:#FFFFFF; padding-top:15px; width:500px; border:0px">
            @endif
            <caption style="display: none"></caption>
            <tbody>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                <tr>
                    <td>
                        <table bgcolor="#FFFFFF" border="0" width="800" style="margin-top: 0px; margin-left: 0px;">
                            <caption style="display: none"></caption>
                        @foreach($dataReport['data'] as $item)
                            <tr style="display: none">
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span style="font-size: 16pt;font-weight: bold;line-weight:bold;"
                                        face="Arial"><b>{{ $item->nocm }}</b>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <font style="font-size: 13pt; font-weight: bold"
                                        face="Arial"><b>{{ $item->namapasien }}</b>
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" height="15"></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table bgcolor="#FFFFFF" width="150" height="70" style="margin-left: 150px;">
                                        <tr>
                                            <td colspan="3" align="center">
                                            <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text={{ $item->barcode}}&scale=1&scaleY=1&scaleX=2" style="height: 40px; width: 150px;"><br/>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                            </tr>
                        @endforeach
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>

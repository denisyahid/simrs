
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style type="text/css">
        @page {
            size: auto;
            margin: 0;
            justify-items: center;
            justify-content: center
        }
        /* div{
            border :1px solid black;
             border-radius: 5px;
            padding-left: 5px;
        } */
        body{
            padding: 1.5px;
        }

    </style>
</head>
    <body>
        <table cellspacing="0" cellpadding="0" style="padding-top: .2cm;padding-bottom: 0cm;padding-left: .2cm;padding-right: .2cm" width="100%">
            <tr>
                <td width="25%">
                    <span style="font-size:9pt; font-weight:bold;">NO CM </span>
                </td>
                <td width="5%">
                    <span style="font-size:9pt; font-weight:bold;">: </span>
                </td>
                <td width=23%>
                    <span style="font-size:9pt; font-weight:bold;">{{ $data->nocm }}</span>
                </td>
                <td width=23%>
                    <span style="font-size:9pt; font-weight:bold;">{{$data->jeniskelamin}}</span>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <span style="font-size:9pt; font-weight:bold;"
                    face="Tahoma"> {{ trim($data->namapasien) }}</span>
                </td>
            </tr>
            <tr>
                <td width="25%">
                    <span style="font-size:9pt; font-weight:bold;">TGL Lahir </span>
                </td>
                <td width="5%">
                    <span style="font-size:9pt; font-weight:bold;">: </span>
                </td>
                <td width=25% colspan="2">
                    <span style="font-size:9pt; font-weight:bold;">{{ date('d/m/Y', strtotime($data->tgllahir)) }}</span>
                </td>
            </tr>
            <tr>
                <td width="25%">
                    <span style="font-size:9pt; font-weight:bold;">TGL Masuk </span>
                </td>
                <td width="5%">
                    <span style="font-size:9pt; font-weight:bold;">: </span>
                </td>
                <td width=25% colspan="2">
                    <span style="font-size:9pt; font-weight:bold;">{{ date('d/m/Y', strtotime($data->tglregistrasi)) }}</span>
                </td>
            </tr>
        </table>
        {{-- <div class="col-4">
            <table style="width:100%">
                <tr>
                    <td>
                        <span style="font-size:8pt; font-weight:bold;"
                        face="Tahoma">NO CM</span>
                    </td>
                    <td>
                        <span style="font-size:8pt; font-weight:bold;"
                        face="Tahoma">:</span>
                    </td>
                    <td>
                        <span style="font-size:8pt; font-weight:bold;"
                        face="Tahoma">{{ $data->nocm }}</span>
                    </td>
                    <td>
                        <span style="font-size:8pt;margin-left: 4px; font-weight:bold;"
                        face="Tahoma"> {{$data->jeniskelamin}} </span>
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="4"> 
                        <span style="font-size:8pt; font-weight:bold;"
                        face="Tahoma"> {{ trim($data->namapasien) }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span style="font-size:8pt; font-weight:bold;"
                        face="Tahoma">TGL. Lahir</span>
                    </td>
                    <td>
                        <span style="font-size:8pt; font-weight:bold;"
                        face="Tahoma">:</span>
                    </td>
                    <td>
                        <span style="font-size:8pt; font-weight:bold;"
                        face="Tahoma"> {{ date('d/m/Y', strtotime($data->tgllahir)) }}</span>
                    </td>
                </tr>
                <tr style="position: static:top:10cm">
                    <td>
                        <span style="font-size:8pt; font-weight:bold;"
                            face="Tahoma">TGL. Masuk</span>
                    </td>
                    <td>
                        <span style="font-size:8pt; font-weight:bold;"
                            face="Tahoma">: </span>
                    </td>
                    <td>
                        <span style="font-size:8pt; font-weight:bold;"
                            face="Tahoma">{{ date('d/m/Y', strtotime( $data->tglregistrasi)) }} </span>
                    </td>
                </tr>
            </table>
        </div> --}}
    </body>
</html>

<!DOCTYPE html>
<html>
    <head>
        <title>Rencana Kontrol</title>
    </head>
    <style>
        @page { margin: 0px; }
        body { 
            margin: 30px;
            font-family: Tahoma, "Trebuchet MS", sans-serif;
        }
    </style>
    @if((isset( $res['pdf']) &&  $res['pdf'] == 'true') || (isset( $res['storage']) && $res['storage']))
        <body>
        <div align="left">
    @else
        <body  onload="window.print()" style="background-color: #CCCCCC;">
  
        <div align="left"  style="width:950px;background-color:#FFFFFF;">
    @endif
        <table width="100%">
            <tr>
                <td style="padding:0px 0px 0px 0px;">
                    <table width="100%">
                        <tr>
                            <td width="15%">
                                <p align="left">
                                    @if((isset( $res['pdf']) &&  $res['pdf'] == 'true') || (isset( $res['storage']) && $res['storage']))
                                        <img src="{{'img/provinsi-rs.svg'}}" width="80px" border="0">
                                    @else
                                        <img src="{{ asset('img/provinsi-rs.svg') }}" width="80px" border="0">
                                    @endif
                                </p>
                            </td>
                            <td width="70%">
                                <p align="center">
                                    <font style="font-size: 12pt; font-weight: bold" color="#000000">PEMERINTAH PROVINSI BALI</font><br>
                                    <font style="font-size: 10pt; font-weight: bold" color="#000000">{{ strtoupper($profile->namalengkap) }}</font><br>
                                    <font style="font-size: 8pt;" color="#000000">{{ strtoupper($profile->alamatlengkap) }}</font><br>
                                    <font style="font-size: 8pt;" color="#000000">No. Telp: {{ $profile->fixedphone }}, Email : {{ $profile->alamatemail }}</font>
                                </p>
                            </td>
                            <td width="15%">
                                <p align="left">
                                    @if((isset( $res['pdf']) &&  $res['pdf'] == 'true') || (isset( $res['storage']) && $res['storage']))
                                        <img src="{{'img/logo-rs.png'}}" width="80px" border="0">
                                    @else
                                        <img src="{{ asset('img/logo-rs.png') }}" width="80px" border="0">
                                    @endif
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr style="border: 0.5px solid;">
        <table width="100%">
            <tr>
                <td width="100%" colspan="2" align="center">
                    <span style="font-size: 10pt">SURAT KETERANGAN  MASIH DALAM PERAWATAN (SURAT KONTROL)</span>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="2" align="center">
                    <span style="font-size: 10pt">NO. {{ $res['data'][0]->nobukti }}</span>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="2" align="left">
                    <span style="font-size: 8pt">Saya yang bertandatangan di bawah ini:</span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">Nama</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;">: {{ $res['dpjp'] }}</span>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="2" align="left">
                    <span style="font-size: 8pt">Menerangkan dengan sebenarnya bahwa:</span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">No. Rekam Medis</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;">: {{ $res['data'][0]->nocm }}</span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">Nama</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;">: {{ $res['data'][0]->namapasien }}</span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">Tanggal Lahir / Umur</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;">: {{ $res['data'][0]->tgllahir }} / {{ $res['data'][0]->umur }} &emsp;&emsp;&emsp;&emsp;&emsp; Jenis Kelamin: {{ $res['data'][0]->jeniskelamin }} </span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">Diagnosa</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;">: {{ $res['diagnosa'] }}</span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">Terapi</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;">: {{ $res['data'][0]->terapi }}</span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">Tanggal Kontrol</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;">: {{ date('Y-m-d', strtotime($res['data'][0]->tglregistrasi)) }}</span>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="2" align="left">
                    <span style="font-size: 8pt">Pasien tersebut di atas Masih Dalam Perawatan dengan tindak lanjut yang dianjurkan:</span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">Perawatan</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;;">: {{ $res['data'][0]->namaruangan }}</span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">Catatan</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;;">: {{ $res['data'][0]->catatan }}</span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">No Antrian</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;;">: {{ $res['data'][0]->noantrianpoli }}</span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">No Surat Kontrol</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;">: {{ $res['nokontrol'] != '' ? $res['nokontrol'] : $res['data'][0]->nosurat }}</span>
                </td>
            </tr>
            <tr>
                <td width="30%" align="left">
                    <span style="font-size: 8pt; margin-left: 100px;" style="margin-left: 50px;">No Reservasi</span>
                </td>
                <td width="70%" align="left">
                    <span style="font-size: 8pt;;">: {{ $res['data'][0]->noreservasi }}</span>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="2" align="left">
                    <span style="font-size: 8pt;">Demikian Surat Keterangan ini kami sampaikan. Untuk dapat dieprgunakan sebagaimana semestinya</span>
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="70%" align="center"></td>
                <td width="30%" align="center">
                    <span style="font-size: 8pt;">Garut, {{ $res['tglkontrol'] != '' && $res['tglkontrol'] != $res['data'][0]->tglkontrol ? date('d-m-Y', strtotime($res['tglkontrol'])) : date('d-m-Y', strtotime($res['data'][0]->tgldef)) }} <br> Dokter yang merawat</span>
                </td>
            </tr>
            <tr>
                <td width="70%" align="center"></td>
                <td width="30%" align="center">
                    <span style="margin-top: 10px;">
                        <br>
                        <img src="data:image/jpeg;base64,{{ $res['qrcode'] }}" border="0" style="height: 60px; width: 60px;">
                    </span>
                </td>
            </tr>
            <tr>
                <td width="70%" align="center"></td>
                <td width="30%" align="center">
                    <span style="font-size: 8pt;">{{$res['dpjp']}}</span>
                </td>
            </tr>
        </table>
    </div>    
</body>
</html>

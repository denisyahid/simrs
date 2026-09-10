<!DOCTYPE html>
<html>
    <head>
        <title>Surat Eligibilitas Peserta</title>
    </head>
    <style>
        @page { margin: 0px; }
        body { 
            margin: 0px;
            font-family: Tahoma, "Trebuchet MS", sans-serif;
        }
    </style>
    <body>
    <div align="left">

        <table width="100%">
            <tr>
                <td style="padding:10px 30px 0px 30px;">
                    <table width="100%">
                        <tr>
                            <td width="35%">
                                <p align="left">
                                    @if((isset( $res['pdf']) &&  $res['pdf']) || (isset( $res['storage']) && $res['storage']))
                                        <img src="{{'img/logo_bpjs.png'}}" width="240px" border="0">
                                    @else
                                        <img src="{{ asset('img/logo_bpjs.png') }}" width="240px" border="0">
                                    @endif
                                </p>
                            </td>
                            <td width="65%">
                                <p align="left">
                                    <font style="font-size: 14pt;" color="#000000">SURAT ELEGIBILITAS PESERTA</font><br>
                                    <font style="font-size: 12pt;" color="#000000">{{ strtoupper($profile->namalengkap) }}</font><br>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 30px 0px 30px;">
                    <table width="100%">
                        <tr>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">NO. SEP</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="38%" style="vertical-align:top;">
                                <font style="font-size: 10pt;" color="#000000">{{ $res['sep']->noSep }}
                                    @if(date_create($res['sep']->tglSep) < date_create($res['tglCreate']))
                                        {{ " (BACKDATE)" }}
                                    @endif
                                </font>
                            </td>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000"></font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000"></font></td>
                            <td height="5" width="28%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000"></font></td>
                        </tr>
                        <tr>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Tgl. SEP</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="38%" style="vertical-align:top;padding-right:10px;"><font style="font-size: 10pt;" color="#000000">{{ date_format(date_create($res['sep']->tglSep), 'd/m/Y') }}</font></td>
                            {{-- batas --}}
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Peserta</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="28%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->peserta->jnsPeserta }}</font></td>
                        </tr>
                        <tr>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">No. Kartu</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="38%" style="vertical-align:top;padding-right:10px;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->peserta->noKartu }} &nbsp;&nbsp;&nbsp; ( MR. {{ $res['sep']->peserta->noMr }} )</font></td>
                            {{-- batas --}}
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">COB</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="28%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->cob !='0'?'Ya':'' }}</font></td>
                        </tr>
                        <tr>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Nama Peserta</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="38%" style="vertical-align:top;padding-right:10px;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->peserta->nama }}</font></td>
                            {{-- batas --}}
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Jns. Rawat</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="28%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->jnsPelayanan }}</font></td>
                        </tr>
                        <tr>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Tgl. Lahir</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="38%" style="vertical-align:top;padding-right:10px;"><font style="font-size: 10pt;" color="#000000">{{ date_format(date_create($res['sep']->peserta->tglLahir), 'd/m/Y') }} &nbsp;&nbsp;&nbsp; Kelamin : {{ $res['sep']->peserta->kelamin }}</font></td>
                            {{-- batas --}}
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Jns. Kunjungan</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="28%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">{{ isset($res['sep']->tujuanKunj) ? $res['sep']->tujuanKunj->nama : '' }}</font></td>
                        </tr>
                        <tr>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">No. Telepon</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="38%" style="vertical-align:top;padding-right:10px;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->peserta->nohp }}</font></td>
                            {{-- batas --}}
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000"></font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="28%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">{{ isset($res['sep']->flagProcedure) ? $res['sep']->flagProcedure->nama : '' }}</font></td>
                        </tr>
                        <tr>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Sub/Spesialis</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="38%" style="vertical-align:top;padding-right:10px;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->poli }}</font></td>
                            {{-- batas --}}
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Poli Perujuk</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="28%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">{{ isset($res['sep']->polirujukannama) ? $res['sep']->polirujukannama : '' }}</font></td>
                        </tr>
                        <tr>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Dokter</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="38%" style="vertical-align:top;padding-right:10px;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->dpjp->nmDPJP }}</font></td>
                            {{-- batas --}}
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Kls. Hak</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="28%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->kelasRawat }}</font></td>
                        </tr>
                        <tr>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Faskes Perujuk</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="38%" style="vertical-align:top;padding-right:10px;"><font style="font-size: 10pt;" color="#000000">{{ isset($res['sep']->nmprovider) ? $res['sep']->nmprovider : '' }}</font></td>
                            {{-- batas --}}
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Kls. Rawat</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="28%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->jnsPelayanan == 'Rawat Inap' ? 'Kelas '. $res['sep']->klsRawat->klsRawatHak :'' }}</font></td>
                        </tr>
                        <tr>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Diagnosa Awal</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="38%" style="vertical-align:top;padding-right:10px;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->diagnosa }}</font></td>
                            {{-- batas --}}
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Penjamin</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="28%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->penjamin }}</font></td>
                        </tr>
                        <tr>
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">Catatan</font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000">:</font></td>
                            <td height="5" width="38%" style="vertical-align:top;padding-right:10px;"><font style="font-size: 10pt;" color="#000000">{{ $res['sep']->catatan }}</font></td>
                            {{-- batas --}}
                            <td height="5" width="15%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000"></font></td>
                            <td height="5" width="2%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000"></font></td>
                            <td height="5" width="28%" style="vertical-align:top;"><font style="font-size: 10pt;" color="#000000"></font></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:0px 30px 0px 30px;">
                    <table width="100%">
                        <tr>
                            <td height="5" width="60%" style="vertical-align:top" align="left">
                                <font style="font-size: 8pt;font-style:italic;" color="#000000">*Saya Menyetujui BPJS Kesehatan menggunakan informasi Medis Pasien jika diperlukan.</font><br/>
                                <font style="font-size: 8pt;font-style:italic;" color="#000000">*SEP bukan sebagai bukti penjaminan peserta</font><br/>
                                @if($res['sep']->jnsPelayanan == "Rawat Inap")
                                <font style="font-size: 8pt;font-style:italic;" color="#000000">** Dengan diterbitkan SEP ini, Peserta rawat inap telah mendapatkan informasi dan <br> menampati kelas rawat sesuai hak akses kelasnya (terkecuali kelas penuh atau naik kelas <br> sesuai aturan yang berlaku)</font><br/>
                                @endif
                                <font style="font-size: 8pt;font-style:italic;" color="#000000">dengan terlebih dahulu.</font><br/>
                                <font style="font-size: 8pt;font-style:italic;" color="#000000">Cetakan Ke 1 {{ $res['tglAyeuna'] }}</font><br/>
                            </td>
                            <td height="5" width="40%" style="vertical-align:top" align="center">
                                <font style="font-size: 10pt;" color="#000000">Pasien/Keluarga Pasien</font><br/>
                                {{-- <font style="font-size: 10pt;" color="#000000">_________________</font><br/> --}}
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $res['sep']->peserta->noKartu }}"><br/>
                                <font style="font-size: 10pt;" color="#000000">{{ ucwords(strtolower($res['sep']->peserta->nama)) }}</font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>    
</body>
</html>

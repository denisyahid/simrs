@extends('template.layout')
@section('title', 'Surat ketangan meninggal')
@section('page-style')

@endsection
@section('content')
<tr>
    <td style="padding-top:20px">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td align="center">
                    <font style="font-size: 14pt;font-weight: 600;text-decoration: underline;" color="#000000">
                        KETERANGAN MENINGGAL DUNIA
                    </font>
                    <br>
                    <font style="font-size: 12pt;font-weight: 600;" color="#000000">
                        {{ $raw->nosurat }}
                    </font>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td style="padding-top:10px">
        <table width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
            <tr>
                <td width="100%" height="80px" colspan="4">
                    <font style="font-size: 12pt;" color="#000000" ;>
                        Kami yang bertanda tangan dibawah ini adalah dokter dari Rumah Sakit RS Jantung dan Pembulu
                        Darah PARAMARTA dengan ini menerangkan bahwa :
                    </font>
                </td>
            </tr>
            <tr>
                <td width="100%" height="5" colspan="4"></td>
            </tr>
            <tr>
                <td width="12%">
                    <font style="font-size: 12pt;" color="#000000"></font>
                </td>
                <td width="18%">
                    <font style="font-size: 12pt;" color="#000000">Nama</font>
                </td>
                <td width="1%">
                    <font style="font-size: 12pt;" color="#000000">:</font>
                </td>
                <td width="69%">
                    <font style="font-size: 12pt; font-weight:bold" color="#000000">
                        {{ $raw->namapasien }}
                    </font>
                </td>
            </tr>
            <tr>
                <td width="100%" height="5" colspan="4"></td>
            </tr>
            <tr>
                <td width="12%">
                    <font style="font-size: 12pt;" color="#000000"></font>
                </td>
                <td width="18%">
                    <font style="font-size: 12pt;" color="#000000">No. MR</font>
                </td>
                <td width="1%">
                    <font style="font-size: 12pt;" color="#000000">:</font>
                </td>
                <td width="69%">
                    <font style="font-size: 12pt;" color="#000000">{{ $raw->noregistrasi }}</font>
                </td>
            </tr>
            <tr>
                <td width="100%" height="5" colspan="4"></td>
            </tr>
            <tr>
                <td width="12%">
                    <font style="font-size: 12pt;" color="#000000"></font>
                </td>
                <td width="18%">
                    <font style="font-size: 12pt;" color="#000000">Umur</font>
                </td>
                <td width="1%">
                    <font style="font-size: 12pt;" color="#000000">:</font>
                </td>
                <td width="69%">
                    <font style="font-size: 12pt;" color="#000000">{{ $umur->y }} Tahun {{ $umur->m }}
                        Bulan {{ $umur->d }} Hari</font>
                </td>
            </tr>
            <tr>
                <td width="100%" height="5" colspan="4"></td>
            </tr>
            <tr>
                <td width="12%">
                    <font style="font-size: 12pt;" color="#000000"></font>
                </td>
                <td width="18%">
                    <font style="font-size: 12pt;" color="#000000">Jenis Kelamin</font>
                </td>
                <td width="1%">
                    <font style="font-size: 12pt;" color="#000000">:</font>
                </td>
                <td width="69%">
                    <font style="font-size: 12pt;" color="#000000">{{ $raw->jeniskelamin }}</font>
                </td>
            </tr>
            <tr>
                <td width="100%" height="5" colspan="4"></td>
            </tr>
            <tr>

                <td width="12%">
                    <font style="font-size: 12pt;" color="#000000"></font>
                </td>
                <td width="18%">
                    <font style="font-size: 12pt;" color="#000000">Alamat</font>
                </td>
                <td width="1%">
                    <font style="font-size: 12pt;" color="#000000">:</font>
                </td>
                <td width="69%">
                    <font style="font-size: 12pt;" color="#000000">{{ $raw->alamatlengkap }}</font>
                </td>
            </tr>

            <tr>
                <td width="100%" height="5" colspan="4"></td>
            </tr>
        </table>`


        <table style="margin-top:5px" width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
            <tr>
                <td width="100%" height="50px" colspan="3">
                    <font style="font-size: 12pt;" color="#000000" ;>
                        Pada tangga{{ \Carbon\Carbon::parse($raw->tglmeninggal)->format('d-m-Y') }} jam
                        {{ \Carbon\Carbon::parse($raw->tglmeninggal)->format('H:i') }} (WIB)
                        telah
                    </font>
                </td>
            </tr>


        </table>
        <table width="100%" style="padding-top: 5px" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td align="center" width="100%">
                    <font style="font-size: 12pt;font-weight: bold" color="#000000">
                        MENINGGAL DUNIA
                    </font>
                    <br>
                </td>
            </tr>
        </table>
        <table style="margin-top:0px" width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
            <td width="100%" height="60px" colspan="3">
                <font style="font-size: 12pt;" color="#000000" ;>
                    Demikian surat keterangan ini kami buat agar yang berkepentingan maklum adanya.
                </font>
            </td>

        </table>

    </td>

</tr>
<tr>
    <td style="padding-top:25px">
        <table width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
            <tr>
                <td width="45%">
                    <font style="font-size: 12pt;" color="#000000">Bandung,
                        {{ App\Traits\Valet::getDateIndo(date('Y-m-d')) }}
                    </font>
                    <br><br>
                    <font style="font-size: 12pt;" color="#000000">Dokter Bersangkutan,</font>
                </td>
                <td width="55%"></td>

            </tr>
            @if($raw->namalengkap)
            <tr>
                <td width="50%">
                    <div style=" padding: 5 0 ; padding-left: 40px">
                        <img src="data:image/jpeg;base64,{{ $qrcode }}" width="80px" border="0">
                    </div>
                </td>
                <td width="50%" style="text-align: center  ; font-size:12pt; font-weight: bold; border: none;">

                </td>
            </tr>

            <tr>
                <td width="50%">
                    <font style="font-size: 12pt;" color="#000000">{{ $raw->namalengkap }}</font>
                    <br>

                </td>
                <td width="50%"></td>
            </tr>
            @else
            <tr>
                <td width="50%" style="margin-left: 40px;" color="#000000">
                    <p>Dokter tidak ditemukan</p>
                </td>
                <td width="50%"></td>
            </tr>
            @endif
        </table>
    </td>
</tr>
@endsection
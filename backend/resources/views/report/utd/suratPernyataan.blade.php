@extends('template.layout')
@section('title', 'Surat Pernyataan UPT')
@section('page-style')

@endsection
@section('content')
    <tr>
        <td style="padding-top:20px">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td align="center">
                        <font style="font-size: 14pt;font-weight: 600;text-decoration: underline;" color="#000000">
                            SURAT PERNYATAAN
                        </font>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td height="20px"></td>
                </tr>
                <tr>
                    <td align="center">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td width="100%" height="5" colspan="3">
                                    <font style="font-size: 12pt;font-weight: 14" color="#000000">
                                        Bersamaan ini sayang bertandatangan dibawah ini
                                    </font>
                                </td>
                            </tr>
                            <tr height="5">
                                <td width="40%">
                                    <font style="font-size: 12pt;" color="#000000">Nama</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="59%">
                                    <font style="font-size: 12pt; font-weight:bold" color="#000000">

                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <font style="font-size: 12pt;" color="#000000">Umur</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="59%">
                                    <font style="font-size: 12pt; font-weight:bold" color="#000000">

                                    </font>
                                </td>
                            </tr>
                            <tr height="6">
                                <td width="40%" style="padding-bottom: 40px">
                                    <font style="font-size: 12pt;" color="#000000">Alamat Rumah</font>
                                </td>
                                <td width="1%" style="padding-bottom: 40px">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="59%" style="padding-bottom: 40px">
                                    <font style="font-size: 12pt; font-weight:bold" color="#000000">

                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <font style="font-size: 12pt;" color="#000000">No KTP</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="59%">
                                    <font style="font-size: 12pt; font-weight:bold" color="#000000">

                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <font style="font-size: 12pt;" color="#000000">Hubungan Dengan Pasien</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="59%">
                                    <font style="font-size: 12pt; font-weight:bold" color="#000000">

                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <font style="font-size: 12pt;" color="#000000">Nama Pasien</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="59%">
                                    <font style="font-size: 12pt; font-weight:bold" color="#000000">
                                        {{ isset($dataReport['namaPasien']) ? $dataReport['namaPasien'] : '' }}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <font style="font-size: 12pt;" color="#000000">Ruangan</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="59%">
                                    <font style="font-size: 12pt; font-weight:bold" color="#000000">
                                        {{ isset($dataReport['registrasi']) && $dataReport['registrasi']['namaruangan'] ? $dataReport['registrasi']['namaruangan'] : '' }}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <font style="font-size: 12pt;" color="#000000">Nomer Rekam Medis</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="59%">
                                    <font style="font-size: 12pt; font-weight:bold" color="#000000">
                                        {{ isset($dataReport['pasien']) && $dataReport['pasien']['nocm'] ? $dataReport['pasien']['nocm'] : '' }}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <font style="font-size: 12pt;" color="#000000">Jenis Kelamin</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="59%">
                                    <font style="font-size: 12pt; font-weight:bold" color="#000000">
                                        {{ isset($dataReport['pasien']) && isset($dataReport['pasien']['jeniskelamin']) ? $dataReport['pasien']['jeniskelamin'] : '' }}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <font style="font-size: 12pt;" color="#000000">Alamat Rumah</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="59%">
                                    <font style="font-size: 12pt; font-weight:bold;text-align: left" color="#000000">
                                        {{ isset($dataReport['pasien']) &&  isset($dataReport['pasien']['alamatlengkap'])? $dataReport['pasien']['alamatlengkap'] : '' }}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" colspan="3" style="padding-left: 20px;padding-top: 40px;"
                                    height="5px">
                                    <font style="font-size: 12pt;font-weight: 14" color="#000000">
                                        Bahwa setuju untuk membayar semua kantong sesuai dengan permintaankantong darah,
                                        walaupun dalam proses pelayanan kesehatan pasien tidak semua kantong darah
                                        ditrasnsusi ke pasien
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" colspan="3" style="padding-left: 20px" height="5px">
                                    <font style="font-size: 12pt; font-weight: 14" color="#000000">
                                        Bahwa setuju bila darah yang tidakj jadi dipakai tersebut diberikan pada orang lain
                                        (pasien) yang membutuhkan
                                    </font>
                                </td>
                            </tr>

                            <tr>
                                <td width="100%" colspan="3" style="padding-left: 20px" height="5px">
                                    <font style="font-size: 12pt;font-weight: 14" color="#000000">
                                        Demikian surat pernyataan ini saya buatdengan iktikad baik dan tanpa paksaan pihak
                                        manapun.
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="33%"></td>
                                <td width="33%"></td>
                                <td width="33%" style="text-align: center;" height="90px">
                                    <font style="font-size: 12pt;" color="#000000">
                                        {{ $profile->namakota }} {{ date('d-m-Y') }}
                                    </font>
                                </td>
                            </tr>
                            <tr height="5">
                                <td width="33%" style="text-align: center;">
                                    <font style="font-size: 12pt;" color="#000000">Mengetahui</font>
                                </td>
                                <td width="33%">
                                    <font style="font-size: 12pt;" color="#000000"></font>
                                </td>
                                <td width="33%" style="text-align: center;">
                                    <font style="font-size: 12pt;" color="#000000">Yang Membuat pernyataan,</font>
                                </td>
                            </tr>
                            <tr height="5">
                                <td width="33%" style="text-align: center;" height="200px">
                                    <font style="font-size: 12pt;" color="#000000">
                                        Nama Jelas <br />
                                        ( )
                                    </font>
                                </td>
                                <td width="33%">
                                    <font style="font-size: 12pt;" color="#000000" height="200px"></font>
                                </td>
                                <td width="33%" style="text-align: center;">
                                    <font style="font-size: 12pt;" color="#000000" height="200px">
                                        Nama Jelas <br />
                                        ( )
                                    </font>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection

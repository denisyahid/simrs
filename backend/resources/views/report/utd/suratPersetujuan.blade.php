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
                            SURAT PERSETUJUAN DARAH INCLOMPLATIBLE
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
                                        Yang bertanda tangan dibawah ini :
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" height="20px" style="padding-left: 20px">
                                    <font style="font-size: 12pt;" color="#000000">Nama Dokter</font>
                                </td>
                                <td width="1%" height="20px">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="79%" height="20px">
                                    <font style="font-size: 12pt; font-weight:400" color="#000000">
                                        {{ isset($data->nama_dokter) ? $data->nama_dokter : '-' }}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" style="padding-left: 20px">
                                    <font style="font-size: 12pt;" color="#000000">Spesialisasi</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="79%">
                                    <font style="font-size: 12pt; font-weight:400" color="#000000">
                                        {{ isset($data['spesialis']) && $data['spesialis'] != 'undefined' ? $data['spesialis'] : '...' }}
                                    </font>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="60px">
                        <font style="font-size: 12pt;" color="#000000">Dengan ini menyatakan menerima darah dengan uji cocok
                            serasi <span style="font-style: italic">Incomplatible</span> dengan hasil pemerikasaan My :
                            {{ isset($data['my']) && $data['my'] != 'undefined' ? $data['my'] : '...' }}
                            MN: {{ isset($data['mn']) && $data['mn'] != 'undefined' ? $data['mn'] : '...' }} AC:
                            {{ $data['ac'] && $data['ac'] != 'undefined' ? $data['ac'] : '...' }}
                            DTC:{{ $data['dtc'] && $data['dtc'] != 'undefined' ? $data['dtc'] : '...' }} .Sedang Ab
                            S1:{{ $data['s1'] && $data['s1'] != 'undefined' ? $data['s1'] : '...' }}
                            S2:{{ $data['s2'] && $data['s2'] != 'undefined' ? $data['s2'] : '...' }},Untuk
                        </font>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td width="20%" style="padding-left: 20px">
                                    <font style="font-size: 12pt;" color="#000000">Nama Pasien</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="79%">
                                    <font style="font-size: 12pt; font-weight:400" color="#000000">
                                        {{ $dataReport->namapasien }}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" style="padding-left: 20px">
                                    <font style="font-size: 12pt;" color="#000000">No Rekam Medis</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="79%">
                                    <font style="font-size: 12pt; font-weight:400" color="#000000">
                                        {{ $dataReport->nocm }}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" style="padding-left: 20px">
                                    <font style="font-size: 12pt;" color="#000000">Gol Darah</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="79%">
                                    <font style="font-size: 12pt; font-weight:400" color="#000000">
                                        {{ $dataReport->golongandarah }}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" style="padding-left: 20px">
                                    <font style="font-size: 12pt;" color="#000000">Ruangan</font>
                                </td>
                                <td width="1%">
                                    <font style="font-size: 12pt;" color="#000000">:</font>
                                </td>
                                <td width="79%">
                                    <font style="font-size: 12pt; font-weight:400" color="#000000">
                                        {{ $dataReport->namaruangan }}
                                    </font>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="40px">
                        <font style="font-size: 12pt;" color="#000000">
                            Semua resiko tarnsfusi menjadi tanggung jawab dokter yang merawat.
                            Demikian surat persetujuanini dibuat untuk dipergunakan semestinya,atas kerja samanya
                            kami ucapkan terima kasih
                        </font>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr style="text-align: right;float: right">
                                <td height="40px" style="text-align: center">
                                    <font style="font-size: 12pt;" color="#000000">
                                        {{ $profile->namakota }} {{ date('d-m-Y') }}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center">
                                    <font style="font-size: 12pt;" color="#000000">
                                        Dokter yang merawat,
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center">
                                    <font style="font-size: 12pt;" color="#000000">
                                        (----------------------------------------------)
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

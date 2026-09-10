@extends('template.layout')
@section('title', 'Tagihan Piutang')
@section('page-style')
    <style>
        .detail tr th {
            border: 0.8px solid #000000;
        }

        .detail tr td {
            border: 0.8px solid #000000;
        }
    </style>
@endsection
@section('content')
    <table class="bayangprint" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="100%"
        style="padding:25px">
        <tr>
            <td colspan="2">
                <font style="font-size: 11pt;font-weight: 600" color="#000000">PT.Global Sekawan Kreasi</font>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <font style="font-size: 10pt;font-weight: 400" color="#000000"> Jl.Soekarno Hatta No.581,Bandung Jawa Barat
                </font>
            </td>
        </tr>
        <tr>
            <td height="5px" colspan="2">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <font style="font-size: 10pt;font-weight: 600" color="#000000">Kepada
                </font>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <font style="font-size: 10pt;font-weight: 600" color="#000000"> {{ $dataReport['datas'][0]->namarekanan }}
                </font>
            </td>
        </tr>
        <tr>
            <td height="15px" colspan="2">
            </td>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="29%">
                        <font style="font-size: 10pt;font-weight: 400" color="#000000"> No Invoice
                        </font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 10pt;font-weight: 400" color="#000000">:
                        </font>
                    </td>
                    <td width="70%">
                        <font style="font-size: 10pt;font-weight: 400" color="#000000">
                            -
                        </font>
                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td>

            </td>
            <td>
                <font style="font-size: 10pt;font-weight: 600" color="#000000"> Keterangan
                </font>
            </td>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="29%">
                        <font style="font-size: 10pt;font-weight: 400" color="#000000"> Tgl Invoice
                        </font>
                    </td>
                    <td width="1%">
                        <font style="font-size: 10pt;font-weight: 400" color="#000000">:
                        </font>
                    </td>
                    <td width="70%">
                        <font style="font-size: 10pt;font-weight: 400" color="#000000">
                            {{ date('d-m-Y') }}
                        </font>
                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td colspan="2" height="5px">
                <font style="font-size: 10pt;font-weight: 600" color="#000000">
                    Tagihan Karyawan
                </font>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table cellspacing="0" cellpadding="0" class="detail" width="100%">
                    <tr>
                        <th width="5%">
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">No
                            </font>
                        </th>
                        <th width="45%">
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">Nama Pasien
                            </font>
                        </th>
                        <th width="50%">
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">Biaya
                            </font>
                        </th>
                    </tr>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($dataReport['datas'] as $key => $data)
                        @php
                            $total += $data->totalppenjamin;
                        @endphp
                        <tr>
                            <td width="5%">
                                <font style="font-size: 10pt;font-weight: 400" color="#000000">{{ $loop->iteration }}
                                </font>
                            </td>
                            <td width="45%">
                                <font style="font-size: 10pt;font-weight: 400" color="#000000">{{ $data->namapasien }}
                                </font>
                            </td>
                            <td width="50%">
                                <font style="font-size: 10pt;font-weight: 400" color="#000000">Rp.{{ number_format($data->totalppenjamin, 0, '.', ',')  }}
                                </font>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" width="50%">
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">Total Tagihan :
                            </font>
                        </td>
                        <td width="50%">
                            <font style="font-size: 10pt;font-weight: 600;text-align: end" color="#000000">
                                Rp.{{ number_format($total, 0, '.', ',')  }}
                            </font>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <font style="font-size: 10pt;font-weight: 600" color="#000000">Terbilang :
                </font>
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <font style="font-size: 10pt;font-weight: 600"> # {{ $terbilang }} #</font>
            </td>
        </tr>
        <tr>
            <td colspan="2" height="20px">
            </td>
        </tr>
        <tr>
            <td colspan="2" width="100%">
                <table width="100%">
                    <tr>
                        <td width="60%">
                            <table z>
                                <tr>
                                    <td>
                                        <font style="font-size: 10pt;font-weight: 400" color="#000000"> Pembayaran Transfer
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <font style="font-size: 10pt;font-weight: 400" color="#000000"> Bank BNI Cab.
                                            Perintis
                                            Kemerdekaan Bandung</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <font style="font-size: 10pt;font-weight: 400" color="#000000"> An. PT. GLOBAL
                                            SEKAWAN KREASI
                                            <br />
                                            No. Rek. 1010105222
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" height="5px"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <font style="font-size: 10pt;font-weight: 400" color="#000000"> Bukti Transfer
                                            Pembayaran Mohon
                                            di Email ke <br />
                                            keuangan@rsjpparamarta.com</font>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="40%">
                            <table>
                                <tr>
                                    <td align="center">
                                        <font style="font-size: 10pt;font-weight: 400" color="#000000"> Bandung,
                                            {{ date('d-m-Y H:i:s') }}</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <font style="font-size: 10pt;font-weight: 400" color="#000000"> Regards</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="80" valign="bottom" align="center">
                                        <font style="font-size: 11pt;" color="#000000">
                                            <u><b>Eriza Safitri Zain,S.E</b></u> <br />
                                            Kepala Bagian Keuangan & SDM
                                        </font>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection

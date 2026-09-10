@extends('template.layout')
{{-- @section('title',  $dataReport['judul'] ) --}}
@section('page-style')
    <style>
        table tr td{
            font-size: 12pt;
        }

        .header-font{
            font-size: 14pt;
        }
        .header-title{
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            text-decoration-thickness: 2pt;
        }
        .normal-font{
            font-size: 12pt;
        }

        .table-identitas{
            width: 90%;
            margin-left: 30px;
        }
    </style>
@endsection
@section('content')
<tr>
    <td>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" colspan="3">
                    {{-- <hr style="border:0.5px solid #000;margin-top:5px" /> --}}
                    <font class="header-font header-title" color="#000000">Surat keterangan kesehatan mata</font>
                    {{-- <hr style="border:0.5px solid #000;margin-top:5px; width:100%" /> --}}
                </td>
            </tr>
            <tr>
                <td align="center">
                    <font class="header-font">
                        No: F./IRS/RSDGJ/XI/{{\Carbon\Carbon::parse(date("Y"))->isoFormat('Y')}}
                    </font>
                </td>
            </tr>
        </table>
        <table style="margin-top: 40px">
            <tr>
                <td>
                    <font class="normal-font">
                        Dengan ini menerangkang bahwa pada saat ini :
                    </font>
                </td>
            </tr>
        </table>
        <table class="table-identitas">
            <tr>
                <td style="width: 30%">
                    <font class="normal-font">
                        Nama
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                    </font>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <font class="normal-font">
                        Tempat Tgl.Lahir
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                    </font>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <font class="normal-font">
                        Jenis Kelamin
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                    </font>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <font class="normal-font">
                        Nama
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                    </font>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width: 75%">
                    <table>
                        <tr>
                            <td style="width: 170px">Visus <b>tanpa</b> kaca mata</td>
                            <td style="width: 5px">: OD :</td>
                            <td>.........................................................</td>
                        </tr>
                        <tr>
                            <td>Visus <b>tanpa</b> kaca mata</td>
                            <td>: OS :</td>
                            <td>.........................................................</td>
                        </tr>
                        <tr>
                            <td>Visus <b>pakai</b> kaca mata</td>
                            <td>: OD :</td>
                            <td>.........................................................</td>
                        </tr>
                        <tr>
                            <td>Visus <b>pakai</b> kaca mata</td>
                            <td>: OS :</td>
                            <td>.........................................................</td>
                        </tr>
                        <tr>
                            <td>Buta warna</td>
                            <td>ya/tidak</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table>
                        <tr>
                            <td align="center">{{$profile->namakota}}, {{\Carbon\Carbon::parse(date("Y"))->isoFormat('DD MMMM Y')}}</td>
                        </tr>
                        <tr>
                            <td align="center">Dokter yang memeriksa</td>
                        </tr>
                        <tr>
                            <td style="height: 40px"></td>
                        </tr>
                        <tr>
                            <td align="center">...........................</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection

@extends('template.layout-mcu-sks')
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
            /* line-height: 1pt; */
        }

        td{
            padding: 0;
        }

        .table-identitas{
            margin: 0;
            width: 90%;
            margin-left: 30px;
            /* padding: 0; */
        }
        .page{
            padding: 0 60px
        }
    </style>
@endsection
@section('content')
@php
    function intToRoman($num) {
    $map = [
        10 => 'X',
        9 => 'IX',
        5 => 'V',
        4 => 'IV',
        1 => 'I',
    ];

    $result = '';
    while ($num > 0) {
        foreach ($map as $key => $value) {
            if ($num >= $key) {
                $num -= $key;
                $result .= $value;
                break;
            }
        }
    }
    return $result;
}
@endphp
<tr>
    <td class="page">
        @if(isset($res[0]))
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" colspan="3">
                    {{-- <hr style="border:0.5px solid #000;margin-top:5px" /> --}}
                    <font class="header-font header-title" color="#000000">Surat keterangan kesehatan</font>
                    {{-- <hr style="border:0.5px solid #000;margin-top:5px; width:100%" /> --}}
                </td>
            </tr>
            <tr>
                <td align="center">
                    <font class="header-font">
                        No: F.{{isset($res[0]['nomorsurat'])?$res[0]['nomorsurat']:""}}/IRS/RSDGJ/{{intToRoman(date('m'))}}/{{\Carbon\Carbon::parse(date("Y"))->isoFormat('Y')}}
                    </font>
                </td>
            </tr>
        </table>
        <table style="margin-top: 0px">
            <tr>
                <td>
                    <p class="normal-font" style="text-indent: 50px">
                        Yang bertanda tangan di bawah ini, Dokter Poliklinik Eksekutif Pakungwati Rumah Sakit ini, dengan ini menerangkan bahwa :
                    </p>
                </td>
            </tr>
        </table>
        <table class="table-identitas" cellspacing="0" cellpadding="0" style="padding: 0;">
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
                        {{$res[0]['namaPasien']}}
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
                        {{$res[0]['tempatLahir']}}, {{\Carbon\Carbon::parse($res[0]['tglLahir'])->isoFormat('DD MMMM Y')}}
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
                    <font class="normal-font" style="text-transform: uppercase">
                        {{$res[0]['jenisKelamin'] == 1?'Laki-laki':'Perempuan'}}
                    </font>
                </td>
            </tr>
            <tr style="vertical-align: top">
                <td style="width: 30%">
                    <font class="normal-font">
                        Alamat
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        {{$res[0]['alamat']}}
                    </font>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="padding: 1px 0">
                    <font class="normal-font">
                        Pada pemeriksaan hari ini diketahui dalam keadaan : <span style="font-weight: bold; text-transform: uppercase; font-size: 12pt">{{isset($res[0]['hasilPemeriksaan']) ? $res[0]['hasilPemeriksaan'] : ""}}</span>
                    </font>
                </td>
            </tr>
            <tr>
                <td style="padding: 1px 0">
                    <font class="normal-font">
                        Adapun Surat Keterangan ini diperlukan untuk : <b>{{ isset($res[0]['permintaan']) ? strtoupper($res[0]['permintaan']) : ''}}</b>
                    </font>
                </td>
            </tr>
            <tr>
                <td style="padding: 1px 0">
                    <font class="normal-font">
                        Demikian Surat Keterangan ini untuk dapat dipergunakan sebagaimana mestinya.
                    </font>
                </td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" style="padding: 0">
            <tr>
                <td style="width: 55%">
                    <table>
                        <tr>
                            <td><u>Keterangan :</u></td>
                        </tr>
                        <tr>
                            <td>Tensi</td>
                            <td>:</td>
                            <td>{{$res[0]['tensiPasien']}} mmHg</td>
                        </tr>
                        <tr>
                            <td>Berat Badan</td>
                            <td>:</td>
                            <td>{{$res[0]['beratPasien']}} Kg</td>
                        </tr>
                        <tr>
                            <td>Tinggi Badan</td>
                            <td>:</td>
                            <td>{{$res[0]['tinggiPasien']}} cm</td>
                        </tr>
                        <tr>
                            <td>Penglihatan</td>
                            <td>:</td>
                            <td>{{isset($res[0]['kondisiPenglihatan']) ? $res[0]['kondisiPenglihatan'] : ""}}</td>
                        </tr>
                        <tr>
                            <td>Pendengaran</td>
                            <td>:</td>
                            <td>{{isset($res[0]['kondisiPendengaran'])?$res[0]['kondisiPendengaran']:""}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table>
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        <td align="center">{{$profile->namakota ? $profile->namakota : 'Bandung'}}, {{\Carbon\Carbon::parse($res[0]['tgl'])->isoFormat('DD MMMM Y')}}</td>
                                    </tr>
                                    <tr>
                                        <td align="center">Dokter yang memeriksa</td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{$dokter[0]->namalengkap}}" alt="">
                                            {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res[0]['dokterPemeriksa']['label']) ? $res[0]['dokterPemeriksa']['label'] : ''}}" alt=""> --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <b><u>{{$dokter[0]->namalengkap}}</u></b> <br>
                                            {{-- <b><u>{{isset($res[0]['dokterPemeriksa']['label']) ? $res[0]['dokterPemeriksa']['label'] : ''}}</u></b> <br> --}}
                                            SIP. {{$dokter[0]->nosip}} <br>
                                            NIP. {{$dokter[0]->nip}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        {{-- {{dd($res[0])}} --}}
        @else
            <h1>Data Belum diinput</h1>
        @endif

    </td>
</tr>
@endsection

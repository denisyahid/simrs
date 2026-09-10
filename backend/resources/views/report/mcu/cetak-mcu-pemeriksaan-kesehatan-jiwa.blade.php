@extends('template.layout-mcu')
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
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" colspan="3">
                    {{-- <hr style="border:0.5px solid #000;margin-top:5px" /> --}}
                    <font class="header-font header-title" color="#000000">Surat keterangan pemeriksaan kesehatan jiwa</font>
                    {{-- <hr style="border:0.5px solid #000;margin-top:5px; width:100%" /> --}}
                </td>
            </tr>
            <tr>
                <td align="center">
                    <font class="header-font">
                        No: F.{{isset($res[0]['nomorsurat']) ? $res[0]['nomorsurat'] : ""}}/IRS/RSDGJ/{{intToRoman(date('m'))}}/{{\Carbon\Carbon::parse(date("Y"))->isoFormat('Y')}}
                    </font>
                </td>
            </tr>
        </table>
        <table style="margin-top: 40px">
            <tr>
                <td>
                    <p class="normal-font">
                        Yang bertanda tangan di bawah ini :
                    </p>
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
                    {{isset($dokter[0]) ? $dokter[0]->namalengkap : ''}}
                        {{-- {{isset($res[0]['dokterPemeriksaJiwa']['label']) ? $res[0]['dokterPemeriksaJiwa']['label'] : ''}} --}}
                    </font>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <font class="normal-font">
                        Jabatan
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        DOKTER SPESIALIS KEDOKTERAN JIWA
                    </font>
                </td>
            </tr>
        </table>

        <table style="margin-top: 10px">
            <tr>
                <td>
                    Menyatakan bahwa :
                </td>
            </tr>
            <tr>
                <td>
                    Berdasarkan kuesioner swa-periksa kesehatan jiwa, wawancara, dan observasi klinis psikiatri yang dilakukan pada tanggal {{\Carbon\Carbon::parse(date("Y"))->isoFormat('DD MMMM Y')}} terhadap :
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
                        {{$res[0]['namaPasien']}}
                    </font>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <font class="normal-font">
                        NIK
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        {{$res[0]['nik']}}
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
        <table style="margin-top: 10px">
            <tr>
                <td style="padding: 5px 0">
                    <font class="normal-font">
                        Hasil Pemeriksaan pada saat ini <span style="font-weight: bold; text-transform: uppercase; font-size:12pt;">{{isset($res[0]['gangguanJiwa']) ? $res[0]['gangguanJiwa'] : ''}}</span> tanda atau gejala gangguan jiwa yang bermakna.
                    </font>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0">
                    <font class="normal-font">
                        Demikian Surat Keterangan Pemeriksaan Kesehatan Jiwa ini dibuat dengan sebenarnya untuk keperluan
                        <b>{{isset($res[0]['permintaan']) ? strtoupper($res[0]['permintaan']) : ''}}</b>
                    </font>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width: 55%">
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
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($dokter[0]) ? $dokter[0]->namalengkap : ''}}" alt="">
                                            {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res[0]['dokterPemeriksaJiwa']['label']) ? $res[0]['dokterPemeriksaJiwa']['label'] : ''}}" alt=""> --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <b><u>{{isset($dokter[0]) ? $dokter[0]->namalengkap : ''}}</u></b> <br>
                                            SIP. {{isset($dokter[0]) ? $dokter[0]->nosip : ''}} <br>
                                            NIP. {{isset($dokter[0]) ? $dokter[0]->nip : ''}}
                                            {{-- {{isset($res[0]['dokterPemeriksaJiwa']['label']) ? $res[0]['dokterPemeriksaJiwa']['label'] : ''}} --}}
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

    </td>
</tr>
@endsection

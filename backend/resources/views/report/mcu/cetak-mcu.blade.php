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
@php
    function intToRoman($num) {
    $map = [
        1000 => 'M',
        900 => 'CM',
        500 => 'D',
        400 => 'CD',
        100 => 'C',
        90 => 'XC',
        50 => 'L',
        40 => 'XL',
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
    <td>
        {{dd($res)}}
        @if($res[0])
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
                        No: F./IRS/RSDGJ/{{intToRoman(date('m'))}}/{{\Carbon\Carbon::parse(date("Y"))->isoFormat('Y')}}
                    </font>
                </td>
            </tr>
        </table>
        <table style="margin-top: 40px">
            <tr>
                <td>
                    <p class="normal-font">
                        Yang bertanda tanga di bawah ini :
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
                        {{-- {{$res[0]['namaPasien']}} --}}
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
                        {{-- {{$res[0]['namaPasien']}} --}}
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
                        {{-- {{$res[0]['tglLahir'] == 1?'Laki-laki':'Perempuan'}} --}}
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
                        {{-- {{$res[0]['tempatLahir']}}, {{\Carbon\Carbon::parse($res[0]['tglLahir'])->isoFormat('DD MMMM Y')}} --}}
                    </font>
                </td>
            </tr>
            <tr>
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
                        {{-- {{$res[0]['alamat']}} --}}
                    </font>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="padding: 5px 0">
                    <font class="normal-font">
                        Hasil Pemeriksaan pada saat ini {ISIAN} tanda atau gejala gangguan jiwa yang bermakna.
                    </font>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0">
                    <font class="normal-font">
                        Demikian Surat Keterangan Pemeriksaan Kesehatan Jiwa ini dibuat dengan sebenarnya untuk keperluan :
                        {{-- {{$res[0]['permintaan']}} --}}
                    </font>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width: 70%">
                </td>
                <td>
                    <table>
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        <td align="center">{{$profile->namakota ? $profile->namakota : 'Bandung'}}, {{\Carbon\Carbon::parse(date("Y"))->isoFormat('DD MMMM Y')}}</td>
                                    </tr>
                                    <tr>
                                        <td align="center">Dokter yang memeriksa</td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{$res[0]['dokterPemeriksa']['label']}}" alt=""> --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            {{-- {{$res[0]['dokterPemeriksa']['label']}} --}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        @else
            <h1>Silakan periksa kembali input data</h1>
        @endif

    </td>
</tr>
@endsection

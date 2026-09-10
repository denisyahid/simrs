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
        .hasil-table,
        .hasil-table tr,
        .hasil-table td{
            border-collapse: collapse;
            border: 1px solid black;
            font-size: 10pt;
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
                    <font class="header-font header-title" color="#000000">Surat keterangan pemeriksaan napza</font>
                    {{-- <hr style="border:0.5px solid #000;margin-top:5px; width:100%" /> --}}
                </td>
            </tr>
            <tr>
                <td align="center">
                    <font class="header-font">
                        No: F.{{isset($res[0]['nomorsurat']) ? $res[0]['nomorsurat'] : ''}}/IRS/RSDGJ/{{intToRoman(date('m'))}}/{{\Carbon\Carbon::parse(date("Y"))->isoFormat('Y')}}
                    </font>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    Yang bertanda tangan di bawah ini :
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
                        {{-- {{isset($res[0]['dokterPemeriksaNapza']['label']) ? $res[0]['dokterPemeriksaNapza']['label'] : ''}} --}}
                    </font>
                </td>
            </tr>
            {{-- <tr>
                <td style="width: 30%">
                    <font class="normal-font">
                        SIP
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        {SIP DOKTER}
                    </font>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <font class="normal-font">
                        NIP
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        {NIP DOKTER}
                    </font>
                </td>
            </tr> --}}
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
            <tr>
                <td style="width: 30%">
                    <font class="normal-font">
                        Instansi
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        {{isset($res[0]['profile']['namaprofile']) ? $res[0]['profile']['namaprofile'] : ''}}
                    </font>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td>
                    Telah melakukan pemeriksaan psikiatrik pada Tanggal  {{\Carbon\Carbon::parse(isset($res[0]['tgl']) ? $res[0]['tgl'] : '')->isoFormat('DD MMMM Y')}}, Terhadap:
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
                        {{isset($res[0]['namaPasien']) ? $res[0]['namaPasien'] : ''}}
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
                        {{isset($res[0]['tempatLahir']) ? $res[0]['tempatLahir'] : "" }}, {{\Carbon\Carbon::parse(isset($res[0]['tglLahir']) ? $res[0]['tglLahir'] : '')->isoFormat('DD MMMM Y')}}
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
                        {{isset($res[0]['pasien']['jeniskelamin']) ? $res[0]['pasien']['jeniskelamin'] : ''}}
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
                        {{isset($res[0]['alamat']) ? $res[0]['alamat'] : ''}}
                    </font>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <font class="normal-font">
                        Dan berdasarkan pemeriksaan :
                    </font>
                </td>
            </tr>
        </table>
        <table class="hasil-table" style="width: 100%">
            <tr>
                @php
                    $date = $res[0]['tglPemeriksaanJiwa']
                @endphp
                <td colspan="3">Fisik Diagnostik tanggal {{\Carbon\Carbon::parse($res[0]['tgl'])->isoFormat('DD MMMM Y')}} jam {{\Carbon\Carbon::parse($date)->isoFormat('HH:mm') }} WIB</td>
            </tr>
            <tr>
                <td>Tanda Vital</td>
                <td>:</td>
                <td>
                    {{-- {{dd($res[0])}} --}}
                    TD : {{ isset($res[0]['tensiPasien']) ? $res[0]['tensiPasien'] : "" }} mmHg
                    N : {{ isset($res[0]['nadiPasien']) ? $res[0]['nadiPasien'] : "" }} BPM
                    RR: {{ isset($res[0]['pernafasanPasien']) ? $res[0]['pernafasanPasien'] : "" }} XPM
                    SB: {{ isset($res[0]['suhuPasien']) ? $res[0]['suhuPasien'] : "" }}<sup>o</sup>C
                </td>
            </tr>
            <tr>
                <td>Penampilan</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['penampilan']) ? $res[0]['penampilan'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Cara Berjalan</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['caraJalan']) ? $res[0]['caraJalan'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Cara Bicara</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['caraBicara']) ? $res[0]['caraBicara'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Mata Pupil</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['mataPupil']) ? $res[0]['mataPupil'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Konjungtiva</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['konjungtiva']) ? $res[0]['mataPupil'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Tremor</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['tremor']) ? $res[0]['tremor'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Kulit</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['kulit']) ? $res[0]['kulit'] : ''}}
                </td>
            </tr>
            <tr>
                <td colspan="3">Psikiatrik tanggal {{\Carbon\Carbon::parse($res[0]['tglPemeriksaanJiwa'])->isoFormat('DD MMMM Y')}} jam {{\Carbon\Carbon::parse($res[0]['tglPemeriksaanJiwa'])->isoFormat('HH:mm')}} WIB</td>
            </tr>
            <tr>
                <td>Alur Pembicaraan</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['alurPembicaraan']) ? $res[0]['alurPembicaraan'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Waham</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['waham']) ? $res[0]['waham'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Halusinasi</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['halusinasi']) ? $res[0]['halusinasi'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Lain-lain</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['lainlain']) ? $res[0]['lainlain'] : ''}}
                </td>
            </tr>
            <tr>
                <td colspan="3">Pemeriksaan Laboratorium tanggal {{\Carbon\Carbon::parse($res[0]['tglPemeriksaanNapza'])->isoFormat('DD MMMM Y')}} jam {{\Carbon\Carbon::parse($res[0]['tglPemeriksaanNapza'])->isoFormat('HH:mm')}} WIB yang dilakukan di {{$res[0]['profile']['namaprofile']}} dengan menggunakan metode {{isset($res[0]['metodeLaboratorium']) ? $res[0]['metodeLaboratorium'] : ''}}</td>
            </tr>
            <tr>
                <td>Cannabis</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['cannabis']) ? $res[0]['cannabis'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Opiate</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['oppiate']) ? $res[0]['oppiate'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Coccain</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['coccain']) ? $res[0]['coccain'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Metamphetamine</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['metamphetamine']) ? $res[0]['metamphetamine'] : ''}}
                </td>
            </tr>
            <tr>
                <td>MDMA</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['mdma']) ? $res[0]['mdma'] : ''}}
                </td>
            </tr>
            <tr>
                <td>Benzodiazepin</td>
                <td>:</td>
                <td>
                    {{isset($res[0]['benzodiazepin']) ? $res[0]['benzodiazepin'] : ''}}
                </td>
            </tr>
        </table>
        <table style="margin-top: 10px">
            <tr>
                <td>menunjukan <span style="font-weight: bold; text-transform: uppercase; font-size:12pt;">{{isset($res[0]['gejalaNarkotika']) ? $res[0]['gejalaNarkotika'] : ''}}</span> gejala-gejala penggunaan narkotika/zat psikotropika</td>
            </tr>
            <tr>
                <td style="padding: 5px 0">
                    <font class="normal-font">
                        Demikian Surat Keterangan Pemeriksaan Kesehatan Jiwa ini digunakan untuk keperluan
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
                                            {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res[0]['dokterPemeriksaNapza']['label']) ? $res[0]['dokterPemeriksaNapza']['label'] : ''}}" alt=""> --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <b><u>{{isset($dokter[0]) ? $dokter[0]->namalengkap : ''}}</u></b> <br>
                                            SIP. {{isset($dokter[0]) ? $dokter[0]->nosip : ''}} <br>
                                            NIP. {{isset($dokter[0]) ? $dokter[0]->nip : ''}}
                                            {{-- {{isset($res[0]['dokterPemeriksaNapza']['label']) ? $res[0]['dokterPemeriksaNapza']['label'] : ''}} --}}
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

@extends('template.layout-emr')
@section('title', 'Triage Pasien IGD')
{{-- @section('kode', 'RM.1A/RLD/01') --}}
@section('page-style')
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        .bold {
            font-weight: bold;
        }

        table {
            border-collapse: collapse !important;
            width: 100%;
        }

        .pd td {
            padding: 3px;
            text-align: center;
            font-size: 10pt;
        }

        .fnt {
            font-size: 9pt;
        }

        .fnt th {
            border: 1px solid black;
            border-bottom: none;
        }

        .fnt td {
            vertical-align: top;
            padding: 3px;
        }

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        .mid {
            text-align: center !important;
        }

        .border {
            border: 1px solid black;
        }

        .font {
            font-size: 10pt !important;
        }
    </style>
@endsection

@php
    function convertToRegularDate($isoDateString)
    {
        $date = new DateTime($isoDateString);
        return $date->format('d-m-Y');
    }

    function convertToRegularTime($isoDateString)
    {
        $date = new DateTime($isoDateString);
        return $date->format('H:i');
    }

    function formatKU($ku)
    {
        switch ($ku) {
            case 1:
                return 'Baik';
                break;
            case 2:
                return 'Sedang';
                break;
            case 3:
                return 'Buruk';
                break;
            default:
                break;
        }
    }
@endphp

@section('content')
    <tr>
        <td>
            <table class="pd">
                <td style="width: 33%;border-right: 1px solid black">
                    Tanggal
                    {{ isset($data['DTanggalKedatangan']) ? ': ' . convertToRegularDate($data['DTanggalKedatangan']) : '-' }}
                </td>
                <td style="width: 33%;border-right: 1px solid black">
                    Jam Kedatangan
                    {{ isset($data['TjamKedatangan']) ? ': ' . convertToRegularTime($data['TjamKedatangan']) : '-' }} WIB
                </td>
                <td style="width: 33%;">
                    Jam Triage {{ isset($data['TjamTriage']) ? ': ' . convertToRegularTime($data['TjamTriage']) : '-' }}
                    WIB
                </td>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="fnt">
                <tr>
                    <th style="background-color: rgb(65, 176, 255)" colspan="4">PRIMARY SURVEY</th>
                </tr>
                <tr>
                    <th style="background-color: rgb(65, 176, 255);width: 13%;">AIRWAY</th>
                    <th style="background-color: rgb(65, 176, 255);width: 17%;">BREATHING</th>
                    <th style="background-color: rgb(65, 176, 255);width: 30%;">CIRCULATION</th>
                    <th style="background-color: rgb(65, 176, 255);width: 30%;">DISABILITY/NEUROLOGICAL</th>
                </tr>
                <tr>
                    <td style="border-right: 1px solid black;border-top:1px solid black">
                        <input type="checkbox" {{ isset($data['Bebas']) ? 'checked' : '' }} />
                        <span>Bebas</span><br>
                        <input type="checkbox" {{ isset($data['Gargling']) ? 'checked' : '' }} />
                        <span>Gargling</span><br>
                        <input type="checkbox" {{ isset($data['Stridor']) ? 'checked' : '' }} />
                        <span>Stridor</span><br>
                        <input type="checkbox" {{ isset($data['Wheezing']) ? 'checked' : '' }} />
                        <span>Wheezing</span><br>
                        <input type="checkbox" {{ isset($data['Ronchi']) ? 'checked' : '' }} />
                        <span>Ronchi</span><br>
                        <input type="checkbox" {{ isset($data['Terintubasi']) ? 'checked' : '' }} />
                        <span>Terintubasi</span>
                    </td>
                    <td style="border-right: 1px solid black;border-top:1px solid black">
                        <input type="checkbox" {{ isset($data['Spontan']) ? 'checked' : '' }} />
                        <span>Spontan</span><br>
                        <input type="checkbox" {{ isset($data['Tachipneu']) ? 'checked' : '' }} />
                        <span>Tachipneu</span><br>
                        <input type="checkbox" {{ isset($data['Dispneu']) ? 'checked' : '' }} />
                        <span>Dispneu</span><br>
                        <input type="checkbox" {{ isset($data['Apneu']) ? 'checked' : '' }} />
                        <span>Apneu</span><br>
                        <input type="checkbox" {{ isset($data['Ventilasi Mekanik']) ? 'checked' : '' }} />
                        <span>Ventilasi Mekanik</span><br>
                        <input type="checkbox" {{ isset($data['Memakai Ventilator']) ? 'checked' : '' }} />
                        <span>Memakai Ventilator</span>
                    </td>
                    <td style="border-right: 1px solid black;border-top:1px solid black">
                        <span class="bold">Nadi</span><br>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBNadi']) && $data['CBNadi'] == 'Kuat' ? 'checked' : '' }} />
                        <span>Kuat</span>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBNadi']) && $data['CBNadi'] == 'Lemah' ? 'checked' : '' }} />
                        <span>Lemah</span>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBNadi']) && $data['CBNadi'] == 'Tidak Ada Nadi' ? 'checked' : '' }} />
                        <span>Tidak Ada Nadi</span><br>
                        <span class="bold">CRT</span><br>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBCRT']) && $data['CBCRT'] == '< 2' ? 'checked' : '' }} />
                        <span>&lt; 2</span>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBCRT']) && $data['CBCRT'] == '> 2' ? 'checked' : '' }} />
                        <span>&gt; 2</span><br>
                        <span class="bold">Warna Kulit : <span
                                style="font-weight: lighter">{{ isset($data['TBWarnakulit']) ? $data['TBWarnakulit'] : '-' }}</span></span><br>
                        <span class="bold">Perdarahan</span><br>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBPerdarahan']) && $data['CBPerdarahan'] == 'Terkontrol' ? 'checked' : '' }} />
                        <span>Terkontrol</span>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBPerdarahan']) && $data['CBPerdarahan'] == 'Tidak terkontrol' ? 'checked' : '' }} />
                        <span>Tidak terkontrol</span><br>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBPerdarahan']) && $data['CBPerdarahan'] == 'Tidak ada' ? 'checked' : '' }} />
                        <span>Tidak ada</span><br>
                        <span class="bold">Tugor Kulit</span><br>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBTurgorKulit']) && $data['CBTurgorKulit'] == 'Baik' ? 'checked' : '' }} />
                        <span>Baik</span>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBTurgorKulit']) && $data['CBTurgorKulit'] == 'Buruk' ? 'checked' : '' }} />
                        <span>Buruk</span><br>
                    </td>
                    <td style="border-right: 1px solid black;border-top:1px solid black">
                        <span class="bold">Respon</span><br>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBRespon']) && $data['CBRespon'] == 'Alert' ? 'checked' : '' }} />
                        <span>Alert</span>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBRespon']) && $data['CBRespon'] == 'Pain' ? 'checked' : '' }} />
                        <span>Pain</span>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBRespon']) && $data['CBRespon'] == 'Verbal' ? 'checked' : '' }} />
                        <span>Verbal</span>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBRespon']) && $data['CBRespon'] == 'Unrespon' ? 'checked' : '' }} />
                        <span>Unrespon</span><br>
                        <span class="bold">Pupil</span><br>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBPupil']) && $data['CBPupil'] == 'Isokor' ? 'checked' : '' }} />
                        <span>Isokor</span>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBPupil']) && $data['CBPupil'] == 'Anisokor' ? 'checked' : '' }} />
                        <span>Anisokor</span>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBPupil']) && $data['CBPupil'] == 'Midriasis' ? 'checked' : '' }} />
                        <span>Midriasis</span><br>
                        <span class="bold">Refleks</span><br>
                        <span>{{ isset($data['TB1Refelks']) ? $data['TB1Refelks'] : '-' }} /
                            {{ isset($data['TB2Refelks']) ? $data['TB2Refelks'] : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="border-top:1px solid black" colspan="4">
                        <span class="bold">Kategori Triage</span><br>
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBKT']) && $data['CBKT'] == '1 (Segera)' ? 'checked' : '' }} />
                        <span>1 (Segera)</span>&nbsp;&nbsp;
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBKT']) && $data['CBKT'] == '2 (10 Menit)' ? 'checked' : '' }} />
                        <span>2 (10 Menit)</span>&nbsp;&nbsp;
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBKT']) && $data['CBKT'] == '3 (30 Menit)' ? 'checked' : '' }} />
                        <span>3 (30 Menit)</span>&nbsp;&nbsp;
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBKT']) && $data['CBKT'] == '4 (60 Menit)' ? 'checked' : '' }} />
                        <span>4 (60 Menit)</span>&nbsp;&nbsp;
                        <input type="checkbox" style="padding-top: 5px;"
                            {{ isset($data['CBKT']) && $data['CBKT'] == '5 (120 Menit)' ? 'checked' : '' }} />
                        <span>5 (120 Menit)</span>
                    </td>
                </tr>
                <tr>
                    <td style="border-top:1px solid black" colspan="4">
                        <span class="bold">
                            Keadaan umum : <span style="font-weight: lighter">{{ isset($data['keadaanumum']) ? formatKU($data['keadaanumum']) : '-' }}</span>
                        </span><br>
                        <span class="bold">GCS : </span><span><b>E</b> {{ isset($data['TBeGCS']) ? $data['TBeGCS'] : '-' }} <b>V</b> {{ isset($data['TBvGCS']) ? $data['TBvGCS'] : '-' }} <b>M</b> {{ isset($data['TBmGCS']) ? $data['TBmGCS'] : '-' }}</span><br>
                        <span class="bold">Tanda-tanda Vital</span><br>
                        <table>
                            <tr>
                                <td style="width:25%">Suhu : {{ isset($data['TBcelciusTTV']) ? $data['TBcelciusTTV'] : '-' }} °C</td>
                                <td style="width:25%">Pernafasan : {{ isset($data['TBPernafasanTTV']) ? $data['TBPernafasanTTV'] : '-' }}</td>
                                <td style="width:25%">Berat Badan : {{ isset($data['TBberatBadanTTV']) ? $data['TBberatBadanTTV'] : '-' }} Kg</td>
                                <td style="width:25%">Nadi : {{ isset($data['TBnadiTTV']) ? $data['TBnadiTTV'] : '-' }} x/mnt</td>
                            </tr>
                            <tr>
                                <td style="width:25%">Tekanan Darah : {{ isset($data['TBtekananDarahTTV']) ? $data['TBtekananDarahTTV'] : '-' }} mmHg</td>
                                <td style="width:25%">SaO2 : {{ isset($data['TBnspo2TTV']) ? $data['TBnspo2TTV'] : '-' }} %</td>
                                <td style="width:25%">Tinggi Badan : {{ isset($data['TBtinggiBadanTTV']) ? $data['TBtinggiBadanTTV'] : '-' }} Cm</td>
                            </tr>
                            <tr>
                                <td width="60%" colspan="3"></td>
                                <td style="text-align: center" width="40%">
                                    <br>
                                    Garut, {{ isset($data['DTanggalKedatangan']) ? ': ' . convertToRegularDate($data['DTanggalKedatangan']) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="60%" colspan="3"></td>
                                <td style="text-align: center" width="40%">
                                    Nama dan Tanda tangan Petugas Triage
                                </td>
                            </tr>
                            <tr>
                                <td width="60%" colspan="3"></td>
                                <td style="text-align: center" width="40%">
                                    {{-- <br> --}}
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $data['CBPetugas']['label'] }}"><br/>
                                    {{-- <br><br> --}}
                                </td>
                            </tr>
                            <tr>
                                <td width="60%" colspan="3"></td>
                                <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                                    @if (is_array($data['CBPetugas']) && array_key_exists('label', $data['CBPetugas']))
                                        {{ $data['CBPetugas']['label'] ?? '-' }}
                                    @else
                                        {{ $data['CBPetugas'] ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection

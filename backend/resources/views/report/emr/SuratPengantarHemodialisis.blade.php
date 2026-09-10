{{-- @extends('template.layout-emr') --}}
{{-- @section('title', 'Surat Pengantar Hemodialisis (Travelling Dialisis)') --}}
{{-- @section('kode', 'RM.13.14/HD/00') --}}
{{-- @section('page-style') --}}
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

    table {
        page-break-inside: auto
    }

    tr {
        page-break-inside: auto;
        page-break-after: auto
    }

    .pdl {
        padding-left: 10px !important;
    }

    .pd td {
        padding: 3px;
        text-align: center;
        font-size: 10pt;
    }

    .fnt {
        font-size: 8pt;
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
        vertical-align: top;
        display: inline-block;
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
{{-- @endsection --}}

<title>Surat Pengantar Hemodialisis</title>

@php
    // Mempersingkat
    $d = $data;
    $imgDefault =
        'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII=';

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

    function convertToMakassarDate($isoDateString)
    {
        $date = new DateTime($isoDateString, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
        return $date->format('d-m-Y H:i:s');
    }
@endphp


<table width="100%" cellspacing="0" cellpadding="0"
    style="border-bottom: 1px solid black;border-collapse: collapse;margin-bottom: 5px">
    <tr>
        <td style="text-align: center; padding: 15px;">
            <img src="{{ 'img/logo-rs.png' }}" width="90px" height="90px" style="display: block;">
        </td>
        <td style="text-align: center;">
            <span style="font-weight: bold;">PEMERINTAH PROVINSI BALI </span><br>
            <span style="font-weight: bold;">DINAS KESEHATAN</span> <br>
            <span style="font-weight: bold;">RUMAH SAKIT UMUM DAERAH BALI MANDARA</span> <br>
            <span>Jalan ByPass Ngurah Rai No.548 Sanur,Garut-Bali</span>
            <span>No.Telp : (0361) 4490566, E-mail : rsud.balimandara@gmail.com</span>
        </td>
        <td style="text-align: center; padding: 15px;vertical-align: top">
            <span>RM.13.14/HD/00</span>
        </td>
    </tr>
</table>
<table>
    <tr>
        <td>
            <table class="fnt">
                <tr>
                    <td>
                        <span><b>Nama Pasien :</b> {{ isset($d['TBNamaPasien']) ? $d['TBNamaPasien'] : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Umur :</b> {{ isset($d['TBSTahun']) ? $d['TBSTahun'] : '-' }} Tahun,
                            {{ isset($d['TBSBulan']) ? $d['TBSBulan'] : '-' }} Bulan, Tanggal Lahir :
                            {{ isset($d['DTanggalLahir']) ? convertToRegularDate($d['DTanggalLahir']) : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Jenis Kelamin :</b>
                            {{ isset($d['TBJenisKelamin']) ? $d['TBJenisKelamin'] : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Alamat :</b> {{ isset($d['TAAlamat']) ? $d['TAAlamat'] : '-' }}, RT :
                            {{ isset($d['TBRT']) ? $d['TBRT'] : '-' }}, RW : {{ isset($d['TBRW']) ? $d['TBRW'] : '-' }},
                            Kota : {{ isset($d['TBKota']) ? $d['TBKota'] : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <table class="fnt">
                                <tr>
                                    <td style="padding: 0px;width: 12%"><b>Diagnosis :</b></td>
                                    <td style="width: 20%">
                                        <input type="checkbox" {{ isset($d['CBESRD']) ? 'checked' : '' }} />
                                        <span>ESRD</span>
                                    </td>
                                    <td style="width: 20%">
                                        <input type="checkbox" {{ isset($d['CBAcutOnCKD']) ? 'checked' : '' }} />
                                        <span>Acut on CKD</span>
                                    </td>
                                    <td style="width: 20%">
                                        <input type="checkbox" {{ isset($d['CBPreOp']) ? 'checked' : '' }} />
                                        <span>Pre Op</span>
                                    </td>
                                    <td style="width: 20%">
                                        <input type="checkbox"
                                            {{ isset($d['CBLainnyaDiagnosis']) ? 'checked' : '' }} />
                                        <span>{{ isset($d['TBLainnyaDiagnosis']) ? $d['TBLainnyaDiagnosis'] : '' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Hemodialisis Pertama :</b>
                            {{ isset($d['DHemodialisisPertama']) ? convertToRegularDate($d['DHemodialisisPertama']) : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Hemodialisis Terakhir :</b>
                            {{ isset($d['DHemodialisisTerakhir']) ? convertToRegularDate($d['DHemodialisisTerakhir']) : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <table class="fnt">
                                <tr>
                                    <td style="padding: 0px;width: 12%"><b>Prekuensi Hemodialisis :</b></td>
                                    <td style="width: 20%">
                                        <input type="checkbox" {{ isset($d['CB1xmgg']) ? 'checked' : '' }} />
                                        <span>1x/mgg</span>
                                    </td>
                                    <td style="width: 20%">
                                        <input type="checkbox" {{ isset($d['CB2xmgg']) ? 'checked' : '' }} />
                                        <span>2x/mgg</span>
                                    </td>
                                    <td style="width: 20%">
                                        <input type="checkbox" {{ isset($d['CB3xmgg']) ? 'checked' : '' }} />
                                        <span>3x/mgg</span>
                                    </td>
                                    <td style="width: 20%">
                                        <input type="checkbox" {{ isset($d['CBLainnyaPH']) ? 'checked' : '' }} />
                                        <span>{{ isset($d['TBLainnyaPH']) ? $d['TBLainnyaPH'] : '' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="fnt">
                            <tr>
                                <td style="padding: 0px;width: 12%"><b>Mesin Hemodialisis :</b></td>
                                <td style="width: 25%">
                                    <input type="checkbox" {{ isset($d['CBFresinius']) ? 'checked' : '' }} />
                                    <span>Fresinius</span>
                                </td>
                                <td style="width: 25%">
                                    <input type="checkbox" {{ isset($d['CBNIPRO']) ? 'checked' : '' }} />
                                    <span>NIPRO</span>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Dialises :</b> {{ isset($d['TBDialises']) ? $d['TBDialises'] : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="fnt">
                            <tr>
                                <td style="padding: 0px;width: 12%"><b>Jenis Dialisat :</b></td>
                                <td style="width: 25%">
                                    <input type="checkbox" {{ isset($d['CBBicarbonat']) ? 'checked' : '' }} />
                                    <span>Bicarbonat</span>
                                </td>
                                <td style="width: 25%">
                                    <input type="checkbox" {{ isset($d['CBAcide']) ? 'checked' : '' }} />
                                    <span>Acide</span>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <table class="fnt">
                                <tr>
                                    <td style="padding: 0px;width: 12%"> <b>Kecepatan Aliran Dialisat (QD) :</b>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['CB500ccQD']) ? 'checked' : '' }} />
                                        <span>500cc/mnt</span>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['CB300ccQD']) ? 'checked' : '' }} />
                                        <span>300cc/mnt</span>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['CABKADQD']) ? 'checked' : '' }} />
                                        <span>{{ isset($d['TBKADQD']) ? $d['TBKADQD'] : '' }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <table class="fnt">
                                <tr>
                                    <td style="padding: 0px;width: 12%"> <b>Kecepatan Aliran Darah (QB) :</b>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['CB150ccQB']) ? 'checked' : '' }} />
                                        <span>150cc/mnt</span>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['CB150200ccQB']) ? 'checked' : '' }} />
                                        <span>150-200cc/mnt</span>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['CB200250ccQB']) ? 'checked' : '' }} />
                                        <span>200-250cc/mnt</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <table class="fnt">
                                <tr>
                                    <td style="padding: 0px;width: 12%"><b>Lama Hemodialisis (TD) :</b>
                                    </td>
                                    <td style="width: 20%">
                                        <input type="checkbox" {{ isset($d['CB2to3jamTD']) ? 'checked' : '' }} />
                                        <span>2 - 3 Jam</span>
                                    </td>
                                    <td style="width: 20%">
                                        <input type="checkbox" {{ isset($d['CB3to4jamTD']) ? 'checked' : '' }} />
                                        <span>3 - < 4 Jam</span>
                                    </td>
                                    <td style="width: 20%">
                                        <input type="checkbox" {{ isset($d['CB4setengahJamTD']) ? 'checked' : '' }} />
                                        <span>4,5 Jam</span>
                                    </td>
                                    <td style="width: 20%">
                                        <input type="checkbox" {{ isset($d['CB5JamTD']) ? 'checked' : '' }} />
                                        <span>5 Jam</span>
                                    </td>
                                    <td style="width: 20%">
                                        <input type="checkbox"
                                            {{ isset($d['CBLamaHemodalisisTD']) ? 'checked' : '' }} />
                                        <span>{{ isset($d['TBHemodialisisTD']) ? $d['TBHemodialisisTD'] : '' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <table class="fnt">
                                <tr>
                                    <td style="padding: 0px;width: 12%"><b>Heparinisasi :</b>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['CBDosisStandarH']) ? 'checked' : '' }} />
                                        <span>Dosis Standar</span>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['CBDosisMinimalH']) ? 'checked' : '' }} />
                                        <span>Dosis Minimal</span>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['CBLMWH']) ? 'checked' : '' }} />
                                        <span>LMWH {{ isset($d['TBLMWH']) ? $d['TBLMWH'] : '' }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <table class="fnt">
                                <tr>
                                    <td style="padding: 0px;width: 12%"><b>Akses Vaskular :</b>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['Femoral']) ? 'checked' : '' }} />
                                        <span>Femoral</span>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['AVShunt']) ? 'checked' : '' }} />
                                        <span>AV-Shunt</span>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['SubclaviaCatheter']) ? 'checked' : '' }} />
                                        <span>Subclavia Catheter</span>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="checkbox" {{ isset($d['JugularCatheter']) ? 'checked' : '' }} />
                                        <span>Jugular Catheter</span>
                                    </td>
                                </tr>
                            </table>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <b>HbsAg :</b> {{ isset($d['CBHbsAg']) ? $d['CBHbsAg'] : '-' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <b>Anti HCV :</b> {{ isset($d['CBAntiHCV']) ? $d['CBAntiHCV'] : '-' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <b>Anti HIV :</b> {{ isset($d['CBAntiHIV']) ? $d['CBAntiHIV'] : '-' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Penyulit HD :</b> {{ isset($d['TBPenyulitHD']) ? $d['TBPenyulitHD'] : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Berat Badan Kering :</b>
                            {{ isset($d['TBSBeratBadanKering']) ? $d['TBSBeratBadanKering'] : '-' }}
                            Kg</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Tekanan Darah :</b> Sistolik :
                            {{ isset($d['TBSSistolikTD']) ? $d['TBSSistolikTD'] : '-' }} mmHg,
                            Diastolik : {{ isset($d['TBDiastolik']) ? $d['TBDiastolik'] : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Hasil Laboratorium Terakhir :</b></span>
                        <table class="fnt">
                            <tr>
                                <td>Hb : {{ isset($d['TBHBHLT']) ? $d['TBHBHLT'] : '-' }}</td>
                                <td>Ureum : {{ isset($d['TBUreumHLT']) ? $d['TBUreumHLT'] : '-' }}</td>
                                <td>Kreatinin : {{ isset($d['TBKreatininHLT']) ? $d['TBKreatininHLT'] : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Natrium : {{ isset($d['TBNatriumHLT']) ? $d['TBNatriumHLT'] : '-' }}</td>
                                <td>Kalium : {{ isset($d['TBKaliumHLT']) ? $d['TBKaliumHLT'] : '-' }}</td>
                                <td>Albumin : {{ isset($d['TBAlbuminHLT']) ? $d['TBAlbuminHLT'] : '-' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Transfusi Darah Terakhir :</b>
                            {{ isset($d['TransfusiDarahTerakhir']) ? convertToRegularDate($d['TransfusiDarahTerakhir']) : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><b>Obat-obatan :</b> {{ isset($d['ObatObatan']) ? $d['ObatObatan'] : '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="fnt">
                            <td style="width: 70%"></td>
                            <td style="width: 30%;text-align: center">
                                <span>Garut,
                                    {{ isset($d['tanggal']) ? convertToRegularDate($d['tanggal']) : '-' }}</span><br>
                                <span><b>Dokter Yang Merawat</b></span><br>
                                @if (isset($d['TTDDokter']) && $d['TTDDokter'] != $imgDefault)
                                    <img style="width: 100px;height: 100px;" src="{{ $d['TTDDokter'] }}">
                                @else
                                    <br><img
                                        src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $d['DokterYangMerawat']['label'] }}"><br />
                                @endif
                                <br>
                                <span>{{ isset($d['Dokter']['label']) ? $d['Dokter']['label'] : '-' }}</span>
                            </td>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

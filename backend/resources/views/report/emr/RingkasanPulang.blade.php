<!DOCTYPE html>
<html>

@php
    use Carbon\Carbon;

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

    function getKeadaanUmum($value)
    {
        $keadaanUmum = [
            1 => 'Baik',
            2 => 'Sedang',
            3 => 'Lemah',
            4 => 'Jelek',
        ];

        return $keadaanUmum[$value] ?? '';
    }

    $d = $data;
    $imgDefault =
        'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII=';
@endphp

<head>
    <title>Ringkasan Pulang</title>
    <style>
        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        input[type=checkbox] {
            margin-bottom: -5px;
        }

        .border {
            border: 1px solid black;
        }

        .table-footer td {
            text-align: center;
            vertical-align: top;
        }

        .table-body {
            font-size: 12px;
            font-family: 'Open Sans', sans-serif;
        }

        .table-body th {
            padding: 3px;
            margin: 0px;
            text-align: center;
            vertical-align: middle;
        }

        .table-body td {
            vertical-align: top;
            padding: 3px;
        }

        .table-obat td {
            vertical-align: middle;
            border: 1px solid black;
        }

        .table-obat th {
            text-align: center;
            vertical-align: middle;
            border: 1px solid black;
            background-color: skyblue;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0px;
            page-break-inside: auto;
        }

        pre {
            margin: 0px;
            padding: 0px;
            font-family: 'Open Sans', sans-serif;
            white-space: pre-wrap;
            word-wrap: break-word;
            width: 100%;
        }

        tr {
            page-break-after: auto
        }
    </style>
</head>

<body>
    <table class="border">
        <thead>
            <tr>
                <td colspan="4" style="border-top: 1px solid black">
                    <table>
                        <tr>
                            <th
                                style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;text-align: left;">
                                RSUD BALI MANDARA
                            </th>
                            <th
                                style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                                RM.12/IRNA/01
                            </th>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding: 0px">
                    <table>
                        <tr>
                            <td width="10%" style="text-align: center;padding: 10px;border-left: none" class="border">
                                <img src="{{ 'img/logo-rs.png' }}" width="65px" height="65px"
                                    style="display: block;">
                            </td>
                            <td width="40%"
                                style="vertical-align: middle;text-align: center;font-weight: bold;font-size: large"
                                class="border">
                                RINGKASAN PULANG
                            </td>
                            <td width="40%" style="padding: 5px;border-right: none" class="border">
                                <table style="width: 100%">
                                    <tr style="font-size: 10pt">
                                        <td style="text-align:left;width: 40%">Nama</td>
                                        <td style="text-align:left;width: 10%;text-align: center">:</td>
                                        <td style="text-align:left;width: 50%">{{ $pasien['namapasien'] }}</td>
                                    </tr>
                                    <tr style="font-size: 10pt">
                                        <td style="text-align:left;width: 40%">Tanggal Lahir</td>
                                        <td style="text-align:left;width: 10%;text-align: center">:</td>
                                        <td style="text-align:left;width: 50%">{{ $pasien['tgllahir'] }}</td>
                                    </tr>
                                    <tr style="font-size: 10pt">
                                        <td style="text-align:left;width: 40%">Jenis Kelamin</td>
                                        <td style="text-align:left;width: 10%;text-align: center">:</td>
                                        <td style="text-align:left;width: 50%">{{ $pasien['jeniskelamin'] }}</td>
                                    </tr>
                                    <tr style="font-size: 10pt">
                                        <td style="text-align:left;width: 40%">No. Rekam Medis</td>
                                        <td style="text-align:left;width: 10%;text-align: center">:</td>
                                        <td style="text-align:left;width: 50%">{{ $pasien['nocm'] }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>
        <tbody class="table-body">
            <tr style="border-bottom: 1px solid black">
                @if($reg->objectkelompokpasienlastfk == 2)
                <th style="width: 25%;text-align: left">
                    Tanggal MRS :
                    {{ isset($reg->tglregistrasi) ? convertToRegularDate($reg->tglregistrasi) : '-' }}
                </th>
                <th style="width: 25%;text-align: left">
                    Tanggal KRS :
                    {{ isset($reg->tglclosing) ? convertToRegularDate($reg->tglclosing) : '-' }}
                </th>
                @else
                <th style="width: 25%;text-align: left">
                    Tanggal MRS :
                    {{ isset($d['tanggalMRS']) ? convertToRegularDate($d['tanggalMRS']) : '-' }}
                </th>
                <th style="width: 25%;text-align: left">
                    Tanggal KRS :
                    {{ isset($d['tanggalKRS']) ? convertToRegularDate($d['tanggalKRS']) : '-' }}
                </th>
                @endif
                <th style="width: 25%;text-align: left">
                    Ruangan : {{ isset($d['ruangan']) ? $d['ruangan'] : '-' }}
                </th>
                <th style="width: 25%;text-align: left">
                    Cara Bayar : {{ isset($d['Carabayar']) ? $d['Carabayar'] : '-' }}
                </th>
            </tr>
            <tr style="border-bottom: 1px solid black">>
                <td colspan="2" style="vertical-align: top">
                    Cara Masuk :
                    <input type="checkbox" {{ isset($d['IGD']) ? 'checked' : '' }} />
                    <span>IGD</span>&nbsp;

                    <input type="checkbox" {{ isset($d['VK']) ? 'checked' : '' }} />
                    <span>VK</span>&nbsp;

                    <input type="checkbox" {{ isset($d['POLIKLINIK']) ? 'checked' : '' }} />
                    <span>POLIKLINIK</span>&nbsp;

                    <input type="checkbox" {{ isset($d['OK']) ? 'checked' : '' }} />
                    <span>OK</span>
                </td>
                <td>
                    Dokter DPJP Utama :<br>
                    <b>{{ isset($d['DpjpUtama']['label']) ? $d['DpjpUtama']['label'] : '-' }}</b>
                </td>
                <td>
                    Dokter Lain Yang Merawat :<br>
                    <b>{{ isset($d['DokterlainMerawat']['label']) ? $d['DokterlainMerawat']['label'] : '-' }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Indikasi Rawat Inap :</b><br>
                    {{ isset($d['IndikasiRawatInap']) ? $d['IndikasiRawatInap'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Ringkasan Riwayat Kesehatan :</b><br>
                    {{ isset($d['Anamnesis']) ? $d['Anamnesis'] : '-' }}
                </td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: left">
                    Pemeriksaan Fisik :
                </th>
            </tr>
            <tr>
                <td colspan="4">
                    Kondisi Umum : {{ isset($d['kondisiUmum']) ? getKeadaanUmum($d['kondisiUmum']) : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    Tanda-tanda Vital
                </td>
            </tr>
            <tr>
                <td>
                    Suhu : {{ isset($d['celcius']) ? $d['celcius'] : '-' }} °C
                </td>
                <td>
                    Pernafasan : {{ isset($d['nafas']) ? $d['nafas'] : '-' }} x/menit
                </td>
                <td>
                    Berat Badan(BB) : {{ isset($d['BB']) ? $d['BB'] : '-' }} Kg
                </td>
                <td>
                    Nadi : {{ isset($d['nadi']) ? $d['nadi'] : '-' }} x/menit
                </td>
            </tr>
            <tr>
                <td>
                    Tekanan Darah : {{ isset($d['tekananDarah']) ? $d['tekananDarah'] : '-' }} mmHg
                </td>
                <td colspan="3">
                    SpO <sup>2</sup> : {{ isset($d['Spo']) ? $d['Spo'] : '-' }} %
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    GCS :
                    E {{ isset($d['gcse']) ? $d['gcse'] : '-' }}
                    V {{ isset($d['gcsv']) ? $d['gcsv'] : '-' }}
                    M {{ isset($d['gcsm']) ? $d['gcsm'] : '-' }}
                </td>
                <td colspan="2">
                    Lainnya Yang Bermakna :
                    {{ isset($d['Lainnyayangbermakna']) ? $d['Lainnyayangbermakna'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Pemeriksaan Diagnostik</b>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    Laboratorium :
                    {{ isset($d['PemeriksaanDiagnostik_Lab']) ? $d['PemeriksaanDiagnostik_Lab'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    Radiologi :
                    {{ isset($d['PemeriksaanDiagnostik_Rad']) ? $d['PemeriksaanDiagnostik_Rad'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    Penunjang :
                    {{ isset($d['PemeriksaanDiagnostik_Penunjang']) ? $d['PemeriksaanDiagnostik_Penunjang'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Hasil Konsultasi :</b><br>
                    {{ isset($d['Hasilkonsultasi']) ? $d['Hasilkonsultasi'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Diagnosa Utama :</b><br>
                    {{ isset($d['DiagnosaUtama']) ? $d['DiagnosaUtama'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Diagnosa Sekunder :</b><br>
                    {{ isset($d['DiagnosaSekunder']) ? $d['DiagnosaSekunder'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Komorbiditas :</b><br>
                    {{ isset($d['Komorbiditas']) ? $d['Komorbiditas'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Prosedur Terapi Yang Telah Dikerjakan :</b><br>
                    {{ isset($d['ProsedurTerapi']) ? $d['ProsedurTerapi'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Tindakan Yang Telah Dikerjakan :</b><br>
                    {{ isset($d['TindakanDikerjakan']) ? $d['TindakanDikerjakan'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Alergi (Reaksi Obat) :</b><br>
                    {{ isset($d['AlergiReaksiObat']) ? $d['AlergiReaksiObat'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Diet Yang Telah Diberikan & Diet Yang Harus Dilakukan di Rumah Diet :</b><br>
                    {{ isset($d['Dietdiberikan']) ? $d['Dietdiberikan'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Instruksi Tindak Lanjut :</b><br>
                    {{ isset($d['InstruksiTindakLanjut']) ? $d['InstruksiTindakLanjut'] : '-' }}
                </td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: left">
                    Kondisi dan Tanda-tanda Vital Pasien Waktu Keluar RS :
                </th>
            </tr>
            <tr>
                <td colspan="4">
                    Kondisi Umum :
                    {{ isset($d['kondisiUmumKeluar']) ? getKeadaanUmum($d['kondisiUmumKeluar']) : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    Tanda-tanda Vital
                </td>
            </tr>
            <tr>
                <td style="width: 25%">
                    Suhu : {{ isset($d['celciusKeluar']) ? $d['celciusKeluar'] : '-' }} °C
                </td>
                <td style="width: 25%">
                    Pernafasan : {{ isset($d['nafasKeluar']) ? $d['nafasKeluar'] : '-' }} x/menit
                </td>
                <td style="width: 25%">
                    Nadi : {{ isset($d['nadiKeluar']) ? $d['nadiKeluar'] : '-' }} x/menit
                </td>
                <td style="width: 25%">
                    Tekanan Darah : {{ isset($d['tekananDarahKeluar']) ? $d['tekananDarahKeluar'] : '-' }} mmHg
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    Status Pasien Keluar RS :
                    <span
                        style="font-family: DejaVu Sans">{{ isset($d['riwayatkeluar']) ? $d['riwayatkeluar'] : '-' }}</span>
                    @if (isset($d['riwayatkeluar']))
                        @switch($d['riwayatkeluar'])
                            @case('Dirujuk Ke :')
                                {{ isset($d['TBDirujukke']) ? $d['TBDirujukke'] : '-' }}
                            @break

                            @case('Lain-lain')
                                {{ isset($d['TBLainlain']) ? $d['TBLainlain'] : '-' }}
                            @break

                            @default
                        @endswitch
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Penyebab Kematian :</b><br>
                    {{ isset($d['PenyebabKematian']) ? $d['PenyebabKematian'] : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <b>Pengobatan Dilanjutkan</b>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="checkbox" {{ isset($d['PoliklinikRSUDBaliMandara']) ? 'checked' : '' }} />
                    <span>Poliklinik RSUD Bali Mandara</span>
                </td>
                <td>
                    Poliklinik Tujuan :
                    {{ isset($d['PoliTujuan']) ? $d['PoliTujuan'] : '-' }}
                </td>
                <td colspan="2">
                    Tanggal Kontrol :
                    {{ isset($d['tanggalKontrol']) ? convertToRegularDate($d['tanggalKontrol']) : '-' }}
                </td>
            </tr>
            <tr>
                <td>
                    <input type="checkbox" {{ isset($d['RSLain']) ? 'checked' : '' }} />
                    <span>RS Lain</span>
                </td>
                <td>
                    Nama RS :
                    {{ isset($d['NamaRS']) ? $d['NamaRS'] : '-' }}
                </td>
                <td>
                    Pasien Bisa Berpergian :<br>
                    <input type="checkbox" {{ isset($d['TanpaPendamping']) ? 'checked' : '' }} />
                    <span>Tanpa Pendamping</span><br>

                    <input type="checkbox" {{ isset($d['DenganPendampingMedis']) ? 'checked' : '' }} />
                    <span>Dengan Pendamping Medis</span>
                </td>
                <td>
                    Pasien Memerlukan :<br>
                    <input type="checkbox" {{ isset($d['TempatDudukBiasa']) ? 'checked' : '' }} />
                    <span>Tempat Duduk Biasa</span><br>

                    <input type="checkbox" {{ isset($d['KursiRoda']) ? 'checked' : '' }} />
                    <span>Kursi Roda</span><br>

                    <input type="checkbox" {{ isset($d['TidakMemerlukan']) ? 'checked' : '' }} />
                    <span>Tidak Memerlukan</span>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="checkbox" {{ isset($d['PengobatanLainlain']) ? 'checked' : '' }} />
                    <span>Lain-lain</span>
                </td>
                <td colspan="3">
                    {{ isset($d['TBPengobatanLainlain']) ? $d['TBPengobatanLainlain'] : '' }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table class="table-obat">
                        <tr>
                            <th colspan="5">Obat Selama Rawat Inap</th>
                            <th colspan="5">Obat Setelah Rawat Inap</th>
                        </tr>
                        <tr>
                            <th style="width: 10%">Nama Obat</th>
                            <th style="width: 10%">Jml</th>
                            <th style="width: 10%">Dosis</th>
                            <th style="width: 10%">Frekuensi</th>
                            <th style="width: 10%">Cara Pemberian</th>
                            <th style="width: 10%">Nama Obat</th>
                            <th style="width: 10%">Jml</th>
                            <th style="width: 10%">Dosis</th>
                            <th style="width: 10%">Frekuensi</th>
                            <th style="width: 10%">Cara Pemberian</th>
                        </tr>
                        @foreach ($d['details'] as $dt)
                            <tr>
                                <td>
                                    {{ isset($dt['NamaobatSelama']) ? $dt['NamaobatSelama'] : '-' }}
                                </td>
                                <td>
                                    {{ isset($dt['JmlSelama']) ? $dt['JmlSelama'] : '-' }}
                                </td>
                                <td>
                                    {{ isset($dt['DosisSelama']) ? $dt['DosisSelama'] : '-' }}
                                </td>
                                <td>
                                    {{ isset($dt['FrekuensiSelama']) ? $dt['FrekuensiSelama'] : '-' }}
                                </td>
                                <td>
                                    {{ isset($dt['CarapemberianSelama']) ? $dt['CarapemberianSelama'] : '-' }}
                                </td>
                                <td>
                                    {{ isset($dt['NamaobatSetelah']) ? $dt['NamaobatSetelah'] : '-' }}
                                </td>
                                <td>
                                    {{ isset($dt['JmlSetelah']) ? $dt['JmlSetelah'] : '-' }}
                                </td>
                                <td>
                                    {{ isset($dt['DosisSetelah']) ? $dt['DosisSetelah'] : '-' }}
                                </td>
                                <td>
                                    {{ isset($dt['FrekuensiSetelah']) ? $dt['FrekuensiSetelah'] : '-' }}
                                </td>
                                <td>
                                    {{ isset($dt['CarapemberianSetelah']) ? $dt['CarapemberianSetelah'] : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">
                    <hr>
                    <table class="table-footer">
                        <tr>
                            <td width="50%">
                                <div>Pasien / Keluarga Pasien</div>
                                <div>&nbsp;</div>
                                @if (isset($d['TTDpasien']) && $d['TTDpasien'] != $imgDefault)
                                    <img src="{{ $d['TTDpasien'] }}" style="width: 100px;height:100px">
                                @else
                                    <div src="" style="width: 100px;height:100px"></div>
                                @endif
                                <br>
                                <span>{{ isset($d['Pasienkeluarga']) ? $d['Pasienkeluarga'] : '-' }}</span>
                            </td>
                            <td width="50%">
                                <div>
                                    Garut,
                                    {{ Carbon::parse($d['tanggalPengisian'])->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                                </div>
                                <div>Dokter Penanggung Jawab Pelayanan</div>
                                <!-- Qrcode ceritanya -->
                                @if (isset($d['DokterPenanggungJawab']['label']))
                                    <img src="data:image/png;base64, {!! $qrcode !!}"
                                        style="width: 100px;height:100px">
                                @else
                                    <img src="data:image/png;base64, {!! $qrcode !!}"
                                        style="width: 100px;height:100px">
                                @endif
                                <br>
                                <span>
                                    @if (isset($d['DokterPenanggungJawab']['label']))
                                        {{ $d['DokterPenanggungJawab']['label'] }}
                                    @else
                                        &nbsp;
                                    @endif
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>

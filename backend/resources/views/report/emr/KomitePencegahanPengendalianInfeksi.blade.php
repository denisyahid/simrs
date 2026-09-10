<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    @yield('page-style')
    <style>
        .checkbox-wrapper {
            white-space: nowrap
        }

        .checkbox {
            vertical-align: top;
            display: inline-block
        }

        .checkbox-label {
            white-space: normal display:inline-block
        }
        .mid {
            text-align: center !important;
        }
        table{
            font-family: Dejavu Sans;
        }
    </style>
</head>

<body>
    @php
        use Carbon\Carbon;

        $imgDefault =
        'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII=';
    @endphp
    <table width="100%" cellspacing="0" cellpadding="0" border="1">
        <thead>
            <tr>
                <td width="100%" style="text-align:right" colspan=2>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr class="bg-blue">
                            <td>
                                <td width="50%" style="text-align:left; font-size: 14px;background-color:rgb(77, 160, 224)">UPT. RSUD BALI MANDARA</td>
                                <td width="50%" style="font-size: 14px; text-align: right;background-color:rgb(77, 160, 224)">RM.5/SIR/00</td>
                            </td>
                        </tr>
                    </table>
                </td>
                <!-- <td width="60%"></td> -->
            </tr>
        </thead>
        <thead>
        <tr>
            <td colspan="2">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td width="10%" style="padding: 5px; border-right: 1px solid black;">
                            <img src="{{ 'img/logo-rs.png' }}" style="width: 90px;">
                        </td>
                        <td style="text-align: center; border-right: 1px solid black;">
                            <b>
                                <span style="font-size: 14px">KOMITE PENCEGAHAN DAN PENGENDALIAN INFEKSI UPT RSUD BALI MANDARA PROVINSI BALI <br>FORMULIR PENGUMPULAN DATA SURVEILANS INFEKSI DAERAH OPERASI
                            </b>
                        </td>
                        <td width="35%" style="padding: 10px">
                            <div class="box" style="text-align: left">
                                <table style="padding: 3px; font-size: 10px;">
                                    <tr>
                                        <td class="bold  text-top">Nama</td>
                                        <td class="bold  text-top">:</td>
                                        <td class="bold  text-top"><b>{{ $pasien['namapasien'] }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bold  text-top">Tgl Lahir</td>
                                        <td class="bold  text-top">:</td>
                                        <td class="bold  text-top">
                                            <b>{{ $pasien['tgllahir'] }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bold  text-top" style="width: 100px">No. RM</td>
                                        <td class="bold  text-top">:</td>
                                        <td class="bold text-top"><b>{{ $pasien['nocm'] }}</b></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </thead>
        <tr>
            <td colspan="2">
                <table width="100%" cellspacing="0" cellpadding="4" border="1">
                    <tr>
                        <td colspan="2">
                            <span><b>Tanggal MRS</b>(Isi tgl/bln/thn)</span><br>
                            {{ isset($data['tglMRS']) ? Carbon::parse($data['tglMRS'])->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td>
                            <span><b>Lama Opr</b>(Isi tgl/bln/thn)</span><br>
                            <span>Isi jam/Mnt</span><br>
                            {{ isset($data['lamaOperasi']) ? ($data['lamaOperasi']) : '-' }}
                        </td>
                        <td>
                            <span><b>Operasi Krn Trauma</b></span><br>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['operasiKarenaTrauma']) && $data['operasiKarenaTrauma'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Ya</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['operasiKarenaTrauma']) && $data['operasiKarenaTrauma'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle" color="#000000">Tidak</span>
                        </td>
                        <td rowspan="2">
                            <span><b>Prosedur Operasi</b></span><br>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['sectioCaesaria_PO']) && $data['sectioCaesaria_PO'] == 'Sectio Caesaria' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Sectio Caesaria</span><br>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['appendictomy_PO']) && $data['appendictomy_PO'] == 'Appendictomy' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Appendictomy</span><br>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['abdominalHysterectomy_PO']) && $data['abdominalHysterectomy_PO'] == 'Abdominal hysterectomy' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Abdominal hysterectomy</span><br>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['orif_PO']) && $data['orif_PO'] == 'ORIF' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">ORIF</span><br>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['explorasiCBD_PO']) && $data['explorasiCBD_PO'] == 'Explorasi CBD' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Explorasi CBD</span><br>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['lainLain_PO']) && $data['lainLain_PO'] == 'Lain-lain' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000"></span>
                            {{ isset($data['lainLainDetail_PO']) ? ($data['lainLainDetail_PO']) : '..................' }}
                        </td>
                        <td>
                            <span><b>Multiprosedur dgn insisi yg sama</b></span><br>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['multiProsedur']) && $data['multiProsedur'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Ya</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['multiProsedur']) && $data['multiProsedur'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle" color="#000000">Tidak</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span><b>Tanggal Operasi</b></span><br>
                            {{ isset($data['tglOperasi']) ? Carbon::parse($data['tglOperasi'])->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td>
                            <span><b>Jenis Operasi</b></span><br>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['jenisOperasi']) && $data['jenisOperasi'] == 'Elektif' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Elektif</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['jenisOperasi']) && $data['jenisOperasi'] == 'Darurat' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle" color="#000000">Darurat</span>
                        </td>
                        <td>
                            <span><b>Jenis Operasi</b></span><br>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['ruangOperasi']) && $data['ruangOperasi'] == '1' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">1</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['ruangOperasi']) && $data['ruangOperasi'] == '2' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">2</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['ruangOperasi']) && $data['ruangOperasi'] == '3' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">3</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['ruangOperasi']) && $data['ruangOperasi'] == '4' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">4</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['ruangOperasi']) && $data['ruangOperasi'] == '5' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">5</span>
                        </td>
                        <td>
                            <span><b>ASA Score</b></span><br>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['asaScore']) && $data['asaScore'] == '1' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">1</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['asaScore']) && $data['asaScore'] == '2' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">2</span>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['asaScore']) && $data['asaScore'] == '3' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">3</span>
                            <input type="checkbox" style="vertical-align: middle;"
                                {{ isset($data['asaScore']) && $data['asaScore'] == '4' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">4</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['asaScore']) && $data['asaScore'] == '5' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">5</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span><b>Berat Badan</b></span><br>
                            {{ isset($data['beratBadan']) ? ($data['beratBadan']) : '-' }}
                        </td>
                        <td colspan="2">
                            <span><b>Kualifikasi Dokter Bedah</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['spesialis_KDB']) && $data['spesialis_KDB'] == 'Spesialis' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Spesialis</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['associateSpesialis_KDB']) && $data['associateSpesialis_KDB'] == 'Associate Specialist' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Associate Specialist</span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['konsultan_KDB']) && $data['konsultan_KDB'] == 'Konsultan' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 25px" color="#000000">Konsultan</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['lainLain_KDB']) && $data['lainLain_KDB'] == 'Lain-lain' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000"></span>
                            {{ isset($data['lainLainDetail_KDB']) ? ($data['lainLainDetail_KDB']) : '................' }}
                        </td>
                        <td>
                            <span><b>Diagnosa :</b></span><br>
                            {{ isset($data['diagnosa']) ? ($data['diagnosa']) : '-' }}
                        </td>
                        <td>
                            <span><b>Klasifikasi Luka</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['klasifikasiLuka']) && $data['klasifikasiLuka'] == 'Bersih' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 105px" color="#000000">Bersih</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['klasifikasiLuka']) && $data['klasifikasiLuka'] == 'Terkontaminasi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Terkontaminasi</span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['klasifikasiLuka']) && $data['klasifikasiLuka'] == 'Bersih Terkontaminasi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 25px" color="#000000">Bersih Terkontaminasi</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['klasifikasiLuka']) && $data['klasifikasiLuka'] == 'Kotor' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Kotor</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="4%" rowspan="4">
                            <table border="0">
                                <tr>
                                    <td  style="writing-mode: vertical-rl; transform: rotate(270deg); text-align: center;">
                                        <b>PRE OP</b>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <span><b>Suhu Pasien</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['suhuPasien']) && $data['suhuPasien'] == '≥ 38°C' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;font-family:Dejavu Sans" color="#000000">≥ 38°C</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['suhuPasien']) && $data['suhuPasien'] == '< 38°C' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">&lt; 38°C</span>
                        </td>
                        <td>
                            <span><b>Suhu Pasien</b></span><br>
                            {{ isset($data['albumin']) ? ($data['albumin']) : '.............' }}g/dl
                        </td>
                        <td>
                            <span><b>Gula Darah</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['gulaDarah']) && $data['gulaDarah'] == '> 200' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;font-family:Dejavu Sans" color="#000000">> 200</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['gulaDarah']) && $data['gulaDarah'] == '≤ 200' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;font-family:Dejavu Sans" color="#000000">≤ 200</span>
                        </td>
                        <td>
                            <span><b>Steroid Jangka Panjang</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['steroidJangkaPanjang']) && $data['steroidJangkaPanjang'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Ya</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['steroidJangkaPanjang']) && $data['steroidJangkaPanjang'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Tidak</span>
                        </td>
                        <td rowspan="2">
                            <span><b>Penyakit Infeksi Lain</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['infeksiKulit_PIL']) && $data['infeksiKulit_PIL'] == 'Infeksi Kulit' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Infeksi Kulit</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['infeksiMulutGigi_PIL']) && $data['infeksiMulutGigi_PIL'] == 'Infeksi Mulut/Gigi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Infeksi Mulut/Gigi</span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['infeksiMata_PIL']) && $data['infeksiMata_PIL'] == 'Infeksi Mata' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Infeksi Mata</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['infeksiTHT_PIL']) && $data['infeksiTHT_PIL'] == 'Infeksi THT' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Infeksi THT</span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['infeksiParu_PIL']) && $data['infeksiParu_PIL'] == 'Infeksi Paru' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Infeksi Paru</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['infeksiGITract_PIL']) && $data['infeksiGITract_PIL'] == 'Infeksi GI Tract' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Infeksi GI Tract</span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['lainLain_PIL']) && $data['lainLain_PIL'] == 'Lain-lain' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000"></span>
                            {{ isset($data['lainLainDetail_PIL']) ? ($data['lainLainDetail_PIL']) : '.............' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span><b>Merokok</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['merokok']) && $data['merokok'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Ya</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['merokok']) && $data['merokok'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Tidak</span> 
                        </td>
                        <td colspan="2">
                            <span><b>Penyakit Saat Ini</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['dm_PSI']) && $data['dm_PSI'] == 'DM' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 50px;" color="#000000">DM</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['ggk_PSI']) && $data['ggk_PSI'] == 'GGK' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">GGK</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['sepsis_PSI']) && $data['sepsis_PSI'] == 'Sepsis' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Sepsis</span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['hipertensi_PSI']) && $data['hipertensi_PSI'] == 'Hipertensi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 20px;" color="#000000">Hipertensi</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['na_PSI']) && $data['na_PSI'] == 'NA' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 38px;" color="#000000">NA</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['lainLain_PSI']) && $data['lainLain_PSI'] == 'Lain-lain' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000"></span>
                            {{ isset($data['lainLainDetail_PSI']) ? ($data['lainLainDetail_PSI']) : '.............' }}
                        </td>
                        <td>
                            <span><b>Radioterapi Sebelumnya</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['merokok']) && $data['merokok'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Ya</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['merokok']) && $data['merokok'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Tidak</span> 
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2">
                            <span><b>Screening MRSA</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['screeningMRSA']) && $data['screeningMRSA'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Ya</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['screeningMRSA']) && $data['screeningMRSA'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Tidak</span><br>
                            <span><b>Hasil: (+)/(-)</b></span>
                        </td>
                        <td rowspan="2">
                            <span><b>Pencukuran</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['clipper_Pencukuran']) && $data['clipper_Pencukuran'] == 'Clipper' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Clipper</span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['silet_Pencukuran']) && $data['silet_Pencukuran'] == 'Silet' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Silet</span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['na_Pencukuran']) && $data['na_Pencukuran'] == 'NA' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">NA</span><br>
                        </td>
                        <td>
                            <span><b>Waktu Pencukuran</b></span><br>
                            <span>Pukul:</span>{{ isset($data['waktuPencukuran']) ? Carbon::parse($data['waktuPencukuran'])->translatedFormat('H i') : '-' }}
                        </td>
                        <td rowspan="2">
                            <span><b>Mandi Sebelum Operasi</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['chlorhexidine_MSO']) && $data['chlorhexidine_MSO'] == 'Chlorhexidine bodywash' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Chlorhexidine bodywash</span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['sabunLain_MSO']) && $data['sabunLain_MSO'] == 'Sabun Lain' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Sabun Lain</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['na_MSO']) && $data['na_MSO'] == 'NA' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">NA</span>
                        </td>
                        <td rowspan="2">
                            <span><b>Profilaksis</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['profilaksis']) && $data['profilaksis'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Ya</span>
                            <span>Nama Obat:</span>{{ isset($data['namaObat_Profilaksis']) ? ($data['namaObat_Profilaksis']) : '.............' }}<br>
                            <span>Dosis:</span>{{ isset($data['dosis_Profilaksis']) ? ($data['dosis_Profilaksis']) : '.............' }}
                            <span>Diberikan Jam:</span>{{ isset($data['diberikanJam']) ? Carbon::parse($data['diberikanJam'])->translatedFormat('H i') : '-' }}<br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['profilaksis']) && $data['profilaksis'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Tidak</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span><b>Mechanical Bowel</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['mechanicalBowel']) && $data['mechanicalBowel'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Ya</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['mechanicalBowel']) && $data['mechanicalBowel'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Tidak</span> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" cellspacing="0" cellpadding="4" border="0">
                    <tr>
                        <td>
                            <span><b>ASA Scoring :</b></span><br>
                            <span>1. Pasien tidak ada kelainan sistemik selain yang akan dioperasi.</span><br>
                            <span>2. Pasien ada gangguan sistemik ringan.</span><br>
                            <span>3. Pasien ada gangguan sistemik sedang/berat - ada gangguan tindakan dapat meninggal dalam 24 jam.
                                aktivitas.</span>
                        </td>
                        <td>
                            <span></span><br>
                            <span>4. Pasien ada gangguan sistemik berat dan mengancam jiwa.</span><br>
                            <span>5. Pasien ada gangguan berat, dilakukan / tidak dilakukan tindakan dapat meninggal dalam 24 jam.</span><br>
                        </td>
                        <td class="mid">
                            <span><br>Perawat Pre OP</span><br>
                            @if (isset($data['TTDPerawatPreOP']) && $data['TTDPerawatPreOP'] != $imgDefault)
                                <img style="width: 100px;height: 100px;"
                                    src="{{ $data['TTDPerawatPreOP'] }}">
                            @else
                                <br>
                                @if(isset($data['perawatPreOP']['label']))
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $data['perawatPreOP']['label'] }}"><br/>
                                @endif
                            @endif
                            <br>
                            <span>{{ isset($data['perawatPreOP']['label']) ? $data['perawatPreOP']['label'] : '-' }}</span>
                        </td>
                        <td class="mid">
                            <span><br>Perawat Intra OP</span><br>
                            @if (isset($data['TTDPerawatIntraOP']) && $data['TTDPerawatIntraOP'] != $imgDefault)
                                <img style="width: 100px;height: 100px;"
                                    src="{{ $data['TTDPerawatIntraOP'] }}">
                            @else
                                <br>
                                @if(isset($data['perawatIntraOP']['label']))
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $data['perawatIntraOP']['label'] }}">
                                @endif
                            @endif
                            <br>
                            <span>{{ isset($data['perawatIntraOP']['label']) ? $data['perawatIntraOP']['label'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" cellspacing="0" cellpadding="4" border="1">
                    <tr>
                        <td rowspan="3">
                            <table border="0">
                                <tr>
                                    <td  style="writing-mode: vertical-rl; transform: rotate(270deg); text-align: center;">
                                        <b>DURANTE OP</b>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <span><b>Sirkulasi Udara OK</b></span><br>
                            {{ isset($data['sirkulasiUdaraOK']) ? $data['sirkulasiUdaraOK'] : '..............' }}x/jam
                        </td>
                        <td>
                            <span><b>Kondisi Pintu OK</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['kondisiPintuOK']) && $data['kondisiPintuOK'] == 'Baik' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Baik</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['kondisiPintuOK']) && $data['kondisiPintuOK'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Tidak</span> 
                        </td>
                        <td>
                            <span><b>Kelembaban Ruang OK</b></span><br>
                            {{ isset($data['kelembapanRuangOK']) ? $data['kelembapanRuangOK'] : '..............' }}
                        </td>
                        <td rowspan="2">
                            <span><b>Antibiotik Tambahan Saat Op</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['antibiotikTambahan']) && $data['antibiotikTambahan'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Ya</span>
                            <span>Nama Obat:</span>{{ isset($data['namaObat_ATSOP']) ? ($data['namaObat_ATSOP']) : '.............' }}<br>
                            <span>Dosis:</span>{{ isset($data['dosis_ATSOP']) ? ($data['dosis_ATSOP']) : '.............' }}
                            <span>Diberikan Jam:</span>{{ isset($data['diberikanJam_ATSOP']) ? Carbon::parse($data['diberikanJam_ATSOP'])->translatedFormat('H i') : '-' }}<br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['antibiotikTambahan']) && $data['antibiotikTambahan'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Tidak</span>
                        </td>
                        <td rowspan="2">
                            <span><b>Disinfeksi Kulit</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['chlorhexidine']) && $data['chlorhexidine'] == 'Chlorhexidine' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Chlorhexidine</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['alkohol70']) && $data['alkohol70'] == 'Alkohol 70%' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Alkohol 70%</span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['povidoneIodine']) && $data['povidoneIodine'] == 'Povidone iodine' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right:20px" color="#000000">Povidone iodine</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['lainLain_DK']) && $data['lainLain_DK'] == 'Lain-lain' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000"></span>
                            {{ isset($data['lainLainDetail_DK']) ? $data['lainLainDetail_DK'] : '..............' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span><b>Tekanan Udara</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['tekananUdara']) && $data['tekananUdara'] == '(+)' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">(+)</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['tekananUdara']) && $data['tekananUdara'] == '(-)' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">(-)</span> 
                        </td>
                        <td>
                            <span><b>Jamur AC</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['jamurAC']) && $data['jamurAC'] == '(+)' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">(+)</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['jamurAC']) && $data['jamurAC'] == '(-)' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">(-)</span> 
                        </td>
                        <td>
                            <span><b>Drain</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['drain']) && $data['drain'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 5px;" color="#000000">Ya,</span>
                            <span>Jenis</span>{{ isset($data['drainJenis']) ? ($data['drainJenis']) : '.............' }}
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['drain']) && $data['drain'] == 'NA' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">NA</span> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span><b>Suhu Ruang</b></span><br>
                            {{ isset($data['suhuRuang']) ? $data['suhuRuang'] : '..............' }}<sup>o</sup>C
                        </td>
                        <td colspan="2">
                            <span><b>Implant</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['implant']) && $data['implant'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 5px;" color="#000000">Ya,</span>
                            <span>Jenis</span><span style="margin-right: 60px">{{ isset($data['implantJenis']) ? ($data['implantJenis']) : '.............' }}</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['implant']) && $data['implant'] == 'NA' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">NA</span><br>
                            <span><b>Sterilisasi CSSD :</b></span> 
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['sterilisasiCSSD']) && $data['sterilisasiCSSD'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Ya</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['sterilisasiCSSD']) && $data['sterilisasiCSSD'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Tidak</span><br>
                        </td>
                        <td>
                            <span><b>Jumlah Staf</b></span><br>
                            {{ isset($data['jumlahStaf']) ? $data['jumlahStaf'] : '..............' }}Orang
                        </td>
                        <td>
                            <span><b>Indikator Instrumen/Alat Steril</b></span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['internal_IIAS']) && $data['internal_IIAS'] == 'Internal' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px;" color="#000000">Internal</span>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['external_IIAS']) && $data['external_IIAS'] == 'External' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;" color="#000000">External</span><br>
                            <input type="checkbox" style="vertical-align: middle"
                                {{ isset($data['tidakAda_IIAS']) && $data['tidakAda_IIAS'] == 'Tidak ada' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;vertical-align: middle;margin-right:20px" color="#000000">Tidak ada</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" cellspacing="0" cellpadding="4" border="1">
                    <tr>
                        <td>
                            <table width="100%" cellspacing="0" cellpadding="4" border="1">
                                <tr>
                                    <td rowspan="21">
                                        <table width="100%" cellspacing="0" cellpadding="4" border="0">
                                            <td style="writing-mode: vertical-rl; transform: rotate(270deg); text-align: center;width:5%;border: none;">
                                                <b>POST OP</b>
                                            </td>
                                        </table>
                                    </td>
                                    <td rowspan="2">
                                        <span><b>Post Op hari ke-</b></span>
                                    </td>
                                    <td colspan="{{ count($data['details']) }}">
                                        <span style="font-family: Dejavu Sans;">
                                            <b>Beri tanda "✔" sesuai tindakan dan gejala. Beri tanda "0" jika tidak ditemukan gejala</b>
                                        </span>
                                    </td>
                                    <td rowspan="2">
                                        <span><b>Keterangan</b> (Isi info penting / Beri tanda ) ✔</span>
                                    </td>
                                </tr>
                                <tr>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        {{ $loop->iteration }}
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>
                                        <span>Rawat Luka</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['rawatLuka']) ? ($item['rawatLuka'] == '✔' ? '✔' : ($item['rawatLuka'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketRawatLuka']) ? $data['ketRawatLuka'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Dressing : Transparan</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['dressingTransparan']) ? ($item['dressingTransparan'] == '✔' ? '✔' : ($item['dressingTransparan'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketDressing']) ? $data['ketDressing'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Hypavix</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['hypavix']) ? ($item['hypavix'] == '✔' ? '✔' : ($item['hypavix'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketHypavix']) ? $data['ketHypavix'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Buang cairan/membuka drain</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['buangCairan']) ? ($item['buangCairan'] == '✔' ? '✔' : ($item['buangCairan'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        <span style="vertical-align: middle;margin-right: 30px">Drain :</span>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['ketBuangCairan']) && $data['ketBuangCairan'] == 'Tertutup' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 5px;" color="#000000">Tertutup</span>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['ketBuangCairan']) && $data['ketBuangCairan'] == 'Terbuka' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Terbuka</span> 
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Aff drain</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['affDrain']) ? ($item['affDrain'] == '✔' ? '✔' : ($item['affDrain'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        <span style="vertical-align: middle;margin-right: 20px">Aff OLeh :</span>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['affPerawat']) && $data['affPerawat'] == 'Perawat' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 5px;" color="#000000">Perawat</span>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['affDokter']) && $data['affDokter'] == 'Dokter' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;" color="#000000">Dokter</span> 
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Angkat Jahitan</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['angkatJahitan']) ? ($item['angkatJahitan'] == '✔' ? '✔' : ($item['hypavix'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketAngkatJahitan']) ? $data['ketAngkatJahitan'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Antibiotik</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['antibiotik']) ? ($item['antibiotik'] == '✔' ? '✔' : ($item['hypavix'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketAntibiotik']) ? $data['ketAntibiotik'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Keluar RS</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['keluarRS']) ? ($item['keluarRS'] == '✔' ? '✔' : ($item['hypavix'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketKeluarRS']) ? $data['ketKeluarRS'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Kontrol poli</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['kontrolPoli']) ? ($item['kontrolPoli'] == '✔' ? '✔' : ($item['hypavix'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketKontrolPoli']) ? $data['ketKontrolPoli'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">
                                        <span>Indetifikasi IDO</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['indetifikasiIDO']) ? ($item['indetifikasiIDO'] == '✔' ? '✔' : ($item['indetifikasiIDO'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketIndetifikasiIDO']) ? $data['ketIndetifikasiIDO'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Nyeri lokal dan sakit</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['nyeriLokal']) ? ($item['nyeriLokal'] == '✔' ? '✔' : ($item['nyeriLokal'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketNyeriLokal']) ? $data['ketNyeriLokal'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Demam (≥ 38°C)</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['demam']) ? ($item['demam'] == '✔' ? '✔' : ($item['demam'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketDemam']) ? $data['ketDemam'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Kemerahan</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['kemerahan']) ? ($item['kemerahan'] == '✔' ? '✔' : ($item['kemerahan'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketKemerahan']) ? $data['ketKemerahan'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Bengkak terlokalisir</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['bengkak']) ? ($item['bengkak'] == '✔' ? '✔' : ($item['bengkak'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketBengkak']) ? $data['ketBengkak'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Kuman pada kultur pus</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['kumanPadaKultur']) ? ($item['kumanPadaKultur'] == '✔' ? '✔' : ($item['kumanPadaKultur'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketKuman']) ? $data['ketKuman'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Ada abses saat re-operasi</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['adaAbses']) ? ($item['adaAbses'] == '✔' ? '✔' : ($item['adaAbses'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketAdaAbses']) ? $data['ketAdaAbses'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Diagnosa Dokter : IDO</span>
                                    </td>
                                    @foreach ($data['details'] as $item)
                                    <td class="mid">
                                        <span style="font-family: Dejavu Sans;">{{ isset($item['diagnosaDokter']) ? ($item['diagnosaDokter'] == '✔' ? '✔' : ($item['diagnosaDokter'] == '0' ? '0' : '')) : '' }}</span>
                                    </td>
                                    @endforeach
                                    <td>
                                        {{ isset($data['ketDiagnosaDokter']) ? $data['ketDiagnosaDokter'] : ' ' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="{{ count($data['details']) + 2 }}" style="text-align: center">
                                        <span style="font-family: Dejavu Sans;"><b>BILA TERJADI INFEKSI,</b> Beri tanda √ pada kotak yang sesuai</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span><b>Jenis Lokasi Infeksi</b></span><br>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['superfisial']) && $data['superfisial'] == 'Superfisial' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 105px" color="#000000">Superfisial</span>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['organRongga']) && $data['organRongga'] == 'Organ/Rongga' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Organ/Rongga</span><br>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['dalamFasciaOtot']) && $data['dalamFasciaOtot'] == 'Dalam (Fascia/Otot)' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 25px" color="#000000">Dalam (Fascia/Otot)</span>
                                    </td>
                                    <td colspan="{{ count($data['details']) + 1 }}">
                                        <span><b>Lokasi Spesifik Untuk Infeksi Organ / Rongga</b></span><br>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['salGastro']) && $data['salGastro'] == 'Sal Gastrointestinal' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 105px" color="#000000">Sal Gastrointestinal</span>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['intraAbdominal']) && $data['intraAbdominal'] == 'Intra-Abdomina' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 30px" color="#000000">Intra-Abdomina</span>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['sendiBursa']) && $data['sendiBursa'] == 'Sendi/Bursa' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 52px" color="#000000">Sendi/Bursa</span>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['vaginalCuff']) && $data['vaginalCuff'] == 'Vaginal Cuff' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 25px" color="#000000">Vaginal Cuff</span><br>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['salGenitalPerempuan']) && $data['salGenitalPerempuan'] == 'Sal.genital perempuan' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 85px" color="#000000">Sal.genital perempuan</span>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['endokardium']) && $data['endokardium'] == 'Endokardium' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 45px" color="#000000">Endokardium</span>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['periMiokradium']) && $data['periMiokradium'] == 'Peri/miokradium' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 25px" color="#000000">Peri/miokradium</span>
                                        <input type="checkbox" style="vertical-align: middle"
                                            {{ isset($data['lainLain_LSUIOR']) && $data['lainLain_LSUIOR'] == 'Lain-lain' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;vertical-align: middle;margin-right: 25px" color="#000000"></span>
                                        {{ isset($data['lainLainDetail_LSUIOR']) ? $data['lainLainDetail_LSUIOR'] : '..............' }}
                                    </td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom: none;">
                <table width="100%" cellspacing="0" cellpadding="4" border="0">
                    <tr>
                        <td>
                            <span><b>Definisi Tingkat Kontaminasi Daerah Operasi</b></span><br>
                            <span>1. Bersih : Luka operasi tidak infeksi, tidak ada inflamasi dan tidak membuka traktus</span><br>
                            <span style="margin-left: 20px">respiratorius/orofaring, traktus gastrointestinal/biliar, traktus genitourinarius</span><br>
                            <span style="margin-left: 20px">dimana kasus luka operasi ini ditutup secara primer serta sistem drainase tertutup.</span><br>
                            <span>2. Bersih Terkontaminasi : Luka operasi yang memasuki / membuka traktus respiratorius,</span><br>
                            <span style="margin-left: 20px">pencernaan/biliar, appendiks, vagina dan orofaring.</span><br>
                        </td>
                        <td>
                            <span></span><br>
                            <span>3. Terkontaminasi : Luka operasi yang membuka semua sistem traktus kecuali</span><br>
                            <span style="margin-left: 20px">ovarium dan nyata terjadi pencemaran (perforasi) baru dan luka trauma dan</span><br>
                            <span style="margin-left: 20px">insisi yang akut < 6 jam - inflamasi non purulen.</span><br>
                            <span>4. Luka kotor : Luka traumatik > 6 jam dengan hilangnya jaringan dan tampak</span><br>
                            <span style="margin-left: 20px">infeksi atau perforasi viseral.</span><br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-top: none;border-bottom: none;">
                <table width="100%" cellspacing="0" cellpadding="4" border="0">
                    <tr>
                        <td>
                            <span><b>Catatan : 1. Kolom PRE OP diisi oleh perawat ruangan</b></span><br>
                            <span style="margin-left: 90px;font-weight: bold">2. Kolom DURANTE diisi oleh perawat OK</span><br>
                            <span style="margin-left: 90px;font-weight: bold">3. Kolom POST OPS diisi oleh perawat ruangan. Jika px kontrol ke poliklinik, diisi oleh perawat poliklinik</span><br>
                            <span style="margin-left: 90px;font-weight: bold">4. Bila Pasien pulang, formulir ini dikumpulkan pada IPCLN (Infection Prevention and Control Link Nurse) di masing-masing unit</span><br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-top: none;">
                <table width="100%" cellspacing="0" cellpadding="4" border="0">
                    <tr>
                        <td class="mid">
                            <span><br>Dokter DPJP/PPI</span><br>
                            @if (isset($data['TTDDokterDPJP']) && $data['TTDDokterDPJP'] != $imgDefault)
                                <img style="width: 100px;height: 100px;"
                                    src="{{ $data['TTDDokterDPJP'] }}">
                            @else
                                <br>
                                @if(isset($data['dokterDPJP']['label']))
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $data['dokterDPJP']['label'] }}"><br/>
                                @endif
                            @endif
                            <br>
                            <span>{{ isset($data['dokterDPJP']['label']) ? $data['dokterDPJP']['label'] : '-' }}</span>
                        </td>
                        <td class="mid">
                            <span><br>IPCN</span><br>
                            @if (isset($data['TTDIPCN']) && $data['TTDIPCN'] != $imgDefault)
                                <img style="width: 100px;height: 100px;"
                                    src="{{ $data['TTDIPCN'] }}">
                            @else
                                <br>
                                @if(isset($data['ipcn']))
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $data['ipcn'] }}">
                                @endif
                            @endif
                            <br>
                            <span>{{ isset($data['ipcn']) ? $data['ipcn'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-top: none;">
                <table width="100%" style="border-collapse: collapse; border: none;">
                    @foreach (array_chunk($fotopendukung->toArray(), 3) as $groupIndex => $group)
                        @if ($groupIndex > 0 && $groupIndex % 2 == 0)
                            <tr>
                                <td colspan="3" style="border: none;">
                                    <div style="page-break-after: always;"></div>
                                </td>
                            </tr>
                        @endif

                        <tr>
                            @foreach ($group as $d)
                                @php
                                    $url = storage_path('app/public/' . $d['file']);
                                @endphp
                                <td style="border: none; border-top: 1px solid black; padding: 10px; text-align: center;">
                                    <span style="font-weight: bold;">{{ $d['nama'] }}</span><br>
                                    <img src="{{ $url }}" style="max-width: 100%; max-height: 300px; height: auto; display: block; margin: 5px auto;">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>
</body>

</html>

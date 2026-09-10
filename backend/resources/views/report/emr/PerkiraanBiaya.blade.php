<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    @yield('page-style')
    <style>
        .checkbox-wrapper {
            white-space: nowrap
        }
        .bg-blue {
            background-color: #91CEDE;
        }
        
        input[type=checkbox] {
            margin-bottom: -5px;
        }
        .checkbox-label {
            white-space: normal display:inline-block
        }
    </style>
</head>

<body>
    @php
        use Carbon\Carbon;
    @endphp
    <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <tr>
                <td width="60%" style="text-align:right" colspan=2>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr class="bg-blue">
                            <td>
                                <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                <td width="50%" style="font-size: 14px; text-align: right;">RM 5/ADM/00</td>
                            </td>
                        </tr>
                    </tabel>
                </td>
                <!-- <td width="60%"></td> -->
            </tr>
        </table>

        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: 1px solid black">
            <tr>
                <td width="10%" style="padding: 5px; border-right: 1px solid black;">
                    <img src="{{ 'img/logo-rs.png' }}" style="width: 90px;">
                </td>
                <td style="text-align: center" style="border-right: 1px solid black;">
                    <b>
                        <span style="font-size: 16px">PERKIRAAN BIAYA
                        </span>
                    </b><br>
                    <span style="font-size: 16px"><i>COST ESTIMATED</i>
                    </span>
                </td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-collapse: collapse" border ="1">
            <tr>
                <td width="25%"> Nama Pasien<br><i>Patient Name</i> </td>
                <td width="25%">:  {{ isset($pasien['namapasien']) ? $pasien['namapasien'] : '-' }}</td>
                <td width="25%" rowspan="2">
                    <table width="100%" border="0">
                        <tr>
                            <td style="width: 100%">
                            <div>
                                <input type="checkbox"
                                    {{ isset($pasien['jeniskelamin']) && $pasien['jeniskelamin'] == 'Laki-laki' ? 'checked' : '' }} />
                                    <span style="font-size: 9pt;" color="#000000">Laki-laki</span><br>
                                    <span style="font-size: 9pt;margin-left:20px;" color="#000000">Male</span>
                            </div>
                            <div>
                                <input type="checkbox"
                                    {{  isset($pasien['jeniskelamin']) && $pasien['jeniskelamin'] == 'Perempuan' ? 'checked' : '' }} />
                                    <span style="font-size: 9pt;" color="#000000">Perempuan</span><br>
                                    <span style="font-size: 9pt;margin-left:20px;" color="#000000">Female</span>
                            </div>
                            <div>
                                <span style="font-size: 9pt;" color="#000000">Umur :</span>{{ isset($pasien['tgllahir']) ? \Carbon\Carbon::parse($pasien['tgllahir'])->age : '-' }}
                                <span style="font-size: 9pt;margin-left:20px;" color="#000000">Tahun</span><br>
                            </div>
                            </td>
                        </tr>
                    </table> 
                </td>
            </tr>
            <tr>
                <td width="25%"> Nomor Rekam Medis<br><i>Medical Record Number</i> </td>
                <td width="25%">:  {{ isset($pasien['nocm']) ? $pasien['nocm'] : '-' }}</td>
            </tr>
            <tr>
                <td width="25%"> Diagnosa<br><i>Diagnose</i> </td>
                <td width="25%">:  {{ isset($data['TADiagnosa']) ? $data['TADiagnosa'] : '-' }}</td>
                <td width="25%">
                    <table width="100%" border="0">
                        <tr>
                            <td style="width: 100%">
                            <div >
                                <input type="checkbox"
                                    {{ isset($data['CBSubsidi']) && $data['CBSubsidi'] == 'Subsidi' ? 'checked' : '' }} />
                                    <span style="font-size: 9pt;" color="#000000">Subsidi</span><br>
                                    <span style="font-size: 9pt;margin-left:20px;" color="#000000">Subsidy</span>
                            </div>
                            <div >
                                <input type="checkbox"
                                    {{  isset($data['CBSubsidi']) && $data['CBSubsidi'] == 'Non Subsidi' ? 'checked' : '' }} />
                                    <span style="font-size: 9pt;" color="#000000">Non Subsidi</span><br>
                                    <span style="font-size: 9pt;margin-left:20px;" color="#000000">Non subsidy</span>
                            </div>
                            </td>
                        </tr>
                    </table> 
                </td>
            </tr>
            <tr>
                <td width="25%"> Rencana Perawatan<br><i>Care Of Plan</i> </td>
                <td width="25%">:  {{ isset($data['TARencanaPerawatan']) ? $data['TARencanaPerawatan'] : '-' }}</td>
                <td width="25%">
                    <table width="100%" border="0">
                        <tr>
                            <td style="width: 100%">
                            <div >
                                <span style="font-size: 9pt;" color="#000000">Kode Tarif</span> : {{ isset($data['TBKodeTarif']) ? $data['TBKodeTarif'] : '-' }}
                            </div>
                            <div >
                                <span style="font-size: 10pt;" color="#000000">Perkiraan hari rawat</span><br>
                                <span style="font-size: 10pt;" color="#000000"><i>Estimated length of stay</i></span><br> {{ isset($data['TB_PHR']) ? $data['TB_PHR'] : '-' }} Hari
                            </div>
                            
                            </td>
                        </tr>
                    </table> 
                </td>
            </tr>
            <tr>
                <td width="25%" colspan="2"> Nama Dokter Operator/Dokter yang merawat : <br><i>Doctor’s Name</i><br>{{ isset($data['DDDokter']['label']) ? $data['DDDokter']['label'] : ' ' }}
                </td>
                <td width="25%">
                    <table width="100%" border="0">
                        <tr>
                            <td style="width: 100%">
                            <div >
                                <span style="font-size: 9pt;" color="#000000">Spesialisasi</span> : {{ isset($data['TB_Spesialisasi']) ? $data['TB_Spesialisasi'] : '-' }}<br>
                                <span style="font-size: 9pt;" color="#000000"><i>Specialization</i></span>
                            </div>
                            </td>
                        </tr>
                    </table> 
                </td>
            </tr>
            <tr>
                <td width="25%" colspan="2"> Pilihan Ruang Perawatan :<br><i>Patient Choice Of Ward Class</i><br>
                   
                </td>
                <td width="25%">{{ isset($data['DDRuangan']['label']) ? $data['DDRuangan']['label'] : ' ' }}</td>
            </tr>
            <tr>
                <td width="15%" rowspan="2"> Biaya Harian<br><i>Daily Charges</i><br>
                </td>
                <td width="25%">Ruang Perawatan :  {{ isset($data['DDRuangPerawatan']['namakelas']) ? $data['DDRuangPerawatan']['namakelas'] : ' ' }}<br><i>Ward Class</i><br>
                </td>
                <td width="25%">
                    {{ isset($data['tarifRuangPerawatan']) ? 'Rp ' . number_format($data['tarifRuangPerawatan'], 0, ',', '.') : '-' }} 
                </td>
            </tr>
            <tr>
                <td width="25%">Biaya Perawatan : {{ isset($data['TB_BiayaPerawatan']) ? 'Rp ' . number_format((float) $data['TB_BiayaPerawatan'], 0, ',', '.') : '-' }}<br><i>Nursing Charges</i><br>
                <td width="25%">
                    {{ isset($data['perkiraanBiaya']) ? 'Rp ' . number_format((float) $data['perkiraanBiaya'], 0, ',', '.') : '-' }}
                </td>
            </tr>
            <tr>
                <td width="15%" rowspan="4"> Perkiraan Biaya-Biaya<br><i>Estimated Charges</i><br>
                </td>
                <td width="25%">Visit Dokter <br><i>Doctor Visite</i><br>
                </td>
                <td width="25%">
                    {{ isset($data['TB_VisitDokter']) ? 'Rp ' . number_format((float) $data['TB_VisitDokter'], 0, ',', '.') : '-' }}
                </td>
            </tr>
            <tr>
                <td width="25%">Penunjang Diagnosa, Obat-obatan & lainnya<br><i>Diagnostic Investigation, Medication, Other Treatment</i><br>
                <td width="25%">
                    {{ isset($data['TB_PenunjanngDiagnosa']) ? 'Rp ' . number_format((float) $data['TB_PenunjanngDiagnosa'], 0, ',', '.') : '-' }}
                </td>
            </tr>
            <tr>
                <td width="25%">Tindakan Bedah <br><i>Surgical Procedure Charges</i><br>
                <td width="25%">
                    {{ isset($data['TB_TindakanBedah']) ? 'Rp ' . number_format((float) $data['TB_TindakanBedah'], 0, ',', '.') : '-' }}
                </td>
            </tr>
            <tr>
                <td width="25%">Alat Kesehatan <br><i>Consumables / Implants / Prosthetic Devices / Graft</i><br>
                <td width="25%">
                    {{ isset($data['TB_AlatKesehatan']) ? 'Rp ' . number_format((float) $data['TB_AlatKesehatan'], 0, ',', '.') : '-' }}
                </td>
            </tr>
            <tr>
                <td width="25%" colspan="2">Perkiraan Total Biaya <br><i>Estimated Total Cost</i><br>
                <td width="25%">
                {{ isset($data['TB_PTB']) ? 'Rp ' . number_format((float) $data['TB_PTB'], 0, ',', '.') : '-' }}
                </td>
            </tr>
            <tr>
                <td width="25%" colspan="2">Dana Titipan <br><i>Deposite</i><br>
                <td width="25%">
                {{ isset($data['TB_Deposite']) ? 'Rp ' . number_format((float) $data['TB_Deposite'], 0, ',', '.') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="tc vt" width="50%" style="{{ empty($data['DDDokter']) ? 'padding-bottom: 2em;' : '' }};text-align:center;">
                    
                    <br>
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(80)->generate($data['DDDokter']['label'] ?? '')) }}" 
                        alt="QR Code">
                    <br>
                    <span>{{ isset($data['petugasAddmision']['label']) ? $data['petugasAddmision']['label'] : ' ' }}</span>
                    <div>{{ Carbon::parse($data['DTanggalAdmission'])->translatedFormat('d F Y') }}</div>
                </td>
                <td class="tc vt" width="50%" style="{{ empty($data['DokterMeminta']) ? 'padding-bottom: 2em;' : '' }};text-align:center;">
                    <!-- Qrcode ceritanya -->
                    @if (isset($data['DokterMeminta']['label']))
                        <img src="data:image/png;base64, {!! $qrcode !!}">
                    @endif
                    <br>
                    <span>{{ isset($data['DokterMeminta']['label']) ? $data['DokterMeminta']['label'] : ' ' }}</span>
                    <div>{{ isset($data['DTanggalDokter']) ? Carbon::parse($data['DTanggalDokter'])->translatedFormat('d F Y') : '' }}</div>
                </td>
                <td class="tc vt" width="50%" style="{{ empty($data['DDDokter']) ? 'padding-bottom: 2em;' : '' }};text-align:center;">
                    
                    <br>
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(80)->generate($data['TB_Namakeluarga'] ?? '')) }}" 
                        alt="QR Code">
                    <br>
                    <span>{{ isset($data['TB_Namakeluarga']) ? $data['TB_Namakeluarga'] : ' ' }}</span>
                    <div>{{ Carbon::parse($data['DTanggalPasien'])->translatedFormat('d F Y') }}</div>
                </td>
            </tr>
        </table>
</body>

</html>

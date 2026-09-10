<!DOCTYPE html>
<html>

@php
    use Carbon\Carbon;
@endphp

<head>
    <title>Print Medical Report</title>
    <style>
        body,
        table,
        td {
            font-family: 'Open Sans', sans-serif !important;
            border-collapse: collapse;
        }

        .tdHeader {
            padding: 5px;
            font-weight: bold;
            background-color: lightblue
        }

        .checkbox-wrapper {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 1px solid black;
            text-align: center;
            line-height: 20px;
        }

        .checked {
            font-weight: bold;
        }

        @media print {
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body>
    {{-- {{dd($data)}} --}}
    <table width="100%">
        @if ($cekWargaNegaraWNA == true)
            <tr>
                <td>
                    <table style="border: 1px solid black;font-size: 10pt" cellspacing="0" cellpadding="0" width="100%">
                        <tr style="border: 1px solid black">
                            <td class="tdHeader">
                                RSUD BALI MANDARA
                            </td>
                            <td class="tdHeader" style="text-align: right;">
                                RM.1N/SK/00
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border: 1px solid black">
                                <table width="100%" style="border-collapse: collapse">
                                    <tr style="border: ">
                                        <td width="15%" style="text-align: center;padding: 10px">
                                            <img src="{{ 'img/provinsi-rs.svg' }}" width="80px" height="80px"
                                                style="display: block;">
                                        </td>
                                        <td width="70%" style="text-align: center;">
                                            <span>
                                                <b>PEMERINTAH PROVINSI BALI</b><br>
                                                <b>RUMAH SAKIT UMUM DAERAH BALI MANDARA</b><br>
                                                Jalan ByPass Ngurah Rai No.548 Sanur,Garut-Bali<br>
                                                No.Telp : (0361) 4490566, E-mail : rsud.balimandara@gmail.com
                                            </span>
                                        </td>
                                        <td width="15%" style="text-align: center;padding: 10px">
                                            <img src="{{ 'img/logo-rs.png' }}" width="80px" height="80px"
                                                style="display: block;">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"
                                style="border: 1px solid black;font-size: 10pt; font-weight: bold; color: #000000;text-align: center;padding:5px">
                                Medical Report
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table width="100%">
                                    <tr>
                                        <td style="padding: 5px;vertical-align: top" width="50%">
                                            <table width="100%" style="border-spacing: 0 8px;font-size:8pt;">
                                                <tr>
                                                    <td width="40%">Date</td>
                                                    <td width="60%">: {{ date('d-m-Y') ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Medical record number</td>
                                                    <td width="60%">: {{ $pasien['nocm'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Name</td>
                                                    <td width="60%">: {{ $data['TBNamaPasien'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Date of Birth</td>
                                                    <td width="60%">:
                                                        {{ isset($data['DTanggalLahir']) ? \Carbon\Carbon::parse($data['DTanggalLahir'])->format('d-m-Y') : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Nasionality</td>
                                                    <td width="60%">:
                                                        {{ isset($data['TBKebangsaanPasien']) ? $data['TBKebangsaanPasien'] : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Current Adress</td>
                                                    <td width="60%">:
                                                        {{ isset($data['TAAlamatPasien']) ? $data['TAAlamatPasien'] : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="padding: 5px;vertical-align: top" width="50%">
                                            <table width="100%" style="border-spacing: 0 8px;font-size:8pt;">
                                                {{-- <tr>
                                                    <td width="40%">Hour</td>
                                                    <td width="60%">:
                                                        {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') ?? '-' }}
                                                    </td>
                                                </tr> --}}
                                                <tr>
                                                    <td width="40%">&nbsp;</td>
                                                    <td width="60%">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Sex</td>
                                                    <td width="60%">:
                                                        {{ isset($data['TBJenisKelaminPasien']) ? App\Traits\Valet::english($data['TBJenisKelaminPasien']) : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Date of Administration</td>
                                                    <td width="60%">:
                                                        {{-- {{ isset($data['DTanggalRegis']) ? date('d-m-Y', strtotime($data['DTanggalRegis'])) : '-' }} --}}
                                                        {{-- {{ isset($data['DTanggalRegis']) ? \Carbon\Carbon::parse($data['DTanggalRegis'])->setTimezone('Asia/Jakarta')->format('d-m-Y H i ') : '-' }} --}}
                                                        {{ isset($data['DTanggalRegis']) ? \Carbon\Carbon::parse($data['DTanggalRegis'])->setTimezone('Asia/Jakarta')->format('d-m-Y h:i A') : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Date of Discharge</td>
                                                    <td width="60%">:
                                                        {{-- {{ isset($data['DTanggalKasir']) ? date('d-m-Y', strtotime($data['DTanggalKasir'])) : '-' }} --}}
                                                        {{-- {{ isset($data['DTanggalKasir']) ? \Carbon\Carbon::parse($data['DTanggalKasir'])->setTimezone('Asia/Jakarta')->format('d-m-Y H i') : '-' }} --}}
                                                        {{ isset($data['DTanggalKasir']) ? \Carbon\Carbon::parse($data['DTanggalKasir'])->setTimezone('Asia/Jakarta')->format('d-m-Y h:i A') : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Phone Number</td>
                                                    <td width="60%">:
                                                        {{ isset($data['TBNomorTeleponPasien']) ? $data['TBNomorTeleponPasien'] : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <table style="font-size: 8pt" cellspacing="0" cellpadding="0" width="100%">
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td width="30%">Patient History</td>
                                                    <td width="70%">:
                                                        {{ isset($data['TAHistoriPasien']) ? App\Traits\Valet::english($data['TAHistoriPasien']) : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30%">Pass Medical History </td>
                                                    <td width="70%">:
                                                        {{ isset($data['TAPassMedicalHistory']) ? App\Traits\Valet::english($data['TAPassMedicalHistory']) : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30%">Allergic History</td>
                                                    <td width="70%">:
                                                        {{ isset($data['TAAllergyHistory']) ? App\Traits\Valet::english($data['TAAllergyHistory']) : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td colspan="3">Vital Sign</td>
                                                </tr>
                                                <tr>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">GCS</td>
                                                                <td style="width: 50%">:
                                                                    E
                                                                    {{ isset($data['TBeGCS']) ? $data['TBeGCS'] : '-' }}
                                                                    V
                                                                    {{ isset($data['TBvGCS']) ? $data['TBvGCS'] : '-' }}
                                                                    M
                                                                    {{ isset($data['TBmGCS']) ? $data['TBmGCS'] : '-' }}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">Temperature</td>
                                                                <td style="width: 50%">:
                                                                    {{ isset($data['TBSSuhu']) ? $data['TBSSuhu'] : '-' }} °C
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">Respiratory Rate</td>
                                                                <td style="width: 50%">:
                                                                    {{ isset($data['TBSPernafasan']) ? $data['TBSPernafasan'] : '-' }} x/minute
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">Blood Pressure</td>
                                                                <td style="width: 50%">:
                                                                    {{ isset($data['TBSTekananDarah']) ? $data['TBSTekananDarah'] : '-' }} mmHg
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">Pulse Rate</td>
                                                                <td style="width: 50%">:
                                                                    {{ isset($data['TBSNadi']) ? $data['TBSNadi'] : '-' }} x/minute
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">SPO<sub>2</sub></td>
                                                                <td style="width: 50%">:
                                                                    {{ isset($data['TBSSaO2']) ? $data['TBSSaO2'] : '-' }} %
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    @if (isset($data['CBPregnantWoman']) && $data['CBPregnantWoman'] != false)
                                        <tr style="border: 1px solid black;">
                                            <td style="padding: 5px">
                                                <table cellspacing="0" cellpadding="0" width="100%">
                                                    <tr style="border-right: none;">
                                                        <td>For Pregnant Woman</td>
                                                    </tr>
                                                </table>
                                                <table style="border: 1px solid black;" cellspacing="0" cellpadding="0" width="100%">
                                                    <tr style="border: 1px solid black;">
                                                        <td style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;padding: 2px">
                                                            <span>Period of Pregnancy :</span>
                                                            {{ isset($data['PeriodofPregnancy']) ? App\Traits\Valet::english($data['PeriodofPregnancy']) : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr style="border: 1px solid black;">
                                                        <td style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;padding: 2px">
                                                            <span>Expected date of Delivery :</span>
                                                            {{ isset($data['ExpecteddateofDelivery']) ? App\Traits\Valet::english($data['ExpecteddateofDelivery']) : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr style="border: 1px solid black;">
                                                        <td style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;padding: 2px">
                                                            <span>Fitness for the trip :</span>
                                                            {{ isset($data['Fitnessforthetrip']) ? App\Traits\Valet::english($data['Fitnessforthetrip']) : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr style="border: 1px solid black;">
                                                        <td style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;padding: 2px">
                                                            <span>Other remarks :</span>
                                                            {{ isset($data['Otherremarks']) ? App\Traits\Valet::english($data['Otherremarks']) : '-' }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td>Physical Examination :</td>
                                                </tr>
                                                <td
                                                    style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ isset($data['TAPhysicalExamination']) ? App\Traits\Valet::english($data['TAPhysicalExamination']) : '-' }}
                                                </td>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td>Other Examination (Radiology,Lab,ECG, CT-Scan, USG, MRI,etc) :
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                        {{ isset($data['TAOtherExamination']) ? App\Traits\Valet::english($data['TAOtherExamination']) : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td>Diagnosis :</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                        {{ isset($data['TADiagnosis']) ? App\Traits\Valet::english($data['TADiagnosis']) : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td>Treatment / Medication :</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                        {{ isset($data['TATreatmentMedication']) ? App\Traits\Valet::english($data['TATreatmentMedication']) : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td>Doctor's Recomendation :</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%">
                                                            <tr>
                                                                <td width="25%">Patient can be transported</td>
                                                                <td width="25%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%"
                                                                                style="vertical-align: middle">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBPatientCanBeTransported']) && $data['CBPatientCanBeTransported'] == 'Yes' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Yes</span>
                                                                            </td>
                                                                            <td width="50%"
                                                                                style="vertical-align: middle;">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBPatientCanBeTransported']) && $data['CBPatientCanBeTransported'] == 'No' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">No</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                                <td width="25%">Patient fit to fly</td>
                                                                <td width="25%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%"
                                                                                style="vertical-align: middle">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBPatientFitToFly']) && $data['CBPatientFitToFly'] == 'Yes' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Yes</span>
                                                                            </td>
                                                                            <td width="50%"
                                                                                style="vertical-align: middle;">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBPatientFitToFly']) && $data['CBPatientFitToFly'] == 'No' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">No</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%">
                                                            <tr>
                                                                <td width="40%">Escorted</td>
                                                                <td width="60%">
                                                                    <table width="100%">
                                                                        <td width="50%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['CBEscorted']) && $data['CBEscorted'] == 'Medical Escort' ? 'checked' : '' }} />
                                                                            <span
                                                                                style="color: #000000; margin-left: 3px;">Medical
                                                                                Escort </span>
                                                                        </td>
                                                                        <td width="50%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['CBEscorted']) && $data['CBEscorted'] == 'Non Medical Escort' ? 'checked' : '' }} />
                                                                            <span
                                                                                style="color: #000000; margin-left: 3px;">Non
                                                                                Medical
                                                                                Escort </span>
                                                                        </td>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40%">Economy Class</td>
                                                                <td width="60%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBbcClass']) && $data['CBbcClass'] == 'Bussiness Class' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Bussiness
                                                                                    Class </span>
                                                                            </td>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBecClass']) && $data['CBecClass'] == 'Economy Class' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Economy
                                                                                    Class </span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40%"></td>
                                                                <td width="60%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBWheelChair']) && $data['CBWheelChair'] == 'Wheel Chair' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Wheel
                                                                                    Chair </span>
                                                                            </td>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBSretcher']) && $data['CBSretcher'] == 'Stretcher' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Stretcher
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40%">Tourist/Expat Patientasking for
                                                                    repatriation
                                                                </td>
                                                                <td width="60%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBTPAFR']) && $data['CBTPAFR'] == 'Yes' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Yes
                                                                                </span>
                                                                            </td>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBTPAFR']) && $data['CBTPAFR'] == 'No' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">No
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40%">In Doctor's Opinion this patient
                                                                    requires
                                                                    reparation
                                                                </td>
                                                                <td width="60%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBInDoctorOpinion']) && $data['CBInDoctorOpinion'] == 'Yes' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Yes
                                                                                </span>
                                                                            </td>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBInDoctorOpinion']) && $data['CBInDoctorOpinion'] == 'No' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">No
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 10px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td width="50%" rowspan="5"
                                                        style="vertical-align: top;word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                        <span>Comment :</span>
                                                        <p>
                                                            {{ isset($data['TAComment']) ? App\Traits\Valet::english($data['TAComment']) : '-' }}
                                                        </p>
                                                    </td>
                                                    <td width="50%"></td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"></td>
                                                    <td width="50%" style="text-align: center;">
                                                        Bali, {{ $data['created_at'] ? Carbon::parse($data['created_at'])->format('d-m-Y') : '-' }}<br>
                                                        <b>Attending Physician</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"></td>
                                                    <td style="text-align: center" width="50%">
                                                        <img src="data:image/png;base64, {!! $tte !!}"
                                                            width="70px" height="70px">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"></td>
                                                    <td style="text-align: center;font-weight: bold;" width="50%">
                                                        @if (is_array($data['DDDokter']) && array_key_exists('label', $data['DDDokter']))
                                                            {{ $data['DDDokter']['label'] ?? '-' }}
                                                        @else
                                                            {{ $data['DDDokter'] ?? '-' }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"></td>
                                                    <td style="text-align: center;font-weight: bold;" width="50%">
                                                        {{-- @if (!empty($data['nip']->nip))
                                                            NIP : {{ $data['nip']->nip }}
                                                        @elseif (!empty($data['nosip']->nosip))
                                                            SIP : {{ $data['nosip']->nosip }}
                                                        @else
                                                            SKP : {{ $data['noskp']->noskp }}
                                                        @endif --}}
                                                        {{-- @if (!empty($data['nip']->nip))
                                                            NIP : {{ $data['nip']->nip }}
                                                        @elseif (!empty($data['nosip']->nosip))
                                                            SIP : {{ $data['nosip']->nosip }}
                                                        @elseif (!empty($data['noskp']->noskp))
                                                            SKP : {{ $data['noskp']->noskp }}
                                                        @else
                                                            {{ '-' }}
                                                        @endif --}}
                                                        @if (!empty($data['nip']->nip))
                                                            NIP : {{ $data['nip']->nip }}<br>
                                                        @endif

                                                        @if (!empty($data['nosip']->nosip))
                                                            SIP : {{ $data['nosip']->nosip }}<br>
                                                        @endif

                                                        @if (!empty($data['noskp']->noskp))
                                                            SKP : {{ $data['noskp']->noskp }}<br>
                                                        @endif

                                                        @if (empty($data['nip']->nip) && empty($data['nosip']->nosip) && empty($data['noskp']->noskp))
                                                            {{ '-' }}
                                                        @endif

                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        @else
            <tr>
                <td>
                    <table style="border: 1px solid black;font-size: 10pt" cellspacing="0" cellpadding="0"
                        width="100%">
                        <tr style="border: 1px solid black">
                            <td class="tdHeader">
                                RSUD BALI MANDARA
                            </td>
                            <td class="tdHeader" style="text-align: right;">
                                RM.1N/SK/00
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border: 1px solid black">
                                <table width="100%" style="border-collapse: collapse">
                                    <tr style="border: ">
                                        <td width="15%" style="text-align: center;padding: 10px">
                                            <img src="{{ 'img/provinsi-rs.svg' }}" width="80px"
                                                height="80px" style="display: block;">
                                        </td>
                                        <td width="70%" style="text-align: center;">
                                            <span>
                                                <b>PEMERINTAH PROVINSI BALI</b><br>
                                                <b>RUMAH SAKIT UMUM DAERAH BALI MANDARA</b><br>
                                                Jalan ByPass Ngurah Rai No.548 Sanur,Garut-Bali<br>
                                                No.Telp : (0361) 4490566, E-mail : rsud.balimandara@gmail.com
                                            </span>
                                        </td>
                                        <td width="15%" style="text-align: center;padding: 10px">
                                            <img src="{{ 'img/logo-rs.png' }}" width="80px" height="80px"
                                                style="display: block;">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"
                                style="border: 1px solid black;font-size: 10pt; font-weight: bold; color: #000000;text-align: center;padding:5px">
                                Medical Report
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table width="100%">
                                    <tr>
                                        <td style="padding: 5px;vertical-align: top" width="50%">
                                            <table width="100%" style="border-spacing: 0 8px;font-size:8pt;">
                                                <tr>
                                                    <td width="40%">Tanggal</td>
                                                    <td width="60%">: {{ date('d-m-Y') ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Nomor Rekam Medis</td>
                                                    <td width="60%">: {{ $pasien['nocm'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Nama</td>
                                                    <td width="60%">: {{ $data['TBNamaPasien'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Tanggal Lahir</td>
                                                    <td width="60%">:
                                                        {{ isset($data['DTanggalLahir']) ? \Carbon\Carbon::parse($data['DTanggalLahir'])->format('d-m-Y') : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Kewarganegaraan</td>
                                                    <td width="60%">:
                                                        {{ isset($data['TBKebangsaanPasien']) ? $data['TBKebangsaanPasien'] : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Alamat</td>
                                                    <td width="60%">:
                                                        {{ isset($data['TAAlamatPasien']) ? $data['TAAlamatPasien'] : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="padding: 5px;vertical-align: top" width="50%">
                                            <table width="100%" style="border-spacing: 0 8px;font-size:8pt;">
                                                {{-- <tr>
                                                    <td width="40%">Hour</td>
                                                    <td width="60%">:
                                                        {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') ?? '-' }}
                                                    </td>
                                                </tr> --}}
                                                <tr>
                                                    <td width="40%">&nbsp;</td>
                                                    <td width="60%">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Jenis Kelamin</td>
                                                    <td width="60%">:
                                                        {{ isset($data['TBJenisKelaminPasien']) ? App\Traits\Valet::english($data['TBJenisKelaminPasien']) : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Tanggal Administrasi</td>
                                                    <td width="60%">:
                                                        {{-- {{ isset($data['DTanggalRegis']) ? date('d-m-Y', strtotime($data['DTanggalRegis'])) : '-' }} --}}
                                                        {{ isset($data['DTanggalRegis']) ? \Carbon\Carbon::parse($data['DTanggalRegis'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Tanggal Kasir</td>
                                                    <td width="60%">:
                                                        {{-- {{ isset($data['DTanggalKasir']) ? date('d-m-Y', strtotime($data['DTanggalKasir'])) : '-' }} --}}
                                                        {{ isset($data['DTanggalKasir']) ? \Carbon\Carbon::parse($data['DTanggalKasir'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%">Nomor HP</td>
                                                    <td width="60%">:
                                                        {{ isset($data['TBNomorTeleponPasien']) ? $data['TBNomorTeleponPasien'] : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <table style="font-size: 8pt" cellspacing="0" cellpadding="0" width="100%">
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td width="30%">Riwayat Pasien</td>
                                                    <td width="70%">:
                                                        {{ isset($data['TAHistoriPasien']) ? $data['TAHistoriPasien'] : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30%">Riwayat Medis Dahulu</td>
                                                    <td width="70%">:
                                                        {{ isset($data['TAPassMedicalHistory']) ? $data['TAPassMedicalHistory'] : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30%">Riwayat Alergi</td>
                                                    <td width="70%">:
                                                        {{ isset($data['TAAllergyHistory']) ? $data['TAAllergyHistory'] : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td colspan="3">Tanda-tanda Vital</td>
                                                </tr>
                                                <tr>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">GCS</td>
                                                                <td style="width: 50%">:
                                                                    E
                                                                    {{ isset($data['TBeGCS']) ? $data['TBeGCS'] : '-' }}
                                                                    V
                                                                    {{ isset($data['TBvGCS']) ? $data['TBvGCS'] : '-' }}
                                                                    M
                                                                    {{ isset($data['TBmGCS']) ? $data['TBmGCS'] : '-' }}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">Suhu</td>
                                                                <td style="width: 50%">:
                                                                    {{ isset($data['TBSSuhu']) ? $data['TBSSuhu'] : '-' }} °C
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">Pernafasan</td>
                                                                <td style="width: 50%">:
                                                                    {{ isset($data['TBSPernafasan']) ? $data['TBSPernafasan'] : '-' }} x/menit
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">Tekanan Darah</td>
                                                                <td style="width: 50%">:
                                                                    {{ isset($data['TBSTekananDarah']) ? $data['TBSTekananDarah'] : '-' }} mmHg
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">Nadi</td>
                                                                <td style="width: 50%">:
                                                                    {{ isset($data['TBSNadi']) ? $data['TBSNadi'] : '-' }} x/menit
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="33%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td style="width: 50%">SPO<sub>2</sub></td>
                                                                <td style="width: 50%">:
                                                                    {{ isset($data['TBSSaO2']) ? $data['TBSSaO2'] : '-' }} %
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    @if (isset($data['CBPregnantWoman']) && $data['CBPregnantWoman'] != false)
                                        <tr style="border: 1px solid black;">
                                            <td style="padding: 5px">
                                                <table cellspacing="0" cellpadding="0" width="100%">
                                                    <tr style="border-right: none;">
                                                        <td>Untuk Wanita Hamil</td>
                                                    </tr>
                                                </table>
                                                <table style="border: 1px solid black;" cellspacing="0" cellpadding="0" width="100%">
                                                    <tr style="border: 1px solid black;">
                                                        <td style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;padding: 3px">
                                                            <span>Masa Kehamilan :</span>
                                                            {{ isset($data['PeriodofPregnancy']) ? App\Traits\Valet::english($data['PeriodofPregnancy']) : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr style="border: 1px solid black;">
                                                        <td style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;padding: 3px">
                                                            <span>Tanggal Pengiriman yang Diharapkan :</span>
                                                            {{ isset($data['ExpecteddateofDelivery']) ? \Carbon\Carbon::parse($data['ExpecteddateofDelivery'])->setTimezone('Asia/Jakarta')->format('d-m-Y h:i A') : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr style="border: 1px solid black;">
                                                        <td style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;padding: 3px">
                                                            <span>kesiapan untuk melakukan perjalanan :</span>
                                                            {{ isset($data['Fitnessforthetrip']) ? App\Traits\Valet::english($data['Fitnessforthetrip']) : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr style="border: 1px solid black;">
                                                        <td style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;padding: 3px">
                                                            <span>Keterangan lain :</span>
                                                            {{ isset($data['Otherremarks']) ? App\Traits\Valet::english($data['Otherremarks']) : '-' }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td>Pemeriksaan Fisik :</td>
                                                </tr>
                                                <td
                                                    style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ isset($data['TAPhysicalExamination']) ? $data['TAPhysicalExamination'] : '-' }}
                                                </td>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td>Pemeriksaan Lain (Radiologi, Lab, ECG, CT-Scan, USG, MRI, dll) :
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                        {{ isset($data['TAOtherExamination']) ? $data['TAOtherExamination'] : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td>Diagnosis :</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                        {{ isset($data['TADiagnosis']) ? $data['TADiagnosis'] : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td>Perawatan / Pengobatan :</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                        {{ isset($data['TATreatmentMedication']) ? $data['TATreatmentMedication'] : '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 5px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td>Rekomendasi Dokter :</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%">
                                                            <tr>
                                                                <td width="25%">Pasien dapat dipindahkan</td>
                                                                <td width="25%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%"
                                                                                style="vertical-align: middle">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBPatientCanBeTransported']) && $data['CBPatientCanBeTransported'] == 'Yes' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Ya</span>
                                                                            </td>
                                                                            <td width="50%"
                                                                                style="vertical-align: middle;">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBPatientCanBeTransported']) && $data['CBPatientCanBeTransported'] == 'No' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Tidak</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                                <td width="25%">Pasien fit untuk terbang</td>
                                                                <td width="25%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%"
                                                                                style="vertical-align: middle">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBPatientFitToFly']) && $data['CBPatientFitToFly'] == 'Yes' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Ya</span>
                                                                            </td>
                                                                            <td width="50%"
                                                                                style="vertical-align: middle;">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBPatientFitToFly']) && $data['CBPatientFitToFly'] == 'No' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Tidak</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%">
                                                            <tr>
                                                                <td width="40%">Ditemani</td>
                                                                <td width="60%">
                                                                    <table width="100%">
                                                                        <td width="50%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['CBEscorted']) && $data['CBEscorted'] == 'Medical Escort' ? 'checked' : '' }} />
                                                                            <span
                                                                                style="color: #000000; margin-left: 3px;">Pengawal
                                                                                Medis</span>
                                                                        </td>
                                                                        <td width="50%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['CBEscorted']) && $data['CBEscorted'] == 'Non Medical Escort' ? 'checked' : '' }} />
                                                                            <span
                                                                                style="color: #000000; margin-left: 3px;">Pengawal
                                                                                Non Medis</span>
                                                                        </td>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40%">Kelas Ekonomi</td>
                                                                <td width="60%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBbcClass']) && $data['CBbcClass'] == 'Bussiness Class' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Kelas
                                                                                    Bisnis</span>
                                                                            </td>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBecClass']) && $data['CBecClass'] == 'Economy Class' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Kelas
                                                                                    Ekonomi</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40%"></td>
                                                                <td width="60%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBWheelChair']) && $data['CBWheelChair'] == 'Wheel Chair' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Kursi
                                                                                    Roda</span>
                                                                            </td>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBSretcher']) && $data['CBSretcher'] == 'Stretcher' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Tempat
                                                                                    Tidur Pasien</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40%">Pasien Turis/Expats yang meminta
                                                                    untuk dipulangkan</td>
                                                                <td width="60%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBTPAFR']) && $data['CBTPAFR'] == 'Yes' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Ya</span>
                                                                            </td>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBTPAFR']) && $data['CBTPAFR'] == 'No' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Tidak</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40%">Menurut Pendapat Dokter, pasien ini
                                                                    memerlukan pemulangan</td>
                                                                <td width="60%">
                                                                    <table width="100%">
                                                                        <tr>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBInDoctorOpinion']) && $data['CBInDoctorOpinion'] == 'Yes' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Ya</span>
                                                                            </td>
                                                                            <td width="50%">
                                                                                <input type="checkbox"
                                                                                    {{ isset($data['CBInDoctorOpinion']) && $data['CBInDoctorOpinion'] == 'No' ? 'checked' : '' }} />
                                                                                <span
                                                                                    style="color: #000000; margin-left: 3px;">Tidak</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="padding: 10px">
                                            <table width="100%" style="border-spacing: 0 8px;table-layout: fixed;">
                                                <tr>
                                                    <td width="50%" rowspan="5"
                                                        style="vertical-align: top;word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                        <span>Komentar :</span>
                                                        <p>
                                                            {{ isset($data['TAComment']) ? $data['TAComment'] : '-' }}
                                                        </p>
                                                    </td>
                                                    <td width="50%"></td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"></td>
                                                    <td width="50%" style="text-align: center;">
                                                        Bali, {{ $data['created_at'] ? Carbon::parse($data['created_at'])->format('d-m-Y') : '-' }}<br>
                                                        <b>Dokter Penanggung Jawab</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"></td>
                                                    <td style="text-align: center" width="50%">
                                                        <img src="data:image/png;base64, {!! $tte !!}"
                                                            width="70px" height="70px">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"></td>
                                                    <td style="text-align: center;font-weight: bold;" width="50%">
                                                        @if (is_array($data['DDDokter']) && array_key_exists('label', $data['DDDokter']))
                                                            {{ $data['DDDokter']['label'] ?? '-' }}
                                                        @else
                                                            {{ $data['DDDokter'] ?? '-' }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"></td>
                                                    <td style="text-align: center;font-weight: bold;" width="50%">
                                                        {{-- NIP :
                                                        @if (isset($data['nip']->nip))
                                                            {{ $data['nip']->nip }}
                                                        @else
                                                            {{ '-' }}
                                                        @endif --}}
                                                        @if (!empty($data['nip']->nip))
                                                            NIP : {{ $data['nip']->nip }}<br>
                                                        @endif

                                                        @if (!empty($data['nosip']->nosip))
                                                            SIP : {{ $data['nosip']->nosip }}<br>
                                                        @endif

                                                        @if (!empty($data['noskp']->noskp))
                                                            SKP : {{ $data['noskp']->noskp }}<br>
                                                        @endif

                                                        @if (empty($data['nip']->nip) && empty($data['nosip']->nosip) && empty($data['noskp']->noskp))
                                                            {{ '-' }}
                                                        @endif

                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        @endif
    </table>
</body>

</html>

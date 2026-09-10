@php
    // Check IMG Default
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

    function isChecked($data, $key)
    {
        return isset($data[$key]) && $data[$key] != '' ? 'checked' : '';
    }
@endphp

<head>
    <title>Persetujuan Tindakan Kedokteran Anestesi</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        .bold {
            font-weight: bold;
        }

        table {
            border-collapse: collapse !important;
            width: 100% !important;
            page-break-inside: auto;
        }

        tr {
            page-break-inside: auto;
            page-break-after: avoid
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
            padding: 5px;
        }

        .bt {
            border-top: 1px solid black;
        }

        .fnt td {
            border-top: 1px solid black;
            vertical-align: top;
            padding: 3px;
        }

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
            margin-bottom: -5px;
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
</head>

<body>
    @foreach ($data as $index => $d)
        <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th colspan="2"
                        style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;text-align: left;border-top: 1px solid black;border-left:1px solid black">
                        RSUD BALI MANDARA
                    </th>
                    <th
                        style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;border-top: 1px solid black;border-right:1px solid black">
                        Halaman-{{ $index + 1 }}
                    </th>
                </tr>
                <tr>
                    <td width="10%" style="text-align: center;padding: 10px" class="border">
                        <img src="{{ 'img/logo-rs.png' }}" width="65px" height="65px" style="display: block;">
                    </td>
                    <td width="40%"
                        style="vertical-align: middle;text-align: center;font-weight: bold;font-size: large"
                        class="border">
                        Persetujuan Tindakan Kedokteran Anestesi
                    </td>
                    <td width="40%" style="padding: 5px" class="border">
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
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" class="border">
                        <table class="fnt">
                            <tr>
                                <th>
                                    PEMBERIAN INFORMASI (INFORMATION)
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <b>Dokter Pelaksana Tindakan :</b>
                                    {{ isset($d['DDDokter']['label']) ? $d['DDDokter']['label'] : '-' }}<br>
                                    <i>Doctor in charge / operator</i>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Pemberi Informasi :</b>
                                    {{ isset($d['DDPemberiInformasi']['label']) ? $d['DDPemberiInformasi']['label'] : '-' }}<br>
                                    <i>Informer</i>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" style="table-layout: fixed; width: 100%;">
                                        <tr>
                                            <td style="width: 35%">
                                                <b>Peneriman Informasi/Pemberi Persetujuan :</b> <br>
                                                <i>Recipient / Approver*</i>
                                            </td>
                                            <td style="width: 65%">
                                                @isset($d['details'])
                                                @php $count = 0; @endphp
                                                    @foreach ($d['details'] as $item)
                                                        {{ $item['TBPemberiPersetujuan'] }},
                                                        @php $count++; @endphp
                                                        @if ($count % 3 == 0)
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px">
                                    <table>
                                        <tr>
                                            <th style="widows: 40%;border-right:1px solid black">
                                                JENIS INFORMASI / <i>TYPE OF INFORMATION</i>
                                            </th>
                                            <th style="widows: 40%;border-right:1px solid black">
                                                ISI INFORMASI / <i>DESCRIPTION OF THE INFORMATION</i>
                                            </th>
                                            <th style="widows: 20%">
                                                TANDA / <i>CHECKLIST</i> <span
                                                    style="font-family: DejaVu Sans">(✓)</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black">
                                                1. Diagnosis (WD & DD) / <i>Diagnosis</i>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                {{ isset($d['pemeriksaantextDiagnosisWD']) ? $d['pemeriksaantextDiagnosisWD'] : '-' }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="checkbox" {{ isChecked($d, 'pemeriksaanDiagnosis') }} />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black">
                                                2. Dasar Diagnosis / <i>Underlying Diagnosis</i>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                {{ isset($d['Diagnosistextdasar']) ? $d['Diagnosistextdasar'] : '-' }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="checkbox" {{ isChecked($d, 'diagnosisTextDasar') }} />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black">
                                                3. Tindakan Kedokteran / <i>Medical Action</i>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                {{ isset($d['pemeriksaantextTindakanKedokteran']) ? $d['pemeriksaantextTindakanKedokteran'] : '-' }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="checkbox"
                                                    {{ isChecked($d, 'TindakanKedokteranCheckbox') }} />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black">
                                                4. Indikasi Tindakan / <i>Indication of Action</i>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                {{ isset($d['pemeriksaantextIndikasiTindakan']) ? $d['pemeriksaantextIndikasiTindakan'] : '-' }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="checkbox"
                                                    {{ isChecked($d, 'pemeriksaantextIndikasiTindakanCheckbox') }} />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black">
                                                5. Tata Cara / <i>Procedures</i>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                {{ isset($d['pemeriksaantextTataCara']) ? $d['pemeriksaantextTataCara'] : '-' }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="checkbox"
                                                    {{ isChecked($d, 'pemeriksaantextTataCaraCheckbox') }} />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black">
                                                6. Tujuan / <i>Purpose</i>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                {{ isset($d['pemeriksaantextTujuan']) ? $d['pemeriksaantextTujuan'] : '-' }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="checkbox" {{ isChecked($d, 'tujuanCheckbox') }} />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black">
                                                7. Risiko / <i>Risk</i>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                {{ isset($d['pemeriksaantextRisiko']) ? $d['pemeriksaantextRisiko'] : '-' }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="checkbox"
                                                    {{ isChecked($d, 'pemeriksaantextRisikoCheckbox') }} />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black">
                                                8. Komplikasi / <i>Complication</i>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                {{ isset($d['pemeriksaantextKomplikasi']) ? $d['pemeriksaantextKomplikasi'] : '-' }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="checkbox"
                                                    {{ isChecked($d, 'pemeriksaantextKomplikasiCheckbox') }} />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black">
                                                9. Prognosis / <i>Prognosis</i>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                {{ isset($d['pemeriksaantextPrognosis']) ? $d['pemeriksaantextPrognosis'] : '-' }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="checkbox"
                                                    {{ isChecked($d, 'pemeriksaantextPrognosisCheckbox') }} />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black">
                                                10. Alternatif Dan Risiko / <i>Alternative & Risk</i>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                {{ isset($d['pemeriksaantextAlternatif']) ? $d['pemeriksaantextAlternatif'] : '-' }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="checkbox"
                                                    {{ isChecked($d, 'pemeriksaantextAlternatifCheckbox') }} />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black">
                                                11. Lain-lain / <i>Other</i>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                {{ isset($d['pemeriksaantextLainLain']) ? $d['pemeriksaantextLainLain'] : '-' }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="checkbox"
                                                    {{ isChecked($d, 'pemeriksaantextLainLainCheckbox') }} />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px;border-top: 0px">
                                    <table>
                                        <tr>
                                            <td style="width: 70%;border-right:1px solid black">
                                                Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas
                                                secara
                                                benar
                                                dan
                                                jelas
                                                dan memberikan kesempatan untuk bertanya dan/atau berdiskusi<br>
                                                <i>I have explained the information correctly, clearly and provide
                                                    opportunity to ask
                                                    and or discuss</i>
                                            </td>
                                            <td style="width: 30%;text-align: center">
                                                <span>Tanda Tangan Dokter</span><br>
                                                @if (isset($d['TTDmenyatakanMenerangkanInformasi']) && $d['TTDmenyatakanMenerangkanInformasi'] != $imgDefault)
                                                    <img style="width: 50px;height: 50px;"
                                                        src="{{ $d['TTDmenyatakanMenerangkanInformasi'] }}">
                                                @else
                                                    <br><br><br>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 70%;border-right:1px solid black">
                                                Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di
                                                atas
                                                yang saya
                                                beri
                                                tanda/paraf di kolom kanannya, dan telah memahaminya<br>
                                                <i>I have received the information as I have given signature in the
                                                    right column and
                                                    have understood</i>
                                            </td>
                                            <td style="width: 30%;text-align: center">
                                                <span>Tanda Tangan (Pasien/Keluarga)</span><br>
                                                @if (isset($d['TTDmenyatakanMenerimaInformasi']) && $d['TTDmenyatakanMenerimaInformasi'] != $imgDefault)
                                                    <img style="width: 50px;height: 50px;"
                                                        src="{{ $d['TTDmenyatakanMenerimaInformasi'] }}">
                                                @else
                                                    <br><br><br>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                *Bila Pasien tidak kompeten atau tidak mau menerima informasi, maka
                                                penerima
                                                informasi
                                                adalah Wali atau
                                                Keluarga terdekat.<br>
                                                <i>If the patient is incompetent or do not want to receive the
                                                    information, then
                                                    information are given to
                                                    his or
                                                    her parents, spouse, next of kin or the guardian</i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <b>PERSETUJUAN TINDAKAN KEDOKTERAN (AGREEMENT FOR MEDICAL
                                                    ACTION)</b><br>
                                                Yang bertandatangan di bawah ini, Saya<br>
                                                <i>The undersigned below, I</i><br>
                                                Nama/Name :
                                                {{ isset($d['namaPasien']) ? strtoupper($d['namaPasien']) : '-' }}<br>
                                                Umur/Age :
                                                {{ isset($d['umurPasien']) ? strtoupper($d['umurPasien']) : '-' }}<br>
                                                Jenis Kelamin/Sex :
                                                {{ isset($d['jeniskelamin']) ? strtoupper($d['jeniskelamin']) : '-' }}<br>
                                                Alamat/Address :
                                                {{ isset($d['alamat']) ? strtoupper($d['alamat']) : '-' }}<br>
                                                dengan ini menyatakan Persetujuan untuk dilakukannya tindakan :
                                                {{ isset($d['persetujuan']) ? $d['persetujuan'] : '-' }}<br><br>
                                                Terhadap, {{ isset($d['terhadap']) ? $d['terhadap'] : 'Saya' }}<br>
                                                Nama/Name :
                                                {{ isset($d['namaPasienTerhadap']) ? strtoupper($d['namaPasienTerhadap']) : '-' }}<br>
                                                Umur/Age :
                                                {{ isset($d['umurPasienTerhadap']) ? strtoupper($d['umurPasienTerhadap']) : '-' }}<br>
                                                Jenis Kelamin/Gender :
                                                {{ isset($d['jeniskelaminTerhadap']) ? strtoupper($d['jeniskelaminTerhadap']) : '-' }}<br>
                                                Alamat/Address :
                                                {{ isset($d['alamatTerhadap']) ? strtoupper($d['alamatTerhadap']) : '-' }}<br><br>
                                                Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah
                                                dijelaskan
                                                seperti di
                                                atas kepada saya, termasuk risiko dan komplikasi yang mungkin
                                                timbul.<br>
                                                Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu
                                                pasti,
                                                maka
                                                keberhasilan
                                                tindakan kedokteran bukanlah keniscayaan, melainkan sangat bergantung
                                                kepada
                                                izin Tuhan
                                                Yang
                                                Maha Esa.<br>
                                                <i>
                                                    I Have Fully Understand the need and the benefits of these action
                                                    which has been explained to
                                                    me,
                                                    including the
                                                    risks and any
                                                    complication that might be occur. <br>
                                                    I understand that the practice of medicine is not an exact science,
                                                    hence the success of the
                                                    medical action
                                                    is not
                                                    an absolute thing but it is
                                                    very dependent on the permission of God Almighty.
                                                </i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"
                                                style="padding: 0px;border-top:none;border-bottom:none">
                                                <table>
                                                    <tr>
                                                        <td style="width: 33%;text-align: center">
                                                            Garut :
                                                            {{ isset($d['DTttd']) ? convertToMakassarDate($d['DTttd']) : '-' }}
                                                        </td>
                                                        <td style="width: 33%;text-align: center">
                                                            Saksi : {{ isset($d['TBSaksi']) ? $d['TBSaksi'] : '-' }}
                                                        </td>
                                                        <td style="width: 33%;text-align: center"></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center">
                                                            <span>Yang Menyatakan</span><br>
                                                            @if (isset($d['TTDmenyatakan']) && $d['TTDmenyatakan'] != $imgDefault)
                                                                <img style="width: 50px;height: 50px;"
                                                                    src="{{ $d['TTDmenyatakan'] }}">
                                                            @else
                                                                <br><br><br>
                                                            @endif
                                                            <br><span>{{ isset($d['namaPasienmenyatakan']) ? $d['namaPasienmenyatakan'] : '-' }}</span>
                                                        </th>
                                                        <th style="text-align: center">
                                                            <span>Pihak Keluarga</span><br>
                                                            @if (isset($d['TTDpihakkeluarga']) && $d['TTDpihakkeluarga'] != $imgDefault)
                                                                <img style="width: 50px;height: 50px;"
                                                                    src="{{ $d['TTDpihakkeluarga'] }}">
                                                            @else
                                                                <br><br><br>
                                                            @endif
                                                            <br><span>{{ isset($d['pihakKeluarga']) ? $d['pihakKeluarga'] : '-' }}</span>
                                                        </th>
                                                        <th style="text-align: center">
                                                            <span>Pihak Rumah Sakit</span><br>
                                                            @if (isset($d['TTDpihak']) && $d['TTDpihak'] != $imgDefault)
                                                                <img style="width: 50px;height: 50px;"
                                                                    src="{{ $d['TTDpihak'] }}">
                                                            @else
                                                                <br><br><br>
                                                            @endif
                                                            <br><span>{{ isset($d['DDDPihakRumahSakit']['label']) ? $d['DDDPihakRumahSakit']['label'] : '-' }}</span>
                                                        </th>
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
            </tbody>
        </table>
    @endforeach
</body>

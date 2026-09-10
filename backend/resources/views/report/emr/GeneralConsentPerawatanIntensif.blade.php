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

    // Mempersingkat
    $d = $data;

    // TTD Default (blank)
    $imgDefault =
        'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII=';
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>General Consent</title>
    <style>
        body,
        table,
        td,
        pre {
            font-family: 'Open Sans', sans-serif !important;
        }

        .border {
            border: 1px solid black;
        }

        .pd {
            padding: 3px;
        }

        .font {
            font-size: 8pt
        }

        .table td {
            padding: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        td,
        th,
        tr {
            page-break-inside: auto;
            page-break-after: auto;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr class="border">
                <td style="background-color: lightblue; padding:5px; font-weight:bold;text-align: left" colspan="2">
                    RSUD BALI MANDARA
                </td>
                <td style="background-color: lightblue; padding:5px; font-weight:bold; text-align: right;">
                    RM 2/ADM/01
                </td>
            </tr>
            <tr>
                <td width="10%" style="text-align: center;padding: 10px" class="border">
                    <img src="{{ 'img/logo-rs.png' }}" width="80px" height="80px" style="display: block;">
                </td>
                <td width="45%"
                    style="vertical-align: middle;text-align: center;font-weight: bold;font-size: large;padding: 3px"
                    class="border">
                    General Consent
                    <br>
                    <span style="font-size: xx-small;">
                        @if ($cekWargaNegaraWNA)
                            CONDITION OF SERVICE AND FINANCIAL OBLIGATION
                        @else
                            KONDISI PELAYANAN DAN KEWAJIBAN KEUANGAN
                        @endif
                    </span>
                </td>
                <td width="45%" style="padding: 5px" class="border">
                    <table>
                        <tr style="font-size: 10pt">
                            <td style="text-align:left;width: 40%;">Nama</td>
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
        @if ($cekWargaNegaraWNA)
            <tbody>
                <tr>
                    <td colspan="3">
                        <table class="border font table" style="border-top: none;">
                            <tr style="border-bottom: 1px solid black">
                                <td>CIRCLE IT</td>
                                <td>
                                    INTENSIF ROOM :
                                </td>
                                @php
                                    // Mencegah Kosong
                                    $d['kelasKamar'] = isset($d['kelasKamar']) ? $d['kelasKamar'] : 'NONE';
                                @endphp
                                <td style="{{ $d['kelasKamar'] == 'SUITE' ? 'font-weight:bold;' : '' }}">SUITE</td>
                                <td style="{{ $d['kelasKamar'] == 'VVIP' ? 'font-weight:bold;' : '' }}">VVIP</td>
                                <td style="{{ $d['kelasKamar'] == 'VIP' ? 'font-weight:bold;' : '' }}">VIP</td>
                                <td style="{{ $d['kelasKamar'] == 'KLS I' ? 'font-weight:bold;' : '' }}">KELAS I</td>
                                <td style="{{ $d['kelasKamar'] == 'KLS II' ? 'font-weight:bold;' : '' }}">KELAS II</td>
                                <td style="{{ $d['kelasKamar'] == 'KLS III' ? 'font-weight:bold;' : '' }}">KELAS III
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    The undersigned below :
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding: 0px;">
                                    <table>
                                        <tr>
                                            <td style="width: 23%">Name</td>
                                            <td style="width: 2%">:</td>
                                            <td style="width: 75%;">
                                                {{ isset($d['namaPasien']) ? $d['namaPasien'] : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 23%">Date of Birth</td>
                                            <td style="width: 2%">:</td>
                                            <td style="width: 75%;">
                                                {{ isset($d['tglLahir']) ? convertToRegularDate($d['tglLahir']) : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 23%">Address</td>
                                            <td style="width: 2%">:</td>
                                            <td style="width: 75%;">
                                                {{ isset($d['alamatPasien']) ? $d['alamatPasien'] : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 23%">Phone Number</td>
                                            <td style="width: 2%">:</td>
                                            <td style="width: 75%;">
                                                {{ isset($d['noTelepon']) ? $d['noTelepon'] : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 23%">Relation with the patient</td>
                                            <td style="width: 2%">:</td>
                                            @php
                                                if (isset($d['hubpasien'])) {
                                                    switch ($d['hubpasien']) {
                                                        case '1':
                                                            $d['hubpasien'] = 'Patient him/herself';
                                                            break;
                                                        case '2':
                                                            $d['hubpasien'] = 'Father';
                                                            break;
                                                        case '3':
                                                            $d['hubpasien'] = 'Mother';
                                                            break;
                                                        case '4':
                                                            $d['hubpasien'] = 'Relatives';
                                                            break;
                                                        case '5':
                                                            $d['hubpasien'] = 'Friend';
                                                            break;
                                                        case '6':
                                                            $d['hubpasien'] = 'Other';
                                                            break;
                                                        default:
                                                            break;
                                                    }
                                                }
                                            @endphp
                                            <td style="width: 75%;">
                                                {{ isset($d['hubpasien']) ? $d['hubpasien'] : '-' }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    I. CONSENT FOR TREATMENT
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    a. I knew that I had a condition that needed medical care, I gave permission to
                                    doctors and other health
                                    professionals to carry out diagnostic procedures and to provide medical treatment as
                                    needed for
                                    professional assessment. Diagnostic procedures and medical treatments including
                                    therapy are not
                                    limited to ECG, X-Ray, blood tests, physical therapy and drug administration.
                                    <br>
                                    b.
                                    I am aware that the practice of medicine and surgery is not an exact science and I
                                    acknowledge that
                                    there is no guarantee of any results for any treatment or examination procedures
                                    performed on me.
                                    <br>
                                    c. I understand that :
                                    <br>
                                    <div style="padding-left: 10px;">
                                        1.
                                        I have the right to ask about the proposed treatment including the identity of
                                        each person who
                                        gives or observes treatment at any time.
                                        <br>
                                        2.
                                        I have the right to consent, or refuse approval for each procedure/therapy.
                                        <br>
                                        3.
                                        I understand that many of the physicians who care for me in this facility are
                                        not employees or
                                        agents of the facility but are allowed by this facility to provide for the care
                                        and treatment of their
                                        patients.
                                        <br>
                                        4.
                                        In special unit of Bali Mandara, will joint educate participant during
                                        hospitalitation that will be
                                        control by hospital employee such as, doctors, nurses, midwifery and other
                                        medical staff.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    II. GOODS BELONG TO PATIENTS
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    a. I understand that I should not bring valuables/personal belongings to the
                                    hospital (axamples :
                                    jewellery, electronic devices, credit cards, money, books, etc.) and the hospital is
                                    not responsible for
                                    the damage and loss of the items mentioned above.
                                    <br>
                                    b. I have to notify the hospital if I have/use dentures, contact lenses, prosthetics
                                    or other personal items
                                    that need to be secured.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    III. AUTHORIZATION FOR RELEASE OF MEDICAL INFORMATION
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    a. I understand that all of my personal medical information, include all diagnostic
                                    results, laboratory
                                    results and other treatments results at this facility will confidentially
                                    guaranteed.
                                    <br>
                                    b. I authorize the facility to release information form my medical records to any
                                    health care provider
                                    involved in any way in my care and treatment and to any person or entity which is or
                                    may be liablefor
                                    all or part of the hospital charges, including but not limited to BPJS/my insurances
                                    carrier or any third
                                    party provider or authorized government agency.
                                    <br>
                                    c.
                                    @if (isset($d['izin']) && $d['izin'] == 1)
                                        I authorized/<s>forbid</s>*)
                                    @else
                                        I <s>authorized</s>/forbid*)
                                    @endif
                                    the facility to disclosure my medical record to my family
                                    or relatives or guardian
                                    member, namely :
                                    <div style="padding-left: 10px;">
                                        1. {{ isset($d['text1']) ? App\Traits\Valet::english($d['text1']) : '-' }}
                                        <br>
                                        2. {{ isset($d['text2']) ? App\Traits\Valet::english($d['text2']) : '-' }}
                                        <br>
                                        3. {{ isset($d['text3']) ? App\Traits\Valet::english($d['text3']) : '-' }}
                                        <br>
                                        4. {{ isset($d['text4']) ? App\Traits\Valet::english($d['text4']) : '-' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    IV. PATIENT RIGHTS AND OBLIGATIONS
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    a. I understand that I have rights and also responsibility to follow the
                                    instructions of my care providers
                                    and to make agreement for the arrangements of follow up care.
                                    <br>
                                    b. I certify that I have been informed about patient rights and obligations in this
                                    facility thru leaflet and
                                    banner, provided by the facility personnel.
                                    <br>
                                    c. Special request (identification of expectations and privacy) :
                                    <br>
                                    {{ isset($d['text5']) ? App\Traits\Valet::english($d['text5']) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    V. INPATIENT INFORMATIONS
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    a. I certify that I have been informed about regulations in this facility and
                                    understand that I and my
                                    relatives must obey the visiting hours in accordance with the regulations in this
                                    facility.
                                    <br>
                                    b. I certify that I have been informed about inpatient facilities. If the room/ward
                                    that become my right
                                    based on my insurance coverage is unavailable, I understand and wiling to be treated
                                    in existing
                                    room/ward.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    VI. FINANCIAL AGREEMENTS
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    I understand to all treatments and services that explained by the facility
                                    personnel. I acknowledge full
                                    financial responsibility for and agree to pay all charges of the Bali Mandara
                                    Hospital services.
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    I acknowledge that I have read this document in this entirety and that I fully
                                    understand it prior to my signing.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding: 0px;">
                                    <table>
                                        <tr>
                                            <td style="width: 50%;text-align: center">
                                                <br><br>
                                                Admission officer
                                                <br>
                                                @if (isset($d['signature_1']) && $d['signature_1'] != $imgDefault)
                                                    <img style="width: 150px;height: 150px;"
                                                        src="{{ $d['signature_1'] }}">
                                                @else
                                                    <div style="height: 20px">&nbsp;</div>
                                                @endif
                                                <br><span>{{ isset($d['yangMenjelaskan']['label']) ? $d['yangMenjelaskan']['label'] : '-' }}</span>
                                            </td>
                                            <td style="width: 50%;text-align: center">
                                                Garut,
                                                {{ isset($d['tglPembuatan']) ? convertToRegularDate($d['tglPembuatan']) : '-' }}
                                                <br>
                                                That stated
                                                <br>
                                                @if (isset($d['signature_2']) && $d['signature_2'] != $imgDefault)
                                                    <img style="width: 150px;height: 150px;"
                                                        src="{{ $d['signature_2'] }}">
                                                @else
                                                    <div style="height: 20px">&nbsp;</div>
                                                @endif
                                                <br><span>{{ isset($d['pasienPenanggungJawab']) ? $d['pasienPenanggungJawab'] : '-' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align: left">
                                                *) Cross the unnecessary ones
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        @else
            <tbody>
                <tr>
                    <td colspan="3">
                        <table class="border font table" style="border-top: none;">
                            <tr style="border-bottom: 1px solid black">
                                <td>LINGKARI</td>
                                <td>
                                    RUANG INTENSIF :
                                </td>
                                @php
                                    // Mencegah Kosong
                                    $d['kelasKamar'] = isset($d['kelasKamar']) ? $d['kelasKamar'] : 'NONE';
                                @endphp
                                <td style="{{ $d['kelasKamar'] == 'SUITE' ? 'font-weight:bold;' : '' }}">SUITE</td>
                                <td style="{{ $d['kelasKamar'] == 'VVIP' ? 'font-weight:bold;' : '' }}">VVIP</td>
                                <td style="{{ $d['kelasKamar'] == 'VIP' ? 'font-weight:bold;' : '' }}">VIP</td>
                                <td style="{{ $d['kelasKamar'] == 'KLS I' ? 'font-weight:bold;' : '' }}">KELAS I</td>
                                <td style="{{ $d['kelasKamar'] == 'KLS II' ? 'font-weight:bold;' : '' }}">KELAS II
                                </td>
                                <td style="{{ $d['kelasKamar'] == 'KLS III' ? 'font-weight:bold;' : '' }}">KELAS III
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    Yang bertanda tangan di bawah ini :
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding: 0px;">
                                    <table>
                                        <tr>
                                            <td style="width: 23%">Nama</td>
                                            <td style="width: 2%">:</td>
                                            <td style="width: 75%;">
                                                {{ isset($d['namaPasien']) ? $d['namaPasien'] : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 23%">Tanggal Lahir</td>
                                            <td style="width: 2%">:</td>
                                            <td style="width: 75%;">
                                                {{ isset($d['tglLahir']) ? convertToRegularDate($d['tglLahir']) : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 23%">Alamat</td>
                                            <td style="width: 2%">:</td>
                                            <td style="width: 75%;">
                                                {{ isset($d['alamatPasien']) ? $d['alamatPasien'] : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 23%">Nomor Telepon</td>
                                            <td style="width: 2%">:</td>
                                            <td style="width: 75%;">
                                                {{ isset($d['noTelepon']) ? $d['noTelepon'] : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 23%">Hubungan Dengan Pasien</td>
                                            <td style="width: 2%">:</td>
                                            @php
                                                if (isset($d['hubpasien'])) {
                                                    switch ($d['hubpasien']) {
                                                        case '1':
                                                            $d['hubpasien'] = 'Pasien Sendiri';
                                                            break;
                                                        case '2':
                                                            $d['hubpasien'] = 'Ayah';
                                                            break;
                                                        case '3':
                                                            $d['hubpasien'] = 'Ibu';
                                                            break;
                                                        case '4':
                                                            $d['hubpasien'] = 'Saudara';
                                                            break;
                                                        case '5':
                                                            $d['hubpasien'] = 'Teman';
                                                            break;
                                                        case '6':
                                                            $d['hubpasien'] = 'Lainnya';
                                                            break;
                                                        default:
                                                            break;
                                                    }
                                                }
                                            @endphp
                                            <td style="width: 75%;">
                                                {{ isset($d['hubpasien']) ? $d['hubpasien'] : '-' }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    I. PERSETUJUAN UNTUK PERAWATAN DAN PENGOBATAN
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    a. Saya mengetahui bahwa saya memiliki kondisi yang membutuhkan perawatan medis,
                                    saya
                                    memberi ijin kepada dokter dan profesi kesehatan lainnya untuk melakukan prosedur
                                    diagnostik dan untuk memberikan pengobatan medis seperti yang diperlukan untuk
                                    penilaian
                                    secara profesional. Prosedur diagnostik dan perawatan medis termasuk terapi tidak
                                    terbatas pada ECG, X-Ray, tes darah, terapi fisik dan pemberian obat.
                                    <br>
                                    b.
                                    Saya sadar bahwa praktik kedokteran dan ilmu bedah bukanlah ilmu pasti dan saya
                                    mengakui
                                    bahwa tidak ada jaminan atas hasil apapun terhadap prosedur perawatan atau
                                    pemeriksaan
                                    apapun yang dilakukan kepada saya.
                                    <br>
                                    c. Saya mengerti dan memahami bahwa :
                                    <br>
                                    <div style="padding-left: 10px;">
                                        1.
                                        Saya memiliki hak untuk menanyakan tentang pengobatan yang diusulkan termasuk
                                        identitas setiap orang yang memberikan atau mengamati pengobatan setiap saat.
                                        <br>
                                        2.
                                        Saya memiliki hak untuk persetujuan, atau menolak persetujuan untuk setiap
                                        prosedur/terapi.
                                        <br>
                                        3.
                                        Banyak dokter pada staf medis rumah sakit yang bukan karyawan tetapi sebagai
                                        staf
                                        tamu yang telah diberikan hak untuk menggunakan fasilitas untuk perawatan dan
                                        pengobatan pasien mereka.
                                        <br>
                                        4. Di unit pelayanan tertentu RSUD Bali Mandara, ada keterlibatan peserta didik
                                        dalam pemberian
                                        pelayanan yang didampingi oleh petugas RS baik dari dokter, perawat, bidan
                                        maupun
                                        tenaga
                                        medis lainnya.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    II. BARANG-BARANG MILIK PASIEN
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    a. Saya tidak boleh membawa barang-barang berharga yang tidak diperlukan dalam
                                    proses
                                    perawatan secara langsung (seperti : perhiasan, elektronik, kartu kredit, uang,
                                    buku,
                                    dll) dan rumah
                                    sakit tidak bertanggung jawab atas kerusakan dan kehilangan barang tersebut di atas.
                                    <br>
                                    b. Saya harus memberi tahu rumah sakit jika saya memiliki/memakai gigi palsu, lensa
                                    kontak, prostetik
                                    atau barang pribadi lainnya yang perlu diamankan.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    III. PERSETUJUAN PELEPASAN INFORMASI MEDIS
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    a. Saya memahami informasi kesehatan yang ada dalam diri saya, termasuk diagnosa,
                                    hasil
                                    laboratorium dan hasil tes diagnostik lainnya akan digunakan untuk perawatan medis
                                    saya,
                                    rumah
                                    sakit akan menjamin kerahasiaannya.
                                    <br>
                                    b. Saya memberi wewenang kepada rumah sakit untuk memberikan informasi tentang
                                    diagnosa
                                    hasil
                                    pelayanan kesehatan dan pengobatan saya bila diperlukan untuk memproses klaim
                                    asuransi
                                    BPJS/perusahaan/perorangan atau lembaga lain yang bertanggung jawab atas biaya
                                    pelayanan
                                    kesehatan saya dan atau lembaga pemerintah yang berwenang.
                                    <br>
                                    c.
                                    @if (isset($d['izin']) && $d['izin'] == 1)
                                        Saya mengijinkan/<s>tidak mengijinkan</s>*)
                                    @else
                                        Saya <s>mengijinkan</s>/tidak mengijinkan*)
                                    @endif
                                    kepada rumah sakit untuk memberikan
                                    informasi
                                    tentang
                                    diagnosa, hasil pelayanan kesehatan dan pengobatan saya kepada anggota
                                    keluarga/kerabat/wali
                                    saya, yaitu kepada :
                                    <div style="padding-left: 10px;">
                                        1. {{ isset($d['text1']) ? $d['text1'] : '-' }}
                                        <br>
                                        2. {{ isset($d['text2']) ? $d['text2'] : '-' }}
                                        <br>
                                        3. {{ isset($d['text3']) ? $d['text3'] : '-' }}
                                        <br>
                                        4. {{ isset($d['text4']) ? $d['text4'] : '-' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    IV. HAK DAN TANGGUNG JAWAB PASIEN
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    a. Saya memiliki hak untuk mengambil bagian dalam keputusan mengenai penyakit saya
                                    dan
                                    dalam
                                    hal perawatan medis dan rencana pengobatan.
                                    <br>
                                    b. Saya telah mendapat informasi tentang Hak dan Kewajiban Pasien di rumah sakit
                                    melalui
                                    leaflet dan
                                    banner yang disediakan oleh petugas.
                                    <br>
                                    c. Permintaan khusus (identifikasi harapan dan privasi) :
                                    <br>
                                    {{ isset($d['text5']) ? $d['text5'] : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    V. INFORMASI RAWAT INAP
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    a. Saya telah menerima informasi tentang peraturan yang diberlakukan oleh rumah
                                    sakit
                                    dan saya
                                    beserta keluarga bersedia untuk mematuhi jam berkunjung pasien sesuai dengan aturan
                                    rumah
                                    sakit.
                                    <br>
                                    b. Saya telah menerima informasi tentang fasilitas rawat inap dan karena situasi
                                    dimana
                                    ruang
                                    perawatan yang menjadi hak saya sesuai kebutuhan tidak tersedia, saya bersedia
                                    dirawat
                                    dengan
                                    fasilitas yang ada.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="font-weight: bold">
                                    VI. INFORMASI BIAYA
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-left: 10px;">
                                    Saya memahami tentang informasi biaya pengobatan atau biaya tindakan medis yang
                                    dijelaskan oleh
                                    petugas rumah sakit yang berwenang dan sanggup untuk melaksanakan seluruh kewajiban
                                    keuangan
                                    yang dikeluarkan selama perawatan di RSUD Bali Mandara Provinsi Bali.
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    Dengan tanda tangan saya di bawah ini, saya menyatakan bahwa saya telah membaca dan
                                    memahami item
                                    pada persetujuan umum ini.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding: 0px;">
                                    <table>
                                        <tr>
                                            <td style="width: 50%;text-align: center">
                                                <br><br>
                                                Petugas
                                                <br>
                                                @if (isset($d['signature_1']) && $d['signature_1'] != $imgDefault)
                                                    <img style="width: 150px;height: 150px;"
                                                        src="{{ $d['signature_1'] }}">
                                                @else
                                                    <div style="height: 20px">&nbsp;</div>
                                                @endif
                                                <br><span>{{ isset($d['yangMenjelaskan']['label']) ? $d['yangMenjelaskan']['label'] : '-' }}</span>
                                            </td>
                                            <td style="width: 50%;text-align: center">
                                                Garut,
                                                {{ isset($d['tglPembuatan']) ? convertToRegularDate($d['tglPembuatan']) : '-' }}
                                                <br>
                                                Yang menyatakan
                                                <br>
                                                @if (isset($d['signature_2']) && $d['signature_2'] != $imgDefault)
                                                    <img style="width: 150px;height: 150px;"
                                                        src="{{ $d['signature_2'] }}">
                                                @else
                                                    <div style="height: 20px">&nbsp;</div>
                                                @endif
                                                <br><span>{{ isset($d['pasienPenanggungJawab']) ? $d['pasienPenanggungJawab'] : '-' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align: left">
                                                *) Coret yang tidak perlu
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        @endif
    </table>
</body>

</html>

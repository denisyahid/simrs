<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    @yield('page-style')

    <style>
        td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    {{-- {{dd($data) }} --}}
    <table width="100%" cellspacing="0" cellpadding="0"
        style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr colspan="3">
            <td style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;">
                RSUD BALI MANDARA
            </td>
            <td colspan="2"
                style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                RM 15C/KEMO/00
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0"
        style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr>
            <td style="border: 1px solid black; width: 10%;">
                <img src="{{ 'img/logo-rs.png' }}" width="100px" height="100px" style="display: block;">
            </td>

            <td style="border: 1px solid black; font-weight: bold; text-align: center; width: 60%;">
                Asesmen Pra Operasi Laki - Laki
            </td>
            <td style="border: 1px solid black">
                <p>Nama : {{ $data['pasien']['namapasien'] }}</p>
                <p>Umur : {{ $data['pasien']['umur'] }}</p>
                <p>Jenis Kelamin: {{ $data['pasien']['jeniskelamin'] }}</p>
                <p>No Rekam Medis : {{ $data['pasien']['nocm'] }}</p>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0"
        style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr>
            <td style="border: 1px solid black; width: 50%;">
                <h5>Data Subyektif</h5>
                <input type="checkbox" {{ isset($data['batuk']) && $data['batuk'] == 'Batuk' ? 'checked' : '' }} />
                <span>Batuk</span>
                <input type="checkbox" {{ isset($data['pilek']) && $data['pilek'] == 'Pilek' ? 'checked' : '' }} />
                <span>Pilek</span>
                <input type="checkbox"
                    {{ isset($data['gigipalsu']) && $data['gigipalsu'] == 'Gigi Palsu' ? 'checked' : '' }} />
                <span>Gigi Palsu</span>
                <input type="checkbox" {{ isset($data['pusing']) && $data['pusing'] == 'Pusing' ? 'checked' : '' }} />
                <span>Pusing</span>
                <input type="checkbox" {{ isset($data['mual']) && $data['mual'] == 'Mual' ? 'checked' : '' }} />
                <span>Mual</span>
                <input type="checkbox"
                    {{ isset($data['sesaknafas']) && $data['sesaknafas'] == 'Sesak Nafas' ? 'checked' : '' }} />
                <span>Sesak Nafas</span>
                <input type="checkbox" {{ isset($data['puasa']) && $data['puasa'] == 'Puasa' ? 'checked' : '' }} />
                <span>Puasa</span>
                <input type="checkbox" {{ isset($data['lainnya1']) && $data['lainnya1'] != '' ? 'checked' : '' }} />
                <span>Lainnya: {{ $data['lainnya'] ?? '' }}</span>
            </td>
            <td style="border: 1px solid black; width: 50%;">
                <h5>Riwayat penyakit</h5>
                <input type="checkbox" {{ isset($data['dm']) && $data['dm'] == 'DM' ? 'checked' : '' }} />
                <span>DM</span>
                <input type="checkbox"
                    {{ isset($data['hipertensi']) && $data['hipertensi'] == 'Hipertensi' ? 'checked' : '' }} />
                <span>Hipertensi</span>
                <input type="checkbox" {{ isset($data['asthma']) && $data['asthma'] == 'Asthma' ? 'checked' : '' }} />
                <span>Asthma</span>
                <input type="checkbox" {{ isset($data['tb']) && $data['tb'] == 'TB Paru' ? 'checked' : '' }} />
                <span>TB Paru</span>
                <input type="checkbox" {{ isset($data['ami']) && $data['ami'] == 'AMI' ? 'checked' : '' }} />
                <span>AMI</span>
                <input type="checkbox" {{ isset($data['chf']) && $data['chf'] == 'CHF' ? 'checked' : '' }} />
                <span>CHF</span>
                <input type="checkbox"
                    {{ isset($data['hepatitis']) && $data['hepatitis'] == 'Hepatitis B-C' ? 'checked' : '' }} />
                <span>Hepatitis</span>
                <input type="checkbox" {{ isset($data['hiv']) && $data['hiv'] == 'HIV / AIDS' ? 'checked' : '' }} />
                <span>HIV / AIDS</span>

                <input type="checkbox"
                    {{ isset($data['lainnya2']) && $data['lainnya2'] == 'Lainnya2' ? 'checked' : '' }} />
                <span>Lainnya: {{ $data['lainnya22'] ?? '' }}</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0"
        style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr>
            <td style="border: 1px solid black; width: 50%;">
                <h5>Data obyektif (pemeriksaan fisik)</h5>
            </td>
            <td style="border: 1px solid black; width: 50%;">
                <h5>Hasil pemeriksaan penunjang yang telah teridentifikasi</h5>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;border-bottom: none;border-collapse: collapse" width="100%">
        <tr>
            <td style="border: 1px solid black;">Pemeriksaan</td>
            <td style="border: 1px solid black;" colspan="2">Jam: {{ isset($data['jam']) ? $data['jam'] : '' }}</td>
            <td style="border: 1px solid black;" colspan="2">
                Secara benar:
            </td>
        </tr>
        <tr>
            <td>Tekanan Darah</td>
            <td>{{ $data['tekanan_darah'] ?? '' }} mmHg</td>
            <td>
            <td><input type="checkbox"
                    {{ isset($data['rontgen']) && $data['rontgen'] == 'Foto Rontgen' ? 'checked' : '' }} />
                Foto Rontgen</td>
            <td><input type="checkbox"
                    {{ isset($data['laboratorium']) && $data['laboratorium'] == 'Laboratorium' ? 'checked' : '' }} />
                Laboratorium</td>
            </td>
        </tr>
        <tr>
            <td>Frekuensi Nafas</td>
            <td>{{ $data['nafasObgyn'] ?? '' }} x/menit</td>
            <td>
            <td><input type="checkbox" {{ isset($data['ctscan']) && $data['ctscan'] == 'CT-Scan' ? 'checked' : '' }} />
                CT Scan</td>
            <td><input type="checkbox" {{ isset($data['ya1']) && $data['ya1'] == 'YA' ? 'checked' : '' }} />
                <span>{{ isset($data['textya1']) ? $data['textya1'] : '' }}</span></td>
            </td>
        </tr>
        <tr>
            <td>Nadi</td>
            <td>{{ $data['nadiObgyn'] ?? '' }} x/menit</td>
            <td>
            <td><input type="checkbox" {{ isset($data['mri']) && $data['mri'] == 'MRI' ? 'checked' : '' }} />
                MRI</td>
            <td><input type="checkbox" {{ isset($data['ya2']) && $data['ya2'] == 'YA' ? 'checked' : '' }} />
                <span>{{ isset($data['textya2']) ? $data['textya2'] : '' }}</span></td>
            </td>
        </tr>
        <tr>
            <td>Suhu Aksila</td>
            <td>{{ $data['celciusObgyn'] ?? '' }} °C</td>
            <td>
            <td><input type="checkbox" {{ isset($data['usg']) && $data['usg'] == 'USG' ? 'checked' : '' }} />
                USG</td>
            <td><input type="checkbox" {{ isset($data['ya3']) && $data['ya3'] == 'YA' ? 'checked' : '' }} />
                <span>{{ isset($data['textya3']) ? $data['textya3'] : '' }}</span></td>
            </td>
        </tr>

        <tr>
            <td>Abdomen</td>
            <td>{{ $data['abdomen'] ?? '' }}</td>
            <td>
            <td><input type="checkbox" {{ isset($data['ekg']) && $data['ekg'] == 'EKG' ? 'checked' : '' }} />
                EKG</td>
            <td><input type="checkbox" {{ isset($data['ya4']) && $data['ya4'] == 'YA' ? 'checked' : '' }} />
                <span>{{ isset($data['textya4']) ? $data['textya4'] : '' }}</span></td>
            </td>
        </tr>
        <tr>
            <td>thorax</td>
            <td>{{ $data['thorax'] ?? '' }}</td>
            <td>
                <td colspan="2">
                    Riwayat Operasi Sebelumnya : {{isset($data['riwayatoperasi']) ? $data['riwayatoperasi'] : ''}}
                </td>
            </td>
        </tr>
        <tr>
            <td>Extermintas</td>
            <td>{{ $data['extermintas'] ?? '' }}</td>
            <td>

                    <td colspan="2">
                        Riwayat Alergi : {{isset($data['riwayatalergi']) ? $data['riwayatalergi'] : ''}} <br>
                        Dokumen rekam medis terkait : {{isset($data['dokumenrm']) ? $data['dokumenrm'] : ''}}
                    </td>

            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr>
            <td>Catatan Penting</td>
            <td>{{isset($data['catatanpenting']) ? $data['catatanpenting'] : ''}}</td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr>
            <td>
                <span>Diagnosa pra operasi : {{isset($data['diagnosapra']) ? $data['diagnosapra'] : ''}}</span>
            </td>
            <td>
                <span>Persiapan darah : {{isset($data['persiapandarah']) ? $data['persiapandarah'] : ''}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span>Posisi pasien dalam operasi : {{isset($data['posisiop']) ? $data['posisiop'] : ''}}</span>
            </td>
            <td rowspan="3">
                <span>Rencana operasi : {{isset($data['rencanaoperasi']) ? $data['rencanaoperasi'] : ''}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span>Profilaksis : {{isset($data['profilaksis']) ? $data['profilaksis'] : ''}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span>Alat Khusus : {{isset($data['alatkhusus']) ? $data['alatkhusus'] : ''}}</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr>
            <td style="width: 50%">
                <span>Dengan ini saya menyatakan bahwa saya telah menerima
                    informasi tentang tujuan dan pentingnya dilakukan assesment
                    pra operasi dan penandaan pada area operasi saya mengerti
                    serta memahami hal tersebut
                </span> <br>
                <p style="text-align: center;">Tanda tangan pasien/keluarga</p> <br>
                <img style="width: 100px;height: 100px;" src="{{ $data['TTDDokter1'] }}"> <br>
                <span>{{isset($data['ttdPasien']) ? $data['ttdPasien'] : ''}}</span>
            </td>
            <td style="width: 50%" style="text-align: center;">
                <br>
                <br>
                <br>
                <p style="text-align: center;">Operator</p> <br>
                @if(is_array($data['CBDokter']))
                    <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $data['CBDokter']['label'] }}" style="align-items: center;"><br />
                @else
                    <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $data['CBDokter'] }}" style="align-items: center;"><br />
                @endif
                @if (is_array($data['CBDokter']))
                    <P style="text-align: center;">{{isset($data['CBDokter']['label']) ? $data['CBDokter']['label'] : ''}}</P>
                @else
                    <P style="text-align: center;">{{isset($data['CBDokter']) ? $data['CBDokter'] : ''}}</P>
                @endif
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr>
            <td style="text-align: center;">
                <p>SITE MARKING</p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <p>Berikan tanda rumput (√) menggunakan marker pada gambar dan pada tubuh pasien sesuai dengan rencana area tempat insisi luka operasi</p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <p>Gambar sesuai dengan rencana area tempat insisi luka (gambar di balik lembar ini)</p>
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr>
            <td style="text-align: center;">
                <p>SITE MARKING LAKI - LAKI</p>
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <thead>
            <th style="text-align: center; max-width: 50%; border: 1px solid black;">Prosedur : {{isset($data['namaprosedur']) ? $data['namaprosedur'] : ''}}</th>
            <th style="text-align: center; max-width: 50%; border: 1px solid black;">TGL. PROSEDUR : {{isset($data['tglProsedur']) ? $data['tglProsedur'] : ''}}</th>
        </thead>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tbody>
            <tr>
                <td>
                    <img src="{{ $data['Gambar1'] }}" alt="" style="background-image: url('img/fullbody1.png')" height="79%" width="100%">
                </td>
            </tr>
            <tr>
                <td>
                    <img src="{{ $data['Gambar6'] }}" alt="" style="background-image: url('img/kaki.png')">
                </td>
            </tr>
            <tr>
                <td>
                    <img src="{{ $data['Gambar2'] }}" alt="" style="background-image: url('img/kepaladepan.png')">
                </td>
            </tr>
            <tr>
                <td>
                    <img src="{{ $data['Gambar3'] }}" alt="" style="background-image: url('img/kepalasamping.png')">
                </td>
            </tr>
            <tr>
                <td>
                    <img src="{{ $data['Gambar4'] }}" alt="" style="background-image: url('img/tanganatas.png')">
                </td>
            </tr>
            <tr>
                <td>
                    <img src="{{ $data['Gambar5'] }}" alt="" style="background-image: url('img/tanganbawah.png')">
                </td>
            </tr>
            <tr>
                <td>
                    <img src="{{ $data['Gambar7'] }}" alt="" style="background-image: url('img/odon.png')" width="690px" height="370px">
                    {{-- <img src="{{ $data['Gambar7'] }}" alt="" style="background-image: url('img/odon.png')"> --}}
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <tr>
            <td style="text-align: center; width: 50%">
                <span>Saya menyatakan bahwa lokasi operasi yang telah ditetapkan pada diagram adalah benar</span> <br>
                <span>Nama dan Tanda Tangan</span> <br>
                <img src="{{ $data['TTDDokter3'] }}" alt="" width="200px" height="100px"> <br>
                <span>{{isset($data['ttdPasien1']) ? $data['ttdPasien1'] : ''}}</span>
            </td>
            <td style="text-align: center; width: 50%;">
                <span>Garut, {{isset($data['tanggal']) ? $data['tanggal'] : ''}}</span> <br>
                <span>Nama dan Tanda Tangan</span> <br>
                @if(array_key_exists('CBDokter1', $data))
                    <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $data['CBDokter1']['label'] }}" style="align-items: center;"><br />
                @else
                    <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ isset($data['CBDokter1']) ? $data['CBDokter1'] : '' }}" style="align-items: center;"><br />
                @endif
                @if(array_key_exists('CBDokter1', $data))
                    <P style="text-align: center;">{{isset($data['CBDokter1']['label']) ? $data['CBDokter']['label'] : ''}}</P>
                @else
                    <P style="text-align: center;">{{isset($data['CBDokter1']) ? $data['CBDokter'] : ''}}</P>
                @endif
                <span>{{isset($data['ttdPasien1']) ? $data['ttdPasien1'] : ''}}</span>
            </td>
        </tr>
    </table>
</body>

</html>

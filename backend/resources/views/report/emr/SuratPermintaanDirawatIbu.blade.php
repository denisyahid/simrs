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

        body{
            font-size: 11pt;
        }
    </style>
</head>

<body>
    @php
        use Carbon\Carbon;
    @endphp
    <table width="100%" cellspacing="0" cellpadding="0"
        style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr colspan="3">
            <td style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;">
                RSUD BALI MANDARA
            </td>
            <td colspan="2"
                style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                RM 4/SK/01
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0"
        style="border: 1px solid black; border-bottom: none;border-collapse: collapse">
        <tr>
            <td style="border: 1px solid black; width: 10%;">
                <img src="{{ 'img/provinsi-rs.svg' }}" width="100px" height="100px" style="display: block;">
            </td>

            <td style="border: 1px solid black; font-weight: bold; text-align: center; width: 60%;">
                <h3>PEMERINTAH PROVINSI BALI <br>RSUD BALI MANDARA <br>Jalan By Pass Ngurah Rai No. 548, Garut - Bali
                </h3>
            </td>
            <td style="border: 1px solid black; width: 10%;">
                <img src="{{ 'img/logo-rs.png' }}" width="100px" height="100px" style="display: block;">
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-collapse: collapse">
        <tr>
            <div style=" font-weight: bold; text-align: center;">
                SURAT PERMINTAAN DIRAWAT<br>
                <span style="font-size:13pt">NOMOR : {{ isset($data['nosurat']) ? $data['nosurat'] : '-' }}</span>
            </div>
        </tr>

        <tr>
            <div style="padding-left: 15px; padding-top: 10px;">
                PERMINTAAN DIRAWAT
            </div>
            <div style="margin-left: 15px; font-weight: bold;">
                Dari : {{ is_array($data['ruangan']) ? $data['ruangan']['label'] : $data['ruangan'] }}
            </div>
            <div style="margin-left: 15px; padding-bottom: 5px;">
                <span>Kepada Yth.Petugas Admission</span> <br> <span>Mohon ditindak lanjuti permintaan dirawat inap
                    pasien</span>
            </div>
            <div style="margin-left: 30px;">
                <div style="margin-bottom: 10px;">
                    No Rekam Medis : {{ isset($data['norm']) ? $data['norm'] : '-' }}
                </div>
                <div style="margin-bottom: 10px;">
                    Nama Pasien : {{ isset($data['namaPasien']) ? $data['namaPasien'] : '-' }}
                </div>
                <div style="margin-bottom: 10px;">
                    Tanggal Lahir/Umur
                    <?php
                    function hitungUmur($tanggalLahir, $tanggalReferensi = null)
                    {
                        if (!$tanggalLahir) {
                            return " ";
                        }

                        $tglLahir = new DateTime($tanggalLahir);
                        $tglReferensi = $tanggalReferensi ? new DateTime($tanggalReferensi) : new DateTime();
                        $umur = $tglReferensi->diff($tglLahir);
                        return $umur->y . 'thn ' . $umur->m . 'bln ' . $umur->d . 'hr';
                    }

                    $tglLahir = isset($data['tgllahir']) ? $data['tgllahir']->tgllahir : '';
                    $tglRawatInap = isset($data['tanggalRawatInap']) ? $data['tanggalRawatInap'] : null;

                    $umur = isset($data['tgllahir']) ? hitungUmur($data['tgllahir']->tgllahir, $tglRawatInap) : '';

                    echo ': ' . $tglLahir . ' / ' . $umur;
                    ?>
                </div>
                <div style="margin-bottom: 10px;">
                    Diagnosa : {{ isset($data['diagnosa']) ? $data['diagnosa'] : '-' }}
                </div>
                <div style="margin-bottom: 10px;">
                    Rencana Tindakan : {{ isset($data['rencanaTindakan']) ? $data['rencanaTindakan'] : '-' }}
                </div>
                <div style="margin-bottom: 10px;">
                    Tanggal Tindakan :
                    {{ isset($data['tanggalTindakan']) ? Carbon::parse($data['tanggalTindakan'])->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') : '-' }}
                </div>
                <div style="margin-bottom: 10px;">
                    Tanggal Rawat Inap :
                    {{ isset($data['tanggalRawatInap']) ? Carbon::parse($data['tanggalRawatInap'])->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') : '-' }}
                </div>
                <div style="margin-bottom: 10px;">
                    Dokter yang Merawat (DPJP) : {{ isset($data['DDDokter']) ? $data['DDDokter']['label'] : '-' }}
                </div>
                <div style="margin-bottom: 10px; display: flex;">
                    @if (isset($data['persiapan']))
                        @switch($data['persiapan'])
                            @case('Puasa')
                                <div class="text-left checkbox-wrapper">
                                    Persiapan :
                                    <input type="checkbox" id="terms" class="checkbox" checked>
                                    <label class="checkbox-label" for="terms">{{ $data['persiapan'] }}</label>

                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Diet</label>

                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Lainnya</label>

                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Tidak Perlu</label>
                                </div>
                            @break

                            @case('Diet')
                                <div class="text-left checkbox-wrapper">
                                    Persiapan :
                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Puasa</label>

                                    <input type="checkbox" id="terms" class="checkbox" checked>
                                    <label class="checkbox-label" for="terms">{{ $data['persiapan'] }}</label>

                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Lainnya</label>

                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Tidak Perlu</label>
                                </div>
                            @break

                            @case('Lainnya')
                                <div class="text-left checkbox-wrapper">
                                    Persiapan :
                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Puasa</label>

                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Diet</label>

                                    <input type="checkbox" id="terms" class="checkbox" checked>
                                    <label class="checkbox-label" for="terms">{{ $data['persiapan'] }}</label>
                                    : {{ isset($data['ketPersiapanLainnya']) ? $data['ketPersiapanLainnya'] : '-' }}

                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Tidak Perlu</label>
                                </div>
                            @break

                            @case('Tidak Perlu Persiapan')
                                <div class="text-left checkbox-wrapper">
                                    Persiapan :

                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Puasa</label>

                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Diet</label>

                                    <input type="checkbox" id="terms" class="checkbox">
                                    <label class="checkbox-label" for="terms">Lainnya</label>

                                    <input type="checkbox" id="terms" class="checkbox" checked>
                                    <label class="checkbox-label" for="terms">{{ $data['persiapan'] }}</label>
                                </div>
                            @break

                            @default
                            @break
                        @endswitch
                    @endif
                </div>

            </div>

        </tr>

        <tr>
            <div style="text-align: center;">
                <div>Garut, {{ Carbon::parse($data['tanggal'])->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}</div>
                <img src="data:image/png;base64, {!! $tte !!}" style="height: 70px;">
                <div>{{ isset($data['DDDokter']) ? $data['DDDokter']['label'] : '-' }}</div>
                <!-- <div>
                    NIP :
                    @if (isset($data['nip']->nip))
                        {{ $data['nip']->nip }}
                    @else
                        {{ '-' }}
                    @endif
                </div> -->
            </div>
        </tr>
        <div style="font-weight: bold; margin-top: 10px; margin-left: 15px; page-break-after: auto;">
            KEPASTIAN DIRAWAT
        </div>
        <div style="margin-left: 15px; margin-top: 10px;">
            Kepada Yth.Dokter {{ isset($data['CBDokter']) ? $data['CBDokter'] : '-' }}
        </div>
        <div style="margin-left: 15px; margin-top: 10px; margin-bottom: 10px;">
            @if (isset($data['ruanganKeterangan']))
                @switch($data['ruanganKeterangan'])
                    @case('Telah Mendapatkan')
                        Pasien diatas <span style="font-weight: bold;">Telah Mendapatkan</span> / <s
                            style="font-weight: bold;">Belum Mendapatkan</s> Ruang Rawat Inap
                    @break

                    @case('Belum Mendapatkan')
                        Pasien diatas <s style="font-weight: bold;">Telah Mendapatkan</s> / <span
                            style="font-weight: bold;">Belum
                            Mendapatkan</span> Ruang Rawat Inap
                    @break

                    @default
                @endswitch
            @endif
        </div>
        <div style=" margin-left: 30px; margin-bottom: 10px;">
            Ruang Rawat Inap:
            {{ isset($data['ruanganRawatInap']) ? $data['ruanganRawatInap'] : '-' }}
            {{-- {{ isset($data['ruanganRawatInap']['label']) ? $data['ruanganRawatInap']['label'] : '-' }} --}}
        </div>
        <div style="margin-bottom: 10px; margin-left: 30px;">
            @if (isset($data['pembayaran']))
                @switch($data['pembayaran'])
                    @case('Umum')
                        Cara Pembayaran :
                        <input type="checkbox" id="terms" class="checkbox" checked>
                        <label class="checkbox-label" for="terms">Umum</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">BPJS</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">BPJS PBI</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">BPJS NON PBI</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">Asuransi Lainnya</label>
                    @break

                    @case('BPJS PBI')
                        Cara Pembayaran :
                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">Umum</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">BPJS</label>

                        <input type="checkbox" id="terms" class="checkbox" checked>
                        <label class="checkbox-label" for="terms">BPJS PBI</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">BPJS NON PBI</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">Asuransi Lainnya</label>
                    @break

                    @case('BPJS NON PBI')
                        Cara Pembayaran :
                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">Umum</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">BPJS</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">BPJS PBI</label>

                        <input type="checkbox" id="terms" class="checkbox" checked>
                        <label class="checkbox-label" for="terms">BPJS NON PBI</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">Asuransi Lainnya</label>
                    @break

                    @case('ASURANSI LAINNYA')
                        Cara Pembayaran :
                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">Umum</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">BPJS</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">BPJS PBI</label>

                        <input type="checkbox" id="terms" class="checkbox">
                        <label class="checkbox-label" for="terms">BPJS NON PBI</label>

                        <input type="checkbox" id="terms" class="checkbox" checked>
                        <label class="checkbox-label" for="terms">Asuransi Lainnya :</label>
                        <label class="checkbox-label"
                            for="terms">{{ isset($data['ketPembayaran']) ? $data['ketPembayaran'] : '-' }}</label>
                    @break

                    @default
                @endswitch
            @endif
        </div>
        <div style="margin-left: 30px; margin-bottom: 10px;">
            Nama Penanggung Jawab : {{ isset($data['kepalaPenanggungJawab']) ? $data['kepalaPenanggungJawab'] : '-' }}
        </div>
        <div style="margin-left: 30px; margin-bottom: 10px;">
            Hubungan dengan Pasien : {{ isset($data['hubungan']) ? $data['hubungan'] : '-' }}
        </div>
        <div style="margin-left: 30px; margin-bottom: 10px;">
            Alamat: {{ isset($data['alamat']) ? $data['alamat'] : '-' }}
        </div>
        <div style="margin-left: 30px; margin-bottom: 10px;">
            Nomor Telepon: {{ isset($data['telepon']) ? $data['telepon'] : '-' }}
        </div>
        <div style="text-align: right; margin-right: 30px;">
            Garut,
            {{ isset($data['tanggalAdmission']) ? Carbon::parse($data['tanggalAdmission'])->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') : '-' }}
        </div>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td style="text-align: center;">
                    <span>Pasien / Penanggung Jawab</span>
                </td>
                <td style="text-align: center;">
                    <span>Petugas Admission</span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    @if (isset($data['TTDpasien']))
                        <img src="{{ $data['TTDpasien'] }}" style="height: 70px;">
                    @else
                        &nbsp;
                    @endif
                </td>
                <td style="text-align: center;">
                    {{-- @if (isset($data['TTDadmission']))
                        <img src="{{ $data['TTDadmission'] }}" style="height: 70px;">
                    @else
                        &nbsp;
                    @endif --}}
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ ($data[
                        'user_input'] ? $data['user_input']['namalengkap'] : '-')}}" style="height: 70px;"><
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <span>{{ isset($data['CBPasien']) ? $data['CBPasien'] : '-' }}</span>
                </td>
                <td style="text-align: center;">
                    <span>{{ isset($data['petugasAddmision']['label']) ? $data['petugasAddmision']['label'] : '-' }}</span>
                    {{-- <span>{{isset($data['user_input']) ? $data['user_input']['namalengkap'] : ''}}</span> --}}
                </td>
            </tr>
        </table>
    </table>
</body>

</html>

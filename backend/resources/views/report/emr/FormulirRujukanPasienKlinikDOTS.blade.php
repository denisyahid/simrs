@extends('template.layout-emr')
@section('title', 'Formulir Rujukan Pasien Ke Klinik DOTS Atau KTS/PDP')
@section('kode', 'RM.2D/ADM/00')
@section('page-style')
    <style>
        body,
        table,
        td {
            font-family: 'Open Sans', sans-serif !important;
        }

        .center {
            vertical-align: middle;
            text-align: center
        }

        .border {
            border: 1px solid black;
        }

        .table {
            width: 100%;
            border-collapse: collapse
        }

        .font {
            font-size: 9pt
        }

        .subJudul1 {
            vertical-align: middle;
            width: 25%;
        }

        .isi1 {
            width: 25%;
            padding-top: none;
        }

        .break {
            page-break-before: always;
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

    // dd($data);

@endphp

@section('content')
    <tr class="font">
        <td>
            <div style="padding: 6.5px">
                <table class="table">
                    <tr>
                        <td class="subJudul1">No. Registrasi TB atau HIV</td>
                        <td class="isi1">
                            : {{ isset($data['TBNoRegisORHIV']) ? $data['TBNoRegisORHIV'] : '-' }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="subJudul1">Tanggal Rujukan</td>
                        <td class="isi1">
                            : {{ isset($data['DTanggalRujukan']) ? convertToRegularDate($data['DTanggalRujukan']) : '-' }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="subJudul1">Institusi Pengiriman</td>
                        <td class="isi1">
                            : {{ isset($data['TBInstitusiPengiriman']) ? $data['TBInstitusiPengiriman'] : '-' }}
                        </td>
                        <td class="subJudul1">Institusi Yang Dituju</td>
                        <td class="isi1">
                            : {{ isset($data['TBInstitusiYangDituju']) ? $data['TBInstitusiYangDituju'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="subJudul1">Alamat</td>
                        <td class="isi1">
                            : {{ isset($data['TBAlamatIP']) ? $data['TBAlamatIP'] : '-' }}
                        </td>
                        <td class="subJudul1">Alamat</td>
                        <td class="isi1">
                            : {{ isset($data['TBAlamatIYD']) ? $data['TBAlamatIYD'] : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="subJudul1">No. Telp</td>
                        <td class="isi1">
                            : {{ isset($data['TBNoTLP_IP']) ? $data['TBNoTLP_IP'] : '-' }}
                        </td>
                        <td class="subJudul1">No. Telp</td>
                        <td class="isi1">
                            : {{ isset($data['TBNoTLP_IYD']) ? $data['TBNoTLP_IYD'] : '-' }}
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr class="font">
        <td>
            <div style="padding: 6.5px">
                <p style="font-weight: bold;margin-bottom: 0px">
                    Kepada Sejawat yang terhormat,<br>
                    Bersama ini kami sampaikan pasien/klien tersebut dibawah ini :
                </p>
                <table class="table">
                    <tr>
                        <td style="padding-left: 10px;padding-top: 5px;width: 33%">
                            Nama : <u>{{ isset($data['TBNamaPasien']) ? $data['TBNamaPasien'] : '-' }}</u>
                        </td>
                        <td style="padding-left: 10px;padding-top: 5px;width: 33%">
                            Usia : <u>{{ isset($data['TBUsiaPasien']) ? $data['TBUsiaPasien'] : '-' }}</u> Tahun
                        </td>
                        <td style="padding-left: 10px;padding-top: 5px;width: 33%">
                            Jenis Kelamin :
                            <table class="table">
                                <tr>
                                    <td style="width: 50%">
                                        <input type="checkbox"
                                            {{ isset($data['CBJenisKelaminPasien']) && $data['CBJenisKelaminPasien'] == 'Laki-laki' ? 'checked' : '' }} />
                                        <span style="font-size: 8pt;">L</span>
                                    </td>
                                    <td style="width: 50%">
                                        <input type="checkbox"
                                            {{ isset($data['CBJenisKelaminPasien']) && $data['CBJenisKelaminPasien'] == 'Perempuan' ? 'checked' : '' }} />
                                        <span style="font-size: 8pt;">P</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px;padding-top: 5px;width: 66%" colspan="2">
                            Alamat : <u>{{ isset($data['TAAlamatPasien']) ? $data['TAAlamatPasien'] : '-' }}</u>
                        </td>
                        <td style="padding-left: 10px;padding-top: 5px;width: 33%">
                            No. Telp :
                            <u>{{ isset($data['TBNomorTeleponPasien']) ? $data['TBNomorTeleponPasien'] : '-' }}</u>
                        </td>
                    </tr>
                </table>
                <p style="font-weight: bold;margin: 0px;margin-top: 10px">
                    Untuk dilakukan :
                </p>
                <table class="table">
                    <tr>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBKonselingHIV']) && $data['CBKonselingHIV'] == 'Konseling HIV' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Konseling HIV</span>
                        </td>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBDiagnosisTB']) && $data['CBDiagnosisTB'] == 'Diagnosis TB' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Diagnosis TB</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBTesHIV']) && $data['CBTesHIV'] == 'Tes HIV' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Tes HIV
                                @if (isset($data['CBTesHIVTandaTangan']))
                                    @if ($data['CBTesHIVTandaTangan'] == 'Tes HIV Sudah')
                                        (pasien telah/<s>belum</s> menandatangani pernyataan persetujuan*-terlampir)
                                    @elseif ($data['CBTesHIVTandaTangan'] == 'Tes HIV Belum')
                                        (pasien <s>telah</s>/belum menandatangani pernyataan persetujuan*-terlampir)
                                    @endif
                                @else
                                    -
                                @endif
                            </span>
                        </td>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBPengobatanTB']) && $data['CBPengobatanTB'] == 'Pengobatan TB' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Pengobatan TB</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBPerawatanDanPengobatanHIV']) && $data['CBPerawatanDanPengobatanHIV'] == 'Perawatan dan Pengobatan HIV' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Perawatan dan Pengobatan HIV</span>
                        </td>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBLainnyaUntukDilakukan']) && $data['CBLainnyaUntukDilakukan'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Lainnya
                                <u>{{ isset($data['TBLainnyaUntukDilakukan']) ? $data['TBLainnyaUntukDilakukan'] : '-' }}</u></span>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr class="font">
        <td>
            <div style="padding: 6.5px">
                <table class="table">
                    <tr>
                        <td colspan="2">
                            Dengan bahan pertimbangan kondisi pasien/klien saat ini :
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            Status HIV tanggal :
                            {{ isset($data['DStatusHIV']) ? convertToRegularDate($data['DStatusHIV']) : '-' }}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            @if (isset($data['CBHasilPos']))
                                hasil Pos/<s>Neg</s>*
                            @elseif(isset($data['CBNeg']))
                                <s>hasil Pos</s>/Neg*
                            @else
                                -
                            @endif
                        </td>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBDalamPengobatan']) && $data['CBDalamPengobatan'] == 'Dalam pengobatan TB, OAT kat' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Dalam pengobatan TB, OAT kat
                                <u>{{ isset($data['TBDalamPengobatan']) ? $data['TBDalamPengobatan'] : '-' }}</u></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBKotrimoksasol']) && $data['CBKotrimoksasol'] == 'Kotrimoksasol' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Kotrimoksasol</span>
                        </td>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBSuspekTB']) && $data['CBSuspekTB'] == 'Suspek TB yang masih dalam proses diagnosis' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Suspek TB yang masih dalam proses diagnosis</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBARV']) && $data['CBARV'] == 'ARV' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">ARV</span>
                        </td>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBSelesaiPengobatanTB']) && $data['CBSelesaiPengobatanTB'] == 'Selesai pengobatan TB' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Selesai pengobatan TB</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBKeadaanLain']) && $data['CBKeadaanLain'] == 'Keadaan lain yang perlu perhatian/catatan Klinis' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Keadaan lain yang perlu perhatian/catatan Klinis</span>
                        </td>
                        <td style="width: 50%">
                            <input type="checkbox"
                                {{ isset($data['CBAdaFaktorRisikoHIV']) && $data['CBAdaFaktorRisikoHIV'] == 'Ada faktor risiko HIV' ? 'checked' : '' }} />
                            <span style="font-size: 8pt;">Ada faktor risiko HIV</span>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr class="font">
        <td>
            <hr class="border">
            <hr class="border">
        </td>
    </tr>
    <tr class="font">
        <td>
            <div style="padding: 6.5px">
                <p style="font-weight: bold;margin: 0px">
                    Mohon umpan balik saudara dengan menggunakan formulir di bawah.<br>
                    Bila memerlukan penjelasan lebih lanjut silahkan hubungi kami pada alamat di atas.<br>
                    Terimakasih atas kerjasama yang diberikan
                </p>
                <table class="table">
                    <tr>
                        <td style="width: 70%;vertical-align: bottom">
                            *coret yang tidak perlu
                        </td>
                        <td style="width: 30%;vertical-align: top;text-align: center">
                            Garut, {{ isset($data['DTttd']) ? convertToRegularDate($data['DTttd']) : '-' }}<br>
                            Hormat Kami,<br><br>
                            <u>{{ isset($data['TBHormatKami']) ? $data['TBHormatKami'] : '-' }}</u>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr class="font">
        <td>
            <hr class="border">
            <div class="break"></div>
            <p style="margin: 0px;margin-left: 6.5px"> Izin Klien untuk pemberitahuan hasil tes kepada petugas TB</p>
            <hr class="border">
        </td>
    </tr>
    <tr class="font">
        <td>
            <div style="padding: 6.5px">
                <p style="margin: 0px">
                    Saya yang bertandatangan di bawah ini,
                </p>
                <table class="table">
                    <tr>
                        <td colspan="2">
                            Nama &nbsp;&nbsp;: <u>{{ isset($data['TBNamaPasien2']) ? $data['TBNamaPasien2'] : '-' }}</u>
                            @if (isset($data['CBJenisKelaminPasien2']))
                                @if ($data['CBJenisKelaminPasien2'] == 'Laki-laki')
                                    ( L / <s>P</s> )
                                @elseif ($data['CBJenisKelaminPasien2'] == 'Perempuan')
                                    ( <s>L</s> / P )
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Alamat : <u>{{ isset($data['TAAlamatPasien2']) ? $data['TAAlamatPasien2'] : '-' }}</u>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p style="margin: 0px;margin-top: 5px">
                                Menyatakan
                                @if (isset($data['CBMemberikanIzinHasilTes']))
                                    @if ($data['CBMemberikanIzinHasilTes'] == 'Memberikan izin')
                                        memberikan izin / <s>tidak memberikan izin</s>*
                                    @elseif ($data['CBMemberikanIzinHasilTes'] == 'Tidak memberikan izin')
                                        <s>memberikan izin</s> / tidak memberikan izin*
                                    @endif
                                @else
                                    -
                                @endif
                                konselor untuk menyampaikan informasi hasil tes HIV kepada petugas TB yang merujuk
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70%;vertical-align: bottom">
                            *coret yang tidak dipilih
                        </td>
                        <td style="width: 30%;vertical-align: top;text-align: center">
                            Nama dan tanda tangan klien<br>
                            <img src="{{ isset($data['TTDKlien']) ? $data['TTDKlien'] : '-' }}" width="150px"
                                height="150px">
                            <br><u>{{ isset($data['TBNamaKlien']) ? $data['TBNamaKlien'] : '-' }}</u>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
@endsection
@section('content2')
    <tr>
        <td>
            <div style="height: 30px">
        </td>
    </tr>
    <tr>
        <td style="padding:5px; text-align: right;padding-bottom: 0px">
            @yield('kode')
        </td>
    </tr>
    <tr>
        <td>
            <hr style="border: 1px dashed black">
        </td>
    </tr>
    <tr class="font">
        <td style="text-align: center">
            <p style="margin-bottom: 0px">FORMULIR JAWABAN RUJUKAN DARI KLINIK DOTS ATAU KLINIK KTS/PDP*</p>
            (Untuk diisi dan dikembalikan ke Unit Pengiriman)
        </td>
    </tr>
    <tr class="font">
        <td>
            <p style="margin: 0px">
                Kepada sejawat yang terhormat,<br>
                Kami sampaikan bahwa, klien/pasien :
            </p>
            <table class="table">
                <tr>
                    <td style="padding-left: 10px;width: 70%">
                        <table class="table">
                            <td style="width: 30%">
                                Nama
                            </td>
                            <td style="width: 70%">
                                : <u>{{ isset($data['TBNamaPasienFormulir']) ? $data['TBNamaPasienFormulir'] : '-' }}</u>
                                &nbsp;&nbsp;&nbsp;Usia :
                                <u>{{ isset($data['TBUsiaPasienFormulir']) ? $data['TBUsiaPasienFormulir'] : '-' }}</u> Thn
                            </td>
                        </table>
                    </td>
                    <td style="padding-left: 10px;width: 30%">
                        <table class="table">
                            <td style="width: 30%">
                                Jenis Kelamin :
                            </td>
                            <td style="width: 70%">
                                <table class="table">
                                    <tr>
                                        <td style="width: 50%">
                                            <input type="checkbox"
                                                {{ isset($data['CBJenisKelaminPasienFormulir']) && $data['CBJenisKelaminPasienFormulir'] == 'Laki-laki' ? 'checked' : '' }} />
                                            <span style="font-size: 8pt;">L</span>
                                        </td>
                                        <td style="width: 50%">
                                            <input type="checkbox"
                                                {{ isset($data['CBJenisKelaminPasienFormulir']) && $data['CBJenisKelaminPasienFormulir'] == 'Perempuan' ? 'checked' : '' }} />
                                            <span style="font-size: 8pt;">P</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;width: 30%">
                        <table class="table">
                            <td style="width: 30%">
                                Alamat
                            </td>
                            <td style="width: 70%">
                                : {{ isset($data['TAAlamatPasienFormulir']) ? $data['TAAlamatPasienFormulir'] : '-' }}
                            </td>
                        </table>
                    </td>
                </tr>
            </table>
            <p style="margin: 0px;margin-top: 10px;margin-bottom: 10px">Dengan hasil :</p>
            <table class="table">
                <tr>
                    <td style="width: 33%">
                        <input type="checkbox"
                            {{ isset($data['CBKonselingPraTes']) && $data['CBKonselingPraTes'] == 'Konseling pra tes' ? 'checked' : '' }} />
                        <span style="font-size: 8pt;">Konseling pra tes</span>
                    </td>
                    <td style="width: 33%" class="center">
                        {{ isset($data['DKonselingPraTes']) ? convertToRegularDate($data['DKonselingPraTes']) : '-' }}
                    </td>
                    <td style="width: 33%;vertical-align: top" rowspan="6">
                        Hasil :
                        <table class="table">
                            <td style="width: 33%">
                                <input type="checkbox"
                                    {{ isset($data['CB_r']) && $data['CB_r'] == 'R' ? 'checked' : '' }} />
                                <span style="font-size: 8pt;">R</span>
                            </td>
                            <td style="width: 33%">
                                <input type="checkbox"
                                    {{ isset($data['CB_nr']) && $data['CB_nr'] == 'NR' ? 'checked' : '' }} />
                                <span style="font-size: 8pt;">NR</span>
                            </td>
                            <td style="width: 33%">
                                <input type="checkbox"
                                    {{ isset($data['CB_i']) && $data['CB_i'] == 'I' ? 'checked' : '' }} />
                                <span style="font-size: 8pt;">I</span>
                            </td>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%">
                        <input type="checkbox"
                            {{ isset($data['CBTesHIVFormulir']) && $data['CBTesHIVFormulir'] == 'Tes HIV' ? 'checked' : '' }} />
                        <span style="font-size: 8pt;">Tes HIV</span>
                    </td>
                    <td style="width: 33%" class="center">
                        {{ isset($data['DTesHIVFormulir']) ? convertToRegularDate($data['DTesHIVFormulir']) : '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%">
                        <input type="checkbox"
                            {{ isset($data['CBKonselingPosTestFormulir']) && $data['CBKonselingPosTestFormulir'] == 'Konseling pos test' ? 'checked' : '' }} />
                        <span style="font-size: 8pt;">Konseling pos test</span>
                    </td>
                    <td style="width: 33%" class="center">
                        {{ isset($data['DKonselingPosTestFormulir']) ? convertToRegularDate($data['DKonselingPosTestFormulir']) : '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%">
                        <input type="checkbox"
                            {{ isset($data['CBDiagnosisTBFormulir']) && $data['CBDiagnosisTBFormulir'] == 'Diagnosis TB' ? 'checked' : '' }} />
                        <span style="font-size: 8pt;">Diagnosis TB</span>
                    </td>
                    <td style="width: 33%" class="center">
                        {{ isset($data['DDiagnosisTBFormulir']) ? convertToRegularDate($data['DDiagnosisTBFormulir']) : '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%">
                        <input type="checkbox"
                            {{ isset($data['CBLainnyaFormulir']) && $data['CBLainnyaFormulir'] == 'Lainnya' ? 'checked' : '' }} />
                        <span style="font-size: 8pt;">Lainnya
                            <u>{{ isset($data['TBLainnyaFormulir']) ? $data['TBLainnyaFormulir'] : '-' }}</u></span>
                    </td>
                    <td style="width: 33%" class="center">
                        {{ isset($data['DLainnyaFormulir']) ? convertToRegularDate($data['DLainnyaFormulir']) : '-' }}
                    </td>
                </tr>
            </table>
            <div style="margin: 10px"></div>
            <table class="table">
                <tr>
                    <td style="width: 33%">
                        <input type="checkbox"
                            {{ isset($data['CBPengobatanTBFormulir']) && $data['CBPengobatanTBFormulir'] == 'Pengobatan TB' ? 'checked' : '' }} />
                        <span style="font-size: 8pt;">Pengobatan TB</span>
                    </td>
                    <td style="width: 33%" class="center">
                        {{ isset($data['DPengobatanTBFormulir']) ? convertToRegularDate($data['DPengobatanTBFormulir']) : '-' }}
                    </td>
                    <td style="width: 33%">
                        {{ isset($data['TBPanduanOAT']) ? $data['TBPanduanOAT'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%">
                        <input type="checkbox"
                            {{ isset($data['CBPengobatanPencegahanKotriFormulir']) && $data['CBPengobatanPencegahanKotriFormulir'] == 'Pengobatan Pencegahan Kotrimoksasol' ? 'checked' : '' }} />
                        <span style="font-size: 8pt;">Pengobatan Pencegahan Kotrimoksasol</span>
                    </td>
                    <td style="width: 33%" class="center">
                        {{ isset($data['DPengobatanPencegahanKotriFormulir']) ? convertToRegularDate($data['DPengobatanPencegahanKotriFormulir']) : '-' }}
                    </td>
                    <td style="width: 33%">
                        {{ isset($data['TBDosisFormulir']) ? $data['TBDosisFormulir'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%">
                        <input type="checkbox"
                            {{ isset($data['CBTerapiARV']) && $data['CBTerapiARV'] == 'Terapi ARV' ? 'checked' : '' }} />
                        <span style="font-size: 8pt;">Terapi ARV</span>
                    </td>
                    <td style="width: 33%" class="center">
                        {{ isset($data['DTerapiARV']) ? convertToRegularDate($data['DTerapiARV']) : '-' }}
                    </td>
                    <td style="width: 33%">
                        {{ isset($data['TBPanduanARVFormulir']) ? $data['TBPanduanARVFormulir'] : '-' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="width: 66%"></td>
                    <td style="width: 33%;text-align: center">
                        Garut,
                        {{ isset($data['DTttdFormulir']) ? convertToRegularDate($data['DTttdFormulir']) : '-' }}<br>
                        Hormat Kami,<br>
                        <img src="{{ isset($data['TTDInstasiUnit']) ? $data['TTDInstasiUnit'] : '-' }}" width="150px"
                            height="150px">
                        <br><br>
                        <u>{{ isset($data['TBInstansiUnit']) ? $data['TBInstansiUnit'] : '-' }}</u>
                        <br>Instansi dan Unit :
                        <br>Alamat : <u>{{ isset($data['TBAlamatIU']) ? $data['TBAlamatIU'] : '-' }}</u>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection

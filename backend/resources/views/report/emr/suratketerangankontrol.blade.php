@extends('template.layout')
@section('title', 'Surat Keterangan Kontrol')
@section('about', 'Surat Keterangan Kontrol')

@section('content')

    <style type="text/css" media="print">
        @media print {
            @page {
                size: auto;
                margin: 0;
                /* size: portrait; */
            }

            footer {
                display: none
            }

            header {
                display: none
            }

            body {
                -webkit-print-color-adjust: exact !important;
            }
        }
    </style>

    @php
        //$data = $dataReport['identitas'];
        //dd($data);
    @endphp



    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td align="center">
                        <span style="font-size: 14pt;font-weight: 600;text-decoration: underline;" color="#000000">
                            SURAT KETERANGAN KONTROL
                        </span>
                        <br>
                        <span style="font-size: 12pt;font-weight: 600;" color="#000000">
                            {{ $dataReport['identitas']->nosurat }}

                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:10px">
            <table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
                <tr>
                    <td width="100%" colspan="3">
                        <span style="font-size: 11pt;" color="#000000">
                            Saya yang bertanda tanda tangan dibawah ini menyatakan pasien di bawah ini:
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="100%" height="20" colspan="3"></td>
                </tr>
                <tr>
                    <td width="25%">
                        <span style="font-size: 11pt;" color="#000000">Nama</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 11pt;" color="#000000">:</span>
                    </td>
                    <td width="74%">
                        <span style="font-size: 11pt;" color="#000000">
                            {{ $dataReport['identitas']->namapasien }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <span style="font-size: 11pt;" color="#000000">No. Rekam Medis</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 11pt;" color="#000000">:</span>
                    </td>
                    <td width="74%">
                        <span style="font-size: 11pt;" color="#000000">{{ $dataReport['identitas']->nocm }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="25%" class="ontop">
                        <span style="font-size: 11pt;" color="#000000">Diagnosa</span>
                    </td>
                    <td width="1%" class="ontop">
                        <span style="font-size: 11pt;" color="#000000">:</span>
                    </td>
                    <td width="74%">
                        <span style="font-size: 11pt;" color="#000000">
                            {{ $dataReport['identitas']->diagnosa }}</span>
                    </td>
                </tr>
                <tr>
                    @php
                        $umur = App\Http\Controllers\EMR\ReportEMRCtrl::getUmurna($dataReport['identitas']->tgllahir, $dataReport['identitas']->tglregistrasi);
                    @endphp
                    <td width="25%">
                        <span style="font-size: 11pt;" color="#000000">Umur</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 11pt;" color="#000000">:</span>
                    </td>
                    <td width="74%">
                        <span style="font-size: 11pt;" color="#000000">
                            {{ $umur['umurtahun'] . ' ' . $umur['umurbulan'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="25%" class="ontop">
                        <span style="font-size: 11pt;" color="#000000">Indikasi kembali ke RS</span>
                    </td>
                    <td width="1%" class="ontop">
                        <span style="font-size: 11pt;" color="#000000">:</span>
                    </td>
                    <td width="74%">
                        <span style="font-size: 11pt;" color="#000000">
                            {{ $dataReport['identitas']->indikasi }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="25%" class="ontop">
                        <span style="font-size: 11pt;" color="#000000">Alamat</span>
                    </td>
                    <td width="1%" class="ontop">
                        <span style="font-size: 11pt;" color="#000000">:</span>
                    </td>
                    <td width="74%">
                        <span style="font-size: 11pt;" color="#000000">
                            {{ $dataReport['identitas']->alamatlengkap }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="100%" height="20" colspan="3"></td>
                </tr>
                <tr>
                    <td width="100%" colspan="3">
                        <span style="font-size: 11pt;" color="#000000">
                            Masih butuh penanganan lebih lanjut di Rumah Sakit pada,
                            <b>{{ App\Traits\Valet::getDateIndo(explode(' ', $dataReport['identitas']->tglkontrol)[0]) }}
                                {{ explode(' ', $dataReport['identitas']->tglkontrol)[1] }}</b>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="100%" colspan="3">
                        <span style="font-size: 11pt;" color="#000000">
                            Demikian saya sampaikan, untuk dapat dipergunakan seperlunya.
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:25px">
            <table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
                <tr>
                    <td width="50%"></td>
                    <td width="50%" align="center">
                        <span style="font-size: 11pt;" color="#000000">{!! $profile->namakota !!},
                            {{ App\Traits\Valet::getDateIndo(date('Y-m-d', strtotime($dataReport['identitas']->tglregistrasi))) }}</span>
                        <br><br>
                        <span style="font-size: 11pt;" color="#000000">Dokter Pemeriksa</span>
                    </td>
                </tr>
                <tr>
                    @if ($dataReport['ttdimg'])
                        <td width="50%"></td>
                        <td width="50%" style="text-align: center;border: none;">
                            <div style="height:140px">
                                <img src="{{ $dataReport['ttdimg'] }}" width="170" height="140" alt="TTD" />
                            </div>
                        </td>
                    @else
                        <td width="50%" height="100"></td>
                        <td width="50%" height="100"></td>
                    @endif
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%" align="center">
                        <span style="font-size: 11pt;font-weight:bold;text-decoration: underline;"
                            color="#000000">{{ $dataReport['identitas']->dokterdpjp }}</span>
                        <br>
                        <span style="font-size: 11pt;" color="#000000">
                            {{ $dataReport['identitas']->nosip }}</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

@endsection

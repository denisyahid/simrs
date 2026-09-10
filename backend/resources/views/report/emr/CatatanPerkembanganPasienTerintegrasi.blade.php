@extends('template.' . $header_use)
@section('title', 'EMR')
@section('koderme', 'XXX.XX/FORM/X/RMIK/2023/Rev. XX')
@section('about', 'CATATAN PERKEMBANGAN PASIEN TERINTEGRASI')
@push('style')
    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
            /* Border untuk elemen table */
        }

        .table th {
            border: 1px solid black;
            /* Border untuk seluruh sel dalam tabel */
            padding: 4px;
            font-size: 10pt;
        }

        .table td {
            border: 1px solid black;
            /* Border untuk seluruh sel dalam tabel */
            padding: 4px;
            font-size: 8pt;
            vertical-align: top;
        }

        .handover tr td{
            border: none !important;
        }

        .table td p {
            vertical-align: top;
        }

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  background-color: var(--white);
  border-color: var(--fade-grey-dark-2) !important;
}

.tg-card {
  background-color: #feffed;
}

.is-dark {
  .tg {
    background-color: var(--dark-sidebar-light-6)
  }

  .tg-card {
    background-color: var(--dark-sidebar-light-6)
  }
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  border-color: var(--fade-grey-dark-3) !important;
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top
}

.scroll-container-rev {
  height: 1000px;
  overflow: auto;
}

@media (max-width: 1144px) {

  .table-tg {
    width: 150%;
  }
}
    </style>
@endpush
@section('content')
    <table width="100%" cellspacing="0" class="table tg">


              <thead class="tg">
                <tr>
                  <th class="tg-0lax text-center font-bold">Tanggal/Jam</th>
                  <th class="tg-0lax text-center">Catatan Perkembangan Pasien Terintegrasi</th>
                  <th class="tg-0lax text-center">Intruksi PPA</th>
                  <th class="tg-0lax text-center">Verifikasi DPJP</th>

                </tr>
              </thead>

        <tbody>
            @foreach ($data['details'] as $item)
                @if ($item['flag'] != 'gizi')
                    <tr>

                        <td style="width:15%">
                            <span class="mb-2">{{ date('Y-m-d H:i',strtotime($item['tgl'])) }}</span><br>
                            <span>{{ $item['tenagaMedis'] ? $item['tenagaMedis']['label'] : '-' }}</span>

                        </td>
                        <td>
                            <table class="tg">
                                <tr>
                                    <td width="5%"
                                        style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">S
                                    </td>
                                    <td>
                                        {{ $item['S'] ?? '' }}
                                    </td>

                                </tr>
                                <tr>
                                    <td width="5%"
                                        style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">O
                                    </td>
                                    <td>
                                        {{ $item['O'] ?? '' }}
                                    </td>

                                </tr>
                                <tr>
                                    <td width="5%"
                                        style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">A
                                    </td>
                                    <td>
                                        {{ $item['A'] ?? '' }}
                                    </td>
                                    {{-- @if ($item['flag'] == 'profesi lain')
                                        <td>
                                            {{ $item['A'] ?? '' }}
                                        </td>
                                    @endif
                                    @if ($item['flag'] == 'dokter')
                                        <td>
                                            <div class="columns is-multiline">
                                                <div class="column is-12">
                                                    <span style="font-size:9pt;font-weight:bold">Diagnosis ICD 10</span>
                                                    <div style="overflow-y:auto;" class="mt-1">
                                                        <table class="tg" style="width:100% !important">
                                                            <thead>
                                                                <tr>

                                                                    <th class="td-fkprj" width="23%"
                                                                        style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                                                        Jenis
                                                                    </th>
                                                                    <th class="td-fkprj" width="25%"
                                                                        style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                                                        Diagnosa
                                                                        Dokter
                                                                    </th>
                                                                    <th class="td-fkprj"
                                                                        style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                                                        ICD
                                                                        10
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($item['diagnosaDokter'] as $itemsss)
                                                                    <tr>
                                                                        <td class="tg-0lax">
                                                                            <div class="column p-1">
                                                                                {{ isset($itemsss['jenisDiagnosa']) ? $itemsss['jenisDiagnosa']['label'] : '' }}
                                                                            </div>
                                                                        </td>
                                                                        <td class="tg-0lax">
                                                                            <div class="column pt-3 pb-0">
                                                                                {{ $itemsss['keterangan'] ?? '' }}

                                                                            </div>
                                                                        </td>
                                                                        <td class="tg-0lax">
                                                                            <div class="column p-1">
                                                                                {{ isset($itemsss['diagnosaa'] )? $itemsss['diagnosaa']['label'] : '' }}

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <span style="font-size:9pt;font-weight:bold">Diagnosis ICD 9</span>
                                                    <div style="overflow-y:auto;" class="mt-1">
                                                        <table class="tg" width="100%">
                                                            <thead>
                                                                <tr>


                                                                    <th class="td-fkprj" width="40%"
                                                                        style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                                                        Keterangan
                                                                    </th>
                                                                    <th class="td-fkprj"
                                                                        style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                                                        ICD
                                                                        9
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($item['diagnosaDokter9'] as $itemsss)
                                                                    <tr>

                                                                        <td class="tg-0lax">
                                                                            <div class="column pt-3 pb-0">
                                                                                {{ $itemsss['keterangan'] ?? '' }}

                                                                            </div>
                                                                        </td>
                                                                        <td class="tg-0lax">
                                                                            <div class="column p-1">
                                                                                {{ isset($itemsss['diagnosaa']) ? $itemsss['diagnosaa']['label'] : '' }}

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                    @if ($item['flag'] == 'perawat')
                                        <td>
                                            <div class="column">
                                                <span style="font-size:11pt;font-weight:bold">Diagnosis Keperawatan</span>
                                                <div class="mt-1">
                                                    <table class="tg">
                                                        <thead>
                                                            <tr>
                                                                <th class="td-fkprj" width="50%"
                                                                    style="vertical-align:inherit;text-align: center;">
                                                                    Diagnosa
                                                                    Keperawatan
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($item['diagnosaKep'] as $item2)
                                                                <tr>
                                                                    <td class="tg-0lax">
                                                                        <div class="column p-1">
                                                                            {{ isset($item2['diagnosaKeperawatan']) ? $item2['diagnosaKeperawatan']['label'] : '-' }}
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    @endif --}}
                                </tr>
                                <tr>
                                    <td width="5%"
                                        style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">P
                                    </td>
                                    <td>
                                        {{ $item['P'] ?? '' }}

                                        {{-- <div class="column">
                                            @if ($item['flag'] == 'perawat') >
                                                <span style="font-size:11pt;font-weight:bold">Tujuan Kriteria (SLKI) </span>
                                                <div class="mt-1">
                                                    <table class="tg">
                                                    <thead>
                                                        <tr>

                                                        <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">
                                                            Tujuan Keperawatan & Intervensi
                                                        </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($item['tujuanKep'] as $item2)
                                                        <tr>
                                                        <td class="tg-0lax">
                                                            <div class="column p-1">
                                                            {{isset( $item2['tujuanKeperawatan'] )? $item2['tujuanKeperawatan']['label'] : '-' }}
                                                            </div>
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                        <td class="tg-0lax">
                                                            <div class="column p-1">
                                                            {{isset( $item2['intervensiKeperawatan']) ? $item2['intervensiKeperawatan']['label'] : '-' }}
                                                            </div>
                                                        </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    </table>
                                                </div> 
                                            @endif
                                            </div> --}}
                                    </td>

                                </tr>
                                @if (isset($item['handover']))
                                @if($item['handover'] == true)
                                <tr>
                                    <td width="5%"
                                        style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">
                                    </td>
                                    <td>
                                        <table class="handover">
                                            <tr>
                                                <td colspan="2">
                                                    <span> Handover Shift Rawat Inap</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" align="center">
                                                    <span> Pemberi Informasi</span> <br>
                                                    <img
                                                        src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ ($item['petugasPemberi'] ? $item['petugasPemberi']['label'] : '-')}}"><br>
                                                    <span> {{ date('Y-m-d H:i:s', strtotime($item['tanggalPemberi'])) ?? '' }}</span> <br>
                                                    <span> {{ $item['petugasPemberi'] ? $item['petugasPemberi']['label'] : '' }}</span> <br>
                                                    
                                                </td>
                                                <td width="50%" align="center">
                                                    <span> Penerima Informasi</span> <br>
                                                    <img
                                                        src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ ($item['petugasPenerima'] ? $item['petugasPenerima']['label'] : '-')}}"><br>
                                                    <span> {{ date('Y-m-d H:i:s', strtotime($item['tanggalPenerima'])) ?? '' }}</span> <br>
                                                    <span> {{ $item['petugasPenerima'] ? $item['petugasPenerima']['label'] : '' }}</span> <br>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif
                                @endif
                            </table>

                        </td>
                        <td style="width:15%">
                            {{ $item['intruksiPPA'] ?? '' }}

                        </td>

                        <td style="width:15%" class="text-center">
                            @if (isset($item['dokterDPJP']))
                                <img
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ ($item[
                                        'dokterDPJP'] ? $item['dokterDPJP']['label'] : '-')}}"><br>
                            @endif
                            <span> {{ $item['keteranganVerifikasiDPJP'] ?? '' }}</span> <br>
                            <span> {{ isset($item['tglVerifikasi'] )? date('Y-m-d H:i',strtotime($item['tglVerifikasi'])) : '' }}</span>
                            <br>
                            <span> {{ isset($item['dokterDPJP'] )? $item['dokterDPJP']['label'] : '' }}</span>
                            <br>

                        </td>

                    </tr>
                @endif
                @if ($item['flag'] == 'gizi')
                    <tr>

                        <td style="width:15%">
                            <span class="mb-2">{{date('Y-m-d H:i',strtotime( $item['tgl'])) }}</span><br>
                            <span>{{ isset($item['tenagaMedis'] )? $item['tenagaMedis']['label'] : '-' }}</span>

                        </td>
                        <td>
                            <table class="tg">
                                <tr>
                                    <td width="5%"
                                        style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">A
                                    </td>
                                    <td>
                                        {{ $item['AGizi'] ?? '' }}
                                    </td>

                                </tr>
                                <tr>
                                    <td width="5%"
                                        style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">D
                                    </td>
                                    <td>
                                        {{ $item['DGizi'] ?? '' }}
                                    </td>

                                </tr>
                                <tr>
                                    <td width="5%"
                                        style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">I
                                    </td>
                                    <td>
                                        {{ $item['IGizi'] ?? '' }}
                                    </td>

                                </tr>
                                <tr>
                                    <td width="5%"
                                        style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">M
                                    </td>
                                    <td>
                                        {{ $item['MGizi'] ?? '' }}
                                    </td>

                                </tr>
                                <tr>
                                    <td width="5%"
                                        style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">E
                                    </td>
                                    <td>
                                        {{ $item['EGizi'] ?? '' }}
                                    </td>

                                </tr>
                            </table>

                        </td>
                        <td style="width:15%">
                            {{ $item['intruksiPPA'] ?? '' }}

                        </td>

                        <td style="width:15%" class="text-center">
                            @if (isset($item['dokterDPJP']))
                                <img
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ ($item[
                                        'dokterDPJP'] ? $item['dokterDPJP']['label'] : '-')}}"><br>
                            @endif
                            <span> {{ $item['keteranganVerifikasiDPJP'] ?? '' }}</span> <br>
                            <span> {{ isset($item['tglVerifikasi'] )? date('Y-m-d H:i',strtotime($item['tglVerifikasi'])) : '' }}</span>
                            <br>
                            <span> {{  isset($item['dokterDPJP']) ? $item['dokterDPJP']['label'] : '' }}</span> <br>

                        </td>

                    </tr>
                @endif
            @endforeach
        </tbody>

        {{-- @foreach ($data['details'] as $item)
            <tr>
                <td align="center">{{ isset($item['tgl']) ? date('Y-m-d H:i', strtotime($item['tgl'])) : ""  }}</td>
                <td align="center">
                @if (isset($item['tenagaMedis']))
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ $item['tenagaMedis']['label'] }}"><br>
                @endif
                {{ isset($item['tenagaMedis']) ? $item['tenagaMedis']['label'] : "" }}
                </td>
                <td>{{ isset($item['S']) ? $item['S'] : ""  }}</td>
                <td>{{ isset($item['O']) ? $item['O'] : ""  }}</td>
                <td>{{ isset($item['A']) ? $item['A'] : ""  }}</td>
                <td>{{ isset($item['P']) ? $item['P'] : ""  }}</td>
                @php
                    $v_dpjp = isset($item['dokterRawatBersama']) ? $item['dokterRawatBersama']['label'] : "";
                    $tglv_dpjp = isset($item['tglVerifikasi']) ? date('Y-m-d H:i', strtotime($item['tglVerifikasi'])) : "";
                @endphp
                <td align="center">
                @if (isset($item['dokterRawatBersama']))
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ $v_dpjp }}"><br>
                @endif
                {{ $v_dpjp  }}<br>{{ $tglv_dpjp }}
                </td>
            </tr>
        @endforeach --}}
    </table>
@endsection


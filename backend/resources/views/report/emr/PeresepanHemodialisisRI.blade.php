@extends('template.layout-emr')
@section('title', ' PERESEPAN HEMODIALISIS RAWAT INAP')
@section('kode', 'RM.13.11/HD/00')
@section('page-style')
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        .bold {
            font-weight: bold;
        }

        table {
            border-collapse: collapse !important;
            width: 100%;
        }

        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: auto;
            page-break-after: avoid
        }

        .pd td {
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }

        .fnt {
            font-size: 9pt;
        }

        .fnt th {
            border: 1px solid black;
            border-bottom: none;
        }

        .fnt td {
            vertical-align: top;
            padding: 3px;
        }

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        .mid {
            text-align: center !important;
        }

        .border {
            border: 1px solid black;
        }

        .bd {
            border-right: 1px solid black;
        }

        .font {
            font-size: 10pt !important;
        }
    </style>
@endsection

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <table  class="pd">
        <tr>
            <td width="15%">
                Hari / Tgl
            </td>
            <td width="2%">:</td>
            <td class="bd" width="33%">
                {{ isset($data['tanggal']) ? Carbon::parse($data['tanggal'])->translatedFormat('d F Y') : '...........' }}
                Jam :
                {{ isset($data['tanggal']) ? Carbon::parse($data['tanggal'])->translatedFormat('H i') : '...........' }}
            </td>
            <td width="15%">
                ultrafiltrasi
            </td>
            <td  width="2%">:</td>
            <td width="33%">
                {{ isset($data['ultrafiltrasi']) ? $data['ultrafiltrasi'] : '...........' }}
            </td>
        </tr>
        <tr>
            <td width="15%">
               Nama Pasien
            </td>
            <td width="2%">:</td>
            <td class="bd">
                {{ isset($data['namaPasien']) ? $data['namaPasien'] : '...........' }}
            </td>
            <td width="15%">
                Luas Membran
            </td>
            <td  width="2%">:</td>
            <td>
                {{ isset($data['luasMembran']) ? $data['luasMembran'] : '...........' }}
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['lowFlux']) && $data['lowFlux'] == 'Low Flux' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Low Flux</span>
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['highFlux']) && $data['highFlux'] == 'High Flux' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">High Flux</span>
            </td>
        </tr>
        <tr>
            <td width="15%">
              Umur
            </td>
            <td width="2%">:</td>
            <td class="bd">
                {{ isset($data['pasien']['umur']) ? $data['pasien']['umur'] : '...........' }}
                Jenis Kelamin:
                {!! $data['jenisKelaminPasien'] == 'Laki-laki' ? 'L' : '<s>L</s>' !!}/{!! $data['jenisKelaminPasien'] == 'Perempuan' ? 'P' : '<s>P</s>' !!}
            </td>
            <td width="15%">
                Anticoagulan
            </td>
            <td  width="2%">:</td>
            <td>
                {{ isset($data['anticoagulan']) ? $data['anticoagulan'] : '...........' }}
            </td>
        </tr>
        <tr>
            <td width="15%">
                Ruangan
            </td>
            <td width="2%">:</td>
            <td class="bd">
                {{ isset($data['ruangan']['label']) ? $data['ruangan']['label'] : '...........' }}
            </td>
            <td width="15%">
                Akses Vaskuler
            </td>
            <td  width="2%">:</td>
            <td>
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['avShunt']) && $data['avShunt'] == 'AV Shunt' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">AV Shunt</span>
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['dLument']) && $data['dLument'] == 'D. Lument' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">D. Lument</span><br>
            </td>
        </tr>
        <tr>
            <td width="15%">
                No CM
            </td>
            <td width="2%">:</td>
            <td class="bd">
                {{ isset($data['rmPasien']) ? $data['rmPasien'] : '...........' }}
            </td>
            <td width="15%">
                
            </td>
            <td  width="2%"></td>
            <td>
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['vFemoralis']) && $data['vFemoralis'] == 'V. Femoralis' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">V. Femoralis</span>
            </td> 
        </tr>
        <tr>
            <td width="15%">
                Diagnosis
            </td>
            <td width="2%">:</td>
            <td  class="bd">
                {{ isset($data['diagnosis']) ? $data['diagnosis'] : '...........' }}
            </td>
            <td width="15%">
                Intruksi Khusus
            </td>
            <td  width="2%">:</td>
            <td rowspan="2">
                {{ isset($data['intruksiKhusus']) ? $data['intruksiKhusus'] : '...........' }}
            </td> 
        </tr>
        <tr>
            <td width="15%">
               Riwayat HD
            </td>
            <td  width="2%">:</td>
            <td class="bd">
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['riwayatHD']) && $data['riwayatHD'] == 'Ya' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Ya</span>
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['riwayatHD']) && $data['riwayatHD'] == 'Reguler' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Reguler</span>
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['riwayatHD']) && $data['riwayatHD'] == 'Antar Waktu' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Antar Waktu</span>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="bd">
                HD terakhir :
                {{ isset($data['HDTerakhir']) ? $data['HDTerakhir'] : '...........' }}
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="bd">
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['travelingDialisis']) && $data['travelingDialisis'] == 'Traveling Dialisis' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Traveling Dialisis</span>
            </td>
            <td>
                Dr. Konsultan
            </td>
            <td>:</td>
            <td>
                {{ isset($data['drKonsultan']['label']) ? $data['drKonsultan']['label'] : '...........' }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="bd">
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['TDTidak']) && $data['TDTidak'] == 'Tidak' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tidak</span>
            </td>
            <td colspan="3" style="text-align:center">Dokter Yang Meminta</td>
        </tr>
        <tr>
            <td width="15%">
              Jenis Tindakan 
            </td>
            <td  width="2%">:</td>
            <td class="bd">
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['jenisTindakan']) && $data['jenisTindakan'] == 'Cito' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Cito</span>
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['jenisTindakan']) && $data['jenisTindakan'] == 'Elektif' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Elektif</span>
                <input type="checkbox" style="vertical-align: bottom;"
                {{ isset($data['jenisTindakan']) && $data['jenisTindakan'] == 'Reguler' ? 'checked' : '' }} />
                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Reguler</span>
            </td>
            <td colspan="3" rowspan="3" style="{{ empty($data['dokterYM']) ? 'padding-bottom: 2em;' : '' }};text-align:center;">
                @if (isset($data['dokterYM']['label']))
                    <img src="data:image/png;base64, {!! $qrcode !!}">
                @endif
                <br>
                <span>{{ isset($data['dokterYM']['label']) ? $data['dokterYM']['label'] : ' ' }}</span>
            </td>
            
        </tr>
        <tr>
            <td>
                Lama HD
            </td>
            <td>:</td>
            <td class="bd"> {{ isset($data['lamaHD']) ? $data['lamaHD'] : '...........' }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>
                Kec. Aliran Darah (QB)
            </td>
            <td>:</td>
            <td class="bd"> {{ isset($data['aliranDarah']) ? $data['aliranDarah'] : '...........' }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

@endsection
@extends('template.layout-lab')
@section('title', 'Cetak Hasil Lab')
@section('page-style')
<style>
    .table-parent{
        border: 2px solid #353434;
        border-collapse: collapse;
    }
    .none-top{
        border-top: none;
    }
    .table-bordered tr{

    }
</style>
@endsection
@section('content')
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr style="padding: 50px;">
                    <td align="center">
                        <font style="font-size: 12pt;font-weight: bold" color="#000000">HASIL PEMERIKSAAN LABORATORIUM</font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <div class="table-container">
                <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-parent">
                    <tr>
                        <td style="padding-left: 10px; padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >Nama Pasien</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >: {{ $dataReport['header']->namapasien ?? "" }}</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >No Lab</font>
                        </td>
                        <td style="padding-right: 10px;  padding-bottom:7px;">
                            <font style="font-size: 9pt; font-weight: bold" color="#000000" >: {{ $dataReport['details'][0]->noorder ?? null }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px; padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >No. RM</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >: {{ $dataReport['header']->nocm ?? "" }}</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >Tgl. Pengambilan Sample</font>
                        </td>
                        <td style="padding-right: 10px;  padding-bottom:7px;">
                            <font style="font-size: 9pt; font-weight: bold" color="#000000" >: </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px; padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >Tgl. Lahir / Umur</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >: {{ $dataReport['header']->tglkelahiran }} / {{ \Carbon\Carbon::parse($dataReport['header']->tglkelahiran)->age }}</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >Tgl. Hasil Selesai</font>
                        </td>
                        <td style="padding-right: 10px;  padding-bottom:7px;">
                            <font style="font-size: 9pt; font-weight: bold" color="#000000" >: </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px; padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >Jenis Kelamin</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >: {{ $dataReport['header']->jk }}</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >Asal / Ruangan</font>
                        </td>
                        <td style="padding-right: 10px;  padding-bottom:7px;">
                            <font style="font-size: 9pt; font-weight: bold" color="#000000" >: {{ $dataReport['details'][0]->ruanganasal ?? "" }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px; padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >Alamat</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >@php
                                    $alamat = $dataReport['header']->alamatlengkap;
                                    $alamatBaru = wordwrap($alamat, 50, "<br/>", true);
                                    echo ": " . $alamatBaru;
                                @endphp</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >Dokter Pengirim</font>
                        </td>
                        <td style="padding-right: 10px;  padding-bottom:7px;">
                            <font style="font-size: 9pt; font-weight: bold" color="#000000" >: {{ $dataReport['details'][0]->namalengkap ?? "" }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px; padding-bottom:7px;"></td>
                        <td style="padding-bottom:7px;"></td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000" >Diagnosa</font>
                        </td>
                        <td style="padding-right: 10px;  padding-bottom:7px;">
                            <font style="font-size: 9pt; font-weight: bold" color="#000000" >: {{ $dataReport['details'][0]->catatanklinis ?? "" }}</font>
                        </td>
                    </tr>
                </table>
                <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-parent none-top table-bordered">
                        <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;  width:40%;" class="none-top"><font style="font-size: 9pt;font-weight:700;" color="#000000" >PEMERIKSAAN</font></td>
                        <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;  width:15%;" class="none-top"><font style="font-size: 9pt;font-weight:700;" color="#000000" >HASIL</font></td>
                        <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-right: none;  width:20%;" class="none-top"><font style="font-size: 9pt;font-weight:700;" color="#000000" >SATUAN</font></td>
                        <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;  width:20%;" class="none-top"><font style="font-size: 9pt;font-weight:700;" color="#000000" >NILAI NORMAL</font></td>
                        <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;  width:20%;" class="none-top"><font style="font-size: 9pt;font-weight:700;" color="#000000" >METODE</font></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align:left;border: 2px solid #3b3b3b;border-top: none;border-bottom: 1px solid #7c7a7a;font-weight:bold;">DIAGNOSTIK IN-VITRO</td>
                    </tr> 
                    @forelse ($dataReport['details'] as $data)
                        @php
                            if($data->nilaitext){
                                $nilai = explode(" ~ ", $data->nilaitext);
                                $hasil = '';
                                $min = $nilai[0];
                                if(isset($nilai[1])){
                                    $max = $nilai[1];
                                    $hasil = $data->hasil < $min ? $hasil = "Low" : $hasil = '';
                                    $hasil = $data->hasil > $max ? $hasil = "High" : $hasil = '';
                                }
                            }
                            $analis = '';
                            if($analis == ''){
                                $analis = $data->analis;
                            }
                        @endphp
                            <tr>
                                <td style="padding-left: {{$data->hasil ? '30px':'10px'}};font-weight: {{$data->hasil ? 'none':'bold'}};border: 2px solid #3b3b3b;border-top: none;border-bottom: 1px solid #7c7a7a;">&nbsp; {{$data->hasil? " - ":""}}{{ $data->detailpemeriksaan }}</td>
                                <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-bottom: 1px solid #7c7a7a;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width: 100%">
                                        <tr style="padding: 0">
                                            <td style="padding: 0; width:10%"></td>
                                            @if($data->flag == 'H')
                                            <td style="padding: 0; color: red; font-weight: bold;" align="left">{{ $data->hasil }}</td>
                                            @elseif($data->flag == 'L')
                                            <td style="padding: 0; color: #0000ff; font-weight: bold;" align="left">{{ $data->hasil }}</td>
                                            @else
                                            <td style="padding: 0; font-weight: bold;" align="left">{{ $data->hasil}}</td>
                                            @endif
                                            @if($data->flag == 'H')
                                            <td style="padding: 0; color: red; font-weight: bold; padding-right: 5" align="right">{{ $data->flag }}</td>
                                            @elseif($data->flag == 'L')
                                            <td style="padding: 0; color: #0000ff; font-weight: bold; padding-right: 5" align="right">{{ $data->flag }}</td>
                                            @else
                                            <td style="padding: 0; font-weight: bold; padding-right: 5" align="right">{{ $data->flag }}</td>
                                            @endif
                                        </tr>
                                    </table>
                                </td>
                                <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-bottom: 1px solid #7c7a7a;">{{ $data->satuanstandar }}</td>
                                <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-bottom: 1px solid #7c7a7a;">{{ $data->nilaitext }}</td>
                                <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-bottom: 1px solid #7c7a7a;">{{ $data->metode }}</td>
                            </tr>
                    @endforeach
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <!-- <td>
                       <table style="display: none !important;">
                        <tr>
                            <td>
                                <font style="font-size: 8pt;" color="#000000" >Jenis Sample</font>
                            </td>
                            <td>
                                <font style="font-size: 8pt;" color="#000000" > : </font>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <font style="font-size: 8pt;" color="#000000" >Terima Sempel</font>
                            </td>
                            <td>
                                <font style="font-size: 8pt;" color="#000000" >: {{ $dataReport['header']->tglregistrasi }}</font>
                            </td>
                            <td>
                                <font style="font-size: 8pt;" color="#000000" ></font>
                            </td>
                        </tr>
                       </table>
                    </td> -->
                    <td style="30%"></td>
                    <td style="text-align:center">
                        <font style="font-size: 9pt;" color="#000000" ><u>Pemeriksa</u></font>
                    </td>
                    <td style="text-align:center">
                        <font style="font-size: 9pt;" color="#000000" ><u>Penanggung Jawab</u></font>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" height="50"></td>
                </tr>
                <tr>
                    <td style="30%"></td>
                    <td style="text-align:center">
                        <font style="font-size: 9pt;font-weight: 600" color="#000000" ><u>{{ $dataReport['details'][0]->pegawaiverifikator ?? "" }}</u></font>
                    </td>
                    <td style="text-align:center;">
                        <font style="font-size: 9pt;font-weight: 600" color="#000000" ><u>{{ $dataReport['details'][0]->dokterlab ?? "" }}</u></font>
                    </td>
                </tr>
                <tr>
                    <td style="30%"></td>
                    <td style="text-align:center">
                        <font style="font-size: 9pt;font-weight: 600" color="#000000" ><u>NIP. {{ $dataReport['details'][0]->nippegawaiverifikator ?? "" }}</u></font>
                    </td>
                    <td style="text-align:center;">
                        <font style="font-size: 9pt;font-weight: 600" color="#000000" ><u>NIP. {{ $dataReport['details'][0]->nipdokterlab ?? "" }}</u></font>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom:30px;">
                        <font style="font-size: 8pt;font-weight: 600" color="#000000" ></font>
                    </td>
                    <td style="padding-bottom:30px;"></td>
                </tr>
            </table>
        </td>
    </tr>
@endsection

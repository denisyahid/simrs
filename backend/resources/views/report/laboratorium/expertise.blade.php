@extends('template.layout')
@section('title', 'Expertise Lab')
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
    .table-bottom tr td {
        width: 33%;
    }
</style>
@endsection

@section('content')
<tr>
    <td>
        <div class="table-container">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-parent">
                <tr>
                    <td style="padding-left: 10px;padding-top: 10px;padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >No Lap</font>
                    </td>
                    <td style="padding-top: 10px; padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >: {{$data->noregistrasi}}</font>
                    </td>
                    <td style="padding-top: 10px; padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Nama</font>
                    </td>
                    <td style="padding-right: 10px;padding-top: 10px; padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >: {{$data->namapasien}}</font>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom:7px;padding-left: 10px;">
                        <font style="font-size: 9pt" color="#000000" >Ruangan Asal</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:  {{$data->namaruangan}}</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 8pt;" color="#000000" >T.Lahir/Umur</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >: {{ \Carbon\Carbon::parse($data->tgllahir)->format('d-m-Y')}} /  {{ \Carbon\Carbon::parse($data->tgllahir)->age }}</font>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Kelas</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:  {{$data->namakelas}}</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Alamat</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >
                            @php
                                $alamat = $data->alamatlengkap;
                                $alamatBaru = wordwrap($alamat, 50, "<br/>", true);
                                echo ": " . $alamatBaru;
                            @endphp
                        </font>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Jenis</font>
                    </td>
                    <td  style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:  {{$data->kelompokpasien}} </font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >NO RM</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >: {{$data->nocm}} </font>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;">
                        <font style="font-size: 9pt" color="#000000" >Tanggal Pemeriksa</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:
                            {{ \Carbon\Carbon::parse($data->tanggalpemeriksaan)->format('d-m-Y H:i:s') }}
                        </font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Penanggung Jawab</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:
                            {{$data->penanggungjawab}}
                        </font>
                    </td>
                </tr>
                <tr >
                    <td style="padding-left: 10px;padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Waktu Validasi</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:   {{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d-m-Y') }}</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Dokter Pengirim</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >: {{$data->dokterpengirim}}</font>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-bottom: 40px">
                        <font style="font-size: 9pt" color="#000000" >Diagnosa</font>
                    </td>
                    <td style="padding-bottom: 40px">
                        <font style="font-size: 9pt" color="#000000" >: </font>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </table>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-parent none-top table-bordered">
                <tr>
                    <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-left: none" class="none-top"><font style="font-size: 10pt;font-weight:700" color="#000000" >No</font></td>
                    <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none" class="none-top"><font style="font-size: 10pt;font-weight:700" color="#000000" >Jenis Pemeriksaan</font></td>
                    <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none" class="none-top"><font style="font-size: 10pt;font-weight:700" color="#000000" >Hasil</font></td>
                    <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none" class="none-top"><font style="font-size: 10pt;font-weight:700" color="#000000" >Nilai Rujukan</font></td>
                    <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-right: none" class="none-top"><font style="font-size: 10pt;font-weight:700" color="#000000" >Satuan</font></td>
                </tr>
                <tr>
                    <td colspan="5" height="15" style="text-align:left;border-bottom: 1px solid #888080"></td>
                </tr>
                {{-- <tr>
                    <th colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px;">
                        <font style="font-size: 9pt;font-weight:600" color="#000000" >
                            HEMATOLOGI
                        </font>
                    </th>
                </tr>
                <tr>
                    <td colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px;">
                        <font style="font-size: 9pt;font-weight:600" color="#000000" >
                            MORFOLOGI APUS DARAH PUTIH
                        </font>
                    </td>
                </tr> --}}
                <tr>
                    <td colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px">
                        <font style="font-size: 9pt;font-weight:600" color="#000000" >
                            makroskopik : {{$data->makroskopik}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px">
                        <font style="font-size: 9pt;font-weight:400" color="#000000" >
                            mikroskopik : {{$data->mikroskopik}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px">
                        <font style="font-size: 9pt;font-weight:400" color="#000000" >
                            kesimpulan :  {{$data->kesimpulan}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px">
                        <font style="font-size: 9pt;font-weight:400" color="#000000" >
                            anjuran :  {{$data->anjuran}}
                        </font>
                    </td>
                </tr>
            </table>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-bottom">
                <tr>
                    <td colspan="3" height="40"></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: center">
                        <font style="font-size: 9pt;font-weight:500" color="#000000" >
                            {{ $profile->namakota ?? 'Bandung' }}, {{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d-M-Y H:i:s')}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" height="40"></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;">
                        <font style="font-size: 9pt;font-weight:600" color="#000000" >
                        {{-- ///    dr .farah primadani kaurow --}}
                        -----------------------------------------------------
                        </font>
                        <br/>
                        <font style="font-size: 9pt;font-weight:600" color="#000000" >
                            (Dokter Patologi Klinik)
                        </font>
                    </td>
                    <td style="text-align: center;">
                        <font style="font-size: 9pt;font-weight:600" color="#000000" >
                            {{-- Aulia rizki pratama --}}
                            -----------------------------------------------------
                        </font>
                        <br/>
                        <font style="font-size: 9pt;font-weight:600" color="#000000" >
                            (Pemeriksa)
                        </font>
                    </td>
                </tr>
            </table>
        </div>
    </td>
</tr>

@endsection


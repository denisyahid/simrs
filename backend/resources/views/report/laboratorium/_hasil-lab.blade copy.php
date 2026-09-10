@extends('template.layout')
@section('title',  $dataReport['judul'] )
@section('page-style')
    <style>
    .class-2 {
        border-top:1px solid black;border-bottom:1px solid black;
    }
    </style>
@endsection
@section('content')
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" height="80px">
                        <font style="font-size: 16pt;font-weight: bold" color="#000000">{{ $dataReport['judul'] }}</font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td>
                        <span class="text-biasa">No. Registrasi / No. RM</font>
                    </td>
                    <td>
                        <span class="text-biasa">:
                            {{ $dataReport['header']->noregistrasi }}&nbsp;/&nbsp;{{ $dataReport['header']->nocm }}</font>
                    </td>

                    <td>
                        <span class="text-biasa">Unit Layanan</font>
                    </td>
                    <td>
                        <span class="text-biasa">: {{ $dataReport['header']->ruanganperiksa }}</font>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text-biasa">Nama Pasien</font>
                    </td>
                    <td>
                        <span class="text-biasa">: {{ $dataReport['header']->namapasien }}</font>
                    </td>

                    <td>
                        <span class="text-biasa">Tipe Pasien</font>
                    </td>
                    <td>
                        <span class="text-biasa">:
                            {{ $dataReport['header']->kelompokpasien }}&nbsp;
                        </font>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text-biasa">Alamat</font>
                    </td>
                    <td>
                        <span class="text-biasa">: {{ $dataReport['header']->alamatlengkap }}</font>
                    </td>

                    <td>
                        <span class="text-biasa">Tgl Registrasi</font>
                    </td>
                    <td>
                        <span class="text-biasa">:
                            {{ $dataReport['header']->tglregistrasi }}&nbsp;/&nbsp;
                        </font>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text-biasa">Jenis Kelamin / Tgl Lahir</font>
                    </td>
                    <td>
                        <span class="text-biasa">:
                            {{ $dataReport['header']->jk }}&nbsp;/&nbsp;{{ $dataReport['header']->tglkelahiran }}</font>
                    </td>

                    <td>
                        <span class="text-biasa">Kamar / Kelas</font>
                    </td>
                    <td>
                        <span class="text-biasa">:
                            {{ $dataReport['header']->namakamar }}&nbsp;/&nbsp;{{ $dataReport['header']->namapenjamin }}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text-biasa">Dokter DPJP</font>
                    </td>
                    <td>
                        <span class="text-biasa">: {{ $dataReport['header']->dpjp }}</font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:10px">
            <table width="100%" cellspacing="0" cellpadding="1" border="0">
                
                <tr>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">No</span>
                    </th>
                    {{-- <th class="class-2 text-center">
                        <span class="text-biasa">Nama Pemeriksaan</span>
                    </th> --}}
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Pemeriksaan</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Hasil</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Status</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Nilai Normal</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Satuan</span>
                    </th>
                    <th  class="class-2 text-center">
                        <span class="text-biasa">Metode</span>
                    </th>
                </tr>
                @php
                    $nomor = 1;
                @endphp
                @forelse ($dataReport['details'] as $item)
                <tr>
                    <td colspan="7" style="text-align:center;">
                        <font style="font-size: 12pt;font-weight:bold;" color="#000000">{{$item->namaproduk }}</font>
                    </td>
                </tr>
                    <tr>
                        <th class="text-top text-left">
                            <span class="text-biasa">{{ $nomor }}</span>
                        </th>
                        {{-- <th class="text-top text-left">
                            <span class="text-biasa">
                                {{ date_format(date_create($item->tglpelayanan), 'd/m/Y') }}</span>
                        </th> --}}
                        {{-- <th class="text-top text-left">
                            <span class="text-biasa">{{ $item->namaproduk }}</span>
                        </th> --}}
                        <th class="text-top text-left">
                            <span class="text-biasa">{{ $item->detailpemeriksaan }}</span>
                        </th>
                        <th class="text-top text-left">
                            <span class="text-biasa">{{ $item->hasil }}</span>
                        </th>
                        <th class="text-top text-left">
                            <span class="text-biasa">{{ $item->flag }}</span>
                        </th>
                        <th class="text-top text-left">
                            <span class="text-biasa">{{ $item->nilaitext }}</span>
                        </th>
                        <th class="text-top text-left">
                            <span class="text-biasa">{{ $item->satuanstandar }}</span>
                        </th>
                        
                  
                    </tr>
                    @php
                        $nomor = $nomor + 1;
                    @endphp
                @empty    
                <tr>
                    <th class=" text-center" colspan="8">
                    Data Tidak ada
                    </th>
                </tr>
                @endforelse
      
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td colspan="2" align="right">
                        <span class="text-biasa">{!! $profile->namakota !!},
                            &nbsp;&nbsp;{{ date('d/m/Y H:i') }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">Pasien</td>
                    <td class="text-center">Dokter</td>
                    <td class="text-center">Kasir</td>
                </tr>
                <tr>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                    <td class="text-center">&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center">{{ $dataReport['header']->namapasien }}</td>
                    <td class="text-center">_______________________</td>
                    <td class="text-center">_______________________</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <span style="font-size: 10pt; color:#000000">
                            Dicetak Oleh : {{ $dataReport['user'] }} &nbsp;&nbsp;&nbsp;
                            Diprint Tanggal : {{ date('d/m/Y H:i') }}
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

@endsection

@extends('template.layout')
@section('title', 'Cetak Lembar Rawat Inap')
@section('page-style')

@endsection
@section('content')
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0" style="border-bottom:2px solid black;">
                <tr>
                    <td class="text-center">
                        <span class="text-judul">SURAT PERNYATAAN</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <section style="margin-top: 30px"></section>
    <tr>
        <td style="font-size: 11pt;margin-left: 30px;margin-top: 50px;">Yang bertanda tangan di bawah ini,
            saya: </td>

    </tr>
    <tr>
        <table style="width: 100%; font-size: 14pt;margin-left: 30px;">
            <tr>
                <td style="width: 15%"><span style="font-size: 11pt;" color="#000000">Nama</span></td>
                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                <td style="width: 80%">
                    <span style="font-size: 11pt;"
                        color="#000000">{{ $penaggungJawab == 'undefined' ? '' : $penaggungJawab }}</span>
                </td>
            </tr>
            <tr>
                <td style="width: 15%"><span style="font-size: 11pt;" color="#000000">Alamat</span></td>
                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                <td style="width: 80%">
                    <span style="font-size: 11pt;" color="#000000">{{ $alamat == 'undefined' ? '' : $alamat }}</span>
                </td>
            </tr>
        </table>
    </tr>
    <section style="margin-top: 10px">
        <span style="font-size: 11pt">Sebagai Penanggung jawab penderita atas nama : </span>
    </section>
    <tr>
        <table style="width: 100%; font-size: 14pt;margin-left: 30px;">
            <tr>
                <td style="width: 17%"><span style="font-size: 11pt;" color="#000000">Nama</span></td>
                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                <td style="width: 80%">
                    <span style="font-size: 11pt;" color="#000000">{{ $data->nama }}</span>
                </td>
            </tr>
            <tr>
                <td style="width: 15%"><span style="font-size: 11pt;" color="#000000">No Rekam Medik</span></td>
                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                <td style="width: 80%">
                    <span style="font-size: 11pt;" color="#000000">{{ $data->noreg }}</span>
                </td>
            </tr>
            <tr>
                <td style="width: 10%"><span style="font-size: 11pt;" color="#000000">Alamat</span></td>
                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                <td style="width: 80%">
                    <span style="font-size: 11pt;" color="#000000">{{ $data->alamatlengkap }}</span>
                </td>
            </tr>

        </table>
        <section style="margin-left: 30px;margin-top: 5px">
            <table>
                <tr>
                    <td style="width: 39%"><span style="font-size: 11pt;" color="#000000">Kelurahan</span></td>
                    <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                    <td style="width: 60%">
                        <span style="font-size: 11pt;" color="#000000">{{ $data->kelurahan }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 39%"><span style="font-size: 11pt;" color="#000000">Kecamatan</span></td>
                    <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                    <td style="width: 60%">
                        <span style="font-size: 11pt;" color="#000000">{{ $data->kecamatan }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 39%"><span style="font-size: 11pt;" color="#000000">Kabupaten/Kota</span></td>
                    <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                    <td style="width: 60%">
                        <span style="font-size: 11pt;" color="#000000">{{ $data->kotakabupaten }}</span>
                    </td>
                </tr>
            </table>
        </section>
        <table style="margin-left: 30px">
            <tr>
                <td style="width: 19%"><span style="font-size: 11pt;" color="#000000">Kelompok Pasien</span></td>
                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                <td style="width: 60%">
                    <span style="font-size: 11pt;" color="#000000">{{ $data->kelompokpasien }}</span>
                </td>
            </tr>
            <tr>
                <td style="width: 19%"><span style="font-size: 11pt;" color="#000000">Menyatakan sebagai penderita</span>
                </td>
                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                <td style="width: 60%">
                    <span style="font-size: 11pt;" color="#000000">
                        @foreach ($diagnosa as $key => $dd)
                            {{ $dd->namadiagnosa }} ,
                        @endforeach
                    </span>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="font-size: 11pt;">
                    Demikian pernyataan saya buat dengan sesungguhnya
                </td>
            </tr>
        </table>
    </tr>

    <tr>
        {{-- <span></span> --}}
        <table style="width: 100%; margin-top: margin-top: -50px">
            <tr>
                <td><p style="display: none;">test</p></td>
                <td style="text-align: center"><p style="font-size: 11pt;">{{ date('d-M-Y H:i:s') }}</p></td>
            </tr>
            <tr style="">
                <td style="text-align: center; margin-top: -50px;">
                    <p style="font-size: 11pt">Mengetahui</p>
                </td>
                <td style="text-align: center">
                    <p style="font-size: 11pt;"> Yang menyatakan</p>
                </td>
            </tr>

        </table>
    </tr>
    <table>
        <tr>
            <td>
            </td>
        </tr>
        <tr>
            <td>
                <p style="margin-top: 40px;">Catatan :</p>
                <p style="font-size: 11pt">1. Apabila persyaratan belum lengkap/tidak membawa kelengkapan persyaratan
                    akan saya susulkan
                    maksimal 2x24 jam sejak
                    pernyataan dibuat.</p>
                <p style="font-size: 11pt">2. Apa sampai batas waktu yang ditentukan belum dilengkapi, maka hari ke-3
                    dinaikkan ke kelas III Umum, sampai semua
                    persyaratan.
                    diserahkan kepada petugas RS.</p>
                <p style="font-size: 11pt">3. Seluruh biaya yang ada sejak masuk sampai tanggal diserahkannya
                    persyaratan secara lengkap menjadi tanggung jawab
                    penderita / Keluarga / Penanggung jawab dan harus dibayar lunas.</p>
            </td>
        </tr>
    </table>
@endsection

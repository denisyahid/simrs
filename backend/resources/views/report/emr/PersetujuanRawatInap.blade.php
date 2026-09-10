@extends('template.head-emr')
@section('title', 'Persetujuan Rawat Inap')
@section('about', 'Persetujuan Rawat Inap')

@push('style')
    <style>
        span {
            font-size: 11pt;
        }
    </style>
@endpush

@section('content')
    @php
        // dd($data);
    @endphp
    <table width="100%">
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 11pt;font-weight: bold">yang bertanda tangan di bawah ini: </span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4" height="10"></td>
        </tr>
        <tr>
            <td width="24%">
                <span style="font-size: 11pt;">Nama</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="75%">
                <span style="font-size: 11pt;">{{ isset($data['namaWali']) ? $data['namaWali'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="24%">
                <span style="font-size: 11pt;">Jenis Kelamin</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="65%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <input type="checkbox" {{ isset($data['lakiLakiWali']) ? $data['lakiLakiWali'] : ''}} />
                            <span style="font-size: 11pt;">Laki-laki</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox" {{ isset($data['perempuanWali']) ? $data['perempuanWali'] : ''}} />
                            <span style="font-size: 11pt;">Perempuan</span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;"></span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="24%">
                <span style="font-size: 11pt;">Tanggal Lahir</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="75%">
                <span style="font-size: 11pt;">
                    {{ isset($data['tglLahirWali']) ? $data['tglLahirWali'] : '' }}
                </span>
            </td>
        </tr>
        <tr>
            <td width="24%">
                <span style="font-size: 11pt;">Alamat</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="75%">
                <span style="font-size: 11pt;"> {{ isset($data['tglLahirWali']) ? $data['tglLahirWali'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 11pt;font-weight: bold">Dalam hal ini bertindak sebagai diri
                    sendiri/suami/istri/ayah/ibu/wali penanggung jawab</span>
                <br>
                <span style="font-size: 11pt;">{{ isset($data['penanggungJawab']) ? $data['penanggungJawab'] : '' }}&nbsp;&nbsp;<b>dari pasien:</b></span>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4" height="10"></td>
        </tr>
        <tr>
            <td width="24%">
                <span style="font-size: 11pt;">Nomor Rekam Medis</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="75%">
                <span style="font-size: 11pt;">{{ isset($data['penanggungJawab']) ? $data['penanggungJawab'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="24%">
                <span style="font-size: 11pt;">Nama</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="75%">
                <span style="font-size: 11pt;">{{ isset($data['nama']) ? $data['nama'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="24%">
                <span style="font-size: 11pt;">Jenis Kelamin</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="75%">
                <span style="font-size: 11pt;">{{ isset($data['jenisKelamin']) ? $data['jenisKelamin'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="24%">
                <span style="font-size: 11pt;">Tanggal Lahir</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="75%">
                <span style="font-size: 11pt;">
                   
                    {{ isset($data['tglLahir']) ? $data['tglLahir'] : '' }}
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 11pt;">Saya telah mendapatkan informasi dari Petugas mengenai hak
                    dan kewajiban saya serta tata tertib di RSJP</span>
                <br>
                <span style="font-size: 11pt;">Paramarta Bandung, dengan demikian saya menyatakan</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 11pt;">1.Pasien tersebut diatas adalah pasien dengan kepesertaan
                </span>
            </td>
        </tr>
        <tr>

            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['model']) ? $data['model'] : '' }} />
                            <span style="font-size: 11pt;">Umum</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['umumLengkap']) ? $data['umumLengkap'] : '' }}/>
                            <span style="font-size: 11pt;">Lengkap</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"  {{ isset($data['umumMenyusul']) ? $data['umumMenyusul'] : '' }}/>
                            <span style="font-size: 11pt;">Menyusul</span>
                        </td>
                        <td width="15%">
                            <span style="font-size: 11pt;">Keterangan:</span>
                        </td>
                        <td width="40%">
                            <span style="font-size: 11pt;">{{ isset($data['umumKeterangan']) ? $data['umumKeterangan'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>

            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['BPJS_PBI']) ? $data['BPJS_PBI'] : '' }}/>
                            <span style="font-size: 11pt;">BPJS PBI</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['BPJSLengkap']) ? $data['BPJSLengkap'] : '' }} />
                            <span style="font-size: 11pt;">Lengkap</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"  {{ isset($data['BPJSMenyusul']) ? $data['BPJSMenyusul'] : '' }}  />
                            <span style="font-size: 11pt;">Menyusul</span>
                        </td>
                        <td width="15%">
                            <span style="font-size: 11pt;">Keterangan:</span>
                        </td>
                        <td width="40%">
                            <span style="font-size: 11pt;">{{  isset($data['BPJSKeterangan']) ? $data['BPJSKeterangan'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>

            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['BPJSMandiri']) ? $data['BPJSMandiri'] : '' }}  />
                            <span style="font-size: 11pt;">BPJS Mandiri</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['BPJSMandiriLengkap']) ? $data['BPJSMandiriLengkap'] : '' }}  />
                            <span style="font-size: 11pt;">Lengkap</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['BPJSMandiriMenyusul']) ? $data['BPJSMandiriMenyusul'] : '' }} />
                            <span style="font-size: 11pt;">Menyusul</span>
                        </td>
                        <td width="15%">
                            <span style="font-size: 11pt;">Keterangan:</span>
                        </td>
                        <td width="40%">
                            <span style="font-size: 11pt;">{{  isset($data['BPJSMandiriKeterangan']) ? $data['BPJSMandiriKeterangan'] : ''  }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>

            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['asuransi']) ? $data['asuransi'] : '' }} />
                            <span style="font-size: 11pt;">Asuransi Lainnya</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['asuransiLengkap']) ? $data['asuransiLengkap'] : '' }} />
                            <span style="font-size: 11pt;">Lengkap</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['asuransiMenyusul']) ? $data['asuransiMenyusul'] : '' }}/>
                            <span style="font-size: 11pt;">Menyusul</span>
                        </td>
                        <td width="15%">
                            <span style="font-size: 11pt;">Keterangan:</span>
                        </td>
                        <td width="40%">
                            <span style="font-size: 11pt;">{{ isset($data['asuransieterangan']) ? $data['asuransieterangan'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 11pt;">2.Setuju akan menempati ruang perawatan</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['kelas1']) ? $data['kelas1'] : '' }} />
                            <span style="font-size: 11pt;">Kelas I</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['kelas2']) ? $data['kelas2'] : '' }}/>
                            <span style="font-size: 11pt;">Kelas II</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['kelas3']) ? $data['kelas3'] : '' }} />
                            <span style="font-size: 11pt;">Kelas III</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['kelas3']) ? $data['kelas3'] : '' }}/>
                            <span style="font-size: 11pt;">VIP</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 11pt;">3. Setuju dengan semua biaya yang ada di RSJP Paramarta
                    Bandung, dengan perkiraan biaya sebagai berikut</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="35%">
                            <span style="font-size: 11pt;">Perawatan rawat inap, sebesar</span>
                        </td>
                        <td width="65%">
                            <span style="font-size: 11pt;">Rp. {{ isset($data['hargaRawatInap']) ? $data['hargaRawatInap'] : ''}}</span>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="35%">
                            <span style="font-size: 11pt;">Operassi / Tindakan, sebesar</span>
                        </td>
                        <td width="65%">
                            <span style="font-size: 11pt;"> Rp. {{ isset($data['hargaOperasi']) ? $data['hargaOperasi'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="35%">
                            <span style="font-size: 11pt;">Tindakan perawatan lain, sebesar</span>
                        </td>
                        <td width="65%">
                            <span style="font-size: 11pt;"> Rp. {{isset($data['hargaPerawatanLain']) ? $data['hargaPerawatanLain']:'' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 11pt;">4. Kepesertaan pasien BPJS PBI atau PBID (Penerima Bantuan
                    Iuran Daerah) akan GUGUR </span>
                <br>
                &nbsp;&nbsp;<span style="font-size: 11pt;">atau TIDAK BERLAKU apabila</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td>
                            <input type="checkbox" {{isset($data['dirawat']) ? 'checked' : ''}} />
                            <span style="font-size: 11pt;">Memilih dirawat di kelas II,I dan VIP</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" {{isset($data['persyaratanTidakLengkap']) ? 'checked' : ''}} />
                            <span style="font-size: 11pt;">Persyaratan tidak dilengkapi dalam waktu 3x24
                                jam</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" {{isset($data['biayaDariPenjaminLain']) ? 'checked' : ''}} />
                            <span style="font-size: 11pt;">Mendapatkan pembiayaan dari penjamin lain di
                                luar BPJS</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 11pt;">5. Sanggup dan Bersedia membayar seluruh biaya perawatan
                    sesuai dengan kelas yang saya kehendaki</span>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 11pt;">6. Memberi Kuasa kepada Dokter / Rumah Sakit untuk
                    memberikan keterangan yang diperlukan oleh pihak</span>
                <br>
                &nbsp;&nbsp;<span style="font-size: 11pt;">penanggung biaya perawatan saya / pasien
                    tersebut di atas</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4" height="10"></td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 11pt;">Demikian pernyataan ini saya buat dengan penuh kesadaran
                    dan tanpa paksaan pihak manapun</span>
            </td>
        </tr>
    </table>
    <table>
        <tbody>
            <tr>
                <td style="text-align: right">
                  
                    <span style="font-size: 11pt;"><b>Bandung</b>, {{isset($data['tglPembuatan']) ? date('Y-m-d',strtotime(explode('T', $data['tglPembuatan'] )[0]) ): ''}}</span>
                    <span style="font-size: 11pt;"><b>Jam</b>: {{isset($data['tglPembuatan']) ? date('H:m',strtotime(explode('T', $data['tglPembuatan'] )[1]) ) : ''}}</span>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4" height="10"></td>
            </tr>
            <tr>
                <td width="50%" style="text-align: center">
                    <b>
                        <span style="font-size: 11pt;">
                            Petugas/Perawat </span>
                    </b>
                    <br>
                
                    @if (isset($data['signature_1']) == 1)
                        <img src="{{ $data['signature_1'] }}" width="75" height="75" alt="TTD" />
                    @endif
                   
            </td>
            <td width="50%" style="text-align: center">
                <b>
                    <span style="font-size: 11pt;">
                        Yang Membuat Pernyataan</span>
                </b>
                <br>
                @if (isset($data['signature_2']) == 1)
                  <img src="{{ $data['signature_2'] }}" width="75" height="75" alt="TTD" />
                @else 
                    <div style="height:75px"></div>
                @endif
          
        </td>
    </tr>

    <tr>
        <td width="40%" style="text-align: center"><b>
                <span style="font-size: 11pt;">
                    ( {{isset($data['yangMembuatPernyataan']) ? $data['yangMembuatPernyataan'] : '-'}} )</span>
            </b>
        </td>

        <td width="40%" style="text-align: center"><b>
                <span style="font-size: 11pt;">
                    ( {{ isset($data['yangMembuatPernyataan']) ? $data['yangMembuatPernyataan'] : '-'}} )</span>
            </b>
        </td>
    </tr>

</tbody>
</table>
@endsection

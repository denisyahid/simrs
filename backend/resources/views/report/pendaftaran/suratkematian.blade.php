 @extends('template.layout')
 @section('title', 'Surat keterangan kematian')
 @section('page-style')

 @endsection
 @section('content')
     <tr>
         <td style="padding-top:10px">
             <table width="100%" cellspacing="0" cellpadding="0" border="0">
                 <tr>
                     <td align="center">
                         <font style="font-size: 14pt;font-weight: 600;text-decoration: underline;" color="#000000">
                             SURAT KETERANGAN KEMATIAN
                         </font>
                         <br>
                         <font style="font-size: 12pt;font-weight: 600;" color="#000000">
                             {{ $raw->nosurat }}
                         </font>
                     </td>
                 </tr>
             </table>
         </td>
     </tr>
     <tr>
         <td style="padding-top:10px">
             <table width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
                 <tr>
                     <td width="100%" colspan="3">
                         <font style="font-size: 12pt; font-weight: bold;" color="#000000" ;>
                             IDENTITAS JENAZAH
                         </font>
                     </td>
                 </tr>
                 <tr>
                     <td width="100%" height="20" colspan="3"></td>
                 </tr>
                 <tr>
                     <td width="29%">
                         <font style="font-size: 12pt;" color="#000000">Nama</font>
                     </td>
                     <td width="1%">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">{{ $raw->namapasien }}</font>
                     </td>
                 </tr>
                 <tr>
                     <td width="29%">
                         <font style="font-size: 12pt;" color="#000000">No. Rekam Medis</font>
                     </td>
                     <td width="1%">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">{{ $raw->noregistrasi }}</font>
                     </td>
                 </tr>
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">NIK</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">{{ $raw->noidentitas }}</font>
                     </td>
                 </tr>
                 <tr>

                     <td width="29%">
                         <font style="font-size: 12pt;" color="#000000">Jenis Kelamin</font>
                     </td>
                     <td width="1%">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">{{ $raw->jeniskelamin }}</font>
                     </td>
                 </tr>
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Tempat/Tanggal Lahir</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">{{ $raw->tempatlahir }}, {{ $raw->tgllahir }}</font>
                     </td>
                 </tr>
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Alamat</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">{{ $raw->alamatlengkap }}</font>
                     </td>
                 </tr>
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Status Kependudukan</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">{{ $raw->name }}</font>
                     </td>
                 </tr>

                 <tr>
                     <td width="100%" height="5" colspan="3"></td>
                 </tr>

             </table>`

             <table width="100%" cellspacing="0" cellpadding="0" border="0">
                 <tr>
                     <td align="center" width="100%">
                         <font style="font-size: 12pt;font-weight: bold" color="#000000">
                             ( YANG BERSANGKUTAN DINYATAKAN TELAH MENINGGGAL DUNIA )
                         </font>
                         <br>
                     </td>
                 </tr>
             </table>
             <table style="margin-top:10px" width="85%" cellspacing="0" cellpadding="0" border="0"
                 align="center">
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Waktu Meninggal</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="29%">
                         <font style="font-size: 12pt;" color="#000000">
                             {{ \Carbon\Carbon::parse($raw->tglmeninggal)->format('d-m-Y') }}
                         </font>
                     </td>
                     <td width="10%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Pukul</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="40%">
                         <font style="font-size: 12pt;" color="#000000">
                             {{ \Carbon\Carbon::parse($raw->tglmeninggal)->format('H:i') }}
                         </font>
                     </td>
                 </tr>

             </table>
             <table width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Umur Saat Meninggal</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">{{ $umur->y }} Tahun {{ $umur->m }}
                             Bulan {{ $umur->d }} Hari
                         </font>
                     </td>
                 </tr>
             </table>
             <table style="margin-top:15px" width="85%" cellspacing="0" cellpadding="0" border="0"
                 align="center">
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Lama Rawat Di RS</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">{{ $interval->format('%a') }} hari</font>
                     </td>
                 </tr>
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Ruangan</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">{{ $raw->namaruangan }}</font>
                     </td>
                 </tr>
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Dasar Diagnosis</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">Anamnesis, Pemeriksaan Fisik, Pemeriksaan
                             Penunjang</font>
                     </td>
                 </tr>
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Rencana Pemulasaran</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">-</font>
                     </td>
                 </tr>
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Pada Tanggal</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">
                             {{ \Carbon\Carbon::parse($raw->tglmeninggal)->format('d-m-Y') }}
                         </font>
                     </td>
                 </tr>
                 <tr>
                     <td width="29%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">Diagnosa Klinis</font>
                     </td>
                     <td width="1%" class="ontop">
                         <font style="font-size: 12pt;" color="#000000">:</font>
                     </td>
                     <td width="70%">
                         <font style="font-size: 12pt;" color="#000000">{{ $raw->diagnosa }}</font>
                     </td>
                 </tr>
             </table>
         </td>

     </tr>
     <tr>
         <td style="padding-top:50px">
             <table width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
                 <tr>
                     <td width="50%"></td>
                     <td width="50%" align="center">
                         <font style="font-size: 12pt;" color="#000000">Bandung,
                             {{ App\Traits\Valet::getDateIndo(date('Y-m-d')) }}
                         </font>
                         <br><br>
                         <font style="font-size: 12pt;" color="#000000">Dokter Yang Menerangkan</font>
                     </td>
                 </tr>
                 @if ($raw->namalengkap)
                     <tr>
                         <td width="50%"></td>
                         <td width="50%" align="center">
                             <font style="font-size: 12pt;font-weight:bold;text-decoration: underline;" color="#000000">
                             </font>
                             <br>
                             <img src="data:image/jpeg;base64,{{ $qrcode }}" width="80px" border="0">
                         </td>
                     </tr>
                     <tr>
                         <td width="50%">
                         </td>
                         <td width="50%" style="align-items: center;text-align: center">
                             <font style="font-size: 12pt;" color="#000000">{{ $raw->namalengkap }}</font>
                             <br>
                         </td>
                     </tr>
                 @else
                     <tr>
                         <td width="50%"></td>
                         <td width="50%" align="center" color="#000000">
                             <p>Dokter tidak ditemukan</p>
                         </td>
                     </tr>
                 @endif
             </table>
         </td>
     </tr>
 @endsection

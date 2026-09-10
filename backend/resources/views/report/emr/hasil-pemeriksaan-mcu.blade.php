@extends('template.head-emr')
@section('title', 'Hasil pemeriksaan MCU')
@section('about', 'Hasil Pemeriksaan MCU')
@section('content')

<table width="100%" cellspacing="0">
         
    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Tanggal Pemeriksaan</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000"  >
                            {{ isset($data['waktuSimpan']) ? date('d-m-Y', strtotime($data['waktuSimpan'])) : '-' }}
                        </span>
                    </td>                   
                </tr> 
      
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <span style="font-size: 11pt;font-weight: bold" color="#000000" >Anamnesis</span>                           
        </td>                        
    </tr>
    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Keluhan saat ini</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['keluhanSaatIni']) ?$data['keluhanSaatIni'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Riwayat Penyakit Dahulu</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['riwayatPenyakitDahulu']) ? $data['riwayatPenyakitDahulu'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Riwayat Keluarga</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['riwayatKeluarga']) ? $data['riwayatKeluarga'] : '-' }}</span>
                    </td>                   
                </tr>    
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="4">
            <span style="font-size: 11pt;font-weight: bold" color="#000000" >Pemeriksaan Fisik</span>                           
        </td>                        
    </tr>

    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >TD</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="30%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '-' }}</span>
                    </td>
                    
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >RR</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="30%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['frekuensiNafas']) ? $data['frekuensiNafas'] : '-' }}</span>
                    </td> 
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Nadi</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="30%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['frekuensiNadi']) ? $data['frekuensiNadi'] : '-' }}</span>
                    </td> 
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Suhu</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="30%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['suhu']) ? $data['suhu'] : '-' }}</span>
                    </td>                 
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >TB</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="30%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '-' }}</span>
                    </td> 
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">IMT</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="30%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['imt']) ? $data['imt'] : '-' }}</span>
                    </td> 
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >BB</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="30%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['beratBadan']) ? $data['beratBadan'] : '-' }}</span>
                    </td>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Lingkar Perut</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="30%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['lingkarPerut']) ? $data['lingkarPerut'] : '-' }}</span>
                    </td>                 
                </tr>     
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Kesadaran</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['kesadaran']) ? $data['kesadaran'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Status Mentalis</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['statusMentalis']) ? $data['statusMentalis'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Kepala</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['kepala']) ? $data['kepala'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Kulit</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['kulit']) ? $data['kulit'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Mata</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['mata']) ? $data['mata'] : '-' }}</span>
                    </td>                   
                </tr> 
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Telinga</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['telinga']) ? $data['telinga'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Hidung</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['hidung']) ? $data['hidung'] : '-' }}</span>
                    </td>                   
                </tr>  
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Tenggorokan</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['tenggorokan']) ? $data['tenggorokan'] : '-' }}</span>
                    </td>                   
                </tr>   
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Gigi dan Mulut</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['gigiDanMulut']) ? $data['gigiDanMulut'] : '-' }}</span>
                    </td>                   
                </tr>          
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td>
                        <span style="font-size: 10pt;font-weight: bold" color="#000000" >Thorax</span>                           
                    </td>                        
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Jantung</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['jantung']) ? $data['jantung'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Paru</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['paru']) ? $data['paru'] : '-' }}</span>
                    </td>                   
                </tr>    
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td>
                        <span style="font-size: 10pt;font-weight: bold" color="#000000" >Perut</span>                           
                    </td>                        
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Hati</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['hati']) ? $data['hati'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Limpa</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['limpa']) ? $data['limpa'] : '-' }}</span>
                    </td>                   
                </tr>  
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Anus</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['anus']) ? $data['anus'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Ginjal</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['ginjal']) ? $data['ginjal'] : '-' }}</span>
                    </td>                   
                </tr>    
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td>
                        <span style="font-size: 10pt;font-weight: bold" color="#000000" >Anggota Gerak</span>                           
                    </td>                        
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Ekstrimitas Atas</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['ekstrimitasAtas']) ? $data['ekstrimitasAtas'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Ekstrimitas Bawah</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['ekstrimitasBawah']) ? $data['ekstrimitasBawah'] : '-' }}</span>
                    </td>                   
                </tr>    
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td>
                        <span style="font-size: 10pt;font-weight: bold" color="#000000" >Saraf</span>                           
                    </td>                        
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Refleks Fisiologis</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['refleksFisiologis']) ? $data['refleksFisiologis'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Refleks Patologis</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['refleksPatologis']) ? $data['refleksPatologis'] : '-' }}</span>
                    </td>                   
                </tr>    
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td>
                        <span style="font-size: 10pt;font-weight: bold" color="#000000" >&nbsp;</span>                           
                    </td>                        
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Tulang Belakang</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['tulangBelakang']) ? $data['tulangBelakang'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Genitalia</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['genitalia']) ? $data['genitalia'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Lain-lain</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['sarafLain']) ? $data['sarafLain'] : '-' }}</span>
                    </td>                   
                </tr>      
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="4">
            <span style="font-size: 11pt;font-weight: bold" color="#000000" >Pemeriksaan Penunjang</span>                           
        </td>                        
    </tr>
    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Thorax Foto</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['thoraxFoto']) ? $data['thoraxFoto'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >EKG</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['ekg']) ? $data['ekg'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000">Laboratorium</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['laboratorium']) ? $data['laboratorium'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Treadmill</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['treadmill']) ? $data['treadmill'] : '-' }}</span>
                    </td>                   
                </tr>  
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Lain-lain</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000">:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['pemeriksaanLain']) ? $data['pemeriksaanLain'] : '-' }}</span>
                    </td>                   
                </tr> 
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Catatan</span>
                    </td>
                    <td width="1% " class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['catatan']) ? $data['catatan'] : '-' }}</span>
                    </td>                   
                </tr>       
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="4">
            <span style="font-size: 11pt;font-weight: bold" color="#000000">Kesimpulan</span>                           
        </td>                        
    </tr>
    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['kesimpulan']) ? $data['kesimpulan'] : '-' }}</span>
                    </td>                   
                </tr>
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000"></span>
                    </td>                   
                </tr>
                  
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="4">
            <span style="font-size: 11pt;font-weight: bold" color="#000000">Saran</span>                           
        </td>                        
    </tr>
    <tr>
        <td width="100%" colspan="3">
            <table width="100%">
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['saran']) ? $data['saran'] : '-' }}</span>
                    </td>                   
                </tr>
                  
            </table>
        </td>
    </tr>
    
   
       
</table>



<table>
    <tbody>
    <tr>
        <td>
            <span style="font-size: 11pt;font-weight: bold" color="#000000" ></span>                           
        </td>                        
    </tr>
    <tr>
        <td style="text-align: left">
            <span style="font-size: 11pt;" color="#000000"><b>Bandung</b>,
                {{ isset($data['waktuSimpan']) ? date('d-m-Y', strtotime($data['waktuSimpan'])) : '-' }}
            </span>
            <span style="font-size: 11pt;" color="#000000"><b>Jam</b>:
                {{ isset($data['waktuSimpan']) ? date('H:i:s', strtotime($data['waktuSimpan'])) : '-' }}</span>
    </tr>
    <tr>
        <td width="100%" colspan="4" height="10"></td>
    </tr>  
    <tr>
        <td width="50%" style="text-align: center">
            &nbsp;
            {{-- @forelse($dataimg as $d)
                @if($d->emrdfk == 1)
                    <img src="{{ $d->image }}" width="75" height="75" alt="TTD" />
                @break    
                @endif   
            @empty
                <div style="height:75px"></div>
            @endforelse --> --}}
        </td>
        <td width="50%" style="text-align: center">
            <b><span style="font-size: 11pt;" color="#000000" >
            Dokter Pemeriksa MCU</span></b>
            {{-- <br>
                @forelse($dataimg as $d)
                    @if($d->emrdfk == 1)
                        <img src="{{ $d->image }}" width="75" height="75" alt="TTD" />
                    @break    
                    @endif   
                @empty
                    <div style="height:75px"></div>
                @endforelse --}}
        </td>
    </tr>

    <tr>
        <td width="40%" style="text-align: center"><b><span style="font-size: 11pt;" color="#000000" >
            &nbsp;
        </td>

        <td width="40%" style="text-align: center"><b><span style="font-size: 11pt;" color="#000000" >
        ( {{ isset($data['dokterPemeriksa']) ? $data['dokterPemeriksa']['label'] : '-' }} )</span></b>
        </td>
    </tr>

    </tbody>
</table>

@endsection

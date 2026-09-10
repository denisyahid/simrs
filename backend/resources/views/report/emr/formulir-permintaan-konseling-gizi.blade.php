@extends('template.head-emr')
@section('title', 'EMR - formulir permintaan konseling gizi')
@section('about', 'Formulir Permintaan Konseling Gizi')

@push('style')
    <style>
        span{
            font-size: 11pt;
        }
    </style>
@endpush

@section('content')
  
<table width="100%" cellspacing="0">

    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="25%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >Yth. Ahli Gizi</span>
                    </td>
                    <td width="1%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >&nbsp;</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000" >&nbsp;</span>
                    </td>                 
                </tr>
            </table>
        </td>
    </tr>
   
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="25%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >Tanggal dan Jam</span>
                    </td>
                    <td width="1%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000"  >{{ isset($data['tanggal']) ? date('d-m-Y - H:m:s', strtotime($data['tanggal'])) : '-' }}</span>
                    </td>                 
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="25%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >Dokter Penanggung Jawab</span>
                    </td>
                    <td width="1%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['dokter']) ? $data['dokter'] : '-' }}</span>
                    </td>                 
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="25%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >Mohon Dilakukan</span>
                    </td>
                    <td width="1%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 9pt;" color="#000000" >
                            <input type="checkbox" {{ isset($data['konselingGizi']) && $data['konselingGizi'] == 'Ya' ? 'checked' : '' }}  />
                            <span style="font-size: 10pt;" color="#000000" >Konseling Gizi</span>
                        </span>
                    </td>                 
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >Hasil Pemeriksaan Laboratorium / Pemeriksaan Klinik Penting :</span>
                    </td>              
                </tr>
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['hasilLaboratorium']) ? $data['hasilLaboratorium'] : '-' }}</span>
                    </td>   
                </tr>
            </table>
        </td>
    </tr>

    
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >Diagnosa Medis :</span>
                    </td>              
                </tr>
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['namadiagnosa']) ? $data['namadiagnosa'] : '-' }}</span>
                    </td>   
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >Pengobatan penting :</span>
                    </td>              
                </tr>
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['pengobatanPenting']) ? $data['pengobatanPenting'] : '-' }}</span>
                    </td>   
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >Diet yang dianjurkan :</span>
                    </td>              
                </tr>
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['diet']) ? $data['diet'] : '-' }}</span>
                    </td>   
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000">PENDAPAT AHLI GIZI</span>
                    </td>              
                </tr>
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000">Pengkajian Gizi</span>
                    </td>   
                </tr>
               
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel" color="#000000" >A. Antropometri</span></td>
    </tr>
    <tr>
        <td width="100%" colspan="6">
            <table width="100%" class="text-top">
                <tr>
                    <td width="10%">
                         <span style="font-size: 10pt;" color="#000000" class="judulLabel" >BB</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 10pt;" color="#000000" class="judulLabel">:</span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 10pt;" color="#000000" >{{ isset($data['beratBadan']) ? $data['beratBadan'] : '-' }}</span>
                        <!-- <span style="font-size: 10pt;" color="#000000" >Kg</span> -->
                    </td>
                   
                    <td width="10%">
                         <span style="font-size: 10pt;" color="#000000" class="judulLabel">TB</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 10pt;" color="#000000" class="judulLabel">:</span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '-' }}</span>
                        <!-- <span style="font-size: 10pt;" color="#000000" >cm</span> -->
                    </td>

                    <td width="10%">
                         <span style="font-size: 10pt;" color="#000000" class="judulLabel">LLA</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 10pt;" color="#000000" class="judulLabel">:</span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['lingkarPerut']) ? $data['lingkarPerut'] : '-' }}</span>
                    </td>  
                </tr>

               

            </table>            
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="6">
            <table width="100%" class="text-top">
                <tr>
                   
                    <td width="10%">
                         <span style="font-size: 10pt;" color="#000000" class="judulLabel" >IMT</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 10pt;" color="#000000" class="judulLabel">:</span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['imt']) ? $data['imt'] : '-' }}</span>
                    </td>  

                    <td width="10%">
                         <span style="font-size: 10pt;" color="#000000" class="judulLabel" >Perubahan BB</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 10pt;" color="#000000" class="judulLabel">:</span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['perubahanBB']) ? $data['perubahanBB'] : '-' }}</span>
                    </td>  

                    <td width="10%">
                         <span style="font-size: 10pt;" color="#000000" >&nbsp;</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 10pt;" color="#000000" >&nbsp;</span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 10pt;" color="#000000" >&nbsp;</span>
                    </td>  

                </tr>
            </table>            
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >B. Biokimia</span>
                    </td>              
                </tr>
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['biokimia']) ? $data['biokimia'] : '-' }}</span>
                    </td>   
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >C. Fisik / Klinik</span>
                    </td>              
                </tr>
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['fisik']) ? $data['fisik'] : '-' }}</span>
                    </td>   
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >D. Riwayat Gizi</span>
                    </td>              
                </tr>
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['riwayatGizi']) ? $data['riwayatGizi'] : '-' }}</span>
                    </td>   
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >E. Riwayat Personal</span>
                    </td>              
                </tr>
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['riwayatPersonal']) ? $data['riwayatPersonal'] : '-' }}</span>
                    </td>   
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >Diagnosa Gizi</span>
                    </td>              
                </tr>
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['diagnosisGizi']) ? $data['diagnosisGizi'] : '-' }}</span>
                    </td>   
                </tr>
            </table>
        </td>
    </tr>
    
    <tr>
        <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel" color="#000000" >Intervensi</span></td>
    </tr>
    <tr>
        <td width="100%" colspan="6">
            <table width="100%" class="text-top">
                <tr>
                    <td width="15%">
                         <span style="font-size: 10pt;" color="#000000" class="judulLabel" >A. Tujuan</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 10pt;" color="#000000" class="judulLabel">:</span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 10pt;" color="#000000" >{{ isset($data['tujuan']) ? $data['tujuan'] : '-' }}</span>
                    </td>
                   
                    <td width="15%">
                         <span style="font-size: 10pt;" color="#000000" class="judulLabel" >B. Intervensi</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 10pt;" color="#000000" class="judulLabel" >:</span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['intervensi']) ? $data['intervensi'] : '-' }}</span>
                     
                    </td>

                    <td width="15%">
                         <span style="font-size: 10pt;" color="#000000" class="judulLabel" >Konseling Gizi / Edukasi</span>
                    </td>
                    <td width="1%">
                        <span style="font-size: 10pt;" color="#000000" class="judulLabel" >:</span>
                    </td>
                    <td width="20%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['konselingGiziEdukasi']) ? $data['konselingGiziEdukasi'] : '-' }}</span>
                    </td>  
                </tr>
            </table>            
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="100%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >Rencana Monitoring dan Evaluasi Gizi :</span>
                    </td>              
                </tr>
                <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['rencanaMonitoring']) ? $data['rencanaMonitoring'] : '-' }}</span>
                    </td>   
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="25%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >Pelaksana</span>
                    </td>
                    <td width="1%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 9pt;" color="#000000" >
                            <input type="checkbox"  /><span style="font-size: 10pt;" color="#000000" >Desy Saritua Munthe, S.Gz</span>
                        </span>
                    </td>                 
                </tr>
                <tr>
                    <td width="25%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" ></span>
                    </td>
                    <td width="1%" class="judulLabel">
                        <span style="font-size: 10pt;" color="#000000" ></span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 9pt;" color="#000000" >
                            <input type="checkbox"  /><span style="font-size: 10pt;" color="#000000" >Muhammad Arief Naufal, Str GZ</span>
                        </span>
                    </td>                 
                </tr>
            </table>
        </td>
    </tr>
   

</table>

@endsection

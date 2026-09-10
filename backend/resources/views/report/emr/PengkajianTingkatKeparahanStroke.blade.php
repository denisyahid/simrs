<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>EMR - Formulir Pengkajian Tingkat Keparahan Stroke</title>
  <style>
    @media print {
        td.merah {
            background-color: #d54242 !important;
            -webkit-print-color-adjust: exact;
        }

        td.kuning {
            background-color: #c5d542 !important;
            -webkit-print-color-adjust: exact;
        }

        td.hijau {
            background-color: #42d55b !important;
            -webkit-print-color-adjust: exact;
        }

        td.hitam {
            background-color: #000000 !important;
            -webkit-print-color-adjust: exact;
        }
    }

    @page {
        size: A4;
    }



    /*@media print {*/
    /*    body {margin:0}*/
    /*}*/
    .double-border {

        border: 4px solid #000;

    }

    .double-border:before {

        border: 4px solid #fff;

    }

    .box {
        border: 2px solid black;
        /*border-radius: 6px;*/
    }

    .mt-5 {
        margin-top: 5px;
    }

    .garis6 td {
        padding: 3px;
    }

    .bold {
        font-weight: bold;
    }

    .f-s-15 {
        font-size: 12px;
    }
    
    .half {
        width: 50%;
    }

    .top-height {
        height: 50px;
        vertical-align: text-top;
        width: 15%;
    }

    .text-top {
        vertical-align: text-top;
    }

    .kotak {
        width: 50px;
        height: 20px;
    }

    .merah {
        background-color: #d54242 !important;
    }

    .kuning {
        background-color: #c5d542 !important;
    }

    .hijau {
        background-color: #42d55b !important;
    }

    .hitam {
        background-color: #000000 !important;
    }

    .bmerah {
        border: thin solid #d54242;
    }

    .bkuning {
        border: thin solid #c5d542;
    }

    .bhijau {
        border: thin solid #42d55b;
    }

    .bhitam {
        border: thin solid #000000;
    }

    .border-lr {
        border-collapse: collapse;
    }

    .border-lr td {
        border: thin solid #000;
    }

    .border-doang {
        border-collapse: collapse;
        border: thin solid #000;
        border-top: none;
    }

    .border-doang td {
        padding: 5px;
    }

    .bg-gray {
        background-color: #DCDCDC;
    }

    .bg-blue {
        background-color: #91CEDE;
    }

    .tc {
        text-align: center;
    }

    .font {
        font-size: 9pt;
    }

    .font-2 {
      font-size: 8pt;
    }

    .font-3 {
      font-size: 7pt;
    }

    * {
      font-family:DejaVu Sans, sans-serif;
      font-size: 9pt;
    }

</style>
</head>
<body>
  <table width="100%" cellspacing="0" cellpadding="0" border="1">
    <tr>
        <td width="60%" style="text-align:right" colspan=2>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr class="bg-blue">
                    <td>
                        <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                        <td width="50%" style="font-size: 14px; text-align: right;">RM.38/SIR/00</td>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
      <td width="10%" style="padding: 5px; border-right: 1px solid black; border-left: 1px solid black;">
          <img src="{{ 'img/logo-rs.png' }}" style="width: 90px;">
      </td>
      <td style="text-align: center; border-right: 1px solid black;">
          <b>
              <span style="font-size: 16px">PENGKAJIAN TINGKAT
              </span>
              <br>
              <span style="font-size: 16px">KEPARAHAN STROKE</span>
              <br>
              <span style="font-size: 12px;">Menggunakan National Institute Of Health Stroke Scale (NIHSS)</span>
          </b>
      </td>
      <td width="50%" style="padding: 10px; border-right: 1px solid black;">
          <div class="box" style="text-align: left">
              <table style="padding: 3px;">
                  <tr>
                      <td class="f-s-15 bold  text-top" style="width: 100px">No. RM</td>
                      <td class="f-s-15 bold  text-top">:</td>
                      <td class="f-s-15 bold text-top"><b>{{ $pasien['nocm'] }}</b></td>
                  </tr>
                  <tr>
                      <td class="f-s-15 bold  text-top">Nama</td>
                      <td class="f-s-15 bold  text-top">:</td>
                      <td class="f-s-15 bold  text-top"><b>{{ $pasien['namapasien'] }}</b>
                      </td>
                  </tr>
                  <tr>
                      <td class="f-s-15 bold  text-top">Jenis Kelamin</td>
                      <td class="f-s-15 bold  text-top">:</td>
                      <td class="f-s-15 bold  text-top">
                          <b>{{ $pasien['jeniskelamin'] }}</b>
                      </td>
                  </tr>
                  <tr>
                      <td class="f-s-15 bold  text-top">Tgl Lahir</td>
                      <td class="f-s-15 bold  text-top">:</td>
                      <td class="f-s-15 bold  text-top">
                          <b>{{ $pasien['tgllahir'] }}</b>
                      </td>
                  </tr>
              </table>
          </div>
      </td>
  </tr>
</table>

  <table width="100%" border="1" cellspacing="0" cellpadding="2" style="border: 1px solid black table-layout: fixed; border-collapse: collapse;">
 
    <tbody>
        
      <tr>
          <td colspan="7">
           <font style="font-size: 16px">
              &nbsp;Ruangan :  <font> {{isset($data['registrasi']) ? $data['registrasi']['namaruangan'] : '-'}} </font>
            </font>
          </td>
      </tr>
        
      <tr>
        <td width="5%" rowspan="2" style="text-align: center; vertical-align: middle;" >
          <font style="font-size: 16px"><strong>NO</strong>
          </font>
        </td>
         <td width="20%" rowspan="2" style="text-align: center; vertical-align: middle;" >
          <font style="font-size: 16px"><strong>PARAMETER YANG DINILAI</strong>
          </font>
        </td>
        <td width="30%" colspan="2" rowspan="2" style="text-align: center; vertical-align: middle;" >
          <font style="font-size: 16px"><strong>SKALA</strong>
          </font>
        </td>
        <td width="15%" height="40" style="text-align: center; vertical-align: middle;" >
          <font style="font-size: 16px"><strong>SKOR MASUK RS</strong>
          </font>
        </td>
          <td width="15%" height="40" style="text-align: center; vertical-align: middle;" >
          <font style="font-size: 16px"><strong>SKOR SAAT PERUBAHAN KONDISI</strong>
          </font>
        </td>
        <td width="15%" height="40" style="text-align: center; vertical-align: middle;" >
          <font style="font-size: 16px"><strong>SKOR DISCHARGE</strong>
          </font>
        </td>
      </tr>
      <tr>
        <td style="text-align: center; vertical-align: middle;" >
            
          <div class="font-2">
            
            <?php
                
               $tgl_skor_masuk_rs = '-';
               if(isset($data['tglSkorMasukRs']) ){				
                   if($data['tglSkorMasukRs']!=''){								
                       $tgl_skor_masuk_rs = date('d-m-y' , strtotime($data['tglSkorMasukRs']));
                   }							
               } 
   
               $tgl_skor_perubahan_kondisi = '-';
               if(isset($data['tglSkorMasukRs']) ){				
                   if($data['tglSkorMasukRs']!=''){								
                       $tgl_skor_perubahan_kondisi = date('d-m-y' , strtotime($data['tglSkorPerubahanKondisi']));
                   }							
               } 
   
               $tgl_skor_discharge = '-';
               if(isset($data['tglSkorMasukRs']) ){				
                   if($data['tglSkorMasukRs']!=''){								
                       $tgl_skor_discharge = date('d-m-y' , strtotime($data['tglSkorDischarge']));
                   }							
               } 
    
            ?>
              
              
               Tgl. {{$tgl_skor_masuk_rs}} 
              <VField>
                      <VDatePicker v-model="input.tglSkorMasukRs" mode="dateTime" style="width: 100%" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" disabled />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
          </div>
   
        </td>
        <td style="text-align: center; vertical-align: middle;">
            <div class="font-2">
                 Tgl. {{$tgl_skor_perubahan_kondisi}}
                <VField>
                      <VDatePicker v-model="input.tglSkorPerubahanKondisi" mode="dateTime" style="width: 100%" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" disabled />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                </VField>
            </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" >
          <div class="font-2">
               Tgl. {{$tgl_skor_discharge}}
              <VField>
                      <VDatePicker v-model="input.tglSkorDischarge" mode="dateTime" style="width: 100%" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" disabled />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
              </VField>
          </div>
        </td>
      </tr>
      <tr>
        <td  style="text-align: center; vertical-align: middle;" >
          <font>1a
          </font>
        </td>
        <td style="text-align: left; vertical-align: middle;" >
            <font class="font-2">
              Tingkat Kesadaran
            </font>
          </td>
          <td colspan="2" style="text-align: left; vertical-align: middle;" >
            <p class="font-2">0 = Sadar penuh<br>
              1 = Somnolen<br>
              2 = Stupor<br>3 = Koma
            </p>
          </td>
          <td style="text-align: center; vertical-align: middle;" > 
            <div class="column pt-3 pb-0">
                <font> {{isset($data['skorMasukRsTingkatKesadaran']) ? $data['skorMasukRsTingkatKesadaran'] : '-'}} </font>
            </div>
          </td>
          <td style="text-align: center; vertical-align: middle;" ><div class="column pt-3 pb-0">
         <font> {{isset($data['skorPerubahanKondisiTingkatKesadaran']) ? $data['skorPerubahanKondisiTingkatKesadaran'] : '-'}} </font> 
              
            </div>
          </td>
        
          <td style="text-align: center; vertical-align: middle;" ><div class="column pt-3 pb-0">
           <font> {{isset($data['skorDischargeTingkatKesadaran']) ? $data['skorDischargeTingkatKesadaran'] : '-'}} </font 
                  
            </div>
          </td>
      </tr>
        
      <tr>
          <td  style="text-align: center; vertical-align: middle;" >
            <font>1b</font>
          </td>
          <td style="text-align: left; vertical-align: middle;" >
            <font class="font-2">
              Menjawab pertanyaan. Tanyakan bulan dan usia pasien. Yang dinilai adalah jawaban pertama, pemeriksa tidak diperkenankan membantu pasien dengan verbal atau non verbal. 
            </font>
          </td>
          <td colspan="2" style="text-align: left; vertical-align: middle;" >
            <p  class="font-2">
                
              0 = Benar semua (2 pertanyaan)<br>
              1 = 1 benar/ETT/Disartria<br>
              2 = Salah semua/afasia/stupor/koma<br>
   
            </p>
          </td>
           <td style="text-align: center; vertical-align: middle;" > 
            <div class="column pt-3 pb-0">
          <font> {{isset($data['skorMasukRsMenjawabPertanyaan']) ? $data['skorMasukRsMenjawabPertanyaan'] : '-'}} </font> 
                
            </div>
          </td>
          <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
   
          <font> {{isset($data['skorPerubahanKondisiMenjawabPertanyaan']) ? $data['skorPerubahanKondisiMenjawabPertanyaan'] : '-'}} </font> 
   
          </div>
          </td>
         <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
        
        <font> {{isset($data['skorDischargeMenjawabPertanyaan']) ? $data['skorDischargeMenjawabPertanyaan'] : '-'}} </font> 
       
          </div>
        </td>
      </tr>  
        
      
      <tr>
          <td  style="text-align: center; vertical-align: middle;" >
          <font>1c
          </font>
        </td>
        <td style="text-align: left; vertical-align: middle;" >
          <font  class="font-2"> 
            Mengikuti perintah. Berikan dua perintah sederhana, membuka dan menutup mata, menggenggam tangan dan melepaskannya atau dua perintah lain.
          </font>
        </td>
        <td colspan="2" style="text-align: left; vertical-align: middle;" >
          <p  class="font-2">
            0 = Mampu melakukan 2  perintah<br>
            1 = Mampu melakukan 1  perintah<br>
            2 = Tidak mampu melakukan perintah
          </p>
        </td>
         <td style="text-align: center; vertical-align: middle;" > 
        
          <div class="column pt-3 pb-0">
             <font> {{isset($data['skorMasukRsMengikutiPerintah']) ? $data['skorMasukRsMengikutiPerintah'] : '-'}} </font> 
          </div>
        </td>
         <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
        <font> {{isset($data['skorPerubahanKondisiMengikutiPerintah']) ? $data['skorPerubahanKondisiMengikutiPerintah'] : '-'}} </font> 
             
          </div>
        </td>
         <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
         <font> {{isset($data['skorDischargeMengikutiPerintah']) ? $data['skorDischargeMengikutiPerintah'] : '-'}} </font> 
          
          </div>
        </td>
      </tr>
        
      <tr>
        <td  style="text-align: center; vertical-align: middle;" >
          <font>2</font>
        </td>
        <td style="text-align: left; vertical-align: middle;" >
          <font  class="font-2">
            Gaze: Gerakan mata konyugat horizontal
          </font>
        </td>
        <td colspan="2" style="text-align: left; vertical-align: middle;" >
          <p  class="font-2">
            0 = Normal<br>
            1 = Abnormal pada 1 mata<br>
            2 = Deviasi konyugat kuat atau paresis konyugat pada 2 mata
          </p>
        </td>
         <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
          <font> {{isset($data['skorMasukRsGaze']) ? $data['skorMasukRsGaze'] : '-'}} </font> 
              
          </div>
        </td>
         <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
        <font> {{isset($data['skorPerubahanKondisiGaze']) ? $data['skorPerubahanKondisiGaze'] : '-'}} </font> 
            
          </div>
        </td>
         <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
        <font> {{isset($data['skorDischargeGaze']) ? $data['skorDischargeGaze'] : '-'}} </font> 
           
          </div>
        </td>
      </tr> 
        
      
      <tr>
          <td  style="text-align: center; vertical-align: middle;" >
          <font>3</font>
          </td>
          <td style="text-align: left; vertical-align: middle;" >
          <font  class="font-2">
            Visual: Lapang pandang pada tes konfrontasi
          </font>
          </td>
          <td colspan="2" style="text-align: left; vertical-align: middle;" >
            <p  class="font-2">
              0 = Tidak ada gangguan<br>
              1 = Kuadrianopsia<br>
              2 = Hemianopia total<br>
              3 = Hemianopia bilateral/buta kortikal
            </p>
          </td>
         <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
          <font> {{isset($data['skorMasukRsVisual']) ? $data['skorMasukRsVisual'] : '-'}} </font> 
     
          </div>
        </td>
         <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
         <font> {{isset($data['skorPerubahanKondisiVisual']) ? $data['skorPerubahanKondisiVisual'] : '-'}} </font> 
            
          </div>
        </td>
         <td style="text-align: center; vertical-align: middle;" > 
            <div class="column pt-3 pb-0">
          <font> {{isset($data['skorDischargeVisual']) ? $data['skorDischargeVisual'] : '-'}} </font> 
            
            </div>
        </td>
      </tr> 
        
        
      <tr>
          <td  style="text-align: center; vertical-align: middle;" >4</td>
          <td style="text-align: left; vertical-align: middle;" >
          <font  class="font-2">Paresis wajah. Anjurkan  pasien menyeringai atau mengangkat alis dan menutup mata
          </font>
          </td>
          <td colspan="2" style="text-align: left; vertical-align: middle;" >
            <p  class="font-2">
              0 = Normal<br>
   
              1 = Paresis wajah ringan (lipatan naso labial datar, senyum asimetris)<br>
              2 = Paresis wajah partial (paresis wajah bawah total atau hampir total)<br>
              3 = Paresis wajah total (paresis wajah sesisi atau dua sisi)
            </p>
          </td>
        <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
            <font> {{isset($data['skorMasukRsParesisWajah']) ? $data['skorMasukRsParesisWajah'] : '-'}} </font> 
    
          </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
        <font> {{isset($data['skorPerubahanKondisiParesisWajah']) ? $data['skorPerubahanKondisiParesisWajah'] : '-'}} </font> 
            
          </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
        <font> {{isset($data['skorDischargeParesisWajah']) ? $data['skorDischargeParesisWajah'] : '-'}} </font> 
            
          </div>
        </td>
      </tr> 
        
        
      <tr>
        <td rowspan="2"  style="text-align: center; vertical-align: middle;" >5</td>
        <td rowspan="2" style="text-align: left; vertical-align: middle;" >
          <font  class="font-2">
            Motorik lengan. Anjurkan pasien mengangkat lengan hingga 45&deg; bila tidur berbaring atau 90&deg; bila posisi duduk. Bila pasien afasia berikan perintah menggunakan pantomime atau peragaan. 
          </font>
          </td>
        
        
        <td rowspan="2"   style="text-align: left; " >
          
          <font  class="font-2"> 
            0 = Mampu mengangkat lengan minimal 10 detik<br>
            1 = Lengan terjatuh sebelum 10 detik<br>
            2 = Tidak mampu mengangkat secara penuh 90&deg; atau 45&deg;<br>
            3 = Tidak mampu mengangkat hanya bergeser<br>
            4 = Tidak ada gerakan
          </font>
        </td>	
        <td height="82"  style="text-align: center; vertical-align: middle;">
          
            K<br>
            I<br>
            R<br>
            I<br> 
        </td>
        
   
       <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
           <font> {{isset($data['skorMasukRsMotorikLenganKiri']) ? $data['skorMasukRsMotorikLenganKiri'] : '-'}} </font> 
     
          </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
          <font> {{isset($data['skorPerubahanKondisiMotorikLenganKiri']) ? $data['skorPerubahanKondisiMotorikLenganKiri'] : '-'}} </font>
         
          </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" > 
          
          <div class="column pt-3 pb-0">
        <font> {{isset($data['skorDischargeMotorikLenganKiri']) ? $data['skorDischargeMotorikLenganKiri'] : '-'}} </font> 
          </div>
        </td>
      </tr>
      <tr>
        <td height="82"  style="text-align: center; vertical-align: middle;">
          
            K<br>
            A<br>
            N<br>
            A<br>
            N        
        </td>
        
        <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
           <font> {{isset($data['skorMasukRsMotorikLenganKanan']) ? $data['skorMasukRsMotorikLenganKanan'] : '-'}} </font>  
          </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
        <font> {{isset($data['skorPerubahanKondisiMotorikLenganKanan']) ? $data['skorPerubahanKondisiMotorikLenganKanan'] : '-'}} </font> 
            
          </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
         <font> {{isset($data['skorDischargeMotorikLenganKanan']) ? $data['skorDischargeMotorikLenganKanan'] : '-'}} </font> 
          </div>
        </td>
      </tr>
        
      
      <tr>
        <td rowspan="2"  style="text-align: center; vertical-align: middle;" >6</td>
        <td rowspan="2" style="text-align: left; vertical-align: middle;" >
          <font  class="font-2">
            Motorik tungkai. Anjurkan pasien tidur terlentang dan mengangkat tungkai 30&deg;
          </font>
          </td>
        
        
        <td rowspan="2"   style="text-align: left;" >
          
          <font  class="font-2"> 
            0 = Mampu mengangkat tungkai 30&deg; minimal 5 detik <br>
            1 = Tungkai jatuh ketempat tidur pada akhir detik ke-5 secara perlahan <br>
            2 = Tungkai jatuh sebelum 5 detik tetapi ada usaha melawan gravitasi <br>
            3 = Tidak ada usaha untuk melawan gravitasi <br>
            4 = Tidak ada gerakan <br>
   
          </font>
        </td>	
        <td height="82"  style="text-align: center; vertical-align: middle;">
          
            K<br>
            I<br>
            R<br>
            I<br> 
        </td>
        
   
         <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
           <font> {{isset($data['skorMasukRsMotorikTungkaiKiri']) ? $data['skorMasukRsMotorikTungkaiKiri'] : '-'}} </font> 
          </div>
        </td>
         <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
        <font> {{isset($data['skorPerubahanKondisiMotorikTungkaiKiri']) ? $data['skorPerubahanKondisiMotorikTungkaiKiri'] : '-'}} </font> 
            
          </div>
        </td>
   
         <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
        <font> {{isset($data['skorDischargeMotorikTungkaikiri']) ? $data['skorDischargeMotorikTungkaikiri'] : '-'}} </font> 
          </div>
        </td>
      </tr>
      <tr>
        <td height="82"  style="text-align: center; vertical-align: middle;">
          
            K<br>
            A<br>
            N<br>
            A<br>
            N                    
        </td>
        
        <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
          <font> {{isset($data['skorMasukRsMotorikTungkaiKanan']) ? $data['skorMasukRsMotorikTungkaiKanan'] : '-'}} </font>       
          </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
          
           <font> {{isset($data['skorPerubahanKondisiMotorikTungkaiKanan']) ? $data['skorPerubahanKondisiMotorikTungkaiKanan'] : '-'}} </font> 
          
          </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" > 
          <div class="column pt-3 pb-0">
             <font> {{isset($data['skorDischargeMotorikTungkaiKanan']) ? $data['skorDischargeMotorikTungkaiKanan'] : '-'}} </font>  
          </div>
        </td>
      </tr>
        
        
      <tr>
          <td  style="text-align: center; vertical-align: middle;" >7</td>
          <td style="text-align: left; vertical-align: middle;" >
          <font  class="font-2"> 
            Ataksia anggota badan. Menggunakan test unjuk jari hidung.
          </font>
          </td>
          <td colspan="2" style="text-align: left; vertical-align: middle;" >
          <p  class="font-2">
            0 = Tidak ada ataksia<br>
            1 = Ataksia pada 1 ekstremitas<br>
            2 = Ataksia pada 2 atau lebih esktremitas<br>
   
          </p>
        </td>
        <td style="text-align: center; vertical-align: middle;" > 
   
    <div class="column pt-3 pb-0">
       <font> {{isset($data['skorMasukRsAtaksia']) ? $data['skorMasukRsAtaksia'] : '-'}} </font> 
    </div>
    </td>
           <td style="text-align: center; vertical-align: middle;" > 
   
    <div class="column pt-3 pb-0">
     <font> {{isset($data['skorPerubahanKondisiAtaksia']) ? $data['skorPerubahanKondisiAtaksia'] : '-'}} </font>     
    </div>
    </td>
        <td style="text-align: center; vertical-align: middle;" > 
   
    <div class="column pt-3 pb-0">
      <font> {{isset($data['skorDischargeAtaksia']) ? $data['skorDischargeAtaksia'] : '-'}} </font> 
      
    </div>
    </td>
      </tr> 
        
        <tr>
          <td  style="text-align: center; vertical-align: middle;" >8</td>
          <td style="text-align: left; vertical-align: middle;" >
            <font  class="font-2"> 
              Sensorik. Lakukan test pada seluruh tubuh: tungkai. lengan. badan, dan wajah. Pasien afasia diberi niliai 1. Pasien stupor dan koma diberi nilai 2. 
            </font>
          </td>
          <td colspan="2" style="text-align: left; vertical-align: middle;" >
            <p  class="font-2">
              0 = Normal<br>
              1 = Gangguan sensorik ringan hingga sedang. Ada gangguan sensorik terhadap nyeri tetapi masih merasa bila disentuh<br>
              2 = Gangguan sensorik berat atau total<br>
            </p>
          </td>
          <td style="text-align: center; vertical-align: middle;" > 
   
            <div class="column pt-3 pb-0">
           <font> {{isset($data['skorMasukRsSensorik']) ? $data['skorMasukRsSensorik'] : '-'}} </font> 
            </div>
          </td>
          <td style="text-align: center; vertical-align: middle;" > 
   
            <div class="column pt-3 pb-0">
         <font> {{isset($data['skorPerubahanKondisiSensorik']) ? $data['skorPerubahanKondisiSensorik'] : '-'}} </font> 
         
            </div>
          </td>
        <td style="text-align: center; vertical-align: middle;" > 
       
          <div class="column pt-3 pb-0">
         <font> {{isset($data['skorDischargeSensorik']) ? $data['skorDischargeSensorik'] : '-'}} </font> 
        
          </div>
        </td>
      </tr> 
    
    
      <tr>
          <td  style="text-align: center; vertical-align: middle;" >9</td>
          <td style="text-align: left; vertical-align: middle;" >
          <font  class="font-2"> 
            Kemampuan berbahasa. Anjurkan pasien untuk menjelaskan suatu gambar atau membaca suatu tulisan. Bila pasien mengalami kebutaan, letakkan suatu benda ditangan pasien dan anjurkan untuk menjelaskan benda tersebut. 
          </font>
          </td>
          <td colspan="2" style="text-align: left; vertical-align: middle;" >
            <p  class="font-2">
              0 = Normal<br>
              1 = Afasia ringan hingga sedang<br>
              2 = Afasia berat <br>
              3 = mute, afasia global, koma
   
            </p>
          </td>
          <td style="text-align: center; vertical-align: middle;" > 
   
            <div class="column pt-3 pb-0">
          <font> {{isset($data['skorMasukRsKemampuanBahasa']) ? $data['skorMasukRsKemampuanBahasa'] : '-'}} </font> 
                 
            </div>
          </td>
         <td style="text-align: center; vertical-align: middle;" > 
       
            <div class="column pt-3 pb-0">
         <font> {{isset($data['skorPerubahanKondisiKemampuanBahasa']) ? $data['skorPerubahanKondisiKemampuanBahasa'] : '-'}} </font> 
            
            </div>
        </td>
       <td style="text-align: center; vertical-align: middle;" > 
   
          <div class="column pt-3 pb-0">
        <font> {{isset($data['skorDischargeKemampuanBahasa']) ? $data['skorDischargeKemampuanBahasa'] : '-'}} </font> 
             
          </div>
        </td>
      </tr> 
   
    
      <tr>
          <td  style="text-align: center; vertical-align: middle;" >10</td>
          <td style="text-align: left; vertical-align: middle;" >
            <font  class="font-2"> 
              Disartria
            </font>
          </td>
          <td colspan="2" style="text-align: left; vertical-align: middle;" >
              <p  class="font-2">
                0 = Normal<br>
                1 = Disartria ringan<br>
                2 = Disartria berat <br>
              </p>
          </td>
          <td style="text-align: center; vertical-align: middle;" >
   
            <div class="column pt-3 pb-0">
          
         <font> {{isset($data['skorDischargeKemampuanBahasa']) ? $data['skorDischargeKemampuanBahasa'] : '-'}} </font>
                <VField  >
                <VControl>
   
                  <VInput type="number" v-model="input.skorMasukRsDisartria" v-on:keyup="hitungSkorMasukRs()" min="0" placeholder="" />
                </VControl>
                </VField>
            </div>
          </td>
          <td style="text-align: center; vertical-align: middle;" >
   
            <div class="column pt-3 pb-0">
         <font> {{isset($data['skorPerubahanKondisiDisartria']) ? $data['skorPerubahanKondisiDisartria'] : '-'}} </font>
              
            </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" >
   
          <div class="column pt-3 pb-0">
        <font> {{isset($data['skorPerubahanKondisiDisartria']) ? $data['skorPerubahanKondisiDisartria'] : '-'}} </font>  
          </div>
        </td>
      </tr> 
      
    
      <tr>
          <td  style="text-align: center; vertical-align: middle;" >11</td>
          <td style="text-align: left; vertical-align: middle;" >
            <font  class="font-2"> 
              Neglect atau inatensi
            </font>
          </td>
          <td colspan="2" style="text-align: left; vertical-align: middle;" >
            <p  class="font-2">
              0 = Tidak ada neglect <br>
              1 = Tidak ada atensi pada salah satu modalitas berikut visual, tactile, auditory, spatial, atau personal inattention<br>
              2 = Tidak ada atensi pada lebih dari satu modalitas<br>
   
            </p>
          </td>
          <td style="text-align: center; vertical-align: middle;" >
   
            <div class="column pt-3 pb-0">
          <font> {{isset($data['skorMasukRsNeglect']) ? $data['skorMasukRsNeglect'] : '-'}} </font> 
            </div>
          </td>
          <td style="text-align: center; vertical-align: middle;" >
   
            <div class="column pt-3 pb-0">
          <font> {{isset($data['skorPerubahanKondisiNeglect']) ? $data['skorPerubahanKondisiNeglect'] : '-'}} </font> 
            </div>
          </td>
          <td style="text-align: center; vertical-align: middle;" >
   
            <div class="column pt-3 pb-0">
          <font> {{isset($data['skorDischargeNeglect']) ? $data['skorDischargeNeglect'] : '-'}} </font>
            </div>
          </td>
      </tr> 
   
   
          
      <tr>
        <td colspan="4"  style="text-align: center; vertical-align: middle;" ><font style="font-size: 14px">TOTAL NILAI</font></td>
        <td style="text-align: center; vertical-align: middle;" >
          <div class="column pt-3 pb-0">
          <font> {{isset($data['totalNilaiSkorMasukRS']) ? $data['totalNilaiSkorMasukRS'] : '-'}} </font>  
          </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" >
          <div class="column pt-3 pb-0">
          <font> {{isset($data['totalNilaiSkorPerubahanKondisi']) ? $data['totalNilaiSkorPerubahanKondisi'] : '-'}} </font>
          </div>
        </td>
        <td style="text-align: center; vertical-align: middle;" >
          <div class="column pt-3 pb-0">
          <font> {{isset($data['totalNilaiSkorDischarge']) ? $data['totalNilaiSkorDischarge'] : '-'}} </font>
          </div>
        </td>
      </tr> 
        
      <tr>
        <td colspan="4" style="text-align: center; vertical-align: middle;" ><font style="font-size: 14px">PARAF</font></td>
        <td class="tc" style="text-align: center; vertical-align: middle;">
          <img src="data:image/png;base64, {!! $qrcode !!}" style="width: 100px; height: 100px;">
      </td>
      <td class="tc" style="text-align: center; vertical-align: middle;">
          <img src="data:image/png;base64, {!! $qrcode2 !!}" style="width: 100px; height: 100px;">
      </td>
      <td class="tc" style="text-align: center; vertical-align: middle;">
          <img src="data:image/png;base64, {!! $qrcode3 !!}" style="width: 100px; height: 100px;">
      </td>
      </tr> 
        
      
      <tr>
        <td colspan="4"  style="text-align: center; vertical-align: middle;" ><font style="font-size: 14px">NAMA</font></td>
        <td style="text-align: center; vertical-align: middle;" >
          <div class="font-2">
            {{isset($data['pegawaiPemberiSkorMasukRs']) ? $data['pegawaiPemberiSkorMasukRs']['label'] : '-'}}
          </div>
        </td>
       <td style="text-align: center; vertical-align: middle;" >
          <div class="font-2">
            {{isset($data['pegawaiPemberiSkorPerubahanKondisi']) ? $data['pegawaiPemberiSkorPerubahanKondisi']['label'] : '-'}}
          </div>
        </td>
        
        <td style="text-align: center; vertical-align: middle;" >
          <div class="font-2">
            {{isset($data['pegawaiPemberiSkorDischarge']) ? $data['pegawaiPemberiSkorDischarge']['label'] : '-'}}
          </div>
        </td>
        
      </tr> 
   
   
      <tr>
        <td colspan="7"  style="text-align: left; vertical-align: middle;"  class="font-2" >
            Interpretasi Skor NIHSS <br>
            Skor &lt; 5 : Defisit neurologis ringan <br>
            Skor 5-14 : Defisit neurologis sedang/cukup berat<br>
            Skor 15-24 : Defisit neurologis berat<br>
            Skor > 25 : Defisit neurologis sangat berat<br>
   
        </td>
      </tr> 
    
    
    </tbody>
   </table>
</body>
</html>
<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Kriteria Robson</h3>
            </div>
            <div class="right buttons">
              <!-- <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                              :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                              @kembaliKeun="kembaliKeun"></ButtonEmr> -->
              <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                @click="print()"> Cetak
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
              </VButton>
              <!-- <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpanTemplate()"> Simpan Template
              </VButton> -->
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="column is-12">
          <table class="table is-bordered is-fullwidth">
            <thead>
              <tr>
                <th class="grey-background">INDIKATOR PROSES</th>
                <th class="grey-background">YA (✓)</th>
                <th class="grey-background">TIDAK (✓)</th>
                <th class="grey-background">KETERANGAN</th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th></th>
                <th></th>
              </tr>
              <tr>
                <th>1 Pasien melakukan ANC minimal 3X di rumah sakit tersebut</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.anc" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.cekanc" true-value="Tidak" color="primary" />
                </th>
                <th class="">
                  <textarea v-model="input.textanc" color="primary" />
                </th>
              </tr>
            </thead>

            <thead>
              <tr>
                <th></th>
                <th></th>
              </tr>
              <tr>
                <th>2 Pasien memiliki dan membawa buku pink KIA sebelum SC</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kia" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kis" true-value="Tidak" color="primary" />
                </th>
                <th class="">
                  <textarea v-model="input.kos" true-value="Tidak" color="primary" />
                </th>
              </tr>
              <tr>
                <th>3 Pasien datang dengan KU baik sebelum tindakan SC</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.ku" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.ka" true-value="Tidak" color="primary" />
                </th>
                <th class="">
                  <textarea v-model="input.okeh"  color="primary" />
                </th>
              </tr>
              <tr>
                <th>4 Pasien dengan KU baik setelah tindakan SC</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kusetelah" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kesetelah" true-value="Tidak" color="primary" />
                </th>
                <th class="">
                  <textarea v-model="input.scsetealh" true-value="Tidak" color="primary" />
                </th>
              </tr>
              <tr>
                <th>5. Pasien datang dengan GCS normal (14-15) sebelum SC</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.gcs" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.sgc" true-value="Tidak" color="primary" />
                </th>
                <th class="">
                  <textarea v-model="input.cgs" true-value="Tidak" color="primary" />
                </th>
              </tr>
              <tr>
                <th>6. Pasien mengalami perubahan TD sistolik > 30 mmHg sebelum dan setelah SC disertai gejala syok</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.td" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.dt" true-value="Tidak" color="primary" />
                </th>
                <th class="">
                  <textarea v-model="input.dr" true-value="Tidak" color="primary" />
                </th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tr>
              <th>7. Pasien diperiksa darah lengkap sebelum SC (Hb, Leukosit,Trombosit, Ht)
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.hb" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.bh" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.hhb" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <thead>
              <tr>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tr>
              <th>8. Pasien diperiksa darah lengkap setelah SC (Hb, Leukosit,Trombosit, Ht)</th>
              <th class="yes-column">
                <VCheckbox v-model="input.leukosit" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.leu" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.eul" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>
              9. Pasien yang diperiksa:
              <div>
                <div>A. PT/APTT</div>
                <div>B. CT/BT sebelum SC</div>
              </div>
            </th>
              <th class="yes-column">
                <VCheckbox v-model="input.pt" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.ct" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.bt" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>10. Pasien dilakukan transfusi darah sesuai indikasi dan/atau memiliki Hb  8 g/dLsebelum SC</th>
              <th class="yes-column">
                <VCheckbox v-model="input.darah" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.transui" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.hb" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>11. Pasien diperiksa golongan darah sebelum SC</th>
              <th class="yes-column">
                <VCheckbox v-model="input.gol" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.lon" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.gan" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>12. Pasien diperiksa urinalis sebelum tindakan SC</th>
              <th class="yes-column">
                <VCheckbox v-model="input.urin" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.lis" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.nalis" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>13. Pasien memiliki data USG sebelum SC</th>
              <th class="yes-column">
                <VCheckbox v-model="input.ush" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.ugs" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.ags" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>14. Pasien memiliki data laboratorium HIV sebelum SC</th>
              <th class="yes-column">
                <VCheckbox v-model="input.hiv" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.hip" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.hipe" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>15. Pasien memiliki data laboratorium Hepatitis sebelum SC</th>
              <th class="yes-column">
                <VCheckbox v-model="input.lab" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.bal" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.hepati" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>16. Asesmen persalinan pasien menggunakan partograf ditulis lengkap sebelum SC</th>
              <th class="yes-column">
                <VCheckbox v-model="input.parto" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.graf" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.ases" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <thead>
              <tr>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tr>
              <th>17. Pasien dilakukan SC sesuai dengan indikasi :</th>
              <th class="yes-column">
                <VCheckbox v-model="input.indi" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.svi" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.dsc" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>A. PEB
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.peb" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.bep" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.ebp" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>

              <th></th>
              <th></th>
            </tr>
            <tr>
              <th>B. Ketuban Pecah Dini
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.dini" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.pecah" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.ketub" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th></th>
              <th></th>
            </tr>
            <tr>
              <th>C. Bekas Sectio
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.sec" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.tio" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.bekas" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>

              <th></th>
              <th></th>
            </tr>
            <tr>
              <th>D. Kelainan Letak Janin
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.letak" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.janin" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.kelain" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th></th>
              <th></th>
            </tr>
            <tr>
              <th>E. Gagal Induksi
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.ggl" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.indk" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.sik" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th></th>
              <th></th>
            </tr>
            <tr>
              <th>F. Kelainan Letak Plasenta
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.plas" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.enta" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.kel" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th></th>
              <th></th>
            </tr>
            <tr>
              <th>G. Persalinan Tidak Maju
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.maju" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.tdk" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.sln" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>

              <th></th>
              <th></th>
            </tr>
            <tr>
              <th>H. Disproporsi Kepala Panggul
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.dispo" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.kpla" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.porsi" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>

              <th></th>
              <th></th>
            </tr>
            <tr>
              <th>I. Lain-Lainnya
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.laen" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.lain" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.lainya" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th class="grey-background">Diagnosis Kehamilan Pasien</th>
              <th class="grey-background"></th>
              <th class="grey-background"></th>
              <th class="grey-background"></th>
            </tr>
            <tr>
              <th>1. Nullipara, janin tunggal, presentasi kepala, usia kehamilan ≥ 37minggu, persalinan dengan induksi atau dengan SC sebelum persalinan
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.nuli" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.para" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.hml" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>2. Nullipara, janin tunggal, presentasi kepala, usia kehamilan ≥ 37minggu, persalinan dengan induksi atau dengan SC sebelumpersalinan
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.per" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.sen" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.tasi" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>2a. Induksi persalinan
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.por" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.abcc" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.bcd" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>2b. SC sebelum persalinan
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.sblm" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.cdf" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.bree" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>3. Multipara, tanpa riwayat perlukaan uterus, janin tunggal,presentasi kepala, usia kehamilan ≥ 37 minggu, lahir spontan
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.mul" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.tip" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.ara" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>4. Multipara, tanpa riwayat perlukaan uterus, janin tunggal,presentasi kepala, usia kehamilan ≥ 37 minggu, persalinan dengan induksi atau dengan SC sebelum persalinan
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.jan" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.uar" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.ri" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>4a. Induksi persalinan
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.dingi" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.bangt" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.cantik" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>4b. SC sebelum persalinan
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.anget" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.bay" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.coy" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>5. Seluruh kehamilan multipara, memiliki riwayat perlukaan uterus, janin tunggal, presentasi kepala, usia kehamilan ≥ 37minggu
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.mlkik" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.rwyt" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.tywr" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>5a. Riwayat 1 SC
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.rytr" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.rwyt1" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.satu" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>5b. Riwayat ≥ 2 SC
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.riew" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.bals" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.bale" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>6. Nullipara, janin tunggal, sungsang
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.pik" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.pok" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.pek" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>7. Multipara, janin tunggal, sungsang, memiliki riwayat perlukaan uterus
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.perlk" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.pelk" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.abs" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>8. Seluruh kehamilan dengan janin multipel, memiliki riwayat perlukaan uterus
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.jamin" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.minjam" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.jem" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>9. Seluruh kehamilan dengan janin tunggal, posisi janin oblik atau melintang, memiliki riwayat perlukaan uterus
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.hamik" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.indo" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.nesia" true-value="Tidak" color="primary" />
                </th>
            </tr>
           <tr>
              <th>10. Seluruh kehamilan dengan janin tunggal, presentasi kepala,usia kehamilan ≤ 36 minggu, memiliki riwayat perlukaan uterus
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.maly" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.ysia" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.hebt" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th class="grey-background">INDIKATOR LUARAN</th>
              <th class="grey-background"></th>
              <th class="grey-background"></th>
              <th class="grey-background"></th>
            </tr>
            <tr>
              <th>1. Pasien meninggal (Ibu) pasca dilakukan tindakan SC</th>
              <th class="yes-column">
                <VCheckbox v-model="input.pascasc1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.pascasc2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.pascasc3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>2. Pasien meninggal (Ibu) pasca dilakukan tindakan SC yang
                merupakan pasien rujukan</th>
              <th class="yes-column">
                <VCheckbox v-model="input.rujukansc1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.rujukansc2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.rujukansc3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>3. Pasien yang mengalami komplikasi pasca tindakan SC (syok
                  hipovolemik, syok lain, sepsis, gagal ginjal, gagal jantung,
                  ARDS, atau komplikasi lainnya)</th>
              <th class="yes-column">
                <VCheckbox v-model="input.komplikasisc1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.komplikasisc2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.komplikasisc3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>4. Pasien yang mengalami perluasan tindakan (ligasi, B-lynch,
                histerektomi, pembedahan lain akibat cedera organ)</th>
              <th class="yes-column">
                <VCheckbox v-model="input.perluasan1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.perluasan2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.perluasan3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>5. Pasien yang memerlukan perluasan pengobatan (transfusi
                darah, hemodialisis, heparin)</th>
              <th class="yes-column">
                <VCheckbox v-model="input.pengobatan1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.pengobatan2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.pengobatan3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>6. Pasien yang saat pulang membutuhkan perawatan lanjutan</th>
              <th class="yes-column">
                <VCheckbox v-model="input.perawatan1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.perawatan2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.perawatan3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>7. Neonatus meninggal pasca dilakukan tindakan SC di rumah
                sakit setempat</th>
              <th class="yes-column">
                <VCheckbox v-model="input.neonatus1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.neonatus2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.neonatus3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>8. Neonatus meninggal pasca dilakukan tindakan SC di rumah
                sakit setempat yang ibunya merupakan pasien rujukan</th>
              <th class="yes-column">
                <VCheckbox v-model="input.meninggal1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.meninggal2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.meninggal3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>9. Neonatus yang mengalami komplikasi pasca tindakan SC
                (RDS, Sepsis, HIE) di rumah sakit setempat</th>
              <th class="yes-column">
                <VCheckbox v-model="input.komplikasi1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.komplikasi2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.komplikasi3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>10. Neonatus yang memerlukan perluasan pengobatan (CPAP,
                Ventilator, Transfusi) di rumah sakit setempat</th>
              <th class="yes-column">
                <VCheckbox v-model="input.ventilator1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.ventilator2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.ventilator3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>11. Neonatus yang saat pulang membutuhkan perawatan lanjutan
                di rumah sakit setempat</th>
              <th class="yes-column">
                <VCheckbox v-model="input.lanjutan1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.lanjutan2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.lanjutan3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>12. Tarif pembiayaan SC rumah sakit tidak melebihi tarif INACBGs</th>
              <th class="yes-column">
                <VCheckbox v-model="input.tarif1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.tarif2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.tarif3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>13. Tarif pembiayaan persalinan pervaginam rumah sakit yang
                tidak melebihi tarif INA-CBGs</th>
              <th class="yes-column">
                <VCheckbox v-model="input.pervaginam1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.pervaginam2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.pervaginam3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>14. Perbedaan tarif INA-CBGs dengan tarif pembiayaan rumah
                sakit</th>
              <th class="yes-column">
                <VCheckbox v-model="input.perbedaan1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.perbedaan2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.perbedaan3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>a. Rerata perbedaan tarif INA-CBGs dengan tarif pembiayaan
                rumah sakit untuk SC</th>
              <th class="yes-column">
                <VCheckbox v-model="input.perbedaana1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.perbedaana2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.perbedaana3" true-value="Tidak" color="primary" />
                </th>
            </tr>
            <tr>
              <th>b. Rerata perbedaan tarif INA-CBGs dengan tarif pembiayaan
                rumah sakit untuk persalinan pervaginam</th>
              <th class="yes-column">
                <VCheckbox v-model="input.perbedaanb1" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.perbedaanb2" true-value="Tidak" color="primary" />
              </th>
              <th class="">
                  <textarea v-model="input.perbedaanb3" true-value="Tidak" color="primary" />
                </th>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>


  <!-- <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalTemplate = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                  <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                  <td class="tg-0lax text-center" width="20%">No EMR</td>
                  <td class="tg-0lax text-center" width="20%">Dokter</td>
                  <td class="tg-0lax text-center" width="20%">Section</td>
                  <td class="tg-0lax text-center" width="5%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>

  <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
    @close="showModalTemplateFix = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Template</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplateFix.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="15%">No</td>
                  <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                  <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                  <td class="tg-0lax text-center" width="50%">Nama Template</td>
                  <td class="tg-0lax text-center" width="15%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplateFix">
                <tr>
                  <td style="width:15%;text-align:center">
                    <span class="mb-2">{{ resep.no }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:50%;text-align:center">
                    <span class="mb-2">{{ resep.namatemplate }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal> -->
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'

useHead({
  title: 'Kriteria Robson - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
let detailStatusFungsional = ref(EMR.detailStatusFungsional())
let statusFungsional: any = ref(EMR.statusFungsional())
let DiagnosaKeperawatanRanap: any = ref(EMR.DiagnosaKeperawatanRanap())
let RencanaKeperawatan: any = ref(EMR.RencanaKeperawatan())
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
  }
)

const route = useRoute()
const pasien: any = ref({})
const d_pegawai: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({})
const COLLECTION: any = ref('Kriteriarobson') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  scsetealh : 'OBGYN',
  dr : 'OBGYN',
  eul : 'OBGYN',
  pascasc3 : 'OBGYN',
  rujukansc3 : 'OBGYN',
  komplikasisc3 : 'OBGYN',
  perluasan3 : 'OBGYN',
  pengobatan3 : 'OBGYN',
  perawatan3 : 'OBGYN',
  neonatus3 : 'ANAK',
  meninggal3 : 'ANAK',
  komplikasi3 : 'ANAK',
  ventilator3 : 'ANAK',
  lanjutan3 : 'ANAK',
  tarif3 : 'RANAP',
  pervaginam3 : 'RANAP',
  perbedaan3 : 'RANAP',
  perbedaana3 : 'RANAP',
  perbedaanb3 : 'RANAP',
})
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const d_freknyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang Timbul' }, { value: 3, label: 'Terus Menerus' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lain-lain' }])
const d_gangguanpsikologis: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Gelisah' }, { value: 3, label: 'Takut' }, { value: 4, label: 'Sedih' }, { value: 5, label: 'Rendah diri' }, { value: 6, label: 'Acuh tak acuh' }, { value: 7, label: 'Mudah tersinggung' }, { value: 8, label: 'Menarik diri' }])
const d_masalahkawin: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_kekerasan: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaankesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi Lainnya' }])
const d_rohaniawan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_penurunanbb: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }, { value: 3, label: '1-5 kg' }, { value: 4, label: '6-10 kg' }, { value: 5, label: '11-15 kg' }, { value: 6, label: '>15 kg' }])
const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_mengontrolbab: any = ref([{ value: 1, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 2, label: 'Kadang inkontinen (1xseminggu)' }, { value: 3, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 1, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 2, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 3, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 1, label: 'Butuh pertolongan orang lain' }, { value: 2, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 1, label: 'Tergantung pertolongan orang lain' }, { value: 2, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 3, label: 'Mandiri' }])
const d_makan: any = ref([{ value: 1, label: 'Tidak mampu' }, { value: 2, label: 'Perlu seseorang menolong memotong makanan' }, { value: 3, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Dengan kursi roda' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 1, label: 'Tergantung orang lain' }, { value: 2, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 3, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Butuh Pertolongan' }, { value: 3, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 1, label: 'Teragantung orang lain' }, { value: 2, label: 'Mandiri' }])
const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)

let listHipertensi: any = ref(EMR.hipertensi())
let listDiabetes: any = ref(EMR.diabetes())
let listDyslipidemia: any = ref(EMR.dyslipidemia())
let listDuaPilihan: any = ref(EMR.duaPilihan())
let listAgama: any = ref(EMR.agama())
let listStatus: any = ref(EMR.status())
let listKeluarga: any = ref(EMR.keluarga())
let listTempatTinggal: any = ref(EMR.tempatTinggal())
let listPsikologis: any = ref(EMR.psikologis())
let listMore: any = ref(EMR.more())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let resikoNutrisional: any = ref(EMR.resikoNutrisional())
let fungsionalPertama: any = ref(EMR.fungsionalPertama())
let listRangeNilaiPoin: any = ref(EMR.nilaiPoin())
let listDESCNilai: any = ref(EMR.descNilai())
let pertanyaanA: any = ref(EMR.pertanyaanA())
let pertanyaanB: any = ref(EMR.pertanyaanB())
let pertanyaanC: any = ref(EMR.pertanyaanC())
let dropdownAllo: any = ref([
  "Suami/Istri",
  "Orang tua",
  "Anak",
  "Lainnya"
])

const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    H.tandaTangan().set("TTDPegawai", response[0]['TTDPegawai'])
  } else {
    getDataExist()
  }
}
const filterMenu: any = ref('')
const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDPegawai'] = H.tandaTangan().get("TTDPegawai");
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}`).then((response) => {
    d_pegawai.value = response
  })
}

const getDataExist = () => {
  input.value.tanggal = new Date()
  input.value.kebjamKedatangan = new Date()
  input.value.kebjamAsesmenAwal = new Date()
}

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true&noregistrasi=${props.registrasi.noregistrasi}`)
}

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});


// const simpanTemplate = () => {
//   if (!input.value.namatemplate) {
//     H.alert('warning', "Nama Template wajib diisi")
//     return;
//   }
//   let ID = input.id ? input.id : ''

//   let object: any = {}

//   object = input.value
//   object.nocm = pasien.value.nocm

//   object.pasien = H.setObjectPasien(pasien.value)
//   object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
//   let json = {
//     'id': ID,
//     'norec_emr': NOREC_EMRPASIEN.value,
//     'collection': COLLECTION.value,
//     'url_form': props.FORM_URL,
//     'name_form': props.FORM_NAME,
//     'jenis_emr': 'asesmen_medis',
//     'data': object
//   }
//   isLoading.value = true

//   useApi().post(
//     `/emr/simpan-emr-template`, json).then((response: any) => {
//       isLoading.value = false
//       input.value.namatemplate = null
//     }).catch((e: any) => {
//       isLoading.value = false
//     })
// }

// const pilihTemplate = async (index: any) => {
//   isLoading.value = true
//   useApi().get(
//     `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
//       isLoading.value = false
//       if (responselast.length) {
//         listTemplate.value = responselast //set ke inputan
//         showModalTemplate.value = true
//       } else {
//         H.alert('warning', 'Data tidak ada')
//       }
//     })
// }

// const addTemplate = (response: any) => {
//   console.log(response)
//   input.value = response //set ke inputan
//   input.value.namatemplate = null
// }

// const pilihTemplateFix = async (index: any) => {
//   isLoading.value = true
//   useApi().get(
//     `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
//       isLoading.value = false
//       console.log(responselast)
//       if (responselast.length) {
//         for (var x = 0; x < responselast.length; x++) {
//           responselast[x].no = x + 1
//           responselast[x].id = ''
//         }
//         listTemplateFix.value = responselast //set ke inputan
//         showModalTemplateFix.value = true
//       } else {
//         H.alert('warning', 'Data tidak ada')
//       }
//     })
// }
</script>

<style lang="scss">
.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

table.assesment {
  border-collapse: collapse;
  width: 100%;
}


.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
  // border: 1px solid black;
}

.assesment th,
.assesment td {
  padding: 8px;
  vertical-align: middle !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

.table.is-borderless {
  border: none !important;
  background-color: transparent;
}

.table.is-borderless th,
tr,
td {
  border: none !important;
  background-color: transparent !important;
}

.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

table.assesment {
  border-collapse: collapse;
  width: 100%;
}

.grey-background {
  background-color: #d3d3d3;
  /* Grey color */
}

.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
  // border: 1px solid black;
}

.assesment th,
.assesment td {
  padding: 8px;
  vertical-align: middle !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
}

.yes-column{
  background-color: green;
}

.no-column{
  background-color: red;
}

.tg2 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg2 td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg2 th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: middle
}
</style>

<style lang="scss">
td {
  padding: 3px;
}
</style>
<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> Pengkajian Tingkat Keparahan Stroke <br>Menggunakan National Institute Of Health Stroke Scale (NIHSS)
            </h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton
                type="button"
                rounded
                outlined
                color="warning"
                raised
                icon="lnir lnir-printer"
                :disabled="isDisabled"
                @click="print"
              >
                Cetak
              </VButton>
              <VButton
                type="button"
                rounded
                outlined
                color="primary"
                raised
                icon="feather:save"
                :loading="isLoading"
                @click="simpan()"
              >
                Simpan
              </VButton>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <table width="100%" border="1">
          <tbody>
            <tr>
              <td width="3%" rowspan="2" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 12px"><strong>NO</strong>
                </font>
              </td>
              <td width="18%" rowspan="2" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 12px"><strong>PARAMETER YANG DINILAI</strong>
                </font>
              </td>
              <td colspan="2" rowspan="2" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 12px"><strong>SKALA</strong>
                </font>
              </td>
              <td width="18%" height="40" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 12px"><strong>SKOR MASUK RS</strong>
                </font>
              </td>
              <td width="17%" height="40" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 12px"><strong>SKOR SAAT PERUBAHAN KONDISI</strong>
                </font>
              </td>
              <td width="14%" height="40" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 12px"><strong>SKOR DISCHARGE</strong>
                </font>
              </td>
            </tr>
            <tr>
              <td width="18%" style="text-align: center; vertical-align: middle;">

                <div class="column pt-3">
                  <font style="font-size: 12px"><strong>Tanggal</strong> </font>
                  <VDatePicker v-model="input.tglSkorMasukRs" mode="dateTime" style="width: 100%" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>

              </td>

              <td width="17%" style="text-align: center; vertical-align: middle;">
                <div class="column pt-3">
                  <font style="font-size: 12px"><strong>Tanggal</strong> </font>
                  <VDatePicker v-model="input.tglSkorPerubahanKondisi" mode="dateTime" style="width: 100%" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
              </td>
              <td width="14%" style="text-align: center; vertical-align: middle;">
                <div class="column pt-3">
                  <font style="font-size: 12px"><strong>Tanggal</strong> </font>
                  <VDatePicker v-model="input.tglSkorDischarge" mode="dateTime" style="width: 100%" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
              </td>
            </tr>
            <tr>
              <td width="3%" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 14px">1a
                </font>
              </td>
              <td style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Tingkat Kesadaran
                </font>
              </td>
              <td colspan="2" style="text-align: left; vertical-align: middle;">
                <p>0 = Sadar penuh<br>
                  1 = Somnolen<br>
                  2 = Stupor<br>3 = Koma
                </p>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsTingkatKesadaran" v-on:keyup="hitungSkorMasukRs()"
                        min="0" max="3" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiTingkatKesadaran"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" max="3" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>

              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeTingkatKesadaran"
                        v-on:keyup="hitungSkorDischarge()" min="0" max="3" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>

            <tr>
              <td width="3%" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 14px">1b</font>
              </td>
              <td style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Menjawab pertanyaan. Tanyakan bulan dan usia pasien. Yang dinilai adalah jawaban pertama, pemeriksa
                  tidak
                  diperkenankan membantu pasien dengan verbal atau non verbal.
                </font>
              </td>
              <td colspan="2" style="text-align: left; vertical-align: middle;">
                <p>

                  0 = Benar semua (2 pertanyaan)<br>
                  1 = 1 benar/ETT/Disartria<br>
                  2 = Salah semua/afasia/stupor/koma<br>

                </p>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>
                      <VInput type="number" v-model="input.skorMasukRsMenjawabPertanyaan"
                        v-on:keyup="hitungSkorMasukRs()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiMenjawabPertanyaan"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeMenjawabPertanyaan"
                        v-on:keyup="hitungSkorDischarge()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>


            <tr>
              <td width="3%" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 14px">1c
                </font>
              </td>
              <td style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Mengikuti perintah. Berikan dua perintah sederhana, membuka dan menutup mata, menggenggam tangan dan
                  melepaskannya
                  atau dua perintah lain.
                </font>
              </td>
              <td colspan="2" style="text-align: left; vertical-align: middle;">
                <p>
                  0 = Mampu melakukan 2 perintah<br>
                  1 = Mampu melakukan 1 perintah<br>
                  2 = Tidak mampu melakukan perintah
                </p>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>
                      <VInput type="number" v-model="input.skorMasukRsMengikutiPerintah"
                        v-on:keyup="hitungSkorMasukRs()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiMengikutiPerintah"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeMengikutiPerintah"
                        v-on:keyup="hitungSkorDischarge()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>

            <tr>
              <td width="3%" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 14px">2</font>
              </td>
              <td style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Gaze: Gerakan mata konyugat horizontal
                </font>
              </td>
              <td colspan="2" style="text-align: left; vertical-align: middle;">
                <p>
                  0 = Normal<br>
                  1 = Abnormal pada 1 mata<br>
                  2 = Deviasi konyugat kuat atau paresis konyugat pada 2 mata
                </p>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsGaze" v-on:keyup="hitungSkorMasukRs()" min="0"
                        placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiGaze"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeGaze" v-on:keyup="hitungSkorDischarge()" min="0"
                        placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>


            <tr>
              <td width="3%" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 14px">3</font>
              </td>
              <td style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Visual: Lapang pandang pada tes konfrontasi
                </font>
              </td>
              <td colspan="2" style="text-align: left; vertical-align: middle;">
                <p>
                  0 = Tidak ada gangguan<br>
                  1 = Kuadrianopsia<br>
                  2 = Hemianopia total<br>
                  3 = Hemianopia bilateral/buta kortikal
                </p>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsVisual" v-on:keyup="hitungSkorMasukRs()" min="0"
                        placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiVisual"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeVisual" v-on:keyup="hitungSkorDischarge()"
                        min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>


            <tr>
              <td width="3%" style="text-align: center; vertical-align: middle;">4</td>
              <td style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">Paresis wajah. Anjurkan pasien menyeringai atau mengangkat alis dan
                  menutup mata
                </font>
              </td>
              <td colspan="2" style="text-align: left; vertical-align: middle;">
                <p>
                  0 = Normal<br>
                  1 = Paresis wajah ringan (lipatan naso labial datar, senyum asimetris)<br>
                  2 = Paresis wajah partial (paresis wajah bawah total atau hampir total)<br>
                  3 = Paresis wajah total (paresis wajah sesisi atau dua sisi)
                </p>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsParesisWajah" v-on:keyup="hitungSkorMasukRs()"
                        min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiParesisWajah"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeParesisWajah" v-on:keyup="hitungSkorDischarge()"
                        min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>


            <tr>
              <td width="3%" rowspan="2" style="text-align: center; vertical-align: middle;">5</td>
              <td rowspan="2" style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Motorik lengan. Anjurkan pasien mengangkat lengan hingga 45&deg; bila tidur berbaring atau 90&deg;
                  bila posisi
                  duduk. Bila pasien afasia berikan perintah menggunakan pantomime atau peragaan.
                </font>
              </td>


              <td width="17%" rowspan="2" style="text-align: left; ">

                <font style="font-size: 14px">
                  0 = Mampu mengangkat lengan minimal 10 detik<br>
                  1 = Lengan terjatuh sebelum 10 detik<br>
                  2 = Tidak mampu mengangkat secara penuh 90&deg; atau 45&deg;<br>
                  3 = Tidak mampu mengangkat hanya bergeser<br>
                  4 = Tidak ada gerakan
                </font>
              </td>
              <td width="13%" height="82" style="text-align: center; vertical-align: middle;">

                K<br>
                I<br>
                R<br>
                I<br>
              </td>


              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsMotorikLenganKiri"
                        v-on:keyup="hitungSkorMasukRs()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiMotorikLenganKiri"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeMotorikLenganKiri"
                        v-on:keyup="hitungSkorDischarge()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>
            <tr>
              <td height="82" style="text-align: center; vertical-align: middle;">

                K<br>
                A<br>
                N<br>
                A<br>
                N
              </td>

              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsMotorikLenganKanan"
                        v-on:keyup="hitungSkorMasukRs()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiMotorikLenganKanan"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeMotorikLenganKanan"
                        v-on:keyup="hitungSkorDischarge()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>


            <tr>
              <td width="3%" rowspan="2" style="text-align: center; vertical-align: middle;">6</td>
              <td rowspan="2" style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Motorik tungkai. Anjurkan pasien tidur terlentang dan mengangkat tungkai 30&deg;
                </font>
              </td>


              <td width="17%" rowspan="2" style="text-align: left;">

                <font style="font-size: 14px">
                  0 = Mampu mengangkat tungkai 30&deg; minimal 5 detik <br>
                  1 = Tungkai jatuh ketempat tidur pada akhir detik ke-5 secara perlahan <br>
                  2 = Tungkai jatuh sebelum 5 detik tetapi ada usaha melawan gravitasi <br>
                  3 = Tidak ada usaha untuk melawan gravitasi <br>
                  4 = Tidak ada gerakan <br>

                </font>
              </td>
              <td width="13%" height="82" style="text-align: center; vertical-align: middle;">

                K<br>
                I<br>
                R<br>
                I<br>
              </td>


              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsMotorikTungkaiKiri"
                        v-on:keyup="hitungSkorMasukRs()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiMotorikTungkaiKiri"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>

              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>


                      <VInput type="number" v-model="input.skorDischargeMotorikTungkaikiri"
                        v-on:keyup="hitungSkorDischarge()" min="0" placeholder="" />

                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>
            <tr>
              <td height="82" style="text-align: center; vertical-align: middle;">

                K<br>
                A<br>
                N<br>
                A<br>
                N
              </td>

              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsMotorikTungkaiKanan"
                        v-on:keyup="hitungSkorMasukRs()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiMotorikTungkaiKanan"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeMotorikTungkaiKanan"
                        v-on:keyup="hitungSkorDischarge()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>


            <tr>
              <td width="3%" style="text-align: center; vertical-align: middle;">7</td>
              <td style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Ataksia anggota badan. Menggunakan test unjuk jari hidung.
                </font>
              </td>
              <td colspan="2" style="text-align: left; vertical-align: middle;">
                <p>
                  0 = Tidak ada ataksia<br>
                  1 = Ataksia pada 1 ekstremitas<br>
                  2 = Ataksia pada 2 atau lebih esktremitas<br>

                </p>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsAtaksia" v-on:keyup="hitungSkorMasukRs()" min="0"
                        placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiAtaksia"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeAtaksia" v-on:keyup="hitungSkorDischarge()"
                        min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>

            <tr>
              <td width="3%" style="text-align: center; vertical-align: middle;">8</td>
              <td style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Sensorik. Lakukan test pada seluruh tubuh: tungkai. lengan. badan, dan wajah. Pasien afasia diberi
                  niliai 1.
                  Pasien stupor dan koma diberi nilai 2.
                </font>
              </td>
              <td colspan="2" style="text-align: left; vertical-align: middle;">
                <p>
                  0 = Normal<br>
                  1 = Gangguan sensorik ringan hingga sedang. Ada gangguan sensorik terhadap nyeri tetapi masih merasa
                  bila
                  disentuh<br>
                  2 = Gangguan sensorik berat atau total<br>
                </p>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsSensorik" v-on:keyup="hitungSkorMasukRs()" min="0"
                        placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>

                <div class="column pt-3 pb-0">

                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiSensorik"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeSensorik" v-on:keyup="hitungSkorDischarge()"
                        min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>


            <tr>
              <td width="3%" style="text-align: center; vertical-align: middle;">9</td>
              <td style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Kemampuan berbahasa. Anjurkan pasien untuk menjelaskan suatu gambar atau membaca suatu tulisan. Bila
                  pasien
                  mengalami kebutaan, letakkan suatu benda ditangan pasien dan anjurkan untuk menjelaskan benda
                  tersebut.
                </font>
              </td>
              <td colspan="2" style="text-align: left; vertical-align: middle;">
                <p>
                  0 = Normal<br>
                  1 = Afasia ringan hingga sedang<br>
                  2 = Afasia berat <br>
                  3 = mute, afasia global, koma

                </p>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsKemampuanBahasa" v-on:keyup="hitungSkorMasukRs()"
                        min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiKemampuanBahasa"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeKemampuanBahasa"
                        v-on:keyup="hitungSkorDischarge()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>


            <tr>
              <td width="3%" style="text-align: center; vertical-align: middle;">10</td>
              <td style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Disartria
                </font>
              </td>
              <td colspan="2" style="text-align: left; vertical-align: middle;">
                <p>
                  0 = Normal<br>
                  1 = Disartria ringan<br>
                  2 = Disartria berat <br>
                </p>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsDisartria" v-on:keyup="hitungSkorMasukRs()"
                        min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiDisartria"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeDisartria" v-on:keyup="hitungSkorDischarge()"
                        min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>

            <tr>
              <td width="3%" style="text-align: center; vertical-align: middle;">11</td>
              <td style="text-align: left; vertical-align: middle;">
                <font style="font-size: 14px">
                  Neglect atau inatensi
                </font>
              </td>
              <td colspan="2" style="text-align: left; vertical-align: middle;">
                <p>
                  0 = Tidak ada neglect <br>
                  1 = Tidak ada atensi pada salah satu modalitas berikut visual, tactile, auditory, spatial, atau
                  personal
                  inattention<br>
                  2 = Tidak ada atensi pada lebih dari satu modalitas<br>

                </p>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorMasukRsNeglect" v-on:keyup="hitungSkorMasukRs()" min="0"
                        placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorPerubahanKondisiNeglect"
                        v-on:keyup="hitungSkorPerubahanKondisi()" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>

                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.skorDischargeNeglect" v-on:keyup="hitungSkorDischarge()"
                        min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>

            <tr>
              <td colspan="4" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 16px">TOTAL NILAI</font>
              </td>
              <td style="text-align: center; vertical-align: middle;">
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.totalNilaiSkorMasukRS" min="0" placeholder=""
                        :readonly="true" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td style="text-align: center; vertical-align: middle;">
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.totalNilaiSkorPerubahanKondisi" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td style="text-align: center; vertical-align: middle;">
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl>

                      <VInput type="number" v-model="input.totalNilaiSkorDischarge" min="0" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>

            <tr>
              <td height="83" colspan="4" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 16px">PARAF</font>
              </td>
              <td style="text-align: center;">
                <TandaTangan :elemenID="'TTDMasukRS'" :width="'150'" :height="'150'" class="dek" />
              </td>
              <td style="text-align: center;">
                <TandaTangan :elemenID="'TTDPerubahanKondisi'" :width="'150'" :height="'150'" class="dek" />
              </td>
              <td style="text-align: center;">
                <TandaTangan :elemenID="'TTDSkorDischarge'" :width="'150'" :height="'150'" class="dek" />
              </td>
            </tr>

            <tr>
              <td colspan="4" style="text-align: center; vertical-align: middle;">
                <font style="font-size: 16px">NAMA</font>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.pegawaiPemberiSkorMasukRs" :suggestions="d_Dokter"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Ketik untuk mencari..." class="mt-2" @item-select="setTandaTangan($event)" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.pegawaiPemberiSkorPerubahanKondisi" :suggestions="d_Dokter"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Ketik untuk mencari..." class="mt-2" @item-select="setTandaTangan($event)" />
                    </VControl>
                  </VField>
                </div>
              </td>

              <td>
                <div class="column pt-3 pb-0">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.pegawaiPemberiSkorDischarge" :suggestions="d_Dokter"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Ketik untuk mencari..." class="mt-2" @item-select="setTandaTangan($event)" />
                    </VControl>
                  </VField>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="7" style="text-align: left; vertical-align: middle;">
                Interpretasi Skor NIHSS <br>
                Skor &lt; 5 : Defisit neurologis ringan <br>
                Skor 5-14 : Defisit neurologis sedang/cukup berat<br>
                Skor 15-24 : Defisit neurologis berat<br>
                Skor > 25 : Defisit neurologis sangat berat<br>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const item: any = reactive({})
const d_Hubungan: any = ref([])
const d_Dokter: any = ref([])
const d_KelompokPasien: any = ref([])
const COLLECTION: any = ref('PengkajianTingkatKeparahanStroke') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const route = useRoute()
const setView = () => {
  useHead({ title: 'Pengkajian Tingkat Keperahan Stroke' + ' - ' + import.meta.env.VITE_PROJECT, })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const print = async () => {
  H.printBlade(`emr/cetak-formulir-pengkajian-tingkat-keparahan-stroke?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
};

const loadRiwayat = async () => {
  const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }

}

async function hitungSkorDischarge() {
  let var_skor_discharge_1 = 0
  if (input.value.skorDischargeTingkatKesadaran == undefined) {
    var_skor_discharge_1 = 0
  } else {
    var_skor_discharge_1 = input.value.skorDischargeTingkatKesadaran
  }
  var skor_discharge_1 = new Number(var_skor_discharge_1)


  let var_skor_discharge_2 = 0
  if (input.value.skorDischargeMenjawabPertanyaan == undefined) {
    var_skor_discharge_2 = 0
  } else {
    var_skor_discharge_2 = input.value.skorDischargeMenjawabPertanyaan
  }
  var skor_discharge_2 = new Number(var_skor_discharge_2)


  let var_skor_discharge_3 = 0
  if (input.value.skorDischargeMengikutiPerintah == undefined) {
    var_skor_discharge_3 = 0
  } else {
    var_skor_discharge_3 = input.value.skorDischargeMengikutiPerintah
  }
  var skor_discharge_3 = new Number(var_skor_discharge_3)

  let var_skor_discharge_4 = 0
  if (input.value.skorDischargeGaze == undefined) {
    var_skor_discharge_4 = 0
  } else {
    var_skor_discharge_4 = input.value.skorDischargeGaze
  }
  var skor_discharge_4 = new Number(var_skor_discharge_4)


  let var_skor_discharge_5 = 0
  if (input.value.skorDischargeVisual == undefined) {
    var_skor_discharge_5 = 0
  } else {
    var_skor_discharge_5 = input.value.skorDischargeVisual
  }
  var skor_discharge_5 = new Number(var_skor_discharge_5)


  let var_skor_discharge_6 = 0
  if (input.value.skorDischargeParesisWajah == undefined) {
    var_skor_discharge_6 = 0
  } else {
    var_skor_discharge_6 = input.value.skorDischargeParesisWajah
  }
  var skor_discharge_6 = new Number(var_skor_discharge_6)


  let var_skor_discharge_7 = 0
  if (input.value.skorDischargeMotorikLenganKiri == undefined) {
    var_skor_discharge_7 = 0
  } else {
    var_skor_discharge_7 = input.value.skorDischargeMotorikLenganKiri
  }
  var skor_discharge_7 = new Number(var_skor_discharge_7)


  let var_skor_discharge_8 = 0
  if (input.value.skorDischargeMotorikLenganKanan == undefined) {
    var_skor_discharge_8 = 0
  } else {
    var_skor_discharge_8 = input.value.skorDischargeMotorikLenganKanan
  }
  var skor_discharge_8 = new Number(var_skor_discharge_8)




  let var_skor_discharge_9 = 0
  if (input.value.skorDischargeMotorikTungkaikiri == undefined) {
    var_skor_discharge_9 = 0
  } else {
    var_skor_discharge_9 = input.value.skorDischargeMotorikTungkaikiri
  }
  var skor_discharge_9 = new Number(var_skor_discharge_9)


  let var_skor_discharge_10 = 0
  if (input.value.skorDischargeMotorikTungkaiKanan == undefined) {
    var_skor_discharge_10 = 0
  } else {
    var_skor_discharge_10 = input.value.skorDischargeMotorikTungkaiKanan
  }
  var skor_discharge_10 = new Number(var_skor_discharge_10)


  let var_skor_discharge_11 = 0
  if (input.value.skorDischargeAtaksia == undefined) {
    var_skor_discharge_11 = 0
  } else {
    var_skor_discharge_11 = input.value.skorDischargeAtaksia
  }
  var skor_discharge_11 = new Number(var_skor_discharge_11)


  let var_skor_discharge_12 = 0
  if (input.value.skorDischargeSensorik == undefined) {
    var_skor_discharge_12 = 0
  } else {
    var_skor_discharge_12 = input.value.skorDischargeSensorik
  }
  var skor_discharge_12 = new Number(var_skor_discharge_12)


  let var_skor_discharge_13 = 0
  if (input.value.skorDischargeKemampuanBahasa == undefined) {
    var_skor_discharge_13 = 0
  } else {
    var_skor_discharge_13 = input.value.skorDischargeKemampuanBahasa
  }
  var skor_discharge_13 = new Number(var_skor_discharge_13)


  let var_skor_discharge_14 = 0
  if (input.value.skorDischargeDisartria == undefined) {
    var_skor_discharge_14 = 0
  } else {
    var_skor_discharge_14 = input.value.skorDischargeDisartria
  }
  var skor_discharge_14 = new Number(var_skor_discharge_14)


  let var_skor_discharge_15 = 0
  if (input.value.skorDischargeNeglect == undefined) {
    var_skor_discharge_15 = 0
  } else {
    var_skor_discharge_15 = input.value.skorDischargeNeglect
  }
  var skor_discharge_15 = new Number(var_skor_discharge_15)




  input.value.totalNilaiSkorDischarge = skor_discharge_1 + skor_discharge_2 + skor_discharge_3 + skor_discharge_4
    + skor_discharge_5 + skor_discharge_6 + skor_discharge_7 + skor_discharge_8
    + skor_discharge_9 + skor_discharge_10 + skor_discharge_11 + skor_discharge_12
    + skor_discharge_13 + skor_discharge_14 + skor_discharge_15
}


async function hitungSkorPerubahanKondisi() {


  let var_skor_perubahan_kondisi_1 = 0
  if (input.value.skorPerubahanKondisiTingkatKesadaran == undefined) {
    var_skor_perubahan_kondisi_1 = 0
  } else {
    var_skor_perubahan_kondisi_1 = input.value.skorPerubahanKondisiTingkatKesadaran
  }
  var skor_perubahan_kondisi_1 = new Number(var_skor_perubahan_kondisi_1)


  let var_skor_perubahan_kondisi_2 = 0
  if (input.value.skorPerubahanKondisiMenjawabPertanyaan == undefined) {
    var_skor_perubahan_kondisi_2 = 0
  } else {
    var_skor_perubahan_kondisi_2 = input.value.skorPerubahanKondisiMenjawabPertanyaan
  }
  var skor_perubahan_kondisi_2 = new Number(var_skor_perubahan_kondisi_2)


  let var_skor_perubahan_kondisi_3 = 0
  if (input.value.skorPerubahanKondisiMengikutiPerintah == undefined) {
    var_skor_perubahan_kondisi_3 = 0
  } else {
    var_skor_perubahan_kondisi_3 = input.value.skorPerubahanKondisiMengikutiPerintah
  }
  var skor_perubahan_kondisi_3 = new Number(var_skor_perubahan_kondisi_3)



  let var_skor_perubahan_kondisi_4 = 0
  if (input.value.skorPerubahanKondisiGaze == undefined) {
    var_skor_perubahan_kondisi_4 = 0
  } else {
    var_skor_perubahan_kondisi_4 = input.value.skorPerubahanKondisiGaze
  }
  var skor_perubahan_kondisi_4 = new Number(var_skor_perubahan_kondisi_4)


  let var_skor_perubahan_kondisi_5 = 0
  if (input.value.skorPerubahanKondisiVisual == undefined) {
    var_skor_perubahan_kondisi_5 = 0
  } else {
    var_skor_perubahan_kondisi_5 = input.value.skorPerubahanKondisiVisual
  }
  var skor_perubahan_kondisi_5 = new Number(var_skor_perubahan_kondisi_5)



  let var_skor_perubahan_kondisi_6 = 0
  if (input.value.skorPerubahanKondisiParesisWajah == undefined) {
    var_skor_perubahan_kondisi_6 = 0
  } else {
    var_skor_perubahan_kondisi_6 = input.value.skorPerubahanKondisiParesisWajah
  }
  var skor_perubahan_kondisi_6 = new Number(var_skor_perubahan_kondisi_6)


  let var_skor_perubahan_kondisi_7 = 0
  if (input.value.skorPerubahanKondisiMotorikLenganKiri == undefined) {
    var_skor_perubahan_kondisi_7 = 0
  } else {
    var_skor_perubahan_kondisi_7 = input.value.skorPerubahanKondisiMotorikLenganKiri
  }
  var skor_perubahan_kondisi_7 = new Number(var_skor_perubahan_kondisi_7)


  let var_skor_perubahan_kondisi_8 = 0
  if (input.value.skorPerubahanKondisiMotorikLenganKanan == undefined) {
    var_skor_perubahan_kondisi_8 = 0
  } else {
    var_skor_perubahan_kondisi_8 = input.value.skorPerubahanKondisiMotorikLenganKanan
  }
  var skor_perubahan_kondisi_8 = new Number(var_skor_perubahan_kondisi_8)


  let var_skor_perubahan_kondisi_9 = 0
  if (input.value.skorPerubahanKondisiMotorikTungkaiKiri == undefined) {
    var_skor_perubahan_kondisi_9 = 0
  } else {
    var_skor_perubahan_kondisi_9 = input.value.skorPerubahanKondisiMotorikTungkaiKiri
  }
  var skor_perubahan_kondisi_9 = new Number(var_skor_perubahan_kondisi_9)


  let var_skor_perubahan_kondisi_10 = 0
  if (input.value.skorPerubahanKondisiMotorikTungkaiKanan == undefined) {
    var_skor_perubahan_kondisi_10 = 0
  } else {
    var_skor_perubahan_kondisi_10 = input.value.skorPerubahanKondisiMotorikTungkaiKanan
  }
  var skor_perubahan_kondisi_10 = new Number(var_skor_perubahan_kondisi_10)


  let var_skor_perubahan_kondisi_11 = 0
  if (input.value.skorPerubahanKondisiAtaksia == undefined) {
    var_skor_perubahan_kondisi_11 = 0
  } else {
    var_skor_perubahan_kondisi_11 = input.value.skorPerubahanKondisiAtaksia
  }
  var skor_perubahan_kondisi_11 = new Number(var_skor_perubahan_kondisi_11)



  let var_skor_perubahan_kondisi_12 = 0
  if (input.value.skorPerubahanKondisiSensorik == undefined) {
    var_skor_perubahan_kondisi_12 = 0
  } else {
    var_skor_perubahan_kondisi_12 = input.value.skorPerubahanKondisiSensorik
  }
  var skor_perubahan_kondisi_12 = new Number(var_skor_perubahan_kondisi_12)



  let var_skor_perubahan_kondisi_13 = 0
  if (input.value.skorPerubahanKondisiKemampuanBahasa == undefined) {
    var_skor_perubahan_kondisi_13 = 0
  } else {
    var_skor_perubahan_kondisi_13 = input.value.skorPerubahanKondisiKemampuanBahasa
  }
  var skor_perubahan_kondisi_13 = new Number(var_skor_perubahan_kondisi_13)


  let var_skor_perubahan_kondisi_14 = 0
  if (input.value.skorPerubahanKondisiDisartria == undefined) {
    var_skor_perubahan_kondisi_14 = 0
  } else {
    var_skor_perubahan_kondisi_14 = input.value.skorPerubahanKondisiDisartria
  }
  var skor_perubahan_kondisi_14 = new Number(var_skor_perubahan_kondisi_14)


  let var_skor_perubahan_kondisi_15 = 0
  if (input.value.skorPerubahanKondisiNeglect == undefined) {
    var_skor_perubahan_kondisi_15 = 0
  } else {
    var_skor_perubahan_kondisi_15 = input.value.skorPerubahanKondisiNeglect
  }
  var skor_perubahan_kondisi_15 = new Number(var_skor_perubahan_kondisi_15)


  input.value.totalNilaiSkorPerubahanKondisi = skor_perubahan_kondisi_1 + skor_perubahan_kondisi_2 + skor_perubahan_kondisi_3
    + skor_perubahan_kondisi_4 + skor_perubahan_kondisi_5 + skor_perubahan_kondisi_6
    + skor_perubahan_kondisi_7 + skor_perubahan_kondisi_8 + skor_perubahan_kondisi_9
    + skor_perubahan_kondisi_10 + skor_perubahan_kondisi_11 + skor_perubahan_kondisi_12
    + skor_perubahan_kondisi_13 + skor_perubahan_kondisi_14 + skor_perubahan_kondisi_15

}

async function hitungSkorMasukRs() {

  let total_skor_masuk_rs = 0

  let var_skor_masuk_rs_1 = 0
  if (input.value.skorMasukRsTingkatKesadaran == undefined) {
    var_skor_masuk_rs_1 = 0
  } else {
    var_skor_masuk_rs_1 = input.value.skorMasukRsTingkatKesadaran
  }
  var skor_masuk_rs_1 = new Number(var_skor_masuk_rs_1)


  let var_skor_masuk_rs_2 = 0
  if (input.value.skorMasukRsMenjawabPertanyaan == undefined) {
    var_skor_masuk_rs_2 = 0
  } else {
    var_skor_masuk_rs_2 = input.value.skorMasukRsMenjawabPertanyaan
  }
  var skor_masuk_rs_2 = new Number(var_skor_masuk_rs_2)



  let var_skor_masuk_rs_3 = 0
  if (input.value.skorMasukRsMengikutiPerintah == undefined) {
    var_skor_masuk_rs_3 = 0
  } else {
    var_skor_masuk_rs_3 = input.value.skorMasukRsMengikutiPerintah
  }
  var skor_masuk_rs_3 = new Number(var_skor_masuk_rs_3)


  let var_skor_masuk_rs_4 = 0
  if (input.value.skorMasukRsGaze == undefined) {
    var_skor_masuk_rs_4 = 0
  } else {
    var_skor_masuk_rs_4 = input.value.skorMasukRsGaze
  }
  var skor_masuk_rs_4 = new Number(var_skor_masuk_rs_4)


  let var_skor_masuk_rs_5 = 0
  if (input.value.skorMasukRsVisual == undefined) {
    var_skor_masuk_rs_5 = 0
  } else {
    var_skor_masuk_rs_5 = input.value.skorMasukRsVisual
  }
  var skor_masuk_rs_5 = new Number(var_skor_masuk_rs_5)


  let var_skor_masuk_rs_6 = 0
  if (input.value.skorMasukRsParesisWajah == undefined) {
    var_skor_masuk_rs_6 = 0
  } else {
    var_skor_masuk_rs_6 = input.value.skorMasukRsParesisWajah
  }
  var skor_masuk_rs_6 = new Number(var_skor_masuk_rs_6)


  let var_skor_masuk_rs_7 = 0
  if (input.value.skorMasukRsMotorikLenganKiri == undefined) {
    var_skor_masuk_rs_7 = 0
  } else {
    var_skor_masuk_rs_7 = input.value.skorMasukRsMotorikLenganKiri
  }
  var skor_masuk_rs_7 = new Number(var_skor_masuk_rs_7)


  let var_skor_masuk_rs_8 = 0
  if (input.value.skorMasukRsMotorikLenganKanan == undefined) {
    var_skor_masuk_rs_8 = 0
  } else {
    var_skor_masuk_rs_8 = input.value.skorMasukRsMotorikLenganKanan
  }
  var skor_masuk_rs_8 = new Number(var_skor_masuk_rs_8)


  let var_skor_masuk_rs_9 = 0
  if (input.value.skorMasukRsMotorikTungkaiKiri == undefined) {
    var_skor_masuk_rs_9 = 0
  } else {
    var_skor_masuk_rs_9 = input.value.skorMasukRsMotorikTungkaiKiri
  }
  var skor_masuk_rs_9 = new Number(var_skor_masuk_rs_9)


  let var_skor_masuk_rs_10 = 0
  if (input.value.skorMasukRsMotorikTungkaiKanan == undefined) {
    var_skor_masuk_rs_10 = 0
  } else {
    var_skor_masuk_rs_10 = input.value.skorMasukRsMotorikTungkaiKanan
  }
  var skor_masuk_rs_10 = new Number(var_skor_masuk_rs_10)


  let var_skor_masuk_rs_11 = 0
  if (input.value.skorMasukRsAtaksia == undefined) {
    var_skor_masuk_rs_11 = 0
  } else {
    var_skor_masuk_rs_11 = input.value.skorMasukRsAtaksia
  }
  var skor_masuk_rs_11 = new Number(var_skor_masuk_rs_11)


  let var_skor_masuk_rs_12 = 0
  if (input.value.skorMasukRsSensorik == undefined) {
    var_skor_masuk_rs_12 = 0
  } else {
    var_skor_masuk_rs_12 = input.value.skorMasukRsSensorik
  }
  var skor_masuk_rs_12 = new Number(var_skor_masuk_rs_12)


  let var_skor_masuk_rs_13 = 0
  if (input.value.skorMasukRsKemampuanBahasa == undefined) {
    var_skor_masuk_rs_13 = 0
  } else {
    var_skor_masuk_rs_13 = input.value.skorMasukRsKemampuanBahasa
  }
  var skor_masuk_rs_13 = new Number(var_skor_masuk_rs_13)


  let var_skor_masuk_rs_14 = 0
  if (input.value.skorMasukRsDisartria == undefined) {
    var_skor_masuk_rs_14 = 0
  } else {
    var_skor_masuk_rs_14 = input.value.skorMasukRsDisartria
  }
  var skor_masuk_rs_14 = new Number(var_skor_masuk_rs_14)


  let var_skor_masuk_rs_15 = 0
  if (input.value.skorMasukRsNeglect == undefined) {
    var_skor_masuk_rs_15 = 0
  } else {
    var_skor_masuk_rs_15 = input.value.skorMasukRsNeglect
  }
  var skor_masuk_rs_15 = new Number(var_skor_masuk_rs_15)



  total_skor_masuk_rs = skor_masuk_rs_1 + skor_masuk_rs_2 + skor_masuk_rs_3 + skor_masuk_rs_4
    + skor_masuk_rs_5 + skor_masuk_rs_6 + skor_masuk_rs_7 + skor_masuk_rs_8
    + skor_masuk_rs_9 + skor_masuk_rs_10 + skor_masuk_rs_11 + skor_masuk_rs_12
    + skor_masuk_rs_13 + skor_masuk_rs_14 + skor_masuk_rs_15



  input.value.totalNilaiSkorMasukRS = total_skor_masuk_rs

}
const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Dokter.value = response
}
const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
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
    NOREC_EMRPASIEN.value = response.norec_emr
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}
onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
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

setView()
</script>
<style lang="scss">
table {
  width: 100% !important;
  border-collapse: collapse !important;
}

th {
  text-align: center !important;
  vertical-align: middle !important;
}

td {
  padding: 5px !important;
}

td,
th {
  border: 1px solid black !important;
}
</style>
<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Edukasi Pasien Untuk Persiapan Kolonoskopi Dengan General Anastesi Dengan Obat Niflec</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :isHideCetak="true">
              </ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->
        <div class="column">
          <div class="columns is-multiline m-0">
            <div class="column is-3">
              <span>Ruangan</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.ruangan" />
              </VControl>
            </div>
            <div class="column is-3">
              <span>Tanggal & Jam</span>
              <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
          </div>
          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>
          <table style="width: 100% !important;border-collapse: collapse !important;">
            <tr>
              <th style="width: 10%;">#</th>
              <th style="width: 70%;">Penjelasan Yang Diberikan</th>
              <th style="width: 20%;">Keterangan & Tanggal</th>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD1" />
                </VControl>
              </th>
              <td>
                1. Persiapaan Laboratorium
                <br> &nbsp; - DL (Hb harus ≥ 8 mg/dl)
                <br> &nbsp;&nbsp;- Faal hemostatis (BT,CT dalam batas normal)
                <br> &nbsp;&nbsp;- HbsAg, AntiHcv (sesuai indikasi)
                <br> EKG
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT1_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT1_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD2" />
                </VControl>
              </th>
              <td>
                2. Kie pasien bila ada rencana konsul ke poli lain sesuai intruksi dokter untuk persiapan Endoskopi
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT2_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT2_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD3" />
                </VControl>
              </th>
              <td>
                3. Untuk pasien yang menggunakan pembiusan umum dikonsulkanke tim Anastesi
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT3_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT3_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD4" />
                </VControl>
              </th>
              <td>
                4. Stop obat-obatan seperti Aspirin, warfarin dan Clopidogrel (5 hari) dan Sukralfat (1 hari) sebelum
                Tindakan
                karena akan mengaburkan pemeriksaan.
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT4_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT4_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD5" />
                </VControl>
              </th>
              <td>
                5. Persiapan pasien sehari sebelum pemeriksaan <br>
                <VDatePicker v-model="input.tglSebelumPemeriksaan" mode="date" trim-weeks style="width: 30%;">
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
                - Makan bubur tanpa serat/bubur tanpa sayur dan daging(tahu,tempe dan telur boleh)pada siang dan
                sore hari
                sampai dengan pukul 18.00<br>
                - Minum air putih minimal 2 liter per hari<br>
                - Bila pasien minum obat hipertensi dan gula, obat tetep diminum sebelum memulai puasa<br>
                Pemberian pukul 14.00
                <br>- Bisacodyl (dulcolac) 15mg (3 tablet sediaan 5mg) oral (tidak diberikan pada kasus obstruksi)<br>
                Pemberian Pukul 19.00
                <br>Dosis dan Cara Pemberian Niflec Powder I
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Tuangkan larutan Niflec setengah takar dengan dengan 1000ml air
                putih minum
                sedikit demi sedikit (1 gelas tiap 15 menit) sampai habis 5 gelas dalam 1 jam
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Buang air besar yang pertama kali biasanya terjadi setelah sekitar 1
                jam
                dari saat awal minum larutan Niflec
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Makan bubur tanpa serat/bubur tanpa sayur dan daging(tahu,tempe dan
                telur
                boleh)pada pagi hari pukul 07.00
                <br>Pemberian Pukul 09.00
                <br>Dosis dan Cara Pemberian Niflec Powder II
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Tuangkan larutan Niflec setengah takar dengan dengan 1000ml air
                putih minum
                sedikit demi sedikit (1 gelas tiap 15 menit) sampai habis 5 gelas dalam 1 jam
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Buang air besar yang pertama kali biasanya terjadi setelah sekitar 1
                jam
                dari saat awal minum larutan Niflec
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT5_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT5_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD6" />
                </VControl>
              </th>
              <td>
                6. Persiapan pasien pada hari pemeriksaan<br>
                <VDatePicker v-model="input.tglHariPemeriksaan" mode="date" trim-weeks style="width: 30%;">
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
                - Pasien puasa 6 jam sebelum tindakan<br>
                - Tidak memakai aksesoris (perhiasan atau kutex) saat tindakan<br>
                Pemberian obat fleet enema/fozen enema pukul 12.00<br>
                Obat dimasukan ke dalam anus dalam posisi miring dan pertahankan posisi kurang lebih 5 menit
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT6_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT6_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD7" />
                </VControl>
              </th>
              <td>
                7. Setelah pemberian obat awasi buang air besar sampai keluar air saja (tidak ada kotoran padat/serat),
                kalau
                masih ada kotoran lanjutan minum air putih sekuatnya dan lakukan lavement (untuk pasien rawat inap)
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT7_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT7_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD8" />
                </VControl>
              </th>
              <td>8. Setelah bersih , pasien dikirim ke ruang endoskopi</td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT8_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT8_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD9" />
                </VControl>
              </th>
              <td>9. Pasien bisa minum air putih sampai tindakan dan tidak memakai aksesoris (perhiasan atau kutex) saat
                tindakan
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT9_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT9_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD10" />
                </VControl>
              </th>
              <td>
                10. Persiapan obat dan bhp untuk premedikasi (dibawa ke ruang endoskopi)
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Handscoen steril nomor 7.5 (2buah), nomor 7 (2 buah)
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Under pad (1 buah)
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Kasa steril
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Nasal Kanul
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT10_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT10_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD11" />
                </VControl>
              </th>
              <td>
                11. Perkiraan biaya<br>
                <VControl>
                  <VInput type="text" class="input" v-model="input.perkiraanBiaya" placeholder="Rp...." />
                </VControl>
                (untuk pasien umum kie k admission dengan membawa form perkiraan biaya)
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT11_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT11_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD12" />
                </VControl>
              </th>
              <td>
                12. Ada Persetujuan Tindakan (infomed concent)
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT12_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT12_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD13" />
                </VControl>
              </th>
              <td>
                13. Ada keluarga yang menemani (untuk pasien rawat jalan) dan ada perawat yang mengantar (untuk pasien
                rawat
                INAP)
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT13_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT13_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
            <tr>
              <th>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="true" label="" v-model="input.PYD14" />
                </VControl>
              </th>
              <td>
                14. Tanggal Tindakan dan Jika terjadi pembatalan Tindakan berikan alasan, tulis keluhan dan Diagnosa
                pasien,
                pasien datang kerumah sakit PUKUL
                <VDatePicker v-model="input.datangKeRS" mode="time" trim-weeks is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:clock" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
              <td>
                <VField>
                  <VTextarea rows="1" v-model="input.KT14_TA"></VTextarea>
                </VField>
                <VDatePicker v-model="input.KT14_D" mode="datetime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
            </tr>
          </table>
          <span>NB : Berikan tanda(√) dan tanggal pada kolom keterangan bila penjelasan telah diberikan dan tulis alas
            an jika
            penjelasan tidak diberikan</span>
          <div class="columns is-multiline m-0">
            <div class="column is-4" style="text-align: center;">
              <span>Pasien/Keluarga yang meneriman penjelasan</span><br>
              <TandaTangan :elemenID="'TTDPasien'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="mt-2">
                <VInput type="text" class="input" v-model="input.namaPasien" />
              </VControl>
            </div>
            <div class="column is-4"></div>
            <div class="column is-4" style="text-align: center;">
              <span>Perawat yang memberikan</span><br>
              <TandaTangan :elemenID="'TTDPerawat'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" class="mt-2" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
              </VControl>
            </div>
          </div>
        </div>
        <!-- form baru -->
      </div>
    </div>
  </div>

  <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalTemplate = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Tanggal Input</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Tanggal Registrasi</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">No Registrasi</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">No EMR</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="20%">Dokter</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Section</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="5%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)" color="info"
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
            <table style="border: 1px solid black;" v-if="listTemplateFix.length > 0">
              <thead>
                <tr>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="5%">No</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Tanggal Dibuat</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="20%">Nama Ruangan</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="25%">Nama Template</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplateFix">
                <tr>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.no }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.namatemplate }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;padding: 3px;">
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
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
// import InputText from 'primevue/inputtext';

useHead({ title: 'Edukasi Pasien Untuk Persiapan Kolonoskopi Dengan Lokal Anastesi Dengan Obat Niflec Tindakan Sore - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({})
const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
// const d_Dokter: any = ref([])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)

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
const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
    H.tandaTangan().set("TTDPasien", dataTTD.value.TTDPasien)
    H.tandaTangan().set("TTDPerawat", dataTTD.value.TTDPerawat)
  } else {
    input.value.datangKeRS = new Date()
    input.value.tanggal = new Date()
    input.value.ruangan = props.registrasi.namaruangan.trim()
    // input.value.DD = { label: user.namaLengkap, value: user.id }
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  object['TTDPasien'] = H.tandaTangan().get("TTDPasien");
  object['TTDPerawat'] = H.tandaTangan().get("TTDPerawat");
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
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}
// const fetchDokter = async (filter: any) => {
//     await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {d_Dokter.value = response})
// }

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

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    console.log()
    return;
  }
  let ID = input.id ? input.id : ''
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

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    input.value.namatemplate = null
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      listTemplate.value = responselast //set ke inputan
      showModalTemplate.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const addTemplate = (response: any) => {
  input.value = response
  delete input.value['id']
  delete input.value['_id']
  input.value.namatemplate = null
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}

const addRiwayat = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      for (var x = 0; x < responselast.length; x++) {
        responselast[x].no = x + 1
        responselast[x].id = ''
      }
      listTemplateFix.value = responselast //set ke inputan
      showModalTemplateFix.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}
</script>
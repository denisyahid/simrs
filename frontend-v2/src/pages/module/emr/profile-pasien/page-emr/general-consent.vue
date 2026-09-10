<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST :isHideCetakWNA="false"></ButtonEmr>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-12">
            <h1>Yang bertanda tangan di bawah ini</h1><br>
            <h1 style="margin-top: -10px;;"><i>The undersigned below</i></h1>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3">
                  <span>Nama<br><i>Name</i></span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nama " v-model="input.namaPasien" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3">
                  <span>Tanggal Lahir<br><i>Date of Birth</i></span>
                </div>
                <div class="column is-9">
                  <VDatePicker v-model="input.tglLahir" mode="date" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3">
                  <span>Alamat<br><i>Address</i></span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Alamat " v-model="input.alamatPasien" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3">
                  <span>Nomor Telepon<br><i>Phone Number</i></span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="No Telp " v-model="input.noTelepon" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3">
                  <span>Hubungan dengan Pasien<br><i>Relation with the patient </i></span>
                </div>
                <div class="column is-9">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.hubpasien" :attrs="{ value }" placeholder="" label="label"
                        :options="d_allo" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0 is-flex">
              <div class="column is-3">
                <span>Ruang Intensif/<i>Intensif Room</i></span>
              </div>
              <div class="column is-9">
                <Multiselect v-model="input.kelasKamar" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_KelasKamar" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
            </div>

            <h1 style="margin-top: 50px;">I.&emsp; PERSETUJUAN UNTUK PERAWATAN DAN PENGOBATAN</h1>
            <h1>&emsp;&emsp;<i>CONSENT FOR TREATMENT</i></h1>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;a.</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Saya mengetahui bahwa saya memiliki kondisi yang membutuhkan perawatan medis, saya
                    memberi ijin kepada dokter dan profesi kesehatan lainnya untuk melakukan prosedur diagnostik dan
                    untuk memberikan pengobatan medis seperti yang diperlukan untuk penilaian secara profesional.
                    Prosedur diagnostik dan perawatan medis termasuk terapi tidak terbatas pada ECG, X-Ray, tes
                    darah, terapi fisik dan pemberian obat <br>
                    <i>
                      I knew that I had a condition that needed medical care, I gave permission to doctors and other
                      health
                      professionals to carry out diagnostic procedures and to provide medical treatment as needed for
                      professional assessment. Diagnostic procedures and medical treatments including therapy are not
                      limited to ECG, X-Ray, blood tests, physical therapy and drug administration.
                    </i>
                  </span>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1">&emsp;&emsp;b.</div>
                <div class="column is-11" style="margin-left: -30px;">
                  <span>
                    Saya sadar bahwa praktik kedokteran dan ilmu bedah bukanlah ilmu pasti dan saya mengakui
                    bahwa tidak ada jaminan atas hasil apapun terhadap prosedur perawatan atau pemeriksaan
                    apapun yang dilakukan kepada saya.<br>
                    <i>
                      I am aware that the practice of medicine and surgery is not an exact science and I acknowledge
                      that
                      there is no guarantee of any results for any treatment or examination procedures performed on me.
                    </i>
                  </span>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1">&emsp;&emsp;c.</div>
                <div class="column is-11" style="margin-left: -30px;">
                  <span>
                    Saya mengerti dan memahami bahwa : <br>
                    <i>I understand that: </i>
                  </span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-1" style="margin-top: -15px;"></div>
                    <div class="column is-1" style="margin-left: -30px; margin-top: -15px;">1.</div>
                    <div class="column is-10" style="margin-left: -60px; margin-top: -15px;">
                      <span>
                        Saya memiliki hak untuk menanyakan tentang pengobatan yang diusulkan termasuk identitas
                        setiap orang yang memberikan atau mengamati pengobatan setiap saat.
                        <br>
                        <i>I have the right to ask about the proposed treatment including the identity of each person
                          who
                          gives or observes treatment at any time.
                        </i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-1" style="margin-top: -15px;"></div>
                    <div class="column is-1" style="margin-left: -30px; margin-top: -15px;">2.</div>
                    <div class="column is-10" style="margin-left: -60px; margin-top: -15px;">
                      <span>
                        Saya memiliki hak untuk persetujuan, atau menolak persetujuan untuk setiap prosedur/terapi<br>
                        <i>I have the right to consent, or refuse approval for each procedure/therapy.</i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-1" style="margin-top: -15px;"></div>
                    <div class="column is-1" style="margin-left: -30px; margin-top: -15px;">3.</div>
                    <div class="column is-10" style="margin-left: -60px; margin-top: -15px;">
                      <span>
                        Banyak dokter pada staf medis rumah sakit yang bukan karyawan tetapi sebagai staf tamu yang
                        telah diberikan hak untuk menggunakan fasilitas untuk perawatan dan pengobatan pasien
                        mereka.<br>
                        <i>I understand that many of the physicians who care for me in this facility are not employees
                          or
                          agents of the facility but are allowed by this facility to provide for the care and treatment
                          of their
                          patients.</i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-1" style="margin-top: -15px;"></div>
                    <div class="column is-1" style="margin-left: -30px; margin-top: -15px;">4.</div>
                    <div class="column is-10" style="margin-left: -60px; margin-top: -15px;">
                      <span>
                        Di unit pelayanan tertentu RSUD Bali Mandara, ada keterlibatan peserta didik dalam pemberian
                        pelayanan yang didampingi oleh petugas RS baik dari dokter, perawat, bidan maupun tenaga
                        medis lainnya.<br>
                        <i>In special unit of Bali Mandara, will joint educate participant during hospitalitation that
                          will be
                          control by hospital employee such as, doctors, nurses, midwifery and other medical staff.</i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <h1 style="margin-top: 20px;">II.&emsp; BARANG-BARANG MILIK PASIEN</h1>
            <h1>&emsp;&emsp;<i>GOODS BELONG TO PATIENTS</i></h1>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;a.</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Saya tidak boleh membawa barang-barang berharga yang tidak diperlukan dalam proses
                    perawatan secara langsung (seperti : perhiasan, elektronik, kartu kredit, uang, buku, dll) dan rumah
                    sakit tidak bertanggung jawab atas kerusakan dan kehilangan barang tersebut di atas. <br>
                    <i>I understand that I should not bring valuables/personal belongings to the hospital (axamples :
                      jewellery, electronic devices, credit cards, money, books, etc.) and the hospital is not
                      responsible for
                      the damage and loss of the items mentioned above.</i>
                  </span>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;b.</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Saya harus memberi tahu rumah sakit jika saya memiliki/memakai gigi palsu, lensa kontak, prostetik
                    atau barang pribadi lainnya yang perlu diamankan.<br>
                    <i>I have to notify the hospital if I have/use dentures, contact lenses, prosthetics or other
                      personal items
                      that need to be secured.</i>
                  </span>
                </div>
              </div>
            </div>

            <h1 style="margin-top: 20px;">III.&emsp; PERSETUJUAN PELEPASAN INFORMASI MEDIS </h1>
            <h1>&emsp;&emsp;<i>AUTHORIZATION FOR RELEASE OF MEDICAL INFORMATION</i></h1>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;a.</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Saya memahami informasi kesehatan yang ada dalam diri saya, termasuk diagnosa, hasil
                    laboratorium dan hasil tes diagnostik lainnya akan digunakan untuk perawatan medis saya, rumah
                    sakit akan menjamin kerahasiaannya.<br>
                    <i>I understand that all of my personal medical information, include all diagnostic results,
                      laboratory
                      results and other treatments results at this facility will confidentially guaranteed.</i>
                  </span>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;b.</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Saya memberi wewenang kepada rumah sakit untuk memberikan informasi tentang diagnosa hasil
                    pelayanan kesehatan dan pengobatan saya bila diperlukan untuk memproses klaim asuransi
                    BPJS/perusahaan/perorangan atau lembaga lain yang bertanggung jawab atas biaya pelayanan
                    kesehatan saya dan atau lembaga pemerintah yang berwenang.<br>
                    <i>I authorize the facility to release information form my medical records to any health care
                      provider
                      involved in any way in my care and treatment and to any person or entity which is or may be
                      liablefor
                      all or part of the hospital charges, including but not limited to BPJS/my insurances carrier or
                      any third
                      party provider or authorized government agency. </i>
                  </span>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;c.</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Saya (<i>I</i>)
                    <VField class="is-autocomplete-select" v-slot="{ id }">
                      <VControl>
                        <Multiselect v-model="input.izin" :attrs="{ value }" placeholder="" label="label"
                          :options="d_izin" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                    kepada rumah sakit untuk memberikan informasi tentang
                    diagnosa, hasil pelayanan kesehatan dan pengobatan saya kepada anggota keluarga/kerabat/wali
                    saya, yaitu kepada :<br>
                    <i> the facility to disclosure my medical record to my family or relatives or guardian
                      member, namely : </i>
                  </span>
                </div>
                <div class="column is-1"></div>
                <div class="column is-11" style="margin-left: -30px;">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.text1" />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.text2" />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.text3" />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.text4" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>

            <h1 style="margin-top: 20px;">IV.&emsp; HAK DAN TANGGUNG JAWAB PASIEN </h1>
            <h1>&emsp;&emsp;<i>PATIENT RIGHTS AND OBLIGANTIONS</i></h1>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;a.</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Saya memiliki hak untuk mengambil bagian dalam keputusan mengenai penyakit saya dan dalam
                    hal perawatan medis dan rencana pengobatan.<br>
                    <i>I understand that I have rights and also responsibility to follow the instructions of my care
                      providers
                      and to make agreement for the arrangements of follow up care.</i>
                  </span>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;b.</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Saya telah mendapat informasi tentang Hak dan Kewajiban Pasien di rumah sakit melalui leaflet dan
                    banner yang disediakan oleh petugas.<br>
                    <i>I certify that I have been informed about patient rights and obligations in this facility thru
                      leaflet and
                      banner, provided by the facility personnel.</i>
                  </span>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;c.</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Permintaan khusus (identifikasi harapan dan privasi) :<br>
                    <i>Special request (identification of expectations and privacy) :</i>
                  </span>
                </div>
                <div class="column is-1"></div>
                <div class="column is-11" style="margin-left: -30px;">
                  <VField>
                    <VTextarea rows="2" v-model="input.text5"></VTextarea>
                  </VField>
                  <!-- <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.text5" />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.text6" />
                    </VControl>
                  </VField> -->
                </div>
              </div>
            </div>

            <h1 style="margin-top: 20px;">V.&emsp; INFORMASI RAWAT INAP</h1>
            <h1>&emsp;&emsp;<i>INPATIENT INFORMATIONS</i></h1>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;a.</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Saya telah menerima informasi tentang peraturan yang diberlakukan oleh rumah sakit dan saya
                    beserta keluarga bersedia untuk mematuhi jam berkunjung pasien sesuai dengan aturan rumah
                    sakit.<br>
                    <i>I certify that I have been informed about regulations in this facility and understand that I and
                      my
                      relatives must obey the visiting hours in accordance with the regulations in this facility.
                    </i>
                  </span>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;b.</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Saya telah menerima informasi tentang fasilitas rawat inap dan karena situasi dimana ruang
                    perawatan yang menjadi hak saya sesuai kebutuhan tidak tersedia, saya bersedia dirawat dengan
                    fasilitas yang ada.<br>
                    <i>I certify that I have been informed about inpatient facilities. If the room/ward that become my
                      right
                      based on my insurance coverage is unavailable, I understand and wiling to be treated in existing
                      room/ward.
                      .</i>
                  </span>
                </div>
              </div>
            </div>

            <h1 style="margin-top: 20px;">VI.&emsp; INFORMASI BIAYA</h1>
            <h1>&emsp;&emsp;<i>FINANCIAL AGREEMENTS</i></h1>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-1" style="margin-top:0.5rem">&emsp;&emsp;</div>
                <div class="column is-11" style="margin-top:0.5rem; margin-left: -30px;">
                  <span>
                    Saya memahami tentang informasi biaya pengobatan atau biaya tindakan medis yang dijelaskan oleh
                    petugas rumah sakit yang berwenang dan sanggup untuk melaksanakan seluruh kewajiban keuangan
                    yang dikeluarkan selama perawatan di RSUD Bali Mandara Provinsi Bali.<br>
                    <i>I understand to all treatments and services that explained by the facility personnel. I
                      acknowledge full
                      financial responsibility for and agree to pay all charges of the Bali Mandara Hospital services.
                    </i>
                  </span>
                </div>
              </div>
            </div>

            <h1 style="margin-top: 50px;">Dengan tanda tangan saya di bawah ini, saya menyatakan bahwa saya telah
              membaca dan
              memahami item
              pada persetujuan umum ini.
              <br><i>I acknowledge that I have read this document in this entirety and that I fully understand it prior
                to my
                signing</i>
            </h1>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-8" style="margin-top: 50px;"></div>
                <div class="column is-4" style="margin-top: 50px;">
                  <h1 style="font-weight: bold;"> Garut </h1>
                  <VField>
                    <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
                      :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4" style="text-align: center;">
                  <h1 class="p-0" style="font-weight: bold;">Petugas (Admission officer)</h1>
                </div>
                <div class="column is-4"></div>
                <div class="column is-4" style="text-align: center;">
                  <h1 class="p-0" style="font-weight: bold;">Yang menyatakan (That stated)</h1>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4" style="text-align: center;">
                  <TandaTangan :elemenID="'signature_1'" :width="'150'" :height="'150'"></TandaTangan>
                </div>
                <div class="column is-4"></div>
                <div class="column is-4" style="text-align: center;">
                  <TandaTangan :elemenID="'signature_2'" :width="'150'" :height="'150'"></TandaTangan>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.yangMenjelaskan" :suggestions="d_Pegawai"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Yang Menjelaskan..." class="mt-2" @item-select="setTandaTangan($event)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4"></div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Pasien/Penanggung Jawab Pasien"
                        v-model="input.pasienPenanggungJawab" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

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
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const dataTTD: any = ref([])
const d_Hubungan: any = ref([])
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const COLLECTION: any = ref('GeneralConsentPerawatanIntensif') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const d_allo: any = ref([{ value: 1, label: 'Pasien Sendiri (Patient him/herself)' }, { value: 2, label: 'Ayah (Father)' }, { value: 3, label: 'Ibu (Mother)' }, { value: 4, label: 'Saudara (Relatives)' }, { value: 5, label: 'Teman (Friend)' }, { value: 6, label: 'Lainnya (Other)' }])
const d_izin: any = ref([{ value: 1, label: 'Mengizinkan (Authorized)' }, { value: 2, label: 'Tidak Mengizinkan (Forbid)' }])
const d_KelasKamar: any = ref([
  { value: 'SUITE', label: 'SUITE' },
  { value: 'VVIP', label: 'VVIP' },
  { value: 'VIP', label: 'VIP' },
  { value: 'KLS I', label: 'KLS I' },
  { value: 'KLS II', label: 'KLS II' },
  { value: 'KLS III', label: 'KLS III' }
])

const setView = () => {
  useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
// List Pilihan
const d_Kelas: any = ref([
  { label: 'KELAS 1', value: 'KELAS 1' },
  { label: 'KELAS 2', value: 'KELAS 2' },
  { label: 'KELAS 3', value: 'KELAS 3' }
])
// List Pilihan
const d_Keterangan: any = ref([
  { label: 'Atas Permintaan Sendiri', value: 'Atas Permintaan Sendiri' },
  { label: 'Kelas Sesuai Jaminan Penuh', value: 'Kelas Sesuai Jaminan Penuh' }
])
const dokterJabatan: any = ref([
  { label: 'Umum', value: 'Umum' },
  { label: 'Specialis', value: 'Specialis' },
  { label: 'Subspecialis', value: 'Subspecialis' }
])
const loadRiwayat = () => {
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      H.tandaTangan().set("signature_1", dataTTD.value.signature_1)
      H.tandaTangan().set("signature_2", dataTTD.value.signature_2)
    }
  })
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Pegawai.value = response
}
const fetchDropdown = async () => {
  const response = await useApi().get(`/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=&limit=10`)
  d_Hubungan.value = response
}
const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.signature_1 = H.tandaTangan().get("signature_1")
  object.signature_2 = H.tandaTangan().get("signature_2")
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

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

  input.value.namaPasien = props.pasien.namapasien
  input.value.jenisKelaminPasien = props.pasien.jeniskelamin
  input.value.notelepon = props.pasien.nohp
  input.value.tglLahir = props.pasien.tgllahir
  input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.dokterRawat = props.registrasi.dokter
  input.value.dirawatDiRuang = props.registrasi.namaruangan
  input.value.kelas = props.registrasi.namakelas
  input.value.kelompokPasien = props.registrasi.kelompokpasien
  input.value.tglPembuatan = new Date()
  input.value.tglDirawat = props.registrasi.tglregistrasi

}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}


setView()
setAutoFill()
fetchDropdown()
loadRiwayat()
</script>
<style>
#signature {
  border: double 3px transparent;
  border-radius: 5px;
  background-image: linear-gradient(white, white),
    radial-gradient(circle at top left, #4bc5e8, #9f6274);
  background-origin: border-box;
  background-clip: content-box, border-box;
}

.container {
  width: "100%";
  padding: 8px 16px;
}

.buttons {
  display: flex;
  gap: 8px;
  justify-content: center;
  margin-top: 8px;
}
</style>

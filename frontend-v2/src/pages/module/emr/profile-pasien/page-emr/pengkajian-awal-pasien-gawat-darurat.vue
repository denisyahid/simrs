<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="columns is-12">
    <VCard>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="PENGKAJIAN KEPERAWATAN" :toggleable="true">
          <div class="columns is-multiline p-3">
            <div class="column is-4 " v-for="(data, index) in detailKeperawatan">
              <VField>
                <h1 style="font-weight: bold;margin-bottom:0.5rem"> {{ data.label }} : </h1>
                <VControl>
                  <VTextarea rows="3" :placeholder="data.placeholder" v-if="data.type == 'text'"
                    v-model="input[data.model]"></VTextarea>
                  <VInput :placeholder="data.placeholder" v-if="data.type == 'input'" class="input"
                    v-model="input[data.model]"></VInput>
                </VControl>
              </VField>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="1.PENILAIAN NYERI" :toggleable="true">
          <div class="columns is-multiline p-3">
            <div class="columns is-multiline is-12 pl-5 pr-5 pt-5 pb-0">
              <div class="column is-7">
                <h1 style="font-weight: bold;">Skoring nyeri (Wong Baker Faces)</h1>
                <div class="columns pt-4">
                  <div class="column" style="text-align: center;" v-for="(image, i) in listImageNyeri.detail">
                    <VAvatar size="medium" :picture="image.img" style="cursor:pointer !important"
                      :class="isAktive == i ? 'active' : ''" @click="skor(image, i)" />
                    <p>{{ image.descNilai }}</p>
                    <p>{{ image.nama }}</p>
                  </div>
                </div>
              </div>
              <div class="column is-5">
                <h1 style="font-weight: bold;">Score</h1>
                <div class="pt-4">
                  <VField v-for="(skor) in listSkoringNyeri.detail">
                    <VControl raw subcontrol class="p-0">
                      <VCheckbox class="pt-0" v-model="input.skoringNyeri" :true-value="skor.descNilai" :label="skor.nama"
                        color="primary" circle />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="2. PENGKAJIAN RISIKO PASIEN JATUH" :toggleable="true">
          <div class="columns is-multiline p-3">
            <div class="mt-5 columns is-multiline">
              <VField v-for="(data, index) in skoringJatuh">
                <VControl raw subcontrol class="p-0">
                  <VCheckbox class="pt-0" v-model="input.skoringJatuh" :true-value="data.label" :label="data.label"
                    color="primary" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-3">
              <VField label="Skor Jatuh">
                <VControl raw subcontrol class="p-0">
                  <VInput placeholder="Skor jatuh..." class="input" v-model="input.skorJatuh">
                  </VInput>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Kategori Jatuh">
                <VControl raw subcontrol class="p-0">
                  <VInput placeholder="Kategori Jatuh..." class="input" v-model="input.skorJatuh">
                  </VInput>
                </VControl>
              </VField>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="3. PEMERIKSAAN STATUS NUTRISI" :toggleable="true">
          <div class="columns is-multiline p-3">
            <div class="column is-12">
              <div class="columns is-multiline" style="border-bottom: 1px solid;">
                <div class="column is-1">
                  <h1 style="font-weight : bold;">No</h1>
                </div>
                <div class="column is-5">
                  <h1 style="font-weight : bold;">Parameter</h1>
                </div>
                <div class="column is-5"></div>
                <div class="column">
                  <h1 style="font-weight : bold;">Nilai</h1>
                </div>
              </div>
              <div class="columns is-multiline" v-for="(data) in resikoNutrisional.pertama">
                <div class="column is-1">
                  <h1 class="emr">{{ data.no }}</h1>
                </div>
                <div class="column is-5">
                  <h1 class="emr">{{ data.parameter }}</h1>
                </div>
                <div class="column is-5 pt-0">
                  <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                    <VField class="">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[value.model]" class="p-0" :true-value="value.value" :label="value.title"
                          color="primary" square />
                      </VControl>
                      <small>{{ value.keterangan }}</small>
                    </VField>

                  </div>
                </div>
                <div class="column pt-0">
                  <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                    <h1 :class="nilai.keterangan ? 'emr mb-5' : 'emr'">
                      {{ nilai.poin }}
                    </h1>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline" v-for="(data) in resikoNutrisional.kedua">
                <div class="column is-1">
                  <h1 class="emr">{{ data.no }}</h1>
                </div>
                <div class="column is-5">
                  <h1 class="emr">{{ data.parameter }}</h1>
                </div>
                <div class="column is-5 pt-0">
                  <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[value.model]" :true-value="value.value" :label="value.title" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column pt-0">
                  <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                    <h1 :class="nilai.keterangan ? 'emr mb-3' : 'emr'">
                      {{ nilai.poin }}
                    </h1>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline" v-for="(data) in resikoNutrisional.ketiga">
                <div class="column is-1">
                  <h1 class="emr">{{ data.no }}</h1>
                </div>
                <div class="column is-5">
                  <h1 class="emr">{{ data.parameter }}</h1>
                </div>
                <div class="column is-5 pt-0">
                  <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[value.model]" :true-value="value.value" :label="value.title" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="columns is-multiline py-5 px-4">
                    <div v-if="input.diagnosaKhusus == 'Ya'" v-for="(children) in data.children"
                      :class="'column is-' + children.column">
                      <VField v-if="children.type == 'checkbox'">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input[children.model]" :true-value="children.value" :label="children.label"
                            class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                      <VField v-if="children.type == 'input' && input.valueDiagnosaKhusus == 'lain_lain'">
                        <VControl raw subcontrol class="p-0">
                          <VInput placeholder="Diagnosa lainya..." class="input" v-model="input[children.model]">
                          </VInput>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column pt-0">
                </div>
              </div>
            </div>
            <div class="column is-7">
              <h1 style="font-weight: 600;">(Bila skor ≥ 2 dan atau pasien dengan diagnosis/kondisi khusus dilaporkan ke
                dokter pemeriksa)</h1>
            </div>
            <div class="column is-3" style="margin-left: auto;">
              <VField label="Jumlah Total">
                <VControl raw subcontrol>
                  <input v-model="input.totalNilaiPemeriksaanNutrisi" class="input v-else" disabled />
                </VControl>
              </VField>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="4. STATUS PASIEN" :toggleable="true">
          <div class="columns is-multiline p-3">
            <div class="column is-4" v-for="(data, index) in statusPasien ">
              <VField :label="data.label">
                <VControl raw subcontrol class="p-0">
                  <VTextarea :placeholder="data.placeholder" v-model="input[data.model]">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="5. PEMERIKSAAN FISIK" :toggleable="true">
          <div class="columns is-multiline p-3">
            <!-- <div class="column is-12">
              <div class="columns is-multiline p-3">
                <div class="column is-4">
                  <VField label="Pilih Jenis Kelamin"></VField>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <div class="form-tambahan-radio">
                        <RadioButton v-model="jenisKelamin" inputId="jk1" name="LAKI-LAKI" value="LAKI-LAKI" />
                        <label for="jk1" class="ml-2">LAKI-LAKI</label>
                      </div>
                    </div>
                    <div class="column is-6">
                      <div class="form-tambahan-radio">
                        <RadioButton v-model="jenisKelamin" inputId="jk2" name="PEREMPUAN" value="PEREMPUAN" />
                        <label for="jk2" class="ml-2">PEREMPUAN</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="column is-12">
              <div class="columns is-multiline p-3">
                <div class="column is-8">
                  <div :style="'background-image:url(' + MARKINGSITE + ')'"
                    style="text-align: center; z-index: 9999;background-repeat: no-repeat;background-position: center;width: 200px;height: 600px;">
                    <canvas id="markingsite" height="600" width="200"></canvas>
                  </div>
                  <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                    @click="clearCanvas('markingsite')"> Clear
                  </VButton>
                </div>
                <div class="column is-4">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline" v-for="(data, index) in pemeriksaanFisik">
                        <div class="column is-5">
                          <h1>{{ data.label }}</h1>
                        </div>
                        <div class="column is-7">
                          <div class="columns is-multiline" v-if="data.type == 'checkbox'">
                            <div class="column">
                              <VField>
                                <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" true-value="ya" label="Ya" class="p-0"
                                    color="primary" square />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column">
                              <VField>
                                <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" true-value="tidak" label="Tidak" class="p-0"
                                    color="primary" square />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                          <div class="columns is-multiline" v-if="data.type == 'input'">
                            <VField>
                              <VControl raw subcontrol class="p-0">
                                <VInput placeholder="Lain nya..." class="input" v-model="input[data.model]">
                                </VInput>
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
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="6. DIAGNOSA KEPERAWATAN DAN RENCANA ASUHAN KEPERAWATAN" :toggleable="true">
          <div class="columns is-multiline p-3">
            <div class="column">
              <div class="mt-1">
                <table width="100%">
                  <thead>
                    <tr class="tr-fkprj">
                      <th class="td-fkprj" width="2%" style="vertical-align: inherit;text-align: center">NO</th>
                      <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">Diagnosa
                        Keperawatan
                      </th>
                      <th class="td-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;" width="7%">#
                      </th>
                    </tr>
                  </thead>
                  <tbody v-for="(items, index) in input.diagnosaKeper" :key="index">
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" style="vertical-align:inherit;text-align:center">{{ items.no }}</td>
                      <td class="td-fkprj">
                        <div class="column p-1">
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="items.diagnosaKeperawatan" :suggestions="d_DiagnosaKeperawatan"
                                @complete="fetchDiagnosaKeperawatan($event)" :optionLabel="'caption'" :dropdown="true"
                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'caption'"
                                placeholder="Cari Diagnosa Keperawatan ..." class="mt-2" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-fkprj" style="vertical-align: inherit;">
                        <div class="column p-0">
                          <VButtons style="justify-content: space-around;">
                            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewDiagnosaKeper()"
                              color="info">
                            </VIconButton>
                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                              @click="removeItemDiagnosaKeper(index)" color="danger">
                            </VIconButton>
                          </VButtons>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="columns is-multiline p-3">
            <div class="column is-6" v-for="(data, index) in keperawatan" :key="index">
              <VField>
                <h1 style="font-weight: bold;margin-bottom:0.5rem"> {{ data.label }} : </h1>
                <VControl>
                  <VTextarea rows="3" :placeholder="data.placeholder" v-if="data.type == 'text'"
                    v-model="input[data.model]">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-5">
        <VCard>
          <div class="column is-12 p-2">
            <h1 style="font-weight: bold;" class="mb-2"> Bandung, Tanggal </h1>
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
            <div class="column is-12">
              <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
            </div>
            <div class="column is-12">
              <h1 class="p-0" style="font-weight: bold;">Perawat </h1>
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.perawat" :suggestions="d_Perawat" @complete="fetchPerawat($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari..." class="mt-2"
                    @item-select="setTandaTangan($event)" />
                </VControl>
              </VField>
            </div>
          </div>
        </VCard>
      </div>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/pengkajian-awal-pasien-gawat-darurat'
import $ from "jquery";
import RadioButton from 'primevue/radiobutton';
import sleep from '/@src/utils/sleep'




let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let detailKeperawatan = ref(EMR.detailKeperawatan())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let skoringJatuh: any = ref(EMR.skoringJatuh())
let resikoNutrisional: any = ref(EMR.listNutrisional())
let statusPasien: any = ref(EMR.statusPasien())
let pemeriksaanFisik: any = ref(EMR.pemeriksaanFisik())
let keperawatan: any = ref(EMR.keperawatan())

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
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Perawat: any = ref([])
const d_Dokter: any = ref([])
const d_DiagnosaKeperawatan: any = ref([])
const isAktive = ref()
const jenisKelamin: any = ref('')
const MARKINGSITE: any = ref('')
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  diagnosaKeper: [{ no: 1 }],
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  jenisKelamin.value = props.pasien.jeniskelamin.toUpperCase()
  MARKINGSITE.value = '/images/simrs/' + (jenisKelamin.value == 'PEREMPUAN' ? 'cewek.png' : 'cowok.png')
}
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
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
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }
const clearCanvas = (canvas: any) => {

  var sigCanvas: any = document.getElementById(canvas);
  var context = sigCanvas.getContext("2d");
  context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);

}
const skor = (e: any, i: any) => {

  let listSkor = listSkoringNyeri.value.detail

  listSkor.forEach((element: any) => {
    if (element.descNilai == e.descNilai) {
      input.value.skoringNyeri = e.descNilai
    }
  });
  isAktive.value = i

}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  await useApi().get(
    "emr/auto-fill?nocmfk=" + ID_PASIEN +
    "&collection=VitalSign" +
    "&field=beratBadan,tinggiBadan"
  ).then((response) => {
    if (response != null) {
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
    }
  })
  input.value.tglPembuatan = new Date()
}
const getPosition = (mouseEvent: any, sigCanvas: any) => {
  let rect = sigCanvas.getBoundingClientRect();
  return {
    X: mouseEvent.clientX - rect.left,
    Y: mouseEvent.clientY - rect.top
  };
}
const markignSite = () => {
  let sigCanvas: any = document.getElementById("markingsite");
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "red";
  context.lineJoin = "round";
  context.lineWidth = 2;
  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {

    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        this.isDrawing = true;
      },
      touchmove: function (coors: any) {
        if (this.isDrawing) {
          context.lineTo(coors.x, coors.y);
          context.stroke();
        }
      },
      touchend: function (coors: any) {
        if (this.isDrawing) {
          this.touchmove(coors);
          this.isDrawing = false;
        }
      }
    };


    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;

      if (obj.offsetParent) {

        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        }

        while ((obj = obj.offsetParent) != null);
      }


      drawer[event.type](coors);
    }


    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);


    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();

    }, false);
  } else {

    $("#markingsite").mousedown(function (mouseEvent: any) {

      let position = getPosition(mouseEvent, sigCanvas);
      context.moveTo(position.X, position.Y);
      context.beginPath();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });
    });

  }
}
const drawLine = (mouseEvent: any, sigCanvas: any, context: any) => {

  let position = getPosition(mouseEvent, sigCanvas);

  context.lineTo(position.X, position.Y);
  context.stroke();
}
const finishDrawing = (mouseEvent: any, sigCanvas: any, context: any) => {
  drawLine(mouseEvent, sigCanvas, context);

  context.closePath();
  $(sigCanvas).unbind("mousemove")
    .unbind("mouseup")
    .unbind("mouseout");
}
const fetchPerawat = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}

const setTandaTangan = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_1", element.ttd)
    } else {
      H.tandaTangan().set("signature_1", '')
    }
  })
}


const fetchDiagnosaKeperawatan = async (filter: any) => {
  await useApi().get(`/emr/get-dropdown-diagnosa-keperawatan?search=${filter.query}`).then((response) => {
    d_DiagnosaKeperawatan.value = response
  })
}
const addNewDiagnosaKeper = () => {
  input.value.diagnosaKeper.push({
    no: input.value.diagnosaKeper[input.value.diagnosaKeper.length - 1].no + 1,
  });
}
const removeItemDiagnosaKeper = (index: any) => {
  input.value.diagnosaKeper.splice(index, 1)
}
watch(() => [
  input.value.penurunanBB,
  input.value.penurunanNafsuMakan,
  jenisKelamin.value,
], () => {

  let poin1 = input.value.penurunanBB ? parseInt(input.value.penurunanBB.poin) : 0
  let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.poin) : 0

  const total = poin1 + poin2
  input.value.totalNilaiPemeriksaanNutrisi = total

})
async function performAction() {
  await sleep(1000);
  markignSite();
}
performAction();
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
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

.table-fkprj,
.tr-fkprj,
.th-fkprj,
.td-fkprj {
  border: 1.6px solid black !important;
}

.th-fkprj,
.td-fkprj {
  padding: 8px !important;
}
</style>
